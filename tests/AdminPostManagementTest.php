<?php
use PHPUnit\Framework\TestCase;

class AdminPostManagementTest extends TestCase {
    private $conn;
    private $testUserId;
    private $testPostId;
    
    protected function setUp(): void {
        require_once(__DIR__ . '/../config/config.php');
        $this->conn = $conn;
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = 1;
        $_SESSION['is_admin'] = true;
        
        $username = "posttest_" . time();
        $email = "posttest_" . time() . "@example.com";
        $password = password_hash("testpassword", PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();
        
        $this->testUserId = $this->conn->insert_id;
        
        $content = "Test post content " . time();
        $stmt = $this->conn->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $this->testUserId, $content);
        $stmt->execute();
        
        $this->testPostId = $this->conn->insert_id;
        $this->testContent = $content;
    }
    
    public function testViewPosts() {
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/posts.php';
        
        ob_start();
        include(__DIR__ . '/../public/admin/posts.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Post Management', $output);
        $this->assertStringContainsString($this->testContent, $output);
    }
    
    public function testEditPost() {
        $_GET['id'] = $this->testPostId;
        
        ob_start();
        include(__DIR__ . '/../public/admin/edit_post.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString($this->testContent, $output);
        $this->assertStringContainsString('Edit Post', $output);
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['post_id'] = $this->testPostId;
        $_POST['content'] = "Updated content " . time();
        
        ob_start();
        include(__DIR__ . '/../public/admin/edit_post.php');
        ob_end_clean();
        
        $stmt = $this->conn->prepare("SELECT content FROM posts WHERE post_id = ?");
        $stmt->bind_param("i", $this->testPostId);
        $stmt->execute();
        $stmt->bind_result($updatedContent);
        $stmt->fetch();
        $stmt->close();
        
        $this->assertEquals($_POST['content'], $updatedContent);
    }
    
    public function testDeletePost() {
        $_GET['id'] = $this->testPostId;
        
        ob_start();
        include(__DIR__ . '/../public/admin/delete_post.php');
        ob_end_clean();
        
        $stmt = $this->conn->prepare("SELECT post_id FROM posts WHERE post_id = ?");
        $stmt->bind_param("i", $this->testPostId);
        $stmt->execute();
        $stmt->store_result();
        
        $this->assertEquals(0, $stmt->num_rows);
        $stmt->close();
    }
    
    public function testUserPostsView() {
        $_GET['user_id'] = $this->testUserId;
        
        ob_start();
        include(__DIR__ . '/../public/admin/user_posts.php');
        $output = ob_get_clean();
        
        if ($this->testPostId) {
            $this->assertStringContainsString($this->testContent, $output);
        }
        $this->assertStringContainsString('Posts by', $output);
    }
    
    protected function tearDown(): void {
        if (isset($this->testPostId)) {
            $this->conn->query("DELETE FROM posts WHERE post_id = {$this->testPostId}");
        }
        
        if (isset($this->testUserId)) {
            $this->conn->query("DELETE FROM users WHERE user_id = {$this->testUserId}");
        }
        
        $_SESSION = array();
        
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
