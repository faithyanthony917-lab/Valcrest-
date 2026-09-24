<?php
require __DIR__ . '/../../../backend/src/bootstrap.php';
require_page_auth();
?>
<!doctype html>
<html lang="en-GB">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Valcrest Meridian Capital client dashboard." />
    <meta name="robots" content="noindex, nofollow, noarchive" />
    <link rel="canonical" href="https://valcrestmeridiancapital.com/share/user/dashboard" />
    <title>Dashboard | Valcrest Meridian Capital</title>
    <link rel="stylesheet" href="../../../brand.css" />
    <link rel="stylesheet" href="../../../assets/dashboard.css" />
  </head>
  <body>
    <div class="dashboard-shell">
      <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
          <a class="brand-logo" href="../../../">Valcrest Meridian Capital</a>
          <button class="icon-button sidebar-close" type="button" aria-label="Close navigation" data-menu-close>×</button>
        </div>
        <nav class="primary-nav" aria-label="Dashboard navigation">
          <a class="nav-link active" href="./" aria-current="page"><span class="nav-icon">▦</span> Overview</a>
          <a class="nav-link" href="#portfolio"><span class="nav-icon">◈</span> My portfolio</a>
          <a class="nav-link" href="../transactions"><span class="nav-icon">↕</span> Transactions</a>
          <a class="nav-link" href="../deposit"><span class="nav-icon">↓</span> Deposit funds</a>
          <a class="nav-link" href="../withdraw"><span class="nav-icon">↑</span> Withdraw funds</a>
          <a class="nav-link" href="#documents"><span class="nav-icon">▤</span> Documents</a>
          <a class="nav-link" href="../support-ticket/"><span class="nav-icon">?</span> Support</a>
          <a class="nav-link admin-only" href="../../../admin/" hidden><span class="nav-icon">◆</span> Admin console</a>
        </nav>
        <div class="sidebar-footer">
          <a class="nav-link" href="../change-password"><span class="nav-icon">⌑</span> Account settings</a>
          <a class="nav-link logout-link" href="../../login/"><span class="nav-icon">↪</span> Log out</a>
        </div>
      </aside>

      <div class="dashboard-main">
        <header class="topbar">
          <button class="icon-button menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" data-menu-open>☰</button>
          <div>
            <p class="eyebrow">Client portal</p>
            <h1>Good morning, <span id="client-name">Alex</span></h1>
          </div>
          <div class="topbar-actions">
            <button class="notification-button" type="button" aria-label="Notifications">
              <span aria-hidden="true">♢</span><i></i>
            </button>
            <a class="profile-chip" href="#profile" aria-label="Open profile">
              <span class="avatar">AC</span>
              <span class="profile-copy"><strong id="profile-name">Account holder</strong><small id="profile-email">Personal account</small></span>
            </a>
          </div>
        </header>

        <main class="content">
          <section class="welcome-panel">
            <div>
              <p class="eyebrow">Portfolio overview</p>
              <h2>Build with confidence.</h2>
              <p>Your long-term strategy is on track. Review your portfolio and recent activity below.</p>
            </div>
            <a class="button button-light" href="#portfolio">View portfolio <span aria-hidden="true">→</span></a>
          </section>

          <section class="stats-grid" aria-label="Portfolio summary">
            <article class="stat-card">
              <div class="stat-icon orange">◈</div>
              <div><span class="stat-label">Available balance</span><strong id="available-balance">Loading...</strong><small>Current account balance</small></div>
            </article>
            <article class="stat-card">
              <div class="stat-icon navy">↗</div>
              <div><span class="stat-label">Pending deposits</span><strong id="pending-deposits">Loading...</strong><small>Awaiting approval</small></div>
            </article>
            <article class="stat-card">
              <div class="stat-icon blue">◫</div>
              <div><span class="stat-label">Pending withdrawals</span><strong id="pending-withdrawals">Loading...</strong><small>Awaiting approval</small></div>
            </article>
            <article class="stat-card">
              <div class="stat-icon green">✓</div>
              <div><span class="stat-label">Account currency</span><strong id="account-currency">--</strong><small>Wallet currency</small></div>
            </article>
          </section>

          <div class="content-grid">
            <section class="panel chart-panel" id="portfolio">
              <div class="panel-heading">
                <div><p class="eyebrow">Performance</p><h2>Portfolio growth</h2></div>
                <select aria-label="Performance period"><option>Last 12 months</option><option>Last 6 months</option><option>Last 30 days</option></select>
              </div>
              <div class="chart" role="img" aria-label="Portfolio value increased steadily over the last twelve months">
                <div class="chart-y-axis"><span>£260k</span><span>£220k</span><span>£180k</span><span>£140k</span></div>
                <div class="chart-area"><div class="chart-line"></div><div class="chart-tooltip"><strong>£248,560</strong><span>Sep 2026</span></div><div class="chart-x-axis"><span>Oct</span><span>Dec</span><span>Feb</span><span>Apr</span><span>Jun</span><span>Aug</span></div></div>
              </div>
            </section>

            <section class="panel allocation-panel">
              <div class="panel-heading"><div><p class="eyebrow">Allocation</p><h2>Asset mix</h2></div><a href="#portfolio">Details</a></div>
              <div class="donut-wrap"><div class="donut"><div><strong>100%</strong><span>Invested</span></div></div></div>
              <ul class="legend">
                <li><span><i class="legend-dot orange"></i> Real assets</span><strong>42%</strong></li>
                <li><span><i class="legend-dot navy"></i> Private equity</span><strong>28%</strong></li>
                <li><span><i class="legend-dot blue"></i> Credit</span><strong>18%</strong></li>
                <li><span><i class="legend-dot green"></i> Cash reserve</span><strong>12%</strong></li>
              </ul>
            </section>
          </div>

          <section class="panel activity-panel" id="transactions">
            <div class="panel-heading"><div><p class="eyebrow">Account activity</p><h2>Recent transactions</h2></div><a href="#transactions">View all</a></div>
            <div class="table-wrap">
              <table>
                <thead><tr><th>Description</th><th>Date</th><th>Type</th><th class="amount">Amount</th><th>Status</th></tr></thead>
                <tbody>
                  <tr><td><span class="transaction-icon deposit">↓</span><span><strong>Portfolio contribution</strong><small>General account</small></span></td><td>18 Sep 2026</td><td>Deposit</td><td class="amount">+£5,000.00</td><td><span class="status complete">Completed</span></td></tr>
                  <tr><td><span class="transaction-icon income">£</span><span><strong>Quarterly distribution</strong><small>Real Assets Fund</small></span></td><td>04 Sep 2026</td><td>Income</td><td class="amount">+£1,240.00</td><td><span class="status complete">Completed</span></td></tr>
                  <tr><td><span class="transaction-icon investment">↗</span><span><strong>Private Equity Fund II</strong><small>Investment allocation</small></span></td><td>28 Aug 2026</td><td>Investment</td><td class="amount">−£12,500.00</td><td><span class="status pending">Processing</span></td></tr>
                </tbody>
              </table>
            </div>
          </section>

          <section class="bottom-grid">
            <article class="panel action-panel" id="documents"><div class="panel-heading"><div><p class="eyebrow">Quick access</p><h2>Manage your account</h2></div></div>            <div class="quick-actions"><a href="../invest-logs"><span>◈</span><strong>Investment history</strong><small>Review your investments</small></a><a href="../deposit/log"><span>▤</span><strong>Deposit history</strong><small>Review account funding</small></a></div></article>
            <article class="panel support-panel" id="support"><p class="eyebrow">Need assistance?</p><h2>Speak with your adviser</h2><p>Our client team can help with your portfolio and account.</p><a class="text-link" href="../../../contact/">Contact Valcrest <span aria-hidden="true">→</span></a></article>
          </section>
          <p class="static-note" id="dashboard-status" role="status">Loading your account data...</p>
        </main>
      </div>
    </div>
    <div class="mobile-scrim" data-menu-close></div>
    <script src="../../../assets/dashboard.js"></script>
  </body>
</html>
