<?php

declare(strict_types=1);

require __DIR__ . '/../backend/src/bootstrap.php';
require_page_auth();

header('Location: /portal/dashboard/');
exit;
