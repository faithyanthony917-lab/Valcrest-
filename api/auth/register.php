<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_csrf();

$input = json_input();
$required = ['firstName', 'lastName', 'email', 'username', 'country', 'password', 'confirmPassword'];
foreach ($required as $field) {
    if (!isset($input[$field]) || !is_string($input[$field]) || trim($input[$field]) === '') {
        respond(['error' => 'All registration fields are required.'], 422);
    }
}

$email = strtolower(trim($input['email']));
$username = trim($input['username']);
$password = $input['password'];
if (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
    || strlen($email) > 255
    || !preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)
    || strlen(trim($input['firstName'])) > 100
    || strlen(trim($input['lastName'])) > 100
    || strlen(trim($input['country'])) > 100
) {
    respond(['error' => 'Enter a valid email and username.'], 422);
}
if (strlen($password) < 12 || !hash_equals($password, $input['confirmPassword'])) {
    respond(['error' => 'Passwords must match and contain at least 12 characters.'], 422);
}

$check = $pdo->prepare('SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1');
$check->execute(['email' => $email, 'username' => $username]);
if ($check->fetch()) {
    respond(['error' => 'That email or username is already registered.'], 409);
}

try {
    $pdo->beginTransaction();
    $insert = $pdo->prepare(
        'INSERT INTO users (first_name, last_name, email, username, country, password_hash)
         VALUES (:first_name, :last_name, :email, :username, :country, :password_hash)'
    );
    $insert->execute([
        'first_name' => trim($input['firstName']),
        'last_name' => trim($input['lastName']),
        'email' => $email,
        'username' => $username,
        'country' => trim($input['country']),
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);
    $newUserId = (int) $pdo->lastInsertId();
    $account = $pdo->prepare('INSERT INTO accounts (user_id, wallet_type) VALUES (:user_id, :wallet_type)');
    $account->execute(['user_id' => $newUserId, 'wallet_type' => 'main']);
    $account->execute(['user_id' => $newUserId, 'wallet_type' => 'profit']);
    $pdo->commit();
} catch (PDOException $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    if ((int) $exception->errorInfo[1] === 1062) {
        respond(['error' => 'That email or username is already registered.'], 409);
    }
    respond(['error' => 'The account could not be created.'], 500);
}

respond(['message' => 'Your account has been created.'], 201);
