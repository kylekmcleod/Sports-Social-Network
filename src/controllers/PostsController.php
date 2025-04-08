<?php
/*
    This PHP script gets the most recent posts that are in the database.

    When the script is accessed via an HTTP request, it will return a JSON-encoded list of posts, 
    with each post including the username, content, and timestamp of creation.
    
    Example Response:
    [
        {
            "username": "john_doe",
            "content": "This is the first post",
            "created_at": "2025-03-10 13:58:12"
        },
        {
            "username": "jane_smith",
            "content": "This is another post",
            "created_at": "2025-03-10 12:45:00"
        }
    ]
*/
?>

<?php
include_once('../../config/config.php');

$conn = getDBConnection();

$sql = "
    SELECT posts.*, users.username
    FROM posts
    INNER JOIN users ON posts.user_id = users.user_id
    ORDER BY posts.created_at DESC
";
$result = $conn->query($sql);

$postsArray = [];
if ($result->num_rows > 0) {
    while ($post = $result->fetch_assoc()) {
        $postsArray[] = [
            'username' => $post['username'],
            'content'  => $post['content'],
            'created_at' => $post['created_at'],
            'id' => $post['post_id']
            
        ];
    }
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($postsArray);

?>
