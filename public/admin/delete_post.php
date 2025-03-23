<?php
session_start();
include_once('../../config/config.php');

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../homepage.php");
    exit();
}

// Check if post_id is set
if (!isset($_GET['id']) || (int)$_GET['id'] <= 0) {
    header("Location: posts.php?error=missingid");
    exit();
}

$post_id = (int)$_GET['id'];

try {
    // Check if post exists
    $checkStmt = $conn->prepare("SELECT post_id FROM posts WHERE post_id = ?");
    $checkStmt->bind_param("i", $post_id);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    if ($checkStmt->num_rows === 0) {
        $checkStmt->close();
        header("Location: posts.php?error=notfound");
        exit();
    }
    $checkStmt->close();
    
    // Delete the post
    $deleteStmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
    $deleteStmt->bind_param("i", $post_id);
    $deleteStmt->execute();
    
    if ($deleteStmt->affected_rows > 0) {
        $deleteStmt->close();
        header("Location: posts.php?success=postdeleted");
        exit();
    } else {
        $deleteStmt->close();
        header("Location: posts.php?error=dberror");
        exit();
    }
} catch (Exception $e) {
    error_log("Delete post error: " . $e->getMessage());
    header("Location: posts.php?error=dberror");
    exit();
}