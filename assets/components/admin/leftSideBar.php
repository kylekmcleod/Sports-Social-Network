<?php
include_once('../../src/controllers/auth.php');
?>

<div class="layout__left-sidebar">
    <div class="sidebar-menu">
        <a href="../homepage.php" class="sidebar-menu__item">
            <img src="/COSC360/assets/svg/home.svg" class="sidebar-menu__item-icon" />
            <span>Home</span>
        </a>
        <a href="index.php" class="sidebar-menu__item sidebar-menu__item--active">
            <img src="../../assets/svg/admin.svg" class="sidebar-menu__item-icon" />
            <span>Admin Dashboard</span>
        </a>
        <a href="users.php" class="sidebar-menu__item">
            <img src="../../assets/svg/profile.svg" class="sidebar-menu__item-icon" />
            <span>Manage Users</span>
        </a>
        <a href="posts.php" class="sidebar-menu__item">
            <img src="../../assets/svg/explore.svg" class="sidebar-menu__item-icon" />
            <span>Manage Posts</span>
        </a>
        <a href="reports.php" class="sidebar-menu__item">
            <img src="../../assets/svg/stats.svg" class="sidebar-menu__item-icon" />
            <span>Usage Reports</span>
        </a>
        <a href="../profile.php" class="sidebar-menu__item">
            <img src="../../assets/svg/profile.svg" class="sidebar-menu__item-icon" />
            <span>My Profile</span>
        </a>
        <a href="../settings.php" class="sidebar-menu__item">
            <img src="../../assets/svg/setting.svg" class="sidebar-menu__item-icon" />
            <span>Settings</span>
        </a>
        <a href="../logout.php" class="sidebar-menu__item">
            <img src="../../assets/svg/logout.svg" class="sidebar-menu__item-icon" />
            <span>Logout</span>
        </a>
    </div>
</div>