<?php
use PHPUnit\Framework\TestCase;

class AddCommentControllerTest extends TestCase {
    private $conn;
    
    protected function setUp(): void {
        $this->conn = require(__DIR__ . '/../config/config.php');
    }

    public function testAddCommentSuccess() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['user_id'] = 1; 
        $_POST['post_id'] = 1;    
        $_POST['content'] = 'Test comment for PHPUnit';

        ob_start();
        include(__DIR__ . '/../src/controllers/AddCommentController.php');
        $output = ob_get_clean();

        $this->assertStringContainsString('success', $output);
        
        // Verify comment was added
        $stmt = $this->conn->prepare("SELECT content FROM comments WHERE content = ?");
        $stmt->bind_param("s", $_POST['content']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $this->assertEquals(1, $result->num_rows);
    }

    public function testAddCommentUnauthorized() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        unset($_SESSION['user_id']);
        
        ob_start();
        include(__DIR__ . '/../src/controllers/AddCommentController.php');
        $headers = xdebug_get_headers();
        ob_end_clean();
        
        $this->assertContains('Location: ../../public/login.php', $headers);
    }

    protected function tearDown(): void {
        $this->conn->query("DELETE FROM comments WHERE content LIKE 'Test comment%'");
        $this->conn->close();
    }
}
