<?php
session_start();
include_once('../../config/config.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];
$userData = [];

try {
    $stmt = $conn->prepare("SELECT user_id, username, first_name, last_name, profile_picture, bio FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $userData = $row;
        
        $postStmt = $conn->prepare("SELECT COUNT(*) as count FROM posts WHERE user_id = ?");
        $postStmt->bind_param("i", $userId);
        $postStmt->execute();
        $postResult = $postStmt->get_result();
        $postCount = $postResult->fetch_assoc();
        $userData['posts_count'] = $postCount['count'];
        $postStmt->close();
        
        $userData['following_count'] = 0;
        $userData['followers_count'] = 0;
        
        echo json_encode($userData);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
