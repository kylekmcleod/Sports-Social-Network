<?php
use PHPUnit\Framework\TestCase;

class AdminAuthTest extends TestCase {
    private $conn;
    
    protected function setUp(): void {
        // Connect to the database
        require_once(__DIR__ . '/../config/config.php');
        $this->conn = $conn;
        
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function testAdminRedirectIfNotLoggedIn() {
        // Clear session to simulate not logged in state
        $_SESSION = array();
        
        // Create mock request to admin page
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/index.php';
        
        // Include the admin page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/index.php');
        ob_end_clean();
        
        // Check if headers contain redirect
        $headers = xdebug_get_headers();
        $this->assertContains('Location: ../homepage.php', $headers);
    }
    
    public function testRegularUserCannotAccessAdmin() {
        // Simulate logged in but not admin
        $_SESSION['user_id'] = 2; // Assuming user ID 2 is not an admin
        $_SESSION['is_admin'] = false;
        
        // Create mock request to admin page
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/index.php';
        
        // Include the admin page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/index.php');
        ob_end_clean();
        
        // Check if headers contain redirect
        $headers = xdebug_get_headers();
        $this->assertContains('Location: ../homepage.php', $headers);
    }
    
    public function testAdminCanAccessAdminPanel() {
        // Simulate admin logged in
        $_SESSION['user_id'] = 1; // Assuming user ID 1 is an admin
        $_SESSION['is_admin'] = true;
        
        // Create mock request to admin page
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/index.php';
        
        // Include the admin page with output buffering
        ob_start();
        $output = include(__DIR__ . '/../public/admin/index.php');
        $content = ob_get_clean();
        
        // Check if output contains admin dashboard elements
        $this->assertStringContainsString('Admin Dashboard', $content);
        $this->assertStringContainsString('Quick Summary', $content);
    }
    
    protected function tearDown(): void {
        // Clean up
        $_SESSION = array();
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
