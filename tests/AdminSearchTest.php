<?php
use PHPUnit\Framework\TestCase;

class AdminSearchTest extends TestCase {
    private $conn;
    private $testUserId;
    private $testUsername;
    private $testPostId;
    private $testContent;
    
    protected function setUp(): void {
        require_once(__DIR__ . '/../config/config.php');
        $this->conn = $conn;
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = 1;
        $_SESSION['is_admin'] = true;
        
        $this->testUsername = "searchtest_" . time();
        $email = "searchtest_" . time() . "@example.com";
        $password = password_hash("testpassword", PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->testUsername, $email, $password);
        $stmt->execute();
        
        $this->testUserId = $this->conn->insert_id;
        
        $this->testContent = "Unique search test content " . time();
        $stmt = $this->conn->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $this->testUserId, $this->testContent);
        $stmt->execute();
        
        $this->testPostId = $this->conn->insert_id;
    }
    
    public function testUserSearch() {
        $_GET['q'] = $this->testUsername;
        $_GET['type'] = 'users';
        
        ob_start();
        include(__DIR__ . '/../public/admin/search.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Search Results', $output);
        $this->assertStringContainsString($this->testUsername, $output);
    }
    
    public function testPostSearch() {
        $_GET['q'] = $this->testContent;
        $_GET['type'] = 'posts';
        
        ob_start();
        include(__DIR__ . '/../public/admin/search.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Search Results', $output);
        $this->assertStringContainsString(substr($this->testContent, 0, 30), $output);
    }
    
    public function testNoResultsSearch() {
        $_GET['q'] = 'nonexistentsearchterm' . time();
        $_GET['type'] = 'users';
        
        ob_start();
        include(__DIR__ . '/../public/admin/search.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString('No results found', $output);
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
