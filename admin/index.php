<?php
require __DIR__ . '/../backend/src/bootstrap.php';
require_admin();
?>
<!doctype html>
<html lang="en-GB">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="robots" content="noindex, nofollow, noarchive">
  <title>Admin | Valcrest Meridian Capital</title>
  <link rel="stylesheet" href="../assets/dashboard.css">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <main class="admin-shell">
    <header class="admin-header"><a class="brand-logo" href="../">Valcrest Meridian Capital</a><div><span class="eyebrow">Administration</span><h1>Operations console</h1></div><button id="logout">Log out</button></header>
    <section class="admin-grid">
      <article class="admin-card"><span class="eyebrow">Users</span><strong id="user-count">—</strong><span>registered accounts</span></article>
      <article class="admin-card"><span class="eyebrow">Requests</span><strong id="pending-count">—</strong><span>pending financial reviews</span></article>
    </section>
    <section class="admin-card table-card"><div class="panel-heading"><div><span class="eyebrow">Review queue</span><h2>Deposit and withdrawal requests</h2></div><button id="refresh">Refresh</button></div><p id="message" role="status"></p><div class="table-wrap"><table><thead><tr><th>Reference</th><th>Client</th><th>Type</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead><tbody id="requests"></tbody></table></div></section>
    <section class="admin-card table-card"><div class="panel-heading"><div><span class="eyebrow">Accounts</span><h2>Registered users</h2></div></div><div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Country</th><th>Role</th><th>Status</th></tr></thead><tbody id="users"></tbody></table></div></section>
    <section class="admin-card table-card"><div class="panel-heading"><div><span class="eyebrow">Support</span><h2>Support tickets</h2></div></div><div class="table-wrap"><table><thead><tr><th>Subject</th><th>Client</th><th>Category</th><th>Status</th><th>Updated</th><th>Action</th></tr></thead><tbody id="support-tickets"></tbody></table></div></section>
    <section class="admin-card table-card"><div class="panel-heading"><div><span class="eyebrow">Investments</span><h2>Investment activity</h2></div></div><div class="table-wrap"><table><thead><tr><th>Reference</th><th>Client</th><th>Plan</th><th>Principal</th><th>Matures</th><th>Status</th></tr></thead><tbody id="investments"></tbody></table></div></section>
  </main>
  <script src="admin.js"></script>
</body>
</html>
