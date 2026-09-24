<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_page_auth();

$plans = $pdo->query(
    "SELECT id, name, description, currency, minimum_amount, maximum_amount,
            return_rate, term_days, capital_back
     FROM investment_plans
     WHERE status = 'active'
     ORDER BY id"
)->fetchAll();
$walletQuery = $pdo->prepare(
    "SELECT id, currency, available_balance
     FROM accounts
     WHERE user_id = :user_id AND wallet_type = 'main'
     ORDER BY currency, id"
);
$walletQuery->execute(['user_id' => $userId]);
$wallets = $walletQuery->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Valcrest Meridian Capital - Investment Plans</title>
    <link rel="stylesheet" href="/assets/frontend/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/global/css/custom.css">
    <link rel="stylesheet" href="/assets/frontend/css/styles.css?var=2.1">
</head>
<body class="dark-theme">
<div class="panel-layout">
    <div class="panel-header">
        <div class="logo"><a href="/portal/dashboard"><span class="brand-logo">Valcrest Meridian Capital</span></a></div>
        <div class="nav-wrap"><div class="nav-right"><a class="user-sidebar-btn" href="/portal/dashboard">Dashboard</a></div></div>
    </div>
    <div class="page-container"><div class="main-content"><div class="section-gap"><div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Investment Plans</h2>
            <a class="user-sidebar-btn" href="/portal/investments/logs">Schema Logs</a>
        </div>
        <div class="row">
            <?php foreach ($plans as $plan): ?>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="site-card h-100"><div class="site-card-body">
                        <h3><?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars((string) ($plan['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                        <ul class="list-unstyled">
                            <li>Return: <strong><?= number_format((float) $plan['return_rate'], 2) ?>%</strong></li>
                            <li>Period: <strong><?= (int) $plan['term_days'] ?> days</strong></li>
                            <li>Minimum: <strong><?= htmlspecialchars($plan['currency'], ENT_QUOTES, 'UTF-8') ?> <?= number_format((float) $plan['minimum_amount'], 2) ?></strong></li>
                            <li>Maximum: <strong><?= $plan['maximum_amount'] === null ? 'No limit' : htmlspecialchars($plan['currency'], ENT_QUOTES, 'UTF-8') . ' ' . number_format((float) $plan['maximum_amount'], 2) ?></strong></li>
                            <li>Capital back: <strong><?= (int) $plan['capital_back'] === 1 ? 'Yes' : 'No' ?></strong></li>
                        </ul>
                        <form data-investment-form>
                            <input type="hidden" name="planId" value="<?= (int) $plan['id'] ?>">
                            <label class="form-label">Amount</label>
                            <input class="form-control mb-2" name="amount" type="number" min="<?= htmlspecialchars((string) $plan['minimum_amount'], ENT_QUOTES, 'UTF-8') ?>" step="0.01" required>
                            <label class="form-label">Wallet</label>
                            <select class="form-control mb-3" name="accountId" required>
                                <option value="">Select <?= htmlspecialchars($plan['currency'], ENT_QUOTES, 'UTF-8') ?> main wallet</option>
                                <?php foreach ($wallets as $wallet): ?>
                                    <?php if ($wallet['currency'] === $plan['currency']): ?>
                                        <option value="<?= (int) $wallet['id'] ?>"><?= htmlspecialchars($wallet['currency'], ENT_QUOTES, 'UTF-8') ?> <?= number_format((float) $wallet['available_balance'], 2) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <button class="user-sidebar-btn red-btn" type="submit">Invest Now</button>
                            <a class="user-sidebar-btn" href="/portal/investments/preview?plan=<?= (int) $plan['id'] ?>">Review confirmation</a>
                        </form>
                    </div></div>
                </div>
            <?php endforeach; ?>
            <?php if (!$plans): ?><div class="col-12"><div class="site-card"><div class="site-card-body">No investment plans are currently available.</div></div></div><?php endif; ?>
        </div>
    </div></div></div></div>
</div>
<script src="/assets/js/portal-actions.js"></script>
</body>
</html>
