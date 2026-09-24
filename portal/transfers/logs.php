<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_page_auth();
$search = trim((string) ($_GET['query'] ?? ''));
$date = trim((string) ($_GET['date'] ?? ''));
$sql = 'SELECT reference, amount, currency, status, note, created_at FROM transfers WHERE sender_user_id = :user_id';
$params = ['user_id' => $userId];
if ($search !== '') { $sql .= ' AND (reference LIKE :search OR status LIKE :search)'; $params['search'] = '%' . $search . '%'; }
if ($date !== '') { $sql .= ' AND DATE(created_at) = :transfer_date'; $params['transfer_date'] = $date; }
$sql .= ' ORDER BY created_at DESC LIMIT 250';
$query = $pdo->prepare($sql); $query->execute($params); $transfers = $query->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Transfer Logs - Valcrest Meridian Capital</title><link rel="stylesheet" href="/assets/frontend/css/vendor/bootstrap.min.css"><link rel="stylesheet" href="/assets/global/css/custom.css"><link rel="stylesheet" href="/assets/frontend/css/styles.css?var=2.1"></head><body class="dark-theme"><div class="panel-layout"><?php require __DIR__ . '/../_header.php'; ?><div class="page-container"><div class="main-content"><div class="section-gap"><div class="container-fluid"><div class="row"><div class="col-xl-12"><div class="site-card"><div class="site-card-header"><h3 class="title">All Send Money Log</h3></div><div class="site-card-body"><form method="get"><div class="search"><input type="text" name="query" placeholder="Search reference or status" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"><input type="date" name="date" value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="apply-btn">Search</button></div></form><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Description</th><th>Transaction ID</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead><tbody><?php foreach ($transfers as $transfer): ?><tr><td><div class="table-description"><div class="icon"><i class="anticon anticon-export"></i></div><div class="description"><strong><?= htmlspecialchars($transfer['note'] ?: 'Send money', ENT_QUOTES, 'UTF-8') ?></strong></div></div></td><td><strong><?= htmlspecialchars($transfer['reference'], ENT_QUOTES, 'UTF-8') ?></strong></td><td><strong><?= htmlspecialchars($transfer['currency'], ENT_QUOTES, 'UTF-8') ?> <?= number_format((float) $transfer['amount'], 2) ?></strong></td><td><div class="site-badge"><?= htmlspecialchars(ucfirst($transfer['status']), ENT_QUOTES, 'UTF-8') ?></div></td><td><?= htmlspecialchars($transfer['created_at'], ENT_QUOTES, 'UTF-8') ?></td></tr><?php endforeach; ?><?php if (!$transfers): ?><tr><td colspan="5">No transfer records found.</td></tr><?php endif; ?></tbody></table></div></div></div></div></div></div></div></div></div></div></body></html>
