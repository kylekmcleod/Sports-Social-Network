<?php
include_once('../../src/controllers/auth.php');
?>

<div class="layout__left-sidebar">
    <div class="sidebar-menu">
        <a href="/COSC360/public/homepage.php" class="sidebar-menu__item">
            <img src="/COSC360/assets/svg/home.svg" class="sidebar-menu__item-icon" />
            <span>Home</span>
        </a>
        <a href="/COSC360/public/admin/index.php" class="sidebar-menu__item sidebar-menu__item--active">
            <img src="/COSC360/assets/svg/admin.svg" class="sidebar-menu__item-icon" />
            <span>Admin Dashboard</span>
        </a>
        <a href="/COSC360/public/admin/users.php" class="sidebar-menu__item">
            <img src="/COSC360/assets/svg/profile.svg" class="sidebar-menu__item-icon" />
            <span>Manage Users</span>
        </a>
        <a href="/COSC360/public/admin/posts.php" class="sidebar-menu__item">
            <img src="/COSC360/assets/svg/explore.svg" class="sidebar-menu__item-icon" />
            <span>Manage Posts</span>
        </a>
        <a href="/COSC360/public/admin/reports.php" class="sidebar-menu__item">
            <img src="/COSC360/assets/svg/stats.svg" class="sidebar-menu__item-icon" />
            <span>Usage Reports</span>
        </a>
        <a href="/COSC360/public/profile.php" class="sidebar-menu__item">
            <img src="/COSC360/assets/svg/profile.svg" class="sidebar-menu__item-icon" />
            <span>My Profile</span>
        </a>
        <a href="/COSC360/public/settings.php" class="sidebar-menu__item">
            <img src="/COSC360/assets/svg/setting.svg" class="sidebar-menu__item-icon" />
            <span>Settings</span>
        </a>
        <a href="../logout.php" class="sidebar-menu__item">
            <img src="/COSC360/assets/svg/logout.svg" class="sidebar-menu__item-icon" />
            <span>Logout</span>
        </a>
    </div>
</div>