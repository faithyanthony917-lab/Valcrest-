<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();
require_post();
require_csrf();
$input = json_input();
$planId = filter_var($input['planId'] ?? null, FILTER_VALIDATE_INT);
$accountId = filter_var($input['accountId'] ?? null, FILTER_VALIDATE_INT);
if (!$planId || !$accountId) {
    respond(['error' => 'Select a valid investment plan and wallet.'], 422);
}
$amount = amount_from_input($input['amount'] ?? null);
$pdo->beginTransaction();
try {
    $query = $pdo->prepare("SELECT id, currency, minimum_amount, maximum_amount, return_rate, term_days FROM investment_plans WHERE id = :id AND status = 'active' FOR UPDATE");
    $query->execute(['id' => $planId]);
    $plan = $query->fetch();
    if (!$plan || (float) $amount < (float) $plan['minimum_amount'] || ($plan['maximum_amount'] !== null && (float) $amount > (float) $plan['maximum_amount']) ) {
        $pdo->rollBack();
        respond(['error' => 'The amount is outside this plan’s limits.'], 422);
    }
    $query = $pdo->prepare("SELECT id, currency, available_balance FROM accounts WHERE id = :id AND user_id = :user_id AND wallet_type = 'main' FOR UPDATE");
    $query->execute(['id' => $accountId, 'user_id' => $userId]);
    $source = $query->fetch();
    if (!$source || $source['currency'] !== $plan['currency'] || (float) $source['available_balance'] < (float) $amount) {
        $pdo->rollBack();
        respond(['error' => 'The selected wallet does not have enough funds.'], 422);
    }
    $query = $pdo->prepare("SELECT id FROM accounts WHERE user_id = :user_id AND currency = :currency AND wallet_type = 'profit' FOR UPDATE");
    $query->execute(['user_id' => $userId, 'currency' => $plan['currency']]);
    $profitAccountId = $query->fetchColumn();
    if (!$profitAccountId) {
        $pdo->rollBack();
        respond(['error' => 'The profit wallet is not configured.'], 409);
    }
    $profit = round((float) $amount * ((float) $plan['return_rate'] / 100), 2);
    $now = new DateTimeImmutable('now');
    $matures = $now->modify('+' . (int) $plan['term_days'] . ' days');
    $reference = 'INV-' . strtoupper(bin2hex(random_bytes(6)));
    $pdo->prepare('UPDATE accounts SET available_balance = available_balance - :amount WHERE id = :id')->execute(['amount' => $amount, 'id' => $accountId]);
    $insert = $pdo->prepare('INSERT INTO investments (user_id, plan_id, source_account_id, profit_account_id, principal, expected_return, currency, starts_at, matures_at, reference) VALUES (:user_id, :plan_id, :source_id, :profit_id, :principal, :expected_return, :currency, :starts_at, :matures_at, :reference)');
    $insert->execute(['user_id' => $userId, 'plan_id' => $planId, 'source_id' => $accountId, 'profit_id' => $profitAccountId, 'principal' => $amount, 'expected_return' => $profit, 'currency' => $plan['currency'], 'starts_at' => $now->format('Y-m-d H:i:s'), 'matures_at' => $matures->format('Y-m-d H:i:s'), 'reference' => $reference]);
    $ledger = $pdo->prepare("INSERT INTO ledger_transactions (user_id, account_id, type, amount, currency, status, description) VALUES (:user_id, :account_id, 'investment', :amount, :currency, 'completed', :description)");
    $ledger->execute(['user_id' => $userId, 'account_id' => $accountId, 'amount' => '-' . $amount, 'currency' => $plan['currency'], 'description' => 'Investment created ' . $reference]);
    $pdo->commit();
    respond(['message' => 'Investment created.', 'reference' => $reference, 'maturesAt' => $matures->format(DATE_ATOM)], 201);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    respond(['error' => 'The investment could not be created.'], 500);
}
