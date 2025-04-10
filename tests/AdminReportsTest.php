<?php
use PHPUnit\Framework\TestCase;

class AdminReportsTest extends TestCase {
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
    }
    
    public function testSignupsReport() {
        // Setup GET parameters for signups report
        $_GET['report_type'] = 'signups';
        $_GET['timeframe'] = 'all';
        
        // Include the reports page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/reports.php');
        $output = ob_get_clean();
        
        // Check if output contains expected report elements
        $this->assertStringContainsString('User Sign-ups Report', $output);
        $this->assertStringContainsString('Total', $output);
    }
    
    public function testPostsReport() {
        // Setup GET parameters for posts report
        $_GET['report_type'] = 'posts';
        $_GET['timeframe'] = 'month';
        
        // Include the reports page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/reports.php');
        $output = ob_get_clean();
        
        // Check if output contains expected report elements
        $this->assertStringContainsString('Posts Report', $output);
        $this->assertStringContainsString('Last 30 Days', $output);
    }
    
    public function testActiveUsersReport() {
        // Setup GET parameters for active users report
        $_GET['report_type'] = 'active_users';
        $_GET['timeframe'] = 'week';
        
        // Include the reports page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/reports.php');
        $output = ob_get_clean();
        
        // Check if output contains expected report elements
        $this->assertStringContainsString('Most Active Users Report', $output);
        $this->assertStringContainsString('Last 7 Days', $output);
    }
    
    public function testCustomDateRangeReport() {
        // Setup GET parameters for custom date range report
        $_GET['report_type'] = 'posts';
        $_GET['timeframe'] = 'custom';
        $_GET['start_date'] = date('Y-m-d', strtotime('-30 days'));
        $_GET['end_date'] = date('Y-m-d');
        
        // Include the reports page with output buffering
        ob_start();
        include(__DIR__ . '/../public/admin/reports.php');
        $output = ob_get_clean();
        
        // Check if output contains expected custom date range elements
        $this->assertStringContainsString('From ' . $_GET['start_date'] . ' to ' . $_GET['end_date'], $output);
    }
    
    protected function tearDown(): void {
        // Clear session
        $_SESSION = array();
        
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
