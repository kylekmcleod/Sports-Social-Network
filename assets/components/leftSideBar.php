<?php
include_once('../src/controllers/auth.php');
?>

<div class="layout__left-sidebar">
    <div class="sidebar-menu">
        <a href="homepage.php" class="sidebar-menu__item sidebar-menu__item--active">
            <img src="../assets/svg/home.svg" class="sidebar-menu__item-icon" />
            <span>Home</span>
        </a>
        <a href="" class="sidebar-menu__item">
            <img src="../assets/svg/explore.svg" class="sidebar-menu__item-icon" />
            <span>Explore</span>
        </a>

        <?php
        if (checkIfLoggedIn()) {
            ?>
            <a href="" class="sidebar-menu__item">
                <img src="../assets/svg/notifications.svg" class="sidebar-menu__item-icon" />
                <span>Notifications</span>
            </a>
            <a href="profile.php" class="sidebar-menu__item">
                <img src="../assets/svg/profile.svg" class="sidebar-menu__item-icon" />
                <span>Profile</span>
            </a>
            <a href="settings.php" class="sidebar-menu__item">
                <img src="../assets/svg/setting.svg" class="sidebar-menu__item-icon" />
                <span>Settings</span>
            </a>
            <a href="logout.php" class="sidebar-menu__item">
                <img src="../assets/svg/logout.svg" class="sidebar-menu__item-icon" />
                <span>Logout</span>
            </a>
            <?php
        } else {
            ?>
            <a href="login.php" class="sidebar-menu__item">
                <img src="../assets/svg/login.svg" class="sidebar-menu__item-icon" />
                <span>Login</span>
            </a>
            <?php
        }
        ?>
    </div>
</div>
