<?php
use PHPUnit\Framework\TestCase;

class GetCommentsControllerTest extends TestCase {
    private $conn;
    
    protected function setUp(): void {
        $this->conn = require(__DIR__ . '/../config/config.php');
        $this->conn->query("INSERT INTO comments (post_id, user_id, content) VALUES (1, 1, 'Test comment for testing')");
    }

    public function testGetCommentsSuccess() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['post_id'] = 1;

        ob_start();
        include(__DIR__ . '/../src/controllers/GetCommentsController.php');
        $output = ob_get_clean();
        
        $response = json_decode($output, true);
        
        $this->assertIsArray($response);
        $this->assertEquals('success', $response['status']);
        $this->assertNotEmpty($response['comments']);
    }

    public function testGetCommentsNoPostId() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_GET['post_id']);

        ob_start();
        include(__DIR__ . '/../src/controllers/GetCommentsController.php');
        $output = ob_get_clean();
        
        $response = json_decode($output, true);
        
        $this->assertEquals('error', $response['status']);
        $this->assertEquals('Post ID required', $response['message']);
    }

    protected function tearDown(): void {
        $this->conn->query("DELETE FROM comments WHERE content = 'Test comment for testing'");
        $this->conn->close();
    }
}
