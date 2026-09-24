<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_page_auth();
$query = $pdo->prepare(
    'SELECT l.type, l.amount, l.currency, l.status, l.description, l.created_at, a.wallet_type
     FROM ledger_transactions l
     JOIN accounts a ON a.id = l.account_id
     WHERE l.user_id = :user_id
     ORDER BY l.created_at DESC
     LIMIT 250'
);
$query->execute(['user_id' => $userId]);
$transactions = $query->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Valcrest Meridian Capital - All Transactions</title>
    <link rel="stylesheet" href="/assets/frontend/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/global/css/custom.css">
    <link rel="stylesheet" href="/assets/frontend/css/styles.css?var=2.1">
</head>
<body class="dark-theme">
<div class="panel-layout">
    <div class="panel-header"><div class="logo"><a href="/portal/dashboard"><span class="brand-logo">Valcrest Meridian Capital</span></a></div><div class="nav-wrap"><div class="nav-right"><a class="user-sidebar-btn" href="/portal/dashboard">Dashboard</a></div></div></div>
    <div class="page-container"><div class="main-content"><div class="section-gap"><div class="container-fluid">
        <div class="site-card"><div class="site-card-header"><h3 class="title">All Transactions</h3></div><div class="site-card-body"><div class="table-responsive">
            <table class="display data-table"><thead><tr><th>Date</th><th>Type</th><th>Description</th><th>Wallet</th><th>Amount</th><th>Status</th></tr></thead><tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $transaction['type'])), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($transaction['description'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars(ucfirst($transaction['wallet_type']), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($transaction['currency'], ENT_QUOTES, 'UTF-8') ?> <?= number_format((float) $transaction['amount'], 2) ?></td>
                    <td><?= htmlspecialchars(ucfirst($transaction['status']), ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$transactions): ?><tr><td colspan="6">No transactions found.</td></tr><?php endif; ?>
            </tbody></table>
        </div></div></div>
    </div></div></div></div>
</div>
</body>
</html>
