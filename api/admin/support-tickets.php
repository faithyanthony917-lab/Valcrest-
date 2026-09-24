<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = $pdo->query(
        'SELECT t.id, t.subject, t.category, t.status, t.created_at, t.updated_at,
                u.email, u.first_name, u.last_name
         FROM support_tickets t
         JOIN users u ON u.id = t.user_id
         ORDER BY t.updated_at DESC'
    );
    respond(['tickets' => $query->fetchAll()]);
}

require_post();
require_csrf();
$input = json_input();
$ticketId = filter_var($input['ticketId'] ?? null, FILTER_VALIDATE_INT);
$status = $input['status'] ?? null;
$message = isset($input['message']) && is_string($input['message']) ? trim($input['message']) : '';
if (!$ticketId || ($status !== null && !in_array($status, ['open', 'in_progress', 'resolved', 'closed'], true)) || ($message === '' && $status === null)) {
    respond(['error' => 'Provide a valid ticket action.'], 422);
}

$pdo->beginTransaction();
try {
    $ticketQuery = $pdo->prepare('SELECT id, user_id FROM support_tickets WHERE id = :id FOR UPDATE');
    $ticketQuery->execute(['id' => $ticketId]);
    $ticket = $ticketQuery->fetch();
    if (!$ticket) {
        throw new RuntimeException('Support ticket not found.');
    }
    if ($status !== null) {
        $update = $pdo->prepare('UPDATE support_tickets SET status = :status WHERE id = :id');
        $update->execute(['status' => $status, 'id' => $ticketId]);
    }
    if ($message !== '') {
        if (strlen($message) > 5000) {
            throw new RuntimeException('Message is too long.');
        }
        $reply = $pdo->prepare('INSERT INTO support_ticket_messages (ticket_id, author_user_id, message) VALUES (:ticket_id, :author_user_id, :message)');
        $reply->execute(['ticket_id' => $ticketId, 'author_user_id' => $_SESSION['user_id'], 'message' => $message]);
    }
    $pdo->commit();
    respond(['message' => 'Support ticket updated.']);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    respond(['error' => $exception->getMessage()], 422);
}
