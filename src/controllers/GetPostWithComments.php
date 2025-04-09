<?php
include_once(__DIR__ . '/../../config/config.php');

function getPostWithComments($post_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT p.*, u.username, u.profile_picture 
        FROM posts p 
        JOIN users u ON p.user_id = u.user_id 
        WHERE p.post_id = ?
    ");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();
    
    $stmt = $conn->prepare("
        SELECT c.*, u.username, u.profile_picture 
        FROM comments c 
        JOIN users u ON c.user_id = u.user_id 
        WHERE c.post_id = ? 
        ORDER BY c.created_at DESC
    ");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    return ['post' => $post, 'comments' => $comments];
}

function formatTimeAgo($timestamp) {
    $time = strtotime($timestamp);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return $diff . "s";
    } elseif ($diff < 3600) {
        return floor($diff/60) . "m";
    } elseif ($diff < 86400) {
        return floor($diff/3600) . "h";
    } else {
        return date("M j", $time);
    }
}
?>
