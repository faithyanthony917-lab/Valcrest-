<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_page_auth();
$query = $pdo->prepare(
    'SELECT i.reference, i.principal, i.expected_return, i.currency, i.starts_at,
            i.matures_at, i.status, i.released_at, p.name AS plan_name,
            p.return_rate, p.term_days, p.capital_back
     FROM investments i
     JOIN investment_plans p ON p.id = i.plan_id
     WHERE i.user_id = :user_id
     ORDER BY i.created_at DESC'
);
$query->execute(['user_id' => $userId]);
$investments = $query->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Valcrest Meridian Capital - Schema Logs</title>
    <link rel="stylesheet" href="/assets/frontend/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/global/css/custom.css">
    <link rel="stylesheet" href="/assets/frontend/css/styles.css?var=2.1">
</head>
<body class="dark-theme">
<div class="panel-layout">
    <div class="panel-header">
        <div class="logo"><a href="/portal/dashboard"><span class="brand-logo">Valcrest Meridian Capital</span></a></div>
        <div class="nav-wrap"><div class="nav-right"><a class="user-sidebar-btn" href="/portal/investments">Invest Now</a></div></div>
    </div>
    <div class="page-container"><div class="main-content"><div class="section-gap"><div class="container-fluid">
        <div class="site-card"><div class="site-card-header"><h3 class="title">All Invested Schemas</h3></div>
            <div class="site-card-body"><div class="table-responsive"><table class="display data-table">
                <thead><tr><th>Reference</th><th>Schema</th><th>Principal</th><th>Return</th><th>Matures</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($investments as $investment): ?>
                    <tr>
                        <td><?= htmlspecialchars($investment['reference'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($investment['plan_name'], ENT_QUOTES, 'UTF-8') ?><br><small><?= number_format((float) $investment['return_rate'], 2) ?>% / <?= (int) $investment['term_days'] ?> days</small></td>
                        <td><?= htmlspecialchars($investment['currency'], ENT_QUOTES, 'UTF-8') ?> <?= number_format((float) $investment['principal'], 2) ?></td>
                        <td><?= htmlspecialchars($investment['currency'], ENT_QUOTES, 'UTF-8') ?> <?= number_format((float) $investment['expected_return'], 2) ?></td>
                        <td><?= htmlspecialchars($investment['matures_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars(ucfirst($investment['status']), ENT_QUOTES, 'UTF-8') ?><?= $investment['released_at'] ? '<br><small>Released ' . htmlspecialchars($investment['released_at'], ENT_QUOTES, 'UTF-8') . '</small>' : '' ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$investments): ?><tr><td colspan="6">No investments have been created yet.</td></tr><?php endif; ?>
                </tbody>
            </table></div></div>
        </div>
    </div></div></div></div>
</div>
</body>
</html>
