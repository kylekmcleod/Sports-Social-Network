<?php
// TEST 1: TESTING FOR EMPTY CONTENT
error_reporting(~E_WARNING);
echo "<h3>Test 1: Empty Content</h3>";

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['content'] = '';
$_SESSION['user_id'] = 1;

ob_start();
include(__DIR__ . '/../src/controllers/AddPostController.php');
$output = ob_get_clean();

echo "Output: " . htmlspecialchars($output) . "<br>";
if (strpos($output, 'Error') !== false || empty($output)) {
    echo "Test passed.<br><br>";
} else {
    echo "Test failed.<br><br>";
}
?>
