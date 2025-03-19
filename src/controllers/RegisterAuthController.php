<?php
session_start();
include_once('../../config/config.php');

// REGISTER CONTROLLER
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['firstName']);
    $last_name = trim($_POST['lastName']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $profile_image = NULL;
    if (!empty($_FILES['profileImage']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profileImage"]["name"]);
        move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file);
        $profile_image = $target_file;
    }

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Username or Email already exists!";
        exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password_hash, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $password, $profile_image);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        header("Location: /COSC360/public/login.php");

        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
