<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();
require_post();
require_csrf();

$input = json_input();
$currentPassword = $input['currentPassword'] ?? '';
$newPassword = $input['newPassword'] ?? '';
 $confirmPassword = $input['confirmPassword'] ?? '';
if (!is_string($currentPassword) || !is_string($newPassword) || !is_string($confirmPassword) || $newPassword !== $confirmPassword || strlen($newPassword) < 12 || strlen($newPassword) > 200) {
    respond(['error' => 'Your new password must be between 12 and 200 characters.'], 422);
}

$query = $pdo->prepare('SELECT password_hash FROM users WHERE id = :id AND status = "active" LIMIT 1');
$query->execute(['id' => $userId]);
$user = $query->fetch();
if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
    respond(['error' => 'The current password is incorrect.'], 422);
}

$update = $pdo->prepare('UPDATE users SET password_hash = :password_hash WHERE id = :id');
$update->execute([
    'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
    'id' => $userId,
]);
session_regenerate_id(true);
respond(['message' => 'Your password was changed successfully.']);
