<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /COSC360/public/login.php');
    exit();
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

function checkIfAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function redirectIfNotAdmin() {
    if (!checkIfLoggedIn() || !checkIfAdmin()) {
        header("Location: /COSC360/public/homepage.php");
        exit();
    }
}

function logoutUser() {
    session_unset();
    session_destroy();
    header("Location: /COSC360/public/login.php");
    exit();
}
?>