<?php
ob_start();

ini_set('display_errors', 0);
error_reporting(E_ALL);
error_log("UserController.php executed");

include_once('../../config/config.php');
include_once('auth.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    error_log("UserController: Using user_id = $user_id");

    $conn = getDBConnection();
    
    if (!$conn) {
        sendErrorResponse('Database connection failed');
    }

    $sql = "SELECT u.user_id, u.first_name, u.last_name, u.username, u.profile_picture, u.bio, u.followers_count, u.following_count, u.posts_count, 
           p.date_of_birth, p.location, p.website_url 
           FROM users u 
           LEFT JOIN profiles p ON u.user_id = p.user_id 
           WHERE u.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        sendErrorResponse('Error preparing statement: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $userData = $result->fetch_assoc()) {
        $userData['bio'] = $userData['bio'] ?: '';
        echo json_encode($userData);
    } else {
        $stmt->close();
        
        $sql = "SELECT user_id, first_name, last_name, username, profile_picture, bio 
               FROM users WHERE user_id = ?";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            sendErrorResponse('Error preparing statement: ' . $conn->error);
        }
        
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $userData = $result->fetch_assoc()) {
            $userData['date_of_birth'] = null;
            $userData['location'] = null;
            $userData['website_url'] = null;
            $userData['bio'] = $userData['bio'] ?: '';
            
            echo json_encode($userData);
        } else {
            sendErrorResponse('User not found', 404);
        }
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    error_log("General error in UserController: " . $e->getMessage());
    sendErrorResponse('Server error: ' . $e->getMessage());
}
