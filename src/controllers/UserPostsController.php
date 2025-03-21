<?php
ob_start();

ini_set('display_errors', 0);
error_reporting(E_ALL);
error_log("UserPostsController.php executed");

include_once('../../config/config.php');
include_once('auth.php'); 
require_once('../utilities/ProfileImageHelper.php');

function sendErrorResponse($message, $statusCode = 500) {
    http_response_code($statusCode);
    echo json_encode(['error' => $message]);
    exit;
}

ob_clean();

header('Content-Type: application/json');

try {
    if (!checkIfLoggedIn()) {
        sendErrorResponse('Unauthorized: Please log in first', 403);
    }

    $user_id = $_SESSION['user_id'];
    error_log("UserPostsController: Using user_id = $user_id");

    $conn = getDBConnection();
    
    if (!$conn) {
        sendErrorResponse('Database connection failed');
    }
    
    $tableCheckQuery = "SHOW TABLES LIKE 'posts'";
    $tableResult = $conn->query($tableCheckQuery);
    
    if ($tableResult->num_rows == 0) {
        // Posts table doesn't exist yet, return empty array
        echo json_encode([]);
        exit;
    }
    
    $sql = "SELECT p.post_id, p.user_id, p.content, p.created_at, u.username, u.profile_picture
           FROM posts p
           JOIN users u ON p.user_id = u.user_id
           WHERE p.user_id = ?
           ORDER BY p.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("SQL Error: " . $conn->error);
        sendErrorResponse('Error preparing posts statement: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $posts = [];
    
    while ($row = $result->fetch_assoc()) {
        $row['like_count'] = 0;
        $row['comment_count'] = 0;
        
        try {
            $likeCheckQuery = "SHOW TABLES LIKE 'post_likes'";
            $likeTableResult = $conn->query($likeCheckQuery);
            
            if ($likeTableResult->num_rows > 0) {
                $likeStmt = $conn->prepare("SELECT COUNT(*) AS count FROM post_likes WHERE post_id = ?");
                $likeStmt->bind_param("i", $row['post_id']);
                $likeStmt->execute();
                $likeResult = $likeStmt->get_result();
                $likeData = $likeResult->fetch_assoc();
                $row['like_count'] = $likeData['count'];
                $likeStmt->close();
            }
        } catch (Exception $e) {
            error_log("Error getting like count: " . $e->getMessage());
        }
        
        try {
            $commentCheckQuery = "SHOW TABLES LIKE 'post_comments'";
            $commentTableResult = $conn->query($commentCheckQuery);
            
            if ($commentTableResult->num_rows > 0) {
                $commentStmt = $conn->prepare("SELECT COUNT(*) AS count FROM post_comments WHERE post_id = ?");
                $commentStmt->bind_param("i", $row['post_id']);
                $commentStmt->execute();
                $commentResult = $commentStmt->get_result();
                $commentData = $commentResult->fetch_assoc();
                $row['comment_count'] = $commentData['count'];
                $commentStmt->close();
            }
        } catch (Exception $e) {
            error_log("Error getting comment count: " . $e->getMessage());
        }
        
        try {
            if ($row['created_at']) {
                $created = new DateTime($row['created_at']);
                $now = new DateTime();
                $diff = $created->diff($now);
                
                if ($diff->y > 0) {
                    $row['time_display'] = $diff->y . 'y';
                } elseif ($diff->m > 0) {
                    $row['time_display'] = $diff->m . 'm';
                } elseif ($diff->d > 0) {
                    $row['time_display'] = $diff->d . 'd';
                } elseif ($diff->h > 0) {
                    $row['time_display'] = $diff->h . 'h';
                } elseif ($diff->i > 0) {
                    $row['time_display'] = $diff->i . 'm';
                } else {
                    $row['time_display'] = 'Just now';
                }
            } else {
                $row['time_display'] = 'Unknown';
            }
        } catch (Exception $e) {
            error_log("Error formatting date: " . $e->getMessage());
            $row['time_display'] = 'Unknown';
        }
        
        $row['profile_picture'] = ProfileImageHelper::getProfileImageUrl($row['profile_picture']);
        
        $posts[] = $row;
    }
    
    echo json_encode($posts);
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    error_log("General error in UserPostsController: " . $e->getMessage());
    sendErrorResponse('Server error: ' . $e->getMessage());
}
?>
