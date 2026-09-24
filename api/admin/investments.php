<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_admin();
$query = $pdo->query(
    'SELECT i.reference, i.principal, i.expected_return, i.currency, i.status,
            i.starts_at, i.matures_at, i.released_at, p.name AS plan_name,
            u.first_name, u.last_name, u.email
     FROM investments i
     JOIN investment_plans p ON p.id = i.plan_id
     JOIN users u ON u.id = i.user_id
     ORDER BY i.created_at DESC
     LIMIT 500'
);
respond(['investments' => $query->fetchAll()]);
