<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_admin();
$query = $pdo->query('SELECT id, first_name, last_name, email, username, country, role, status, created_at FROM users ORDER BY created_at DESC LIMIT 250');
respond(['users' => $query->fetchAll()]);
