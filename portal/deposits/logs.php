<?php
require __DIR__ . '/../../backend/src/bootstrap.php';
require_page_auth();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="keywords" content="Valcrest Meridian Capital">
    <meta name="description" content="Valcrest Meridian Capital">
    <link rel="canonical" href="https://valcrestmeridiancapital.com/portal/deposits/logs"/>
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

    <title>Valcrest Meridian Capital -     Deposit Logs
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
<noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>    <!--/Header-->

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
                <div class="balance">�</div>
            </div>
            <div class="wallet-info">
                <div class="wallet-id"><i icon-name="landmark"></i>Profit Wallet</div>
                <div class="balance">�</div>
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
            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/dashboard"><i
                        class="anticon anticon-appstore"></i><span>Dashboard</span></a>
            </li>

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


            <li class="side-nav-item ">
                <a href="https://valcrestmeridiancapital.com/portal/deposits"><i
                        class="anticon anticon-file-add"></i><span>Add Money</span></a>
            </li>
            <li class="side-nav-item active">
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
                        <div class="row">
                    <div class="col-xl-12">
                <div class="site-card">
                    <div class="site-card-header">
                        <h3 class="title">All Deposit Log</h3>
                    </div>
                    <div class="site-card-body">
                        <div class="site-table">
                            <div class="table-filter">
                                <div class="filter">
                                    <form action="https://valcrestmeridiancapital.com/portal/deposits/logs" method="get">
                                        <div class="search">
                                            <input type="text" id="search" placeholder="Search"
                                                   value=""
                                                   name="query"/>
                                            <input type="date" name="date" value=""/>
                                            <button type="submit" class="apply-btn"><i
                                                    icon-name="search"></i>Search</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Transactions ID</th>
                                        <th>Amount</th>
                                        <th>Fee</th>
                                        <th>Status</th>
                                        <th>Method</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                                                            <tr>
                                            <td>
                                                <div class="table-description">
                                                    <div class="icon">
                                                        <i icon-name="arrow-down-left"></i>
                                                    </div>
                                                    <div class="description">
                                                        <strong>Deposit With Bitcoin</strong>                                                        <div class="date">Sep 24 2026 03:44</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><strong>TRXAR2XZ5YISR</strong></td>
                                            <td><strong
                                                    class="green-color">+23444 USD</strong>
                                            </td>
                                            <td><strong class="red-color">-0 USD</strong>
                                            <td>
                                                                                                        <div class="site-badge warnning">Pending</div>
                                                                                                    </td>
                                            <td><strong>BTC</strong></td>
                                        </tr>
                                                                        </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            </div>
                    <!--Page Content-->
                </div>
            </div>
        </div>
    </div>


    <!-- Show in 575px in Mobile Screen -->
    <div class="mobile-screen-show">
        <div class="bottom-appbar">
    <a href="https://valcrestmeridiancapital.com/portal/dashboard" class="">
        <i icon-name="layout-dashboard"></i>
    </a>
    <a href="https://valcrestmeridiancapital.com/portal/deposits" class="active">
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

