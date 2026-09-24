<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$adminId = require_admin();
require_csrf();
$input = json_input();
$requestId = filter_var($input['requestId'] ?? null, FILTER_VALIDATE_INT);
$action = $input['action'] ?? '';
if (!$requestId || !in_array($action, ['approve', 'reject'], true)) {
    respond(['error' => 'A valid request and action are required.'], 422);
}

$pdo->beginTransaction();
try {
    $query = $pdo->prepare('SELECT id, user_id, account_id, type, amount, currency, status, reference FROM financial_requests WHERE id = :id FOR UPDATE');
    $query->execute(['id' => $requestId]);
    $request = $query->fetch();
    if (!$request || $request['status'] !== 'pending') {
        $pdo->rollBack();
        respond(['error' => 'This request is no longer pending.'], 409);
    }
    $newStatus = $action === 'approve' ? 'approved' : 'rejected';
    if ($action === 'approve') {
        $account = $pdo->prepare('SELECT available_balance FROM accounts WHERE id = :id FOR UPDATE');
        $account->execute(['id' => $request['account_id']]);
        $balance = $account->fetchColumn();
        if ($request['type'] === 'withdrawal' && (float) $balance < (float) $request['amount']) {
            $pdo->rollBack();
            respond(['error' => 'The user does not have enough available balance.'], 422);
        }
        $change = $request['type'] === 'deposit' ? $request['amount'] : '-' . $request['amount'];
        $updateBalance = $pdo->prepare('UPDATE accounts SET available_balance = available_balance + :change WHERE id = :id');
        $updateBalance->execute(['change' => $change, 'id' => $request['account_id']]);
        $ledger = $pdo->prepare('INSERT INTO ledger_transactions (user_id, account_id, type, amount, currency, status, description) VALUES (:user_id, :account_id, :type, :amount, :currency, "completed", :description)');
        $ledgerAmount = $request['type'] === 'withdrawal' ? '-' . $request['amount'] : $request['amount'];
        $ledger->execute(['user_id' => $request['user_id'], 'account_id' => $request['account_id'], 'type' => $request['type'], 'amount' => $ledgerAmount, 'currency' => $request['currency'], 'description' => ucfirst($request['type']) . ' request ' . $request['reference']]);
    }
    $update = $pdo->prepare('UPDATE financial_requests SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
    $update->execute(['status' => $newStatus, 'id' => $requestId]);
    $pdo->commit();
    respond(['message' => 'Request ' . $newStatus . '.']);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    respond(['error' => 'The request could not be updated.'], 500);
}
