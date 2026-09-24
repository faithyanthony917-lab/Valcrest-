<?php

declare(strict_types=1);

require __DIR__ . '/../../backend/src/bootstrap.php';
$userId = require_page_auth();
$planId = filter_var($_GET['plan'] ?? null, FILTER_VALIDATE_INT);
if (!$planId) {
    header('Location: /portal/investments/');
    exit;
}
$planQuery = $pdo->prepare(
    "SELECT id, name, description, currency, minimum_amount, maximum_amount,
            return_rate, term_days, capital_back
     FROM investment_plans WHERE id = :id AND status = 'active'"
);
$planQuery->execute(['id' => $planId]);
$plan = $planQuery->fetch();
if (!$plan) {
    http_response_code(404);
    exit('Investment plan not found.');
}
$walletQuery = $pdo->prepare(
    "SELECT id, currency, available_balance FROM accounts
     WHERE user_id = :user_id AND wallet_type = 'main' AND currency = :currency
     ORDER BY id"
);
$walletQuery->execute(['user_id' => $userId, 'currency' => $plan['currency']]);
$wallets = $walletQuery->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Investment - Valcrest Meridian Capital</title>
    <link rel="stylesheet" href="/assets/frontend/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/global/css/custom.css">
    <link rel="stylesheet" href="/assets/frontend/css/styles.css?var=2.1">
</head>
<body class="dark-theme">
<div class="panel-layout">
    <div class="panel-header"><div class="logo"><a href="/portal/dashboard"><span class="brand-logo">Valcrest Meridian Capital</span></a></div></div>
    <div class="page-container"><div class="main-content"><div class="section-gap"><div class="container-fluid">
        <div class="site-card"><div class="site-card-header"><h3 class="title">Confirm Investment</h3></div><div class="site-card-body">
            <h4><?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?></h4>
            <p><?= htmlspecialchars((string) ($plan['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
            <dl class="row">
                <dt class="col-sm-5">Return rate</dt><dd class="col-sm-7"><?= number_format((float) $plan['return_rate'], 2) ?>%</dd>
                <dt class="col-sm-5">Period</dt><dd class="col-sm-7"><?= (int) $plan['term_days'] ?> days</dd>
                <dt class="col-sm-5">Capital back</dt><dd class="col-sm-7"><?= (int) $plan['capital_back'] === 1 ? 'Yes' : 'No' ?></dd>
            </dl>
            <div id="investment-result" role="status"></div>
            <form data-investment-form data-return-rate="<?= htmlspecialchars((string) $plan['return_rate'], ENT_QUOTES, 'UTF-8') ?>" data-currency="<?= htmlspecialchars($plan['currency'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="planId" value="<?= (int) $plan['id'] ?>">
                <label class="form-label">Investment amount</label>
                <input class="form-control mb-3" name="amount" type="number" min="<?= htmlspecialchars((string) $plan['minimum_amount'], ENT_QUOTES, 'UTF-8') ?>" max="<?= $plan['maximum_amount'] === null ? '' : htmlspecialchars((string) $plan['maximum_amount'], ENT_QUOTES, 'UTF-8') ?>" step="0.01" required>
                <p>Expected return: <strong data-expected-return><?= htmlspecialchars($plan['currency'], ENT_QUOTES, 'UTF-8') ?> 0.00</strong></p>
                <label class="form-label">Debit wallet</label>
                <select class="form-control mb-3" name="accountId" required>
                    <option value="">Select wallet</option>
                    <?php foreach ($wallets as $wallet): ?><option value="<?= (int) $wallet['id'] ?>"><?= htmlspecialchars($wallet['currency'], ENT_QUOTES, 'UTF-8') ?> <?= number_format((float) $wallet['available_balance'], 2) ?></option><?php endforeach; ?>
                </select>
                <button class="user-sidebar-btn red-btn" type="submit">Confirm and Invest</button>
                <a class="user-sidebar-btn" href="/portal/investments/">Back to Plans</a>
            </form>
        </div></div>
    </div></div></div></div>
</div>
<script src="/assets/js/portal-actions.js"></script>
</body>
</html>
