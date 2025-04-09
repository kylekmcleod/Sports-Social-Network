<?php
include_once(__DIR__ . '/../../config/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../public/login.php');
        exit;
    }

    $post_id = $_POST['post_id'] ?? null;
    $content = $_POST['content'] ?? null;
    $user_id = $_SESSION['user_id'];

    if (!$post_id || !$content) {
        header('Location: ../../public/post.php?id=' . $post_id . '&error=missing_fields');
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $post_id, $user_id, $content);
        
        if ($stmt->execute()) {
            header('Location: ../../public/post.php?id=' . $post_id . '#comments-container');
        } else {
            header('Location: ../../public/post.php?id=' . $post_id . '&error=failed');
        }
        exit;
    } catch (Exception $e) {
        header('Location: ../../public/post.php?id=' . $post_id . '&error=database');
        exit;
    }
}
?>
