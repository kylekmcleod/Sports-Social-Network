<?php
use PHPUnit\Framework\TestCase;

class AdminSearchTest extends TestCase {
    private $conn;
    private $testUserId;
    private $testUsername;
    private $testPostId;
    private $testContent;
    
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
        
        // Create a unique test user
        $this->testUsername = "searchtest_" . time();
        $email = "searchtest_" . time() . "@example.com";
        $password = password_hash("testpassword", PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->testUsername, $email, $password);
        $stmt->execute();
        
        $this->testUserId = $this->conn->insert_id;
        
        // Create a unique test post
        $this->testContent = "Unique search test content " . time();
        $stmt = $this->conn->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $this->testUserId, $this->testContent);
        $stmt->execute();
        
        $this->testPostId = $this->conn->insert_id;
    }
    
    public function testUserSearch() {
        // Setup GET parameters for searching users
        $_GET['q'] = $this->testUsername;
        $_GET['type'] = 'users';
        
        // Include the search page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/search.php');
        $output = ob_get_clean();
        
        // Check if output contains search results with our test user
        $this->assertStringContainsString('Search Results', $output);
        $this->assertStringContainsString($this->testUsername, $output);
    }
    
    public function testPostSearch() {
        // Setup GET parameters for searching posts
        $_GET['q'] = $this->testContent;
        $_GET['type'] = 'posts';
        
        // Include the search page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/search.php');
        $output = ob_get_clean();
        
        // Check if output contains search results with our test post
        $this->assertStringContainsString('Search Results', $output);
        $this->assertStringContainsString(substr($this->testContent, 0, 30), $output);
    }
    
    public function testNoResultsSearch() {
        // Setup GET parameters for a search that should return no results
        $_GET['q'] = 'nonexistentsearchterm' . time();
        $_GET['type'] = 'users';
        
        // Include the search page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/search.php');
        $output = ob_get_clean();
        
        // Check if output indicates no results
        $this->assertStringContainsString('No results found', $output);
    }
    
    protected function tearDown(): void {
        // Clean up - delete test post
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
