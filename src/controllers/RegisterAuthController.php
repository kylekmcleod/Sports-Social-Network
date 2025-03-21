<?php
session_start();
include_once('../../config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profilePicturePath = null;

    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $uploadsDir = __DIR__ . '/../../uploads/';
        if (!file_exists($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $filename = time() . '_' . basename($_FILES['profileImage']['name']);
        $targetFilePath = $uploadsDir . $filename;
        
        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFilePath)) {
            $profilePicturePath = 'uploads/' . $filename;
        } else {
            $_SESSION['error'] = "Failed to upload profile image.";
            header("Location: ../../public/register.php");
            exit();
        }
    }

    $checkStmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    if ($checkStmt->num_rows > 0) {
        $_SESSION['error'] = "Username or email already exists!";
        header("Location: ../../public/register.php");
        exit();
    }
    $checkStmt->close();
    
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password_hash, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstName, $lastName, $username, $email, $password, $profilePicturePath);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! You can now log in.";
        header("Location: ../../public/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed: " . $conn->error;
        header("Location: ../../public/register.php");
        exit();
    }
    
    $stmt->close();
} else {
    $_SESSION['error'] = "Please use the registration form.";
    header("Location: ../../public/register.php");
    exit();
}
?>
