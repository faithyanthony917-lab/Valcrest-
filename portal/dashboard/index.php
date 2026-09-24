<?php
require __DIR__ . '/../../backend/src/bootstrap.php';
$dashboardUserId = require_page_auth();
$dashboardAccountQuery = $pdo->prepare('SELECT currency, available_balance FROM accounts WHERE user_id = :user_id ORDER BY id LIMIT 1');
$dashboardAccountQuery->execute(['user_id' => $dashboardUserId]);
$dashboardAccount = $dashboardAccountQuery->fetch() ?: ['currency' => 'GBP', 'available_balance' => '0.00'];
$dashboardBalance = number_format((float) $dashboardAccount['available_balance'], 2);
$dashboardCurrency = htmlspecialchars((string) $dashboardAccount['currency'], ENT_QUOTES, 'UTF-8');
$dashboardUserQuery = $pdo->prepare('SELECT role, status FROM users WHERE id = :user_id LIMIT 1');
$dashboardUserQuery->execute(['user_id' => $dashboardUserId]);
$dashboardUser = $dashboardUserQuery->fetch() ?: [];
$isAdmin = ($dashboardUser['role'] ?? '') === 'admin' && ($dashboardUser['status'] ?? '') === 'active';
$dashboardTransactionQuery = $pdo->prepare('SELECT COUNT(*) FROM ledger_transactions WHERE user_id = :user_id');
$dashboardTransactionQuery->execute(['user_id' => $dashboardUserId]);
$dashboardTransactionCount = (int) $dashboardTransactionQuery->fetchColumn();
$dashboardTicketQuery = $pdo->prepare('SELECT COUNT(*) FROM support_tickets WHERE user_id = :user_id');
$dashboardTicketQuery->execute(['user_id' => $dashboardUserId]);
$dashboardTicketCount = (int) $dashboardTicketQuery->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="keywords" content="Valcrest Meridian Capital">
    <meta name="description" content="Valcrest Meridian Capital">
    <link rel="canonical" href="https://valcrestmeridiancapital.com/portal/dashboard"/>
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/x-icon"/>

    <link rel="icon" href="/assets/images/favicon.png" type="image/x-icon"/>
    <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/global/css/fontawesome.min.css"/>
    <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/frontend/css/vendor/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/frontend/css/animate.css"/>
    <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/frontend/css/owl.carousel.min.css"/>
    <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/global/css/nice-select.css"/>
    <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/global/css/datatables.min.css"/>
    <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/global/css/simple-notify.min.css"/>
        <link rel="stylesheet" type="text/css" href="https://valcrestmeridiancapital.com/assets/vendor/mckenziearts/laravel-notify/css/notify.css"/>        <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/global/css/custom.css"/>
    <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/frontend/css/magnific-popup.css"/>
            <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/frontend/css/aos.css"/>
        <link rel="stylesheet" href="https://valcrestmeridiancapital.com/assets/frontend/css/styles.css?var=2.1"/>

    <style>
        //The Custom CSS will be added on the site head tag 
.site-head-tag {
	margin: 0;
  	padding: 0;
}
    </style>

    <title>Valcrest Meridian Capital -     Dashboard
</title>


</head>
<body class="dark-theme">
<script>
    var notify = {
        timeout: "5000",
    }
</script>
<!--Full Layout-->
<div class="panel-layout">
    <!--Header-->
    <div class="panel-header">
    <div class="logo">
        <a href="https://valcrestmeridiancapital.com/share">
            <span class="brand-logo">Valcrest Meridian Capital</span>
            <span class="brand-logo">Valcrest Meridian Capital</span>
        </a>
    </div>
    <div class="nav-wrap">
        <div class="nav-left">
            <button class="sidebar-toggle">
                <i class="anticon anticon-arrow-left"></i>
            </button>
            <div class="mob-logo">
                <a href="https://valcrestmeridiancapital.com/share">
                    <span class="brand-logo">Valcrest Meridian Capital</span>
                </a>
            </div>
        </div>
        <div class="nav-right">
            <div class="single-nav-right">
                
                <div class="single-right">
                    <div class="color-switcher">
                        <i icon-name="moon" class="dark-icon" data-mode="dark"></i>
                        <i icon-name="sun" class="light-icon" data-mode="light"></i>
                    </div>
                </div>

                
                                                        <div class="single-nav-right user-notifications896">
                        <button type="button" class="item notification-dot" data-bs-toggle="dropdown" aria-expanded="false">
    <i icon-name="bell-ring" class=""></i>
    <div class="number">0</div>
