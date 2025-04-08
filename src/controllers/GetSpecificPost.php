<?php
/*
    This PHP script gets a specific post based on the ID provided in the URL.

    When the script is accessed via an HTTP request with a post_id parameter,
    it will return a JSON-encoded post with details including the username, 
    content, and timestamp of creation.
    
    Example Request: /src/controllers/GetSpecificPost.php?id=123
    
    Example Response:
    {
        "post": {
            "username": "john_doe",
            "content": "This is the first post",
            "created_at": "2025-03-10 13:58:12",
            "post_id": 123,
            "user_id": 45,
            "profile_picture": "profile1.jpg"
        }
    }
*/
?>

<?php
include_once('../../config/config.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Missing or invalid post ID']);
    exit;
}

$post_id = (int)$_GET['id'];
$conn = getDBConnection();

$post_sql = "
    SELECT posts.*, users.username, users.profile_picture
    FROM posts
    INNER JOIN users ON posts.user_id = users.user_id
    WHERE posts.post_id = ?
";

$post_stmt = $conn->prepare($post_sql);
$post_stmt->bind_param("i", $post_id);
$post_stmt->execute();
$post_result = $post_stmt->get_result();

if ($post_result->num_rows === 0) {
    $conn->close();
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Post not found']);
    exit;
}

$post = $post_result->fetch_assoc();

// Prepare response
$response = [
    'post' => [
        'username' => $post['username'],
        'content' => $post['content'],
        'created_at' => $post['created_at'],
        'post_id' => $post['post_id'],
        'user_id' => $post['user_id'],
        'profile_picture' => $post['profile_picture']
    ]
];

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>