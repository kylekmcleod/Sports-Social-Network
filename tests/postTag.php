<?php
session_start();
include_once(__DIR__ . '/../config/config.php');

$conn = getDBConnection();
$_SESSION['user_id'] = 1;

$testContent = "Test post with tags";
$testTags = ["php", "testing", "web"];

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['content'] = $testContent;
$_POST['tags'] = $testTags;

require_once(__DIR__ . '/../src/controllers/AddPostController.php');

$stmt = $conn->prepare("SELECT content, tags FROM posts WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

echo "Test Results:\n";
echo "Expected content: " . $testContent . "\n";
echo "Actual content: " . $post['content'] . "\n";
echo "Expected tags: " . implode(',', $testTags) . "\n";
echo "Actual tags: " . $post['tags'] . "\n";
echo "Test " . ($post['content'] === $testContent && $post['tags'] === implode(',', $testTags) ? "PASSED" : "FAILED");

$stmt->close();
$conn->close();
?>