</button>
<div class="dropdown-menu dropdown-menu-end notification-pop">
    <div class="noti-head">Notifications <span>0</span></div>
    <div class="all-noti">
        
                    <p>Notification Not Found</p>
            </div>

    </div>


                    </div>
                                

                <div class="single-right">
                    <!--<select name="language" id="" class="site-nice-select"-->
                    <!--        onchange="window.location.href=this.options[this.selectedIndex].value;">-->
                    <!--    -->
                    <!--        <option-->
                    <!--            value="https://valcrestmeridiancapital.com/language-update?name=en" selected>English</option>-->
                    <!--    -->
                    <!--        <option-->
                    <!--            value="https://valcrestmeridiancapital.com/language-update?name=es" >Spanish</option>-->
                    <!--    -->
                    <!--        <option-->
                    <!--            value="https://valcrestmeridiancapital.com/language-update?name=fr" >Franch</option>-->
                    <!--    -->
                    <!--</select>-->
                    
                    
                     <div id="google_element"></div>

<script src="https://translate.google.com/translate_a/element.js?cb=loadGoogleTranslate"></script>

<script>
function loadGoogleTranslate() {
    new google.translate.TranslateElement({
        pageLanguage: 'en', // You can specify the default language here
        layout: google.translate.TranslateElement.InlineLayout.VERTICAL
    }, 'google_element');
}
</script>


                </div>
                <div class="single-right">
                    <button
                        type="button"
                        class="item"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="anticon anticon-user"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="https://valcrestmeridiancapital.com/portal/settings" class="dropdown-item" type="button"><i
                                    class="anticon anticon-setting"></i>Settings</a>
                        </li>
                        <li>
                            <a href="https://valcrestmeridiancapital.com/portal/settings/change-password" class="dropdown-item" type="button">
                                <i class="anticon anticon-lock"></i>Change Password
                            </a>
                        </li>
                        <li>
                            <a href="https://valcrestmeridiancapital.com/portal/support" class="dropdown-item" type="button">
                                <i class="anticon anticon-customer-service"></i>Support Tickets
                            </a>
                        </li>
                        <li class="logout">
                            <form method="POST" action="/api/auth/logout.php" id="logout-form">
                                                                <a href="/api/auth/logout.php" class="dropdown-item"
                                   onclick="event.preventDefault(); localStorage.clear();  $('#logout-form').submit();"><i
                                        class="anticon anticon-logout"></i>Logout</a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = 'a1ebda6419f6a0ce739d7c1ece796621462889e5';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript> Powered by <a href=â€œhttps://www.smartsupp.comâ€ target=â€œ_blankâ€>Smartsupp</a></noscript>    <!--/Header-->

    <div class="desktop-screen-show">
        <div class="side-nav">
    <div class="side-wallet-box default-wallet mb-0">
        <div class="user-balance-card">
            <div class="wallet-name">
                <div class="name">Account Balance</div>
                <div class="default">Wallet</div>
            </div>
            <div class="wallet-info">
                <div class="wallet-id"><i icon-name="wallet"></i>Main Wallet</div>
                <div class="balance"><?= $dashboardCurrency ?> <?= htmlspecialchars($dashboardBalance, ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            <div class="wallet-info">
                <div class="wallet-id"><i icon-name="landmark"></i>Profit Wallet</div>
                <div class="balance"><?= $dashboardCurrency ?> 0.00</div>
            </div>
        </div>
        <div class="actions">
            <a href="https://valcrestmeridiancapital.com/portal/deposits" class="user-sidebar-btn"><i
                    class="anticon anticon-file-add"></i>Deposit</a>
            <a href="/portal/investments" class="user-sidebar-btn red-btn"><i
                    class="anticon anticon-export"></i>Invest Now</a>
        </div>
    </div>
    <div class="side-nav-inside">
        <ul class="side-nav-menu">
            <li class="side-nav-item active">
                <a href="https://valcrestmeridiancapital.com/portal/dashboard"><i
                        class="anticon anticon-appstore"></i><span>Dashboard</span></a>
            </li>
            <?php if ($isAdmin): ?>
            <li class="side-nav-item">
                <a href="/admin/"><i class="anticon anticon-setting"></i><span>Admin Console</span></a>
            </li>
            <?php endif; ?>

            <li class="side-nav-item ">
                <a href="/portal/investments"><i
                        class="anticon anticon-check-square"></i><span>All Schema</span></a>
            </li>
            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/investments/logs"><i
                        class="anticon anticon-copy"></i><span>Schema Logs</span></a>
            </li>

            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/transactions"><i
                        class="anticon anticon-inbox"></i><span>All Transactions</span></a>
            </li>


            <li class="side-nav-item   ">
                <a href="https://valcrestmeridiancapital.com/portal/deposits"><i
                        class="anticon anticon-file-add"></i><span>Add Money</span></a>
            </li>
            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/deposits/logs"><i
                        class="anticon anticon-folder-add"></i><span>Add Money Log</span></a>
            </li>

            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/wallet/exchange"><i
                        class="anticon anticon-transaction"></i><span>Wallet Exchange</span></a>
            </li>

            <li class="side-nav-item   ">
                <a href="https://valcrestmeridiancapital.com/portal/transfers"><i
                        class="anticon anticon-export"></i><span>Send Money</span></a>
            </li>
            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/transfers/logs"><i
                        class="anticon anticon-cloud"></i><span>Send Money Log</span></a>
            </li>

            <li class="side-nav-item   ">
                <a href="https://valcrestmeridiancapital.com/portal/withdrawals"><i
                        class="anticon anticon-bank"></i><span>Withdraw</span></a>
            </li>
            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/withdrawals/logs"><i
                        class="anticon anticon-credit-card"></i><span>Withdraw Log</span></a>
            </li>

            <li class="side-nav-item ">
                <a href="/portal/investments"><i
                        class="anticon anticon-star"></i><span>Ranking Badge</span></a>
            </li>

                            <li class="side-nav-item ">
                    <a href="https://valcrestmeridiancapital.com/portal/referral"><i
                            class="anticon anticon-usergroup-add"></i><span>Referral</span></a>
                </li>
            
            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/settings"><i
                        class="anticon anticon-setting"></i><span>Settings</span></a>
            </li>
            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/support"
                ><i class="anticon anticon-tool"></i><span>Support Tickets</span></a
                >
            </li>

            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/notifications"
                ><i class="anticon anticon-notification"></i><span>Notifications</span></a
                >
            </li>

            <li class="side-nav-item">
                <!-- Authentication -->
                <form method="POST" action="/api/auth/logout.php">
                                        <button type="submit" class="site-btn grad-btn w-100">
                        <i class="anticon anticon-logout"></i><span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
    </div>

    <div class="page-container">
        <div class="main-content">
            <div class="section-gap">
                <div class="container-fluid">
                                        <!--Page Content-->
                    
        <div class="desktop-screen-show">
            
            <div class="row">
    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
        <div class="user-ranking" >
            <h4>Level 1</h4>
            <p>Valcrest Member</p>
            <div class="rank" data-bs-toggle="tooltip" data-bs-placement="top" title="By signing up to the account">
                <img src="https://valcrestmeridiancapital.com/assets/global/images/sCQgIyl0OKzFiO73nmWF.svg" alt="">
            </div>
        </div>
    </div>
            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">Referral URL</h3>
                </div>
                <div class="site-card-body">
                    <div class="referral-link">
                        <div class="referral-link-form">
                            <input type="text" value="https://valcrestmeridiancapital.com/auth/register/" id="refLink"/>
                            <button type="submit" onclick="copyRef()">
                                <i class="anticon anticon-copy"></i>
                                <span id="copy">Copy</span>
                            </button>
                        </div>
                        <p class="referral-joined">
                            Referral activity will appear after users join with your referral link
                        </p>
                    </div>
                </div>
            </div>
        </div>
    
</div>

            
            <div class="row user-cards ">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-inbox"></i></div>
            <div class="content">
                <h4><span class="count">—</span></h4>
                <p>All Transactions</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-file-add"></i></div>
            <div class="content">
                <h4><b>$</b><span class="count">—</span></h4>
                <p>Total Deposit</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-check-square"></i></div>
            <div class="content">
                <h4><b>$</b><span class="count">—</span></h4>
                <p>Total Investment</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-credit-card"></i></div>
            <div class="content">
                <h4><b>$</b><span class="count">—</span></h4>
                <p>Total Profit</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-arrow-right"></i></div>
            <div class="content">
                <h4><b>$</b><span class="count">—</span></h4>
                <p>Total Transfer </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-money-collect"></i></div>
            <div class="content">
                <h4><b>$</b><span class="count">—</span></h4>
                <p>Total Withdraw</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-gift"></i></div>
            <div class="content">
                <h4><b>$</b><span class="count">—</span>
                </h4>
                <p>Referral Bonus</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-account-book"></i></div>
            <div class="content">
                <h4><b>$</b><span class="count">—</span></h4>
                <p>Deposit Bonus</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-gold"></i></div>
            <div class="content">
                <h4><b>$</b><span class="count">—</span></h4>
                <p>Investment Bonus</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-inbox"></i></div>
            <div class="content">
                <h4 class="count">—</h4>
                <p>Total Referral</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-radar-chart"></i></div>
            <div class="content">
                <h4 class="count">â€”</h4>
                <p>Rank Achieved</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="single">
            <div class="icon"><i class="anticon anticon-question"></i></div>
            <div class="content">
                <h4 class="count"><?= $dashboardTicketCount ?></h4>
                <p>Total Ticket</p>
            </div>
        </div>
    </div>
</div>

            
            <div class="row">
    <div class="col-xl-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">Recent Transactions</h3>
            </div>
            <div class="site-card-body table-responsive">
                <div class="site-datatable">
                    <table class="display data-table">
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th>Transactions ID</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Gateway</th>
                        </tr>
                        </thead>
                        <tbody>
                        

                                                    <tr class="centered">
                                <td colspan="7">No Data Found</td>
                            </tr>
                                                </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>

         
        <div class="mobile-screen-show">
            <style>
    .user-wallets-mobile{
        
    background-color:black;
        
    }
    
    .user-ranking-mobile{
            background-color:black;

        
    }
    .wallet-shadow{
            background-color:black !important;

        
    }
</style>


<div class="row">
    <div class="col-12">
        <div class="user-ranking-mobile">
            <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/global/materials/user.png" alt=""/></div>
            <div class="name">
                <h4>Hi, Emmanuel Edidiong</h4>
                <p>Valcrest Member - <span>Level 1</span></p>
            </div>
            <div class="rank-badge"><img src="https://valcrestmeridiancapital.com/assets/global/images/sCQgIyl0OKzFiO73nmWF.svg" alt=""/></div>
        </div>
        <div class="user-wallets-mobile">
            <img src="https://valcrestmeridiancapital.com/assets/frontend/materials/wallet-shadow.png" alt="" class="wallet-shadow">
            <div class="head">All Wallets in <?= $dashboardCurrency ?></div>
            <div class="one">
                <div class="balance">

                    <span class="symbol">$</span>0<span
                        class="after-dot">.00 </span>
                </div>
                <div class="wallet">Main Wallet</div>
            </div>


            <div class="one p-wal">
                <div class="balance">
                    <span class="symbol">$</span>0<span
                        class="after-dot">.00 </span>
                </div>
                <div class="wallet">Profit Wallet</div>
            </div>
            <div class="info">
                <i icon-name="info"></i>Account earnings are shown after completed transactions
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="mob-shortcut-btn">
            <a href="https://valcrestmeridiancapital.com/portal/deposits"><i icon-name="download"></i> Deposit</a>
            <a href="/portal/investments"><i icon-name="box"></i> Investment</a>
            <a href="https://valcrestmeridiancapital.com/portal/withdrawals"><i icon-name="send"></i> Withdraw</a>
        </div>
    </div>
    
    
    <div class="col-12">
    
<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
  <div class="tradingview-widget-container__widget"></div>

  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
  {
  "symbol": "BINANCE:BTCUSDT",
  "width": 350,
  "height": 220,
  "locale": "en",
  "dateRange": "12M",
  "colorTheme": "light",
  "isTransparent": false,
  "autosize": false,
  "largeChartUrl": ""
}
  </script>
</div>
<!-- TradingView Widget END -->
  
</div>

    <div class="col-12">
        <!-- all navigation -->
        <div class="all-feature-mobile mb-3 mobile-screen-show">
    <div class="title">All Navigations</div>
    <div class="contents row">
        <div class="col-4">
            <div class="single">
                <a href="/portal/investments">
                    <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/schema.png" alt="">
                    </div>
                    <div class="name">Schemas</div>
                </a>
            </div>
        </div>
        <div class="col-4">
            <div class="single">
                <a href="https://valcrestmeridiancapital.com/portal/investments/logs">
                    <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/schema-log.png" alt="">
                    </div>
                    <div class="name">Investment</div>
                </a>
            </div>
        </div>
        <div class="col-4">
            <div class="single">
                <a href="https://valcrestmeridiancapital.com/portal/transactions">
                    <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/transactions.png" alt="">
                    </div>
                    <div class="name">Transactions</div>
                </a>
            </div>
        </div>
        <div class="col-4">
            <div class="single">
                <a href="https://valcrestmeridiancapital.com/portal/deposits">
                    <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/deposit.png" alt="">
                    </div>
                    <div class="name">Deposit</div>
                </a>
            </div>
        </div>
        <div class="col-4">
            <div class="single">
                <a href="https://valcrestmeridiancapital.com/portal/deposits/logs">
                    <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/deposit-log.png" alt="">
                    </div>
                    <div class="name">Deposit Log</div>
                </a>
            </div>
        </div>
        <div class="col-4">
            <div class="single">
                <a href="https://valcrestmeridiancapital.com/portal/wallet/exchange">
                    <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/wallet-exchange.png"
                                           alt="">
                    </div>
                    <div class="name">Wallet Exch.</div>
                </a>
            </div>
        </div>
    </div>
    <div class="moretext">
        <div class="row contents">
            <div class="col-4">
                <div class="single">
                    <a href="https://valcrestmeridiancapital.com/portal/transfers">
                        <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/transfer.png"
                                               alt="">
                        </div>
                        <div class="name">Transfer</div>
                    </a>
                </div>
            </div>
            <div class="col-4">
                <div class="single">
                    <a href="https://valcrestmeridiancapital.com/portal/transfers/logs">
                        <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/transfer-log.png"
                                               alt="">
                        </div>
                        <div class="name">Transfer Log</div>
                    </a>
                </div>
            </div>
            <div class="col-4">
                <div class="single">
                    <a href="https://valcrestmeridiancapital.com/portal/withdrawals">
                        <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/withdraw.png"
                                               alt="">
                        </div>
                        <div class="name">Withdraw</div>
                    </a>
                </div>
            </div>
            <div class="col-4">
                <div class="single">
                    <a href="https://valcrestmeridiancapital.com/portal/withdrawals/logs">
                        <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/withdraw-log.png"
                                               alt="">
                        </div>
                        <div class="name">Withdraw Log</div>
                    </a>
                </div>
            </div>
            <div class="col-4">
                <div class="single">
                    <a href="/portal/investments">
                        <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/ranking.png"
                                               alt="">
                        </div>
                        <div class="name">Ranking Badge</div>
                    </a>
                </div>
            </div>
            <div class="col-4">
                <div class="single">
                    <a href="https://valcrestmeridiancapital.com/portal/referral">
                        <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/referral.png"
                                               alt="">
                        </div>
                        <div class="name">Referral</div>
                    </a>
                </div>
            </div>
            <div class="col-4">
                <div class="single">
                    <a href="https://valcrestmeridiancapital.com/portal/settings">
                        <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/settings.png"
                                               alt="">
                        </div>
                        <div class="name">Settings</div>
                    </a>
                </div>
            </div>
            <div class="col-4">
                <div class="single">
                    <a href="https://valcrestmeridiancapital.com/portal/support">
                        <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/support-ticket.png"
                                               alt="">
                        </div>
                        <div class="name">Support Ticket</div>
                    </a>
                </div>
            </div>
            <div class="col-4">
                <div class="single">
                    <a href="https://valcrestmeridiancapital.com/portal/notifications">
                        <div class="icon"><img src="https://valcrestmeridiancapital.com/assets/frontend/materials/profile.png"
                                               alt="">
                        </div>
                        <div class="name">Notifications</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="centered">
        <button class="moreless-button site-btn-sm grad-btn">Load more</button>
    </div>
</div>

        <!-- all Statistic -->
        <div class="all-feature-mobile mb-3 mobile-screen-show">
    <div class="title">All Statistic</div>
    <div class="row">
        <div class="col-12">
            <div class="all-cards-mobile">
                <div class="contents row">
                    <div class="col-12">
                        <div class="single-card">
                            <div class="icon"><i icon-name="arrow-left-right"></i></div>
                            <div class="content">
                                <div class="amount count">0</div>
                                <div class="name">All Transactions</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="single-card">
                            <div class="icon"><i icon-name="download"></i></div>
                            <div class="content">
                                <div class="amount">$<span
                                        class="count">0</span>
                                </div>
                                <div class="name">Total Deposit</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="single-card">
                            <div class="icon"><i icon-name="box"></i></div>
                            <div class="content">
                                <div class="amount">$<span
                                        class="count">0</span>
                                </div>
                                <div class="name">Total Investment</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="moretext-2">
                    <div class="contents row">
                        <div class="col-12">
                            <div class="single-card">
                                <div class="icon"><i icon-name="credit-card"></i></div>
                                <div class="content">
                                    <div class="amount"> $<span
                                            class="count">0</span>
                                    </div>
                                    <div class="name">Total Profit</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="single-card">
                                <div class="icon"><i icon-name="log-in"></i></div>
                                <div class="content">
                                    <div class="amount">$<span
                                            class="count">0</span>
                                    </div>
                                    <div class="name">Total Transfer</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="single-card">
                                <div class="icon"><i icon-name="send"></i></div>
                                <div class="content">
                                    <div class="amount"> $<span
                                            class="count">0</span>
                                    </div>
                                    <div class="name">Total Withdraw</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="single-card">
                                <div class="icon"><i icon-name="users-2"></i></div>
                                <div class="content">
                                    <div class="amount"> $<span
                                            class="count">0</span>
                                    </div>
                                    <div class="name">Referral Bonus</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="single-card">
                                <div class="icon"><i icon-name="anchor"></i></div>
                                <div class="content">
                                    <div class="amount">$<span class="count">—</span>
                                    </div>
                                    <div class="name">Deposit Bonus</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="single-card">
                                <div class="icon"><i icon-name="archive"></i></div>
                                <div class="content">
                                    <div class="amount">$<span
                                            class="count">0</span>
                                    </div>
                                    <div class="name"> Investment Bonus</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="single-card">
                                <div class="icon"><i icon-name="gift"></i></div>
                                <div class="content">
                                    <div class="amount count">0</div>
                                    <div class="name"> Total Referral</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="single-card">
                                <div class="icon"><i icon-name="award"></i></div>
                                <div class="content">
                                    <div class="amount count"> 1</div>
                                    <div class="name">Rank Achieved</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="single-card">
                                <div class="icon"><i icon-name="alert-triangle"></i>
                                </div>
                                <div class="content">
                                    <div class="amount count">0</div>
                                    <div class="name"> Total Ticket</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="centered">
                    <button class="moreless-button-2 site-btn-sm grad-btn">Load more</button>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .single-card{
        background-color:white !important;
    }
    
    
    
    .grad-btn{
        background-color:black !important;
    }
        
    
</style>
        <!-- Recent Transactions -->
        <div class="all-feature-mobile mobile-transactions mb-3 mobile-screen-show">
    <div class="title">Recent Transactions</div>
    <div class="contents">

            </div>
</div>
    </div>

    <div class="col-12">
        <div class="mobile-ref-url mb-4">
            <div class="all-feature-mobile">
                <div class="title">Referral URL</div>
                <div class="mobile-referral-link-form">
                    <input type="text" value="https://valcrestmeridiancapital.com/auth/register/" id="refLink"/>
                    <button type="submit" onclick="copyRef()">
                        <span id="copy">Copy</span>
                    </button>
                </div>
                <p class="referral-joined">Referral activity will appear after users join with your referral link</p>
            </div>
        </div>
    </div>
</div>



  
    
    
    
    
    
    
    
    <style>
    
    .grad-btn {
    background: white;
    color: #ffffff;
}
        
        .mob-shortcut-btn a:first-child {
    margin-left: 0;
    background: #ffffff;
}

.mob-shortcut-btn a {
    display: block;
    color: #001219;
    text-align: center;
    background: #ffffff;
    border-radius: 10px;
    font-size: 14px;
    width: 100%;
    margin: 0 5px;
    padding: 15px 0;
}


.mob-shortcut-btn a:last-child {
    margin-right: 0;
    background: #ffffff;
}
    
</style>        </div>


                    <!--Page Content-->
                </div>
            </div>
        </div>
    </div>


    <!-- Show in 575px in Mobile Screen -->
    <div class="mobile-screen-show">
        <div class="bottom-appbar">
    <a href="https://valcrestmeridiancapital.com/portal/dashboard" class="active">
        <i icon-name="layout-dashboard"></i>
    </a>
    <a href="https://valcrestmeridiancapital.com/portal/deposits" class="">
        <i icon-name="download"></i>
    </a>
    <a href="/portal/investments" class="">
        <i icon-name="box"></i>
    </a>
    <a href="https://valcrestmeridiancapital.com/portal/referral" class="">
        <i icon-name="gift"></i>
    </a>
    <a href="https://valcrestmeridiancapital.com/portal/settings" class="">
        <i icon-name="settings"></i>
    </a>
</div>


<style>
    .bottom-appbar{
        background-color:black;
    }
</style>    </div>

    <!-- Show in 575px in Mobile Screen End -->

    <!-- Automatic Popup -->
    
    <!-- /Automatic Popup End -->
</div>
<!--/Full Layout-->

<script src="https://valcrestmeridiancapital.com/assets/global/js/jquery.min.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/global/js/jquery-migrate.js"></script>

<script src="https://valcrestmeridiancapital.com/assets/frontend/js/bootstrap.bundle.min.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/frontend/js/scrollUp.min.js"></script>

<script src="https://valcrestmeridiancapital.com/assets/frontend/js/owl.carousel.min.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/global/js/waypoints.min.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/frontend/js/jquery.counterup.min.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/global/js/jquery.nice-select.min.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/global/js/lucide.min.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/frontend/js/magnific-popup.min.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/frontend/js/aos.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/global/js/datatables.min.js" type="text/javascript" charset="utf8"></script>
<script src="https://valcrestmeridiancapital.com/assets/global/js/simple-notify.min.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/frontend/js/main.js?var=5"></script>
<script src="https://valcrestmeridiancapital.com/assets/frontend/js/cookie.js"></script>
<script src="https://valcrestmeridiancapital.com/assets/global/js/custom.js?var=5"></script>
    <script src="https://valcrestmeridiancapital.com/assets/global/js/pusher.min.js"></script>
    <script>
    (function ($) {
        'use strict';

        let pusherAppKey = "";
        let pusherAppCluster = "mt1";
        let soundUrl = "";

        var notification = new Pusher(pusherAppKey, {
            encrypted: true,
            cluster: pusherAppCluster,
        });
        var channel = notification.subscribe('user-notification896');
        channel.bind('notification-event', function (result) {
            playSound();
            latestNotification();
            notifyToast(result);
        });

        function latestNotification() {
            $.get('/api/auth/me.php', function (data) {
                $('.user-notifications896').html(data);
            })
        }

        function notifyToast(data) {
            new Notify({
                status: 'info',
                title: data.data.title,
                text: data.data.notice,
                effect: 'slide',
                speed: 300,
                customClass: '',
                customIcon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone"><path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>',
                showIcon: true,
                showCloseButton: true,
                autoclose: true,
                autotimeout: 9000,
                gap: 20,
                distance: 20,
                type: 1,
                position: 'right bottom',
                customWrapper: '<div><a href="' + data.data.action_url + '" class="learn-more-link">Explore<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="external-link" class="lucide lucide-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" x2="21" y1="14" y2="3"></line></svg></a></div>',
            })

        }

        function playSound() {
            $.get(soundUrl, function (data) {
                var audio = new Audio(data);
                audio.play();
                audio.muted = false;
            });
        }



    })(jQuery);
</script>
    <script>
        (function ($) {
            'use strict';
            // AOS initialization
            AOS.init();
        })(jQuery);
    </script>
    <script>
        (function ($) {
            'use strict';
            // To top
            $.scrollUp({
                scrollText: '<i class="fas fa-caret-up"></i>',
                easingType: 'linear',
                scrollSpeed: 500,
                animation: 'fade'
            });
        })(jQuery);
    </script>

<script type="text/javascript" src="https://valcrestmeridiancapital.com/assets/vendor/mckenziearts/laravel-notify/js/notify.js"></script>
    <script>
        function copyRef() {
            /* Get the text field */
            var textToCopy = $('#refLink').val();
            // Create a temporary input element
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(textToCopy).select();
            // Copy the text from the temporary input
            document.execCommand('copy');
            // Remove the temporary input element
            tempInput.remove();
            $('#copy').text('Copied'); var copyApi = document.getElementById("refLink");
            /* Select the text field */
            copyApi.select();
            copyApi.setSelectionRange(0, 999999999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand('copy');
            $('#copy').text('Copied')

        }

        // Load More
        $('.moreless-button').click(function () {
            $('.moretext').slideToggle();
            if ($('.moreless-button').text() == "Load more") {
                $(this).text("Load less")
            } else {
                $(this).text("Load more")
            }
        });

        $('.moreless-button-2').click(function () {
            $('.moretext-2').slideToggle();
            if ($('.moreless-button-2').text() == "Load more") {
                $(this).text("Load less")
            } else {
                $(this).text("Load more")
            }
        });
    </script>
    <script>
        // Color Switcher
        $(".color-switcher").on('click', function () {
            "use strict"
            $("body").toggleClass("dark-theme");
            var url = '/api/auth/me.php';
            $.get(url)
        });
    </script>







</body>
</html>
