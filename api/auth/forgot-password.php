<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_post();
require_csrf();

$input = json_input();
$email = is_string($input['email'] ?? null) ? strtolower(trim($input['email'])) : '';
$generic = ['message' => 'If an account matches that email, a password reset link will be sent shortly.'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond($generic);
}

$query = $pdo->prepare('SELECT id, first_name, last_name, email FROM users WHERE email = :email AND status = "active" LIMIT 1');
$query->execute(['email' => $email]);
$user = $query->fetch();
if (!$user) {
    respond($generic);
}

$token = bin2hex(random_bytes(32));
$tokenHash = hash('sha256', $token);
$pdo->prepare('UPDATE password_reset_tokens SET used_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND used_at IS NULL')->execute(['user_id' => $user['id']]);
$insert = $pdo->prepare('INSERT INTO password_reset_tokens (user_id, token_hash, expires_at) VALUES (:user_id, :token_hash, DATE_ADD(NOW(), INTERVAL 60 MINUTE))');
$insert->execute(['user_id' => $user['id'], 'token_hash' => $tokenHash]);

$url = rtrim($config['app']['base_url'], '/') . $config['app']['reset_url_path'] . '?token=' . urlencode($token);
try {
    require_once __DIR__ . '/../../backend/src/mailer.php';
    send_password_reset_email($config, $user['email'], $user['first_name'] . ' ' . $user['last_name'], $url);
} catch (Throwable $exception) {
    respond(['error' => 'The password reset service is not configured.'], 503);
}
respond($generic);
