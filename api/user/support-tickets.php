<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = $pdo->prepare('SELECT id, subject, category, status, created_at, updated_at FROM support_tickets WHERE user_id = :user_id ORDER BY updated_at DESC');
    $query->execute(['user_id' => $userId]);
    respond(['tickets' => $query->fetchAll()]);
}

require_post();
require_csrf();
$input = json_input();
$subject = trim((string) ($input['subject'] ?? ''));
$category = trim((string) ($input['category'] ?? 'general'));
$message = trim((string) ($input['message'] ?? ''));
if ($subject === '' || strlen($subject) > 180 || strlen($category) > 60 || $message === '' || strlen($message) > 5000) {
    respond(['error' => 'Enter a valid subject, category, and message.'], 422);
}
$pdo->beginTransaction();
try {
    $ticket = $pdo->prepare('INSERT INTO support_tickets (user_id, subject, category) VALUES (:user_id, :subject, :category)');
    $ticket->execute(['user_id' => $userId, 'subject' => $subject, 'category' => $category]);
    $messageQuery = $pdo->prepare('INSERT INTO support_ticket_messages (ticket_id, author_user_id, message) VALUES (:ticket_id, :author_user_id, :message)');
    $messageQuery->execute(['ticket_id' => $pdo->lastInsertId(), 'author_user_id' => $userId, 'message' => $message]);
    $ticketId = $pdo->lastInsertId();
    $pdo->commit();
    respond(['message' => 'Support ticket submitted.', 'ticketId' => (int) $ticketId, 'status' => 'open'], 201);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    respond(['error' => 'Support ticket could not be submitted.'], 500);
}
