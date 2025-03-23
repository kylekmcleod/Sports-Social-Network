<?php
session_start();
include_once('../../config/config.php');

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: /COSC360/public/homepage.php");
    exit();
}

// Check if user_id is set
if (!isset($_POST['user_id'])) {
    header("Location: users.php?error=missingid");
    exit();
}

$user_id = $_POST['user_id'];

try {
    // First get current status
    $stmt = $conn->prepare("SELECT is_active FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($current_status);
        $stmt->fetch();
        $stmt->close();
        
        // Toggle the status (1 to 0 or 0 to 1)
        // If it's NULL, treat it as 0 (inactive)
        $newStatus = $current_status ? 0 : 1;
        
        // Update the status
        $updateStmt = $conn->prepare("UPDATE users SET is_active = ? WHERE user_id = ?");
        $updateStmt->bind_param("ii", $newStatus, $user_id);
        $updateStmt->execute();
        $updateStmt->close();
        
        header("Location: users.php?success=userstatus");
        exit();
    } else {
        header("Location: users.php?error=notfound");
        exit();
    }
} catch (Exception $e) {
    error_log("Toggle user status error: " . $e->getMessage());
    header("Location: users.php?error=dberror");
    exit();
}