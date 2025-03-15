<?php
function redirectIfNotLoggedIn() {
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: /COSC360/public/login.php");
        exit();
    }
}

function checkIfLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logoutUser() {
    session_unset();
    session_destroy();
    header("Location: /COSC360/public/login.php");
    exit();
}
?>
