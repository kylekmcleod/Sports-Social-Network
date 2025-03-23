<?php
session_start();
include_once('../../config/config.php');
include_once('../../src/controllers/auth.php');

// Redirect if not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../homepage.php");
    exit();
}

// Check if user_id is set in POST data
if (!isset($_POST['user_id']) || (int)$_POST['user_id'] <= 0) {
    header("Location: users.php?error=missingid");
    exit();
}

$user_id = (int)$_POST['user_id'];

// Prevent changing your own admin status
if ($user_id === (int)$_SESSION['user_id']) {
    header("Location: users.php?error=selfadmin");
    exit();
}

try {
    // First, check if user exists and get current admin status
    $checkUser = $conn->prepare("SELECT username, is_admin FROM users WHERE user_id = ?");
    $checkUser->bind_param("i", $user_id);
    $checkUser->execute();
    $result = $checkUser->get_result();
    
    if ($result->num_rows === 0) {
        $checkUser->close();
        header("Location: users.php?error=notfound");
        exit();
    }
    
    $userData = $result->fetch_assoc();
    $username = $userData['username'];
    $currentAdminStatus = $userData['is_admin'];
    $newAdminStatus = $currentAdminStatus ? 0 : 1;
    $checkUser->close();
    
    // Update the admin status
    $updateStatus = $conn->prepare("UPDATE users SET is_admin = ? WHERE user_id = ?");
    $updateStatus->bind_param("ii", $newAdminStatus, $user_id);
    $updateStatus->execute();
    
    if ($updateStatus->affected_rows === 0 && $updateStatus->errno !== 0) {
        // Error occurred during update
        $updateStatus->close();
        header("Location: users.php?error=dberror");
        exit();
    }
    
    $updateStatus->close();
    
    // Log the admin status change
    $statusChange = $newAdminStatus ? "promoted to admin" : "demoted from admin";
    error_log("Admin (ID: {$_SESSION['user_id']}) $statusChange user: $username (ID: $user_id)");
    
    header("Location: users.php?success=adminstatus");
    
} catch (Exception $e) {
    error_log("Error updating admin status: " . $e->getMessage());
    header("Location: users.php?error=dberror");
}

exit();
