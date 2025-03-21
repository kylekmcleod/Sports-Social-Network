<?php

include_once(__DIR__ . '/auth.php');
include_once(__DIR__ . '/../../config/config.php');

// Was having problems when trying to update data, this fixed it for some reason
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    error_log("User not authenticated - no user_id in session");
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'User not authenticated']);
    exit;
}


function getUserData() {
    global $conn;
    error_log("getUserData() called in settingsController.php");
    
    if (!isset($_SESSION['user_id'])) {
        error_log("No user_id in session");
        return null;
    }
    
    $userId = (int)$_SESSION['user_id'];
    error_log("Attempting to fetch user data for ID: $userId");
    
    try {
        $sql = "SELECT * FROM users WHERE user_id = $userId";
        error_log("Executing query: $sql");
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            error_log("User data found successfully");
            
            return [
                'id' => $userData['user_id'],
                'first_name' => $userData['first_name'] ?? '',
                'last_name' => $userData['last_name'] ?? '',
                'username' => $userData['username'],
                'email' => $userData['email'],
                'about' => $userData['bio'] ?? '',
                'profile_image' => $userData['profile_picture'] ?? '',
                'banner_image' => '' 
            ];
        }
    } catch (Exception $e) {
        error_log("Exception in getUserData: " . $e->getMessage());
    }
    
    return null;
}


function updateUserProfile($field, $value) {
    global $conn;
    
    error_log("updateUserProfile called with field=$field, value=$value");
    
    if (!isset($_SESSION['user_id'])) {
        error_log("User not authenticated - no user_id in session");
        return ['success' => false, 'message' => 'User not authenticated'];
    }
    
    $userId = (int)$_SESSION['user_id'];
    error_log("Using user_id: $userId");
    
    $fieldMap = [
        'first_name' => 'first_name',
        'last_name' => 'last_name',
        'username' => 'username',
        'email' => 'email',
        'about' => 'bio'
    ];
    
    if (!array_key_exists($field, $fieldMap)) {
        error_log("Invalid field name: $field");
        return ['success' => false, 'message' => 'Invalid field name'];
    }
    
    $dbField = $fieldMap[$field];
    error_log("Mapped field '$field' to database column '$dbField'");
    
    try {
        $sql = "UPDATE users SET $dbField = ? WHERE user_id = ?";
        error_log("SQL query: $sql");
        
        $columnCheckSql = "SHOW COLUMNS FROM users LIKE '$dbField'";
        error_log("Checking column existence: $columnCheckSql");
        $columnResult = $conn->query($columnCheckSql);
        
        if (!$columnResult || $columnResult->num_rows === 0) {
            error_log("Column '$dbField' does not exist in users table");
            return ['success' => false, 'message' => "Column '$dbField' does not exist in users table"];
        }
        
        error_log("Column '$dbField' exists, proceeding with update");
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement failed: " . $conn->error);
            return ['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error];
        }
        
        $stmt->bind_param("si", $value, $userId);
        error_log("Bound parameters: value=$value, userId=$userId");
        
        $result = $stmt->execute();
        if ($result) {
            error_log("Update execute() successful, affected rows: " . $stmt->affected_rows);
            
            if ($stmt->affected_rows > 0) {
                return ['success' => true, 'message' => "Successfully updated $field"];
            } else {
                error_log("No rows affected - value might be unchanged");
                
                $checkSql = "SELECT COUNT(*) as count FROM users WHERE user_id = $userId";
                $checkResult = $conn->query($checkSql);
                $userExists = false;
                
                if ($checkResult && $checkResult->num_rows > 0) {
                    $row = $checkResult->fetch_assoc();
                    $userExists = ($row['count'] > 0);
                }
                
                if (!$userExists) {
                    error_log("User with ID $userId does not exist");
                    return ['success' => false, 'message' => "User with ID $userId not found"];
                }
                
                return ['success' => true, 'message' => "No changes made to $field"];
            }
        } else {
            error_log("Update failed: " . $stmt->error);
            return ['success' => false, 'message' => 'Database error: ' . $stmt->error];
        }
    } catch (Exception $e) {
        error_log("Exception during update: " . $e->getMessage());
        return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
    }
}

function updateUserImage($imageType, $imageData) {
    global $conn;
    
    if (!isset($_SESSION['user_id'])) {
        return ['success' => false, 'message' => 'User not authenticated'];
    }
    
    $userId = $_SESSION['user_id'];
    
    if (strpos($imageData, 'data:image') === 0) {
        $uploadsDir = __DIR__ . '/../../uploads/';
        if (!file_exists($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }
        
        list($type, $data) = explode(';', $imageData);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        
        $filename = time() . '_' . uniqid() . '.png';
        $filepath = $uploadsDir . $filename;
        
        if (file_put_contents($filepath, $data)) {
            $relativePath = 'uploads/' . $filename;
            
            $fieldMap = [
                'profile_image' => 'profile_picture',
                'banner_image' => 'banner_image' 
            ];
            
            if ($imageType === 'banner_image') {
                $result = $conn->query("SHOW COLUMNS FROM users LIKE 'banner_image'");
                if ($result->num_rows === 0) {
                    return ['success' => false, 'message' => 'Banner image not supported in database schema'];
                }
            }
            
            $dbField = $fieldMap[$imageType] ?? null;
            if (!$dbField) {
                return ['success' => false, 'message' => 'Invalid image type'];
            }
            
            $stmt = $conn->prepare("UPDATE users SET $dbField = ? WHERE user_id = ?");
            if (!$stmt) {
                return ['success' => false, 'message' => 'Prepare failed: ' . $conn->error];
            }
            
            $stmt->bind_param("si", $relativePath, $userId);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => "Image updated successfully", 'path' => $relativePath];
            } else {
                return ['success' => false, 'message' => 'Database update failed: ' . $stmt->error];
            }
        } else {
            return ['success' => false, 'message' => 'Failed to save image file'];
        }
    } else {
        return ['success' => false, 'message' => 'Invalid image data format'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        error_log("POST data: " . print_r($_POST, true));

        $action = $_POST['action'] ?? null;
        $value = $_POST['value'] ?? null;

        if (!$action || !$value) {
            echo json_encode(['success' => false, 'error' => 'Invalid request. Missing action or value.']);
            exit;
        }

        switch ($action) {
            case 'update_first_name':
            case 'update_last_name':
            case 'update_username':
            case 'update_email':
            case 'update_about':
                $field = str_replace('update_', '', $action);
                $result = updateUserProfile($field, $value); 

                if ($result['success']) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => $result['message']]);
                }
                break;

            default:
                echo json_encode(['success' => false, 'error' => 'Unknown action.']);
        }
    } catch (Exception $e) {
        error_log("Error in settingsController.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'An internal error occurred.']);
    }
    exit;
}
?>
