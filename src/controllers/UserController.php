<?php
// Prevent any output before headers
ob_start();

// Enable error logging
ini_set('display_errors', 0);
error_reporting(E_ALL);
error_log("UserController.php executed");

// Include files for database connection and authentication
include_once('../../config/config.php');
include_once('auth.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to send error response
function sendErrorResponse($message, $statusCode = 500) {
    http_response_code($statusCode);
    echo json_encode(['error' => $message]);
    exit;
}

// Clear any buffered output
ob_clean();

// Set proper content type
header('Content-Type: application/json');

try {
    // Check login status using the correct function from auth.php
    if (!checkIfLoggedIn()) {
        sendErrorResponse('Unauthorized: Please log in first', 403);
    }

    $user_id = $_SESSION['user_id'];
    error_log("UserController: Using user_id = $user_id");

    // Get database connection
    $conn = getDBConnection();
    
    if (!$conn) {
        sendErrorResponse('Database connection failed');
    }

    // Get user data directly without checking for table existence
    $sql = "SELECT u.user_id, u.first_name, u.last_name, u.username, u.profile_picture, u.bio, 
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
        echo json_encode($userData);
    } else {
        // If no user found with profile data, try just the users table
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
            // Add empty profile data
            $userData['date_of_birth'] = null;
            $userData['location'] = null;
            $userData['website_url'] = null;
            
            echo json_encode($userData);
        } else {
            sendErrorResponse('User not found', 404);
        }
    }
    
    // Close the connection
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    error_log("General error in UserController: " . $e->getMessage());
    sendErrorResponse('Server error: ' . $e->getMessage());
}
