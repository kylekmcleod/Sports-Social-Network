<?php
use PHPUnit\Framework\TestCase;

class AdminReportsTest extends TestCase {
    private $conn;
    
    protected function setUp(): void {
        require_once(__DIR__ . '/../config/config.php');
        $this->conn = $conn;
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = 1;
        $_SESSION['is_admin'] = true;
    }
    
    public function testSignupsReport() {
        $_GET['report_type'] = 'signups';
        $_GET['timeframe'] = 'all';
        
        ob_start();
        include(__DIR__ . '/../public/admin/reports.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString('User Sign-ups Report', $output);
        $this->assertStringContainsString('Total', $output);
    }
    
    public function testPostsReport() {
        $_GET['report_type'] = 'posts';
        $_GET['timeframe'] = 'month';
        
        ob_start();
        include(__DIR__ . '/../public/admin/reports.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Posts Report', $output);
        $this->assertStringContainsString('Last 30 Days', $output);
    }
    
    public function testActiveUsersReport() {
        $_GET['report_type'] = 'active_users';
        $_GET['timeframe'] = 'week';
        
        ob_start();
        include(__DIR__ . '/../public/admin/reports.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Most Active Users Report', $output);
        $this->assertStringContainsString('Last 7 Days', $output);
    }
    
    public function testCustomDateRangeReport() {
        $_GET['report_type'] = 'posts';
        $_GET['timeframe'] = 'custom';
        $_GET['start_date'] = date('Y-m-d', strtotime('-30 days'));
        $_GET['end_date'] = date('Y-m-d');
        
        ob_start();
        include(__DIR__ . '/../public/admin/reports.php');
        $output = ob_get_clean();
        
        $this->assertStringContainsString('From ' . $_GET['start_date'] . ' to ' . $_GET['end_date'], $output);
    }
    
    protected function tearDown(): void {
        $_SESSION = array();
        
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
