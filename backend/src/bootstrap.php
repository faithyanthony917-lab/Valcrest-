<?php

declare(strict_types=1);

$configPath = __DIR__ . '/../config/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Backend configuration is missing. Copy config.example.php to config.php.']);
    exit;
}

$config = require $configPath;

session_name($config['app']['session_name']);
session_set_cookie_params([
    'httponly' => true,
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'samesite' => 'Lax',
]);
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && hash_equals((string) $config['app']['allowed_origin'], $origin)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
}
header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    $pdo = new PDO(
        $config['db']['dsn'],
        $config['db']['username'],
        $config['db']['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

function json_input(): array
{
    $body = file_get_contents('php://input');
    $data = json_decode($body ?: '{}', true);
    return is_array($data) ? $data : [];
}

function respond(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

function require_csrf(): void
{
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $requestToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($sessionToken) || $sessionToken === '' || !is_string($requestToken) || !hash_equals($sessionToken, $requestToken)) {
        respond(['error' => 'Invalid security token. Refresh the page and try again.'], 419);
    }
}

function require_auth(): int
{
    $userId = $_SESSION['user_id'] ?? null;
    if (!is_int($userId) && !ctype_digit((string) $userId)) {
        respond(['error' => 'Authentication is required.'], 401);
    }
    return (int) $userId;
}

function require_page_auth(): int
{
    $userId = $_SESSION['user_id'] ?? null;
    if (!is_int($userId) && !ctype_digit((string) $userId)) {
        header('Location: /auth/login/');
        exit;
    }
    return (int) $userId;
}

function require_admin(): int
{
    $userId = require_auth();
    $adminQuery = $GLOBALS['pdo']->prepare('SELECT role, status FROM users WHERE id = :id LIMIT 1');
    $adminQuery->execute(['id' => $userId]);
    $user = $adminQuery->fetch();
    if (!$user || $user['role'] !== 'admin' || $user['status'] !== 'active') {
        respond(['error' => 'Administrator access is required.'], 403);
    }
    return $userId;
}

function require_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond(['error' => 'POST is required.'], 405);
    }
}

function amount_from_input(mixed $value): string
{
    if (!is_string($value) && !is_int($value)) {
        respond(['error' => 'Enter a valid amount.'], 422);
    }

    $raw = trim((string) $value);
    if (!preg_match('/^(?:0|[1-9]\d{0,11})(?:\.\d{1,2})?$/', $raw)) {
        respond(['error' => 'Enter an amount greater than zero.'], 422);
    }
    $amount = number_format((float) $raw, 2, '.', '');
    if ($amount === '0.00') {
        respond(['error' => 'Enter an amount greater than zero.'], 422);
    }
    return $amount;
}
