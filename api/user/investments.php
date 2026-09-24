<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();
$plans = $pdo->query("SELECT id, name, description, currency, minimum_amount, maximum_amount, return_rate, term_days, capital_back FROM investment_plans WHERE status = 'active' ORDER BY id")->fetchAll();
$wallets = $pdo->prepare("SELECT id, currency, wallet_type, available_balance FROM accounts WHERE user_id = :user_id ORDER BY wallet_type, currency");
$wallets->execute(['user_id' => $userId]);
$investments = $pdo->prepare('SELECT i.id, i.reference, i.principal, i.expected_return, i.currency, i.starts_at, i.matures_at, i.status, p.name AS plan_name, p.return_rate, p.term_days FROM investments i JOIN investment_plans p ON p.id = i.plan_id WHERE i.user_id = :user_id ORDER BY i.created_at DESC');
$investments->execute(['user_id' => $userId]);
respond(['plans' => $plans, 'wallets' => $wallets->fetchAll(), 'investments' => $investments->fetchAll()]);
