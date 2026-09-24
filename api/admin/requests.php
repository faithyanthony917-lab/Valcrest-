<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_admin();
$query = $pdo->query(
    'SELECT r.id, r.reference, r.type, r.amount, r.currency, r.status, r.notes, r.created_at,
            u.first_name, u.last_name, u.email
     FROM financial_requests r JOIN users u ON u.id = r.user_id
     ORDER BY r.created_at DESC LIMIT 250'
);
respond(['requests' => $query->fetchAll()]);
