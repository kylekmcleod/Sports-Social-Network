<?php
use PHPUnit\Framework\TestCase;

class AdminUserManagementTest extends TestCase {
    private $conn;
    
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
        
        // Create a test user for our tests
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
        // Create mock request to users page
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/users.php';
        
        // Include the users page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/users.php');
        $output = ob_get_clean();
        
        // Check if output contains expected user data
        $this->assertStringContainsString('User Management', $output);
        $this->assertStringContainsString($this->testUsername, $output);
    }
    
    public function testToggleUserStatus() {
        // Setup POST data
        $_POST['user_id'] = $this->testUserId;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        // Include the toggle script with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/toggle_user_status.php');
        ob_end_clean();
        
        // Verify user status was changed in database
        $stmt = $this->conn->prepare("SELECT is_active FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->bind_result($isActive);
        $stmt->fetch();
        $stmt->close();
        
        // Status should now be 0 (disabled)
        $this->assertEquals(0, $isActive);
        
        // Toggle again to restore
        ob_start();
        include(__DIR__ . '/../public/admin/toggle_user_status.php');
        ob_end_clean();
        
        // Verify status is back to 1
        $stmt = $this->conn->prepare("SELECT is_active FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->bind_result($isActive);
        $stmt->fetch();
        $stmt->close();
        
        $this->assertEquals(1, $isActive);
    }
    
    public function testToggleAdminStatus() {
        // Setup POST data
        $_POST['user_id'] = $this->testUserId;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        // Include the toggle script with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/toggle_admin_status.php');
        ob_end_clean();
        
        // Verify admin status was changed in database
        $stmt = $this->conn->prepare("SELECT is_admin FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->bind_result($isAdmin);
        $stmt->fetch();
        $stmt->close();
        
        // Status should now be 1 (admin)
        $this->assertEquals(1, $isAdmin);
        
        // Toggle again to restore
        ob_start();
        include(__DIR__ . '/../public/admin/toggle_admin_status.php');
        ob_end_clean();
        
        // Verify admin status is back to 0
        $stmt = $this->conn->prepare("SELECT is_admin FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->bind_result($isAdmin);
        $stmt->fetch();
        $stmt->close();
        
        $this->assertEquals(0, $isAdmin);
    }
    
    public function testDeleteUser() {
        // Setup GET data for delete operation
        $_GET['user_id'] = $this->testUserId;
        
        // Include the delete script with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/delete_user.php');
        ob_end_clean();
        
        // Verify user was deleted from database
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $this->testUserId);
        $stmt->execute();
        $stmt->store_result();
        
        $this->assertEquals(0, $stmt->num_rows);
        $stmt->close();
    }
    
    protected function tearDown(): void {
        // Clean up - delete test user if it still exists
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
