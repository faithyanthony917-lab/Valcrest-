<?php

declare(strict_types=1);

require __DIR__ . '/../backend/src/bootstrap.php';
$userId = require_page_auth();
$type = $_GET['type'] ?? 'deposit';
if (!in_array($type, ['deposit', 'withdrawal'], true)) {
    http_response_code(400);
    exit('Invalid request type.');
}
$search = trim((string) ($_GET['query'] ?? ''));
$date = trim((string) ($_GET['date'] ?? ''));
$sql = 'SELECT reference, type, amount, currency, status, created_at FROM financial_requests WHERE user_id = :user_id AND type = :type';
$params = ['user_id' => $userId, 'type' => $type];
if ($search !== '') {
    $sql .= ' AND (reference LIKE :search OR status LIKE :search)';
    $params['search'] = '%' . $search . '%';
}
if ($date !== '') {
    $sql .= ' AND DATE(created_at) = :request_date';
    $params['request_date'] = $date;
}
$sql .= ' ORDER BY created_at DESC LIMIT 250';
$query = $pdo->prepare($sql);
$query->execute($params);
$requests = $query->fetchAll();
$title = $type === 'deposit' ? 'All Deposit Log' : 'All Withdraw Log';
$action = $type === 'deposit' ? '/portal/deposits/logs' : '/portal/withdrawals/logs';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> - Valcrest Meridian Capital</title>
<link rel="stylesheet" href="/assets/frontend/css/vendor/bootstrap.min.css">
<link rel="stylesheet" href="/assets/global/css/custom.css">
<link rel="stylesheet" href="/assets/frontend/css/styles.css?var=2.1">
</head>
<body class="dark-theme"><div class="panel-layout">
<?php require __DIR__ . '/_header.php'; ?>
<div class="page-container"><div class="main-content"><div class="section-gap"><div class="container-fluid">
<div class="row"><div class="col-xl-12"><div class="site-card"><div class="site-card-header"><h3 class="title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3></div><div class="site-card-body"><div class="site-table">
<form action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" method="get"><div class="search"><input type="text" name="query" placeholder="Search reference or status" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"><input type="date" name="date" value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="apply-btn">Search</button></div></form>
<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Description</th><th>Transaction ID</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead><tbody>
<?php foreach ($requests as $request): ?><tr><td><div class="table-description"><div class="icon"><i class="anticon <?= $type === 'deposit' ? 'anticon-arrow-down' : 'anticon-arrow-up' ?>"></i></div><div class="description"><strong><?= htmlspecialchars(ucfirst($type), ENT_QUOTES, 'UTF-8') ?> request</strong></div></div></td><td><strong><?= htmlspecialchars($request['reference'], ENT_QUOTES, 'UTF-8') ?></strong></td><td><strong><?= htmlspecialchars($request['currency'], ENT_QUOTES, 'UTF-8') ?> <?= number_format((float) $request['amount'], 2) ?></strong></td><td><div class="site-badge"><?= htmlspecialchars(ucfirst($request['status']), ENT_QUOTES, 'UTF-8') ?></div></td><td><?= htmlspecialchars($request['created_at'], ENT_QUOTES, 'UTF-8') ?></td></tr><?php endforeach; ?>
<?php if (!$requests): ?><tr><td colspan="5">No <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?> records found.</td></tr><?php endif; ?>
</tbody></table></div></div></div></div></div></div>
</div></div></div></div></div></body></html>
