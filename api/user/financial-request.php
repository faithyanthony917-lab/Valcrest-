<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();
require_csrf();

$input = json_input();
$type = $input['type'] ?? '';
if (!in_array($type, ['deposit', 'withdrawal'], true)) {
    respond(['error' => 'Request type must be deposit or withdrawal.'], 422);
}
$amount = amount_from_input($input['amount'] ?? null);
$notes = isset($input['notes']) && is_string($input['notes']) ? trim($input['notes']) : null;
if ($notes !== null && strlen($notes) > 500) {
    respond(['error' => 'Notes cannot exceed 500 characters.'], 422);
}

$accountQuery = $pdo->prepare('SELECT id FROM accounts WHERE user_id = :user_id AND currency = "GBP" LIMIT 1');
$accountQuery->execute(['user_id' => $userId]);
$account = $accountQuery->fetch();
if (!$account) {
    respond(['error' => 'Account wallet is not configured.'], 404);
}

$reference = strtoupper(bin2hex(random_bytes(8)));
$insert = $pdo->prepare(
    'INSERT INTO financial_requests (user_id, account_id, type, amount, reference, notes)
     VALUES (:user_id, :account_id, :type, :amount, :reference, :notes)'
);
$insert->execute([
    'user_id' => $userId,
    'account_id' => $account['id'],
    'type' => $type,
    'amount' => $amount,
    'reference' => $reference,
    'notes' => $notes,
]);

respond([
    'message' => 'Your ' . $type . ' request was submitted for review.',
    'reference' => $reference,
    'status' => 'pending',
], 201);
