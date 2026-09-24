<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_csrf();
$input = json_input();
$identity = is_string($input['identity'] ?? null) ? trim($input['identity']) : '';
$password = is_string($input['password'] ?? null) ? $input['password'] : '';

$query = $pdo->prepare('SELECT id, password_hash, role, status FROM users WHERE email = :email_identity OR username = :username_identity LIMIT 1');
$query->execute(['email_identity' => $identity, 'username_identity' => $identity]);
$user = $query->fetch();
if (!$user || $user['role'] !== 'admin' || $user['status'] !== 'active' || !password_verify($password, $user['password_hash'])) {
    respond(['error' => 'Administrator login details are incorrect.'], 401);
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
respond(['message' => 'Administrator login successful.', 'redirect' => '/admin/']);
