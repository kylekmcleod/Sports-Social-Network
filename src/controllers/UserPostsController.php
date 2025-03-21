<?php
ob_start();

ini_set('display_errors', 0);
error_reporting(E_ALL);

include_once('../../config/config.php');
include_once('auth.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_clean();

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $conn = getDBConnection();

    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $checkTable = $conn->query("SHOW TABLES LIKE 'posts'");
    if ($checkTable->num_rows == 0) {
        echo json_encode([]);
        exit;
    }

    $checkPosts = $conn->query("SELECT COUNT(*) as count FROM posts WHERE user_id = $user_id");
    $postsCount = $checkPosts->fetch_assoc()['count'];
    
    if ($postsCount == 0) {
        echo json_encode([]);
        exit;
    }


    $sql = "
        SELECT p.post_id, p.content, p.created_at, 
               u.username, u.profile_picture 
        FROM posts p
        INNER JOIN users u ON p.user_id = u.user_id
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $postsArray = [];
    if ($result && $result->num_rows > 0) {
        while ($post = $result->fetch_assoc()) {
            
            $time_diff = time() - strtotime($post['created_at']);
            if ($time_diff < 60) {
                $time_display = $time_diff . "s";
            } elseif ($time_diff < 3600) {
                $time_display = floor($time_diff / 60) . "m";
            } elseif ($time_diff < 86400) {
                $time_display = floor($time_diff / 3600) . "h " . floor(($time_diff % 3600) / 60) . "m";
            } else {
                $time_display = date("M j", strtotime($post['created_at']));
            }
            

            $comment_count = 0;
            $like_count = 0;
            
            $checkComments = $conn->query("SHOW TABLES LIKE 'comments'");
            if ($checkComments->num_rows > 0) {
                $commentQuery = $conn->query("SELECT COUNT(*) as count FROM comments WHERE post_id = " . $post['post_id']);
                if ($commentQuery) {
                    $comment_count = $commentQuery->fetch_assoc()['count'];
                }
            }
            
            $checkLikes = $conn->query("SHOW TABLES LIKE 'likes'");
            if ($checkLikes->num_rows > 0) {
                $likeQuery = $conn->query("SELECT COUNT(*) as count FROM likes WHERE post_id = " . $post['post_id']);
                if ($likeQuery) {
                    $like_count = $likeQuery->fetch_assoc()['count'];
                }
            }
            
            $postsArray[] = [
                'post_id' => $post['post_id'],
                'username' => $post['username'],
                'profile_picture' => $post['profile_picture'],
                'content' => $post['content'],
                'created_at' => $post['created_at'],
                'time_display' => $time_display,
                'comment_count' => $comment_count,
                'like_count' => $like_count
            ];
        }
    }

    $stmt->close();
    $conn->close();
    
    echo json_encode($postsArray);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>
