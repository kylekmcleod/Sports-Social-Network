<?php
use PHPUnit\Framework\TestCase;

class AdminPostManagementTest extends TestCase {
    private $conn;
    private $testUserId;
    private $testPostId;
    
    protected function setUp(): void {
        // Connect to the database
        require_once(__DIR__ . '/../config/config.php');
        $this->conn = $conn;
        
        // Start session and set admin privileges
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = 1; // Assuming user ID 1 is an admin
        $_SESSION['is_admin'] = true;
        
        // Create a test user for posts
        $username = "posttest_" . time();
        $email = "posttest_" . time() . "@example.com";
        $password = password_hash("testpassword", PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();
        
        $this->testUserId = $this->conn->insert_id;
        
        // Create a test post
        $content = "Test post content " . time();
        $stmt = $this->conn->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $this->testUserId, $content);
        $stmt->execute();
        
        $this->testPostId = $this->conn->insert_id;
        $this->testContent = $content;
    }
    
    public function testViewPosts() {
        // Create mock request to posts page
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/posts.php';
        
        // Include the posts page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/posts.php');
        $output = ob_get_clean();
        
        // Check if output contains expected post data
        $this->assertStringContainsString('Post Management', $output);
        $this->assertStringContainsString($this->testContent, $output);
    }
    
    public function testEditPost() {
        // First, setup to view the edit page
        $_GET['id'] = $this->testPostId;
        
        // Include the edit page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/edit_post.php');
        $output = ob_get_clean();
        
        // Check if edit form contains current content
        $this->assertStringContainsString($this->testContent, $output);
        $this->assertStringContainsString('Edit Post', $output);
        
        // Now, setup POST data to edit the post
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['post_id'] = $this->testPostId;
        $_POST['content'] = "Updated content " . time();
        
        // Include the edit script with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/edit_post.php');
        ob_end_clean();
        
        // Verify post was updated in database
        $stmt = $this->conn->prepare("SELECT content FROM posts WHERE post_id = ?");
        $stmt->bind_param("i", $this->testPostId);
        $stmt->execute();
        $stmt->bind_result($updatedContent);
        $stmt->fetch();
        $stmt->close();
        
        $this->assertEquals($_POST['content'], $updatedContent);
    }
    
    public function testDeletePost() {
        // Setup GET data for delete operation
        $_GET['id'] = $this->testPostId;
        
        // Include the delete script with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/delete_post.php');
        ob_end_clean();
        
        // Verify post was deleted from database
        $stmt = $this->conn->prepare("SELECT post_id FROM posts WHERE post_id = ?");
        $stmt->bind_param("i", $this->testPostId);
        $stmt->execute();
        $stmt->store_result();
        
        $this->assertEquals(0, $stmt->num_rows);
        $stmt->close();
    }
    
    public function testUserPostsView() {
        // Setup request to view posts by user
        $_GET['user_id'] = $this->testUserId;
        
        // Include the user posts page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/user_posts.php');
        $output = ob_get_clean();
        
        // Check if page displays user's posts
        if ($this->testPostId) { // If post wasn't deleted by previous test
            $this->assertStringContainsString($this->testContent, $output);
        }
        $this->assertStringContainsString('Posts by', $output);
    }
    
    protected function tearDown(): void {
        // Clean up - delete test post if it still exists
        if (isset($this->testPostId)) {
            $this->conn->query("DELETE FROM posts WHERE post_id = {$this->testPostId}");
        }
        
        // Delete test user
        if (isset($this->testUserId)) {
            $this->conn->query("DELETE FROM users WHERE user_id = {$this->testUserId}");
        }
        
        // Clear session
        $_SESSION = array();
        
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
