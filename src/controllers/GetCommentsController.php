<?php
include_once(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $post_id = $_GET['post_id'] ?? null;

    if (!$post_id) {
        echo json_encode(['status' => 'error', 'message' => 'Post ID required']);
        exit;
    }

    try {
        $stmt = $conn->prepare("
            SELECT c.*, u.username, u.profile_picture 
            FROM comments c 
            JOIN users u ON c.user_id = u.user_id 
            WHERE c.post_id = ? 
            ORDER BY c.created_at DESC
        ");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = $result->fetch_all(MYSQLI_ASSOC);
        
        echo json_encode(['status' => 'success', 'comments' => $comments]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
}
?>
