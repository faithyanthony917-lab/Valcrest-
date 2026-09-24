<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();

$accountQuery = $pdo->prepare('SELECT id, currency, available_balance FROM accounts WHERE user_id = :user_id AND currency = "GBP" LIMIT 1');
$accountQuery->execute(['user_id' => $userId]);
$account = $accountQuery->fetch();
if (!$account) {
    respond(['error' => 'Account wallet is not configured.'], 404);
}

$requestQuery = $pdo->prepare(
    'SELECT COALESCE(SUM(CASE WHEN type = "deposit" AND status = "pending" THEN amount ELSE 0 END), 0) AS pending_deposits,
            COALESCE(SUM(CASE WHEN type = "withdrawal" AND status = "pending" THEN amount ELSE 0 END), 0) AS pending_withdrawals
     FROM financial_requests WHERE user_id = :user_id'
);
$requestQuery->execute(['user_id' => $userId]);
$pending = $requestQuery->fetch();

respond([
    'account' => [
        'currency' => $account['currency'],
        'availableBalance' => $account['available_balance'],
        'pendingDeposits' => $pending['pending_deposits'],
        'pendingWithdrawals' => $pending['pending_withdrawals'],
    ],
]);
