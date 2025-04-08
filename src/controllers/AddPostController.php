<?php
session_start();
include_once(__DIR__ . '/../../config/config.php');
include_once(__DIR__ . '/auth.php');
$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content']) && !empty($_POST['content'])) {
    if (checkIfLoggedIn()) {
        $content = $_POST['content'];
        $user_id = $_SESSION['user_id'];
        
        $tags = isset($_POST['tags']) ? implode(',', $_POST['tags']) : null;
        
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content, tags, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $user_id, $content, $tags);
        
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            echo json_encode(['message' => 'Error creating post']);
        }

        $stmt->close();
    } else {
        echo json_encode(['message' => 'User not logged in']);
    }
    exit;
} else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    echo json_encode(['message' => 'Error creating post']);
}
?>