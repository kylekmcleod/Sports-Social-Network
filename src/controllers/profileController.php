<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log('Profile controller started');

ob_start();

require_once(__DIR__ . '/auth.php');
include_once('../../config/config.php'); 
error_log('Config included');


function getUserProfile($userId = null) {
    global $conn;
    
    try {
        if ($userId === null && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            error_log("Using session user ID: $userId");
        } elseif ($userId === null) {
            error_log("No user ID provided");
            return ['error' => 'No user ID provided'];
        }
        
        $userId = (int)$userId;
        error_log("Getting profile for user ID: $userId");
        
        $sql = "SELECT * FROM users WHERE user_id = $userId";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            error_log("User found");
            
            $mappedData = [
                'id' => $userData['user_id'],
                'name' => $userData['first_name'] . ' ' . $userData['last_name'],
                'username' => $userData['username'],
                'email' => $userData['email'],
                'about' => $userData['bio'] ?? '',
                'profile_image' => $userData['profile_picture'] ?? '',
                'banner_image' => '' 
            ];
            
            return $mappedData;
        }
        
        error_log("User not found with user_id: $userId");
        return ['error' => 'User not found'];
    } catch (Exception $e) {
        error_log("Exception in getUserProfile: " . $e->getMessage());
        return ['error' => 'Exception: ' . $e->getMessage()];
    }
}


if (isset($_GET['action'])) {
    try {
        if ($_GET['action'] === 'get_profile') {
            error_log("Processing get_profile action");
            
            $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
            error_log("User ID from request: " . ($userId ?? 'null'));
            
            $profileData = getUserProfile($userId);
            
            ob_end_clean();
            
            header('Content-Type: application/json');
            echo json_encode($profileData);
            exit;
        } else {
            error_log("Invalid action: " . $_GET['action']);
            throw new Exception('Invalid action requested');
        }
    } catch (Exception $e) {
        error_log("Exception in action handler: " . $e->getMessage());
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
} else {
    error_log("No action parameter provided");
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No action specified']);
    exit;
}
?>
