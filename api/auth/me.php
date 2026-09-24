<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();

$query = $pdo->prepare('SELECT first_name, last_name, email, username, role, status FROM users WHERE id = :id LIMIT 1');
$query->execute(['id' => $userId]);
$user = $query->fetch();
if (!$user || $user['status'] !== 'active') {
    respond(['error' => 'Your account is not available.'], 403);
}

respond([
    'user' => [
        'firstName' => $user['first_name'],
        'lastName' => $user['last_name'],
        'email' => $user['email'],
        'username' => $user['username'],
        'role' => $user['role'],
        'status' => $user['status'],
    ],
]);
