<?php
use PHPUnit\Framework\TestCase;

class AdminUserManagementTest extends TestCase {
    private $conn;
    
    protected function setUp(): void {
        require_once(__DIR__ . '/../config/config.php');
        $this->conn = $conn;
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = 1;
        $_SESSION['is_admin'] = true;
        
        $username = "testuser_" . time();
        $email = "test_" . time() . "@example.com";
        $password = password_hash("testpassword", PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password_hash, is_admin, is_active) VALUES (?, ?, ?, 0, 1)");
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();
        
        $this->testUserId = $this->conn->insert_id;
        $this->testUsername = $username;
    }
    
    public function testViewUsers() {
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/users.php';
        
        ob_start();
        include(__DIR__ . '/../public/admin/users.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString('User Management', $output);
        $this->assertStringContainsString($this->testUsername, $output);
    }
    
    public function testToggleUserStatus() {
        $_POST['user_id'] = $this->testUserId;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        ob_start();
        include(__DIR__ . '/../public/admin/toggle_user_status.php');
        ob_end_clean();
        
        $stmt = $this->conn->prepare("SELECT is_active FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->bind_result($isActive);
        $stmt->fetch();
        $stmt->close();
        
        $this->assertEquals(0, $isActive);
        
        ob_start();
        include(__DIR__ . '/../public/admin/toggle_user_status.php');
        ob_end_clean();
        
        $stmt = $this->conn->prepare("SELECT is_active FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->bind_result($isActive);
        $stmt->fetch();
        $stmt->close();
        
        $this->assertEquals(1, $isActive);
    }
    
    public function testToggleAdminStatus() {
        $_POST['user_id'] = $this->testUserId;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        ob_start();
        include(__DIR__ . '/../public/admin/toggle_admin_status.php');
        ob_end_clean();
        
        $stmt = $this->conn->prepare("SELECT is_admin FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->bind_result($isAdmin);
        $stmt->fetch();
        $stmt->close();
        
        $this->assertEquals(1, $isAdmin);
        
        ob_start();
        include(__DIR__ . '/../public/admin/toggle_admin_status.php');
        ob_end_clean();
        
        $stmt = $this->conn->prepare("SELECT is_admin FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->bind_result($isAdmin);
        $stmt->fetch();
        $stmt->close();
        
        $this->assertEquals(0, $isAdmin);
    }
    
    public function testDeleteUser() {
        $_GET['user_id'] = $this->testUserId;
        
        ob_start();
        include(__DIR__ . '/../public/admin/delete_user.php');
        ob_end_clean();
        
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->store_result();
        
        $this->assertEquals(0, $stmt->num_rows);
        $stmt->close();
    }
    
    protected function tearDown(): void {
        if (isset($this->testUserId)) {
            $this->conn->query("DELETE FROM users WHERE user_id = {$this->testUserId}");
        }
        
        $_SESSION = array();
        
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
