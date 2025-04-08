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

$tags = isset($_GET['tags']) ? explode(',', $_GET['tags']) : [];

$sql = "
    SELECT posts.*, users.username, users.profile_picture
    FROM posts
    INNER JOIN users ON posts.user_id = users.user_id
";

if (!empty($tags)) {
    $placeholders = str_repeat('?,', count($tags) - 1) . '?';
    $sql .= " WHERE (";
    $conditions = array_map(function($tag) {
        return "FIND_IN_SET(?, posts.tags) > 0";
    }, $tags);
    $sql .= implode(" OR ", $conditions) . ")";
}

$sql .= " ORDER BY posts.created_at DESC";

$stmt = $conn->prepare($sql);

if (!empty($tags)) {
    $types = str_repeat('s', count($tags));
    $stmt->bind_param($types, ...$tags);
}

$stmt->execute();
$result = $stmt->get_result();

$postsArray = [];
if ($result->num_rows > 0) {
    while ($post = $result->fetch_assoc()) {
        $postsArray[] = [
            'username' => $post['username'],
            'content' => $post['content'],
            'created_at' => $post['created_at'],
            'profile_picture' => $post['profile_picture'],
            'tags' => $post['tags']
        ];
    }
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($postsArray);
?>