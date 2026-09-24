<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
require_auth();
respond(['error' => 'Investment processing is not configured.'], 501);
