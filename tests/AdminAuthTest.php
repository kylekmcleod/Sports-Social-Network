<?php
use PHPUnit\Framework\TestCase;

class AdminAuthTest extends TestCase {
    private $conn;
    
    protected function setUp(): void {
        require_once(__DIR__ . '/../config/config.php');
        $this->conn = $conn;
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function testAdminRedirectIfNotLoggedIn() {
        $_SESSION = array();
        
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/index.php';
        
        ob_start();
        include(__DIR__ . '/../public/admin/index.php');
        ob_end_clean();
        
        $headers = xdebug_get_headers();
        $this->assertContains('Location: ../homepage.php', $headers);
    }
    
    public function testRegularUserCannotAccessAdmin() {
        $_SESSION['user_id'] = 2;
        $_SESSION['is_admin'] = false;
        
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/index.php';
        
        ob_start();
        include(__DIR__ . '/../public/admin/index.php');
        ob_end_clean();
        
        $headers = xdebug_get_headers();
        $this->assertContains('Location: ../homepage.php', $headers);
    }
    
    public function testAdminCanAccessAdminPanel() {
        $_SESSION['user_id'] = 1;
        $_SESSION['is_admin'] = true;
        
        $_SERVER['REQUEST_URI'] = '/COSC360/public/admin/index.php';
        
        ob_start();
        $output = include(__DIR__ . '/../public/admin/index.php');
        $content = ob_get_clean();
        
        $this->assertStringContainsString('Admin Dashboard', $content);
        $this->assertStringContainsString('Quick Summary', $content);
    }
    
    protected function tearDown(): void {
        $_SESSION = array();
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
