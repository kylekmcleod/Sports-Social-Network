<?php
use PHPUnit\Framework\TestCase;

class GetPostWithCommentsTest extends TestCase {
    private $conn;
    
    protected function setUp(): void {
        $this->conn = require(__DIR__ . '/../config/config.php');
    }

    public function testGetPostWithComments() {
        $result = getPostWithComments(1);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('post', $result);
        $this->assertArrayHasKey('comments', $result);
        $this->assertIsArray($result['comments']);
    }

    public function testFormatTimeAgo() {
        $now = date('Y-m-d H:i:s');
        $this->assertEquals('0s', formatTimeAgo($now));
        
        $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $this->assertEquals('1h', formatTimeAgo($oneHourAgo));
        
        $oneDayAgo = date('Y-m-d H:i:s', strtotime('-1 day'));
        $this->assertEquals(date('M j', strtotime('-1 day')), formatTimeAgo($oneDayAgo));
    }

    public function testNonExistentPost() {
        $result = getPostWithComments(99999);
        
        $this->assertIsArray($result);
        $this->assertNull($result['post']);
        $this->assertEmpty($result['comments']);
    }

    protected function tearDown(): void {
        $this->conn->close();
    }
}
