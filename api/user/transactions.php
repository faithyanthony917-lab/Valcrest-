<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();
$limit = min(max((int) ($_GET['limit'] ?? 25), 1), 100);

$query = $pdo->prepare(
    'SELECT l.type, l.amount, l.currency, l.status, l.description, l.created_at,
            a.wallet_type
     FROM ledger_transactions l
     JOIN accounts a ON a.id = l.account_id
     WHERE l.user_id = :user_id
     ORDER BY l.created_at DESC LIMIT ' . $limit
);
$query->execute(['user_id' => $userId]);
respond(['transactions' => $query->fetchAll()]);
