<?php

declare(strict_types=1);

return [
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=u103464132_valcrest;charset=utf8mb4',
        'username' => 'u103464132_valcrest',
        'password' => 'replace_with_database_password',
    ],
    'app' => [
        'session_name' => 'valcrest_session',
        'allowed_origin' => 'https://valcrestmeridiancapital.com',
        'base_url' => 'https://valcrestmeridiancapital.com',
        'mail_from' => 'no-reply@valcrestmeridiancapital.com',
        'mail_from_name' => 'Valcrest Meridian Capital',
        'reset_url_path' => '/share/reset-password/',
    ],
    'smtp' => [
        'host' => 'smtp.example.com',
        'port' => 587,
        'username' => 'smtp-user@example.com',
        'password' => 'replace_with_smtp_password',
        'encryption' => 'tls',
    ],
];
