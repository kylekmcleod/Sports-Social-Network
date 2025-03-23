<?php
session_start();
include_once('../../config/config.php');
include_once('../../src/controllers/auth.php');

// Redirect if not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../homepage.php");
    exit();
}

// Check if user_id is set
if (!isset($_GET['user_id']) || (int)$_GET['user_id'] <= 0) {
    header("Location: users.php?error=missingid");
    exit();
}

$user_id = (int)$_GET['user_id'];

// Prevent deleting yourself
if ($user_id === (int)$_SESSION['user_id']) {
    header("Location: users.php?error=selfdelete");
    exit();
}

try {
    // Begin transaction
    $conn->begin_transaction();
    
    // Check if user exists
    $checkUser = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
    $checkUser->bind_param("i", $user_id);
    $checkUser->execute();
    $checkUser->store_result();
    
    if ($checkUser->num_rows === 0) {
        $checkUser->close();
        $conn->rollback();
        header("Location: users.php?error=notfound");
        exit();
    }
    
    $checkUser->bind_result($username);
    $checkUser->fetch();
    $checkUser->close();
    
    // Delete user's comments if the table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'comments'");
    if ($checkTable->num_rows > 0) {
        $deleteComments = $conn->prepare("DELETE FROM comments WHERE user_id = ?");
        $deleteComments->bind_param("i", $user_id);
        $deleteComments->execute();
        $deleteComments->close();
    }
    
    // Delete user's likes if the table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'likes'");
    if ($checkTable->num_rows > 0) {
        $deleteLikes = $conn->prepare("DELETE FROM likes WHERE user_id = ?");
        $deleteLikes->bind_param("i", $user_id);
        $deleteLikes->execute();
        $deleteLikes->close();
    }
    
    // Delete user's posts if the table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'posts'");
    if ($checkTable->num_rows > 0) {
        // First get post IDs to delete associated records
        $getPostIds = $conn->prepare("SELECT post_id FROM posts WHERE user_id = ?");
        $getPostIds->bind_param("i", $user_id);
        $getPostIds->execute();
        $postsResult = $getPostIds->get_result();
        
        $postIds = [];
        while ($row = $postsResult->fetch_assoc()) {
            $postIds[] = $row['post_id'];
        }
        $getPostIds->close();
        
        // Delete likes and comments related to the user's posts
        if (!empty($postIds)) {
            $postIdsList = implode(',', $postIds);
            
            $checkTable = $conn->query("SHOW TABLES LIKE 'comments'");
            if ($checkTable->num_rows > 0) {
                $conn->query("DELETE FROM comments WHERE post_id IN ($postIdsList)");
            }
            
            $checkTable = $conn->query("SHOW TABLES LIKE 'likes'");
            if ($checkTable->num_rows > 0) {
                $conn->query("DELETE FROM likes WHERE post_id IN ($postIdsList)");
            }
        }
        
        // Now delete the posts
        $deletePosts = $conn->prepare("DELETE FROM posts WHERE user_id = ?");
        $deletePosts->bind_param("i", $user_id);
        $deletePosts->execute();
        $deletePosts->close();
    }
    
    // Delete the user profile if exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'profiles'");
    if ($checkTable->num_rows > 0) {
        $deleteProfile = $conn->prepare("DELETE FROM profiles WHERE user_id = ?");
        $deleteProfile->bind_param("i", $user_id);
        $deleteProfile->execute();
        $deleteProfile->close();
    }
    
    // Finally delete the user
    $deleteUser = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $deleteUser->bind_param("i", $user_id);
    $deleteUser->execute();
    
    if ($deleteUser->affected_rows === 0) {
        $deleteUser->close();
        $conn->rollback();
        header("Location: users.php?error=notfound");
        exit();
    }
    
    $deleteUser->close();
    
    // Commit transaction
    $conn->commit();
    
    // Log the deletion
    error_log("Admin (ID: {$_SESSION['user_id']}) deleted user: $username (ID: $user_id)");
    
    header("Location: users.php?success=userdeleted");
    
} catch (Exception $e) {
    $conn->rollback();
    error_log("Error deleting user: " . $e->getMessage());
    header("Location: users.php?error=dberror");
}

exit();
