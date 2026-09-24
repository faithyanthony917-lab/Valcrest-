<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_csrf();

$input = json_input();
$identity = isset($input['identity']) && is_string($input['identity']) ? trim($input['identity']) : '';
$password = isset($input['password']) && is_string($input['password']) ? $input['password'] : '';
if ($identity === '' || $password === '') {
    respond(['error' => 'Email/username and password are required.'], 422);
}

$query = $pdo->prepare('SELECT id, first_name, last_name, email, username, password_hash, role, status FROM users WHERE email = :email_identity OR username = :username_identity LIMIT 1');
$query->execute(['email_identity' => $identity, 'username_identity' => $identity]);
$user = $query->fetch();
if (!$user || $user['status'] !== 'active' || !password_verify($password, $user['password_hash'])) {
    respond(['error' => 'The login details are incorrect.'], 401);
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
respond([
    'message' => 'Login successful.',
    'redirect' => '/share/user/dashboard/',
    'user' => ['firstName' => $user['first_name'], 'lastName' => $user['last_name'], 'email' => $user['email'], 'username' => $user['username'], 'role' => $user['role']],
]);
