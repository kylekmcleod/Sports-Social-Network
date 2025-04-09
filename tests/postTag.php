<?php
session_start();
include_once(__DIR__ . '/../config/config.php');

// Test setup
$conn = getDBConnection();
$_SESSION['user_id'] = 1; // Assuming user ID 1 exists for testing

// Test data
$testContent = "Test post with tags";
$testTags = ["php", "testing", "web"];

// Simulate POST request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['content'] = $testContent;
$_POST['tags'] = $testTags;

// Include the controller
require_once(__DIR__ . '/../src/controllers/AddPostController.php');

// Verify the post was created with correct tags
$stmt = $conn->prepare("SELECT content, tags FROM posts WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

// Display test results
echo "Test Results:\n";
echo "Expected content: " . $testContent . "\n";
echo "Actual content: " . $post['content'] . "\n";
echo "Expected tags: " . implode(',', $testTags) . "\n";
echo "Actual tags: " . $post['tags'] . "\n";
echo "Test " . ($post['content'] === $testContent && $post['tags'] === implode(',', $testTags) ? "PASSED" : "FAILED");

// Clean up
$stmt->close();
$conn->close();
?>
