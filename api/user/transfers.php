<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();
require_post();
require_csrf();

$input = json_input();
$recipient = trim((string) ($input['recipient'] ?? ''));
$amount = amount_from_input($input['amount'] ?? null);
$note = isset($input['note']) && is_string($input['note']) ? trim($input['note']) : null;
if ($recipient === '' || strlen($recipient) > 255 || ($note !== null && strlen($note) > 500)) {
    respond(['error' => 'Enter a valid recipient and note.'], 422);
}

$pdo->beginTransaction();
try {
    $senderQuery = $pdo->prepare('SELECT id, user_id, currency, available_balance FROM accounts WHERE user_id = :user_id AND currency = "GBP" FOR UPDATE');
    $senderQuery->execute(['user_id' => $userId]);
    $sender = $senderQuery->fetch();
    $recipientQuery = $pdo->prepare('SELECT id, user_id FROM users WHERE email = :recipient OR username = :recipient LIMIT 1');
    $recipientQuery->execute(['recipient' => $recipient]);
    $recipientUser = $recipientQuery->fetch();
    if (!$sender || !$recipientUser || (int) $recipientUser['id'] === $userId) {
        throw new RuntimeException('Recipient or sender account is invalid.');
    }
    $recipientAccountQuery = $pdo->prepare('SELECT id FROM accounts WHERE user_id = :user_id AND currency = :currency FOR UPDATE');
    $recipientAccountQuery->execute(['user_id' => $recipientUser['id'], 'currency' => $sender['currency']]);
    $recipientAccount = $recipientAccountQuery->fetch();
    if (!$recipientAccount || (float) $sender['available_balance'] < (float) $amount) {
        throw new RuntimeException('Insufficient balance or recipient wallet is unavailable.');
    }
    $reference = strtoupper(bin2hex(random_bytes(8)));
    $debit = $pdo->prepare('UPDATE accounts SET available_balance = available_balance - :amount WHERE id = :id');
    $debit->execute(['amount' => $amount, 'id' => $sender['id']]);
    $credit = $pdo->prepare('UPDATE accounts SET available_balance = available_balance + :amount WHERE id = :id');
    $credit->execute(['amount' => $amount, 'id' => $recipientAccount['id']]);
    $transfer = $pdo->prepare('INSERT INTO transfers (sender_user_id, recipient_user_id, sender_account_id, recipient_account_id, amount, currency, reference, note) VALUES (:sender_user, :recipient_user, :sender_account, :recipient_account, :amount, :currency, :reference, :note)');
    $transfer->execute([
        'sender_user' => $userId,
        'recipient_user' => $recipientUser['id'],
        'sender_account' => $sender['id'],
        'recipient_account' => $recipientAccount['id'],
        'amount' => $amount,
        'currency' => $sender['currency'],
        'reference' => $reference,
        'note' => $note,
    ]);
    $ledger = $pdo->prepare('INSERT INTO ledger_transactions (user_id, account_id, type, amount, currency, status, description) VALUES (:user_id, :account_id, :type, :amount, :currency, "completed", :description)');
    $ledger->execute(['user_id' => $userId, 'account_id' => $sender['id'], 'type' => 'transfer_out', 'amount' => '-' . $amount, 'currency' => $sender['currency'], 'description' => 'Transfer ' . $reference]);
    $ledger->execute(['user_id' => $recipientUser['id'], 'account_id' => $recipientAccount['id'], 'type' => 'transfer_in', 'amount' => $amount, 'currency' => $sender['currency'], 'description' => 'Transfer ' . $reference]);
    $pdo->commit();
    respond(['message' => 'Transfer completed.', 'reference' => $reference, 'status' => 'completed'], 201);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    respond(['error' => $exception->getMessage()], 422);
}
