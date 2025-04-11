<?php
session_start();
include_once('../../config/config.php');

// LOGIN CONTROLLER
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST['usernameOrEmail']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, username, password_hash, is_admin FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "No user found with that username or email.";
        exit();
    }
    $stmt->bind_result($user_id, $username, $stored_password_hash, $is_admin);
    $stmt->fetch();

    if (password_verify($password, $stored_password_hash)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = (bool)$is_admin;
        if ($is_admin) {
            $_SESSION['admin_logged_in'] = true;
        }
        header("Location: ../../public/homepage.php");
        exit();
    } else {
        echo "Incorrect password.";
        exit();
    }

    $stmt->close();
}
?>