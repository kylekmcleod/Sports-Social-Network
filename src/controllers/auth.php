<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /COSC360/public/login.php");
        exit();
    }
}

function checkIfLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isLoggedIn() {
    return checkIfLoggedIn();
}

function logoutUser() {
    session_unset();
    session_destroy();
    header("Location: /COSC360/public/login.php");
    exit();
}
?>
