<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();
$query = $pdo->prepare('SELECT reference, amount, currency, status, notes, created_at FROM financial_requests WHERE user_id = :user_id AND type = "deposit" ORDER BY created_at DESC');
$query->execute(['user_id' => $userId]);
respond(['deposits' => $query->fetchAll()]);
