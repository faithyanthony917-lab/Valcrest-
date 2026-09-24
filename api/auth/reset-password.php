<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_post();
require_csrf();

$input = json_input();
$token = is_string($input['token'] ?? null) ? trim($input['token']) : '';
$password = is_string($input['password'] ?? null) ? $input['password'] : '';
$confirmation = is_string($input['confirmPassword'] ?? null) ? $input['confirmPassword'] : '';
if (!preg_match('/^[a-f0-9]{64}$/i', $token) || strlen($password) < 12 || !hash_equals($password, $confirmation)) {
    respond(['error' => 'Use a valid reset link and a matching password of at least 12 characters.'], 422);
}

$query = $pdo->prepare('SELECT id, user_id FROM password_reset_tokens WHERE token_hash = :token_hash AND used_at IS NULL AND expires_at > NOW() LIMIT 1');
$query->execute(['token_hash' => hash('sha256', $token)]);
$reset = $query->fetch();
if (!$reset) {
    respond(['error' => 'This reset link is invalid or has expired.'], 400);
}

$pdo->beginTransaction();
try {
    $update = $pdo->prepare('UPDATE users SET password_hash = :password_hash, updated_at = CURRENT_TIMESTAMP WHERE id = :user_id');
    $update->execute(['password_hash' => password_hash($password, PASSWORD_DEFAULT), 'user_id' => $reset['user_id']]);
    $mark = $pdo->prepare('UPDATE password_reset_tokens SET used_at = CURRENT_TIMESTAMP WHERE id = :id');
    $mark->execute(['id' => $reset['id']]);
    $pdo->commit();
} catch (Throwable $exception) {
    $pdo->rollBack();
    respond(['error' => 'The password could not be updated.'], 500);
}
respond(['message' => 'Your password has been reset. You can now sign in.']);
