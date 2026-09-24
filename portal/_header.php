<?php
$portalPath = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$portalActive = static function (string $prefix) use ($portalPath): string {
    return str_starts_with($portalPath, trim($prefix, '/')) ? 'active' : '';
};
?>
<div class="panel-header">
    <div class="logo"><a href="/portal/dashboard"><span class="brand-logo">Valcrest Meridian Capital</span></a></div>
    <div class="nav-wrap">
        <div class="nav-left">
            <button class="sidebar-toggle" type="button" aria-label="Toggle navigation"><i class="anticon anticon-menu"></i></button>
            <div class="mob-logo"><a href="/portal/dashboard"><span class="brand-logo">Valcrest Meridian Capital</span></a></div>
        </div>
        <div class="nav-right">
            <div class="single-nav-right">
                <div class="single-right"><div class="color-switcher"><i icon-name="moon" class="dark-icon" data-mode="dark"></i><i icon-name="sun" class="light-icon" data-mode="light"></i></div></div>
                <div class="single-right"><a class="user-sidebar-btn" href="/portal/dashboard">Dashboard</a></div>
                <div class="single-right"><a class="user-sidebar-btn" href="/api/auth/logout.php">Logout</a></div>
            </div>
        </div>
    </div>
</div>
<div class="desktop-screen-show">
    <aside class="side-nav">
        <div class="side-nav-inside">
            <ul class="side-nav-menu">
                <li class="side-nav-item <?= $portalActive('/portal/dashboard') ?>"><a href="/portal/dashboard"><i class="anticon anticon-appstore"></i><span>Dashboard</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/investments') ?>"><a href="/portal/investments"><i class="anticon anticon-check-square"></i><span>Investments</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/investments/logs') ?>"><a href="/portal/investments/logs"><i class="anticon anticon-copy"></i><span>Investment Logs</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/transactions') ?>"><a href="/portal/transactions"><i class="anticon anticon-inbox"></i><span>Transactions</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/deposits') ?>"><a href="/portal/deposits"><i class="anticon anticon-file-add"></i><span>Deposits</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/transfers') ?>"><a href="/portal/transfers"><i class="anticon anticon-export"></i><span>Transfers</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/withdrawals') ?>"><a href="/portal/withdrawals"><i class="anticon anticon-bank"></i><span>Withdrawals</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/referral') ?>"><a href="/portal/referral"><i class="anticon anticon-usergroup-add"></i><span>Referral</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/settings') ?>"><a href="/portal/settings"><i class="anticon anticon-setting"></i><span>Settings</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/support') ?>"><a href="/portal/support"><i class="anticon anticon-tool"></i><span>Support</span></a></li>
                <li class="side-nav-item <?= $portalActive('/portal/notifications') ?>"><a href="/portal/notifications"><i class="anticon anticon-notification"></i><span>Notifications</span></a></li>
            </ul>
        </div>
    </aside>
</div>
