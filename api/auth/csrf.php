<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

respond(['csrfToken' => $_SESSION['csrf_token']]);
