<?php
session_start();
include_once('../../config/config.php');
include_once('../../src/controllers/auth.php');

// Redirect if not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../homepage.php");
    exit();
}

// Check if user_id is set
if (!isset($_GET['user_id']) || (int)$_GET['user_id'] <= 0) {
    header("Location: users.php?error=missingid");
    exit();
}

$user_id = (int)$_GET['user_id'];

// Get user info
$userStmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userStmt->store_result();

if ($userStmt->num_rows === 0) {
    $userStmt->close();
    header("Location: users.php?error=notfound");
    exit();
}

$userStmt->bind_result($username);
$userStmt->fetch();
$userStmt->close();

// Get user's posts - Fixed the missing WHERE clause
$postsStmt = $conn->prepare("
    SELECT post_id, content, created_at
    FROM posts 
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$postsStmt->bind_param("i", $user_id);
$postsStmt->execute();
$postsResult = $postsStmt->get_result();
$posts = [];
while ($row = $postsResult->fetch_assoc()) {
    $posts[] = $row;
}
$postsStmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts by <?php echo htmlspecialchars($username); ?> - Sport Page</title>
    <link rel="stylesheet" href="../../assets/css/globals.css" />
    <link rel="stylesheet" href="../../assets/css/homepage/brand.css" />
    <link rel="stylesheet" href="../../assets/css/homepage/layout.css" />
    <link rel="stylesheet" href="../../assets/css/nav/sidebar-menu.css" />
    <link rel="stylesheet" href="../../assets/css/homepage/header.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin/admin.css" />
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header__content">
            <h4 class="m-0">Posts by <?php echo htmlspecialchars($username); ?></h4>
        </div>
    </header>

    <div class="layout">
        <?php include_once('../../assets/components/admin/leftSideBar.php'); ?>
        
        <!-- Main content -->
        <div class="layout__main">
            <h1 class="admin-panel__header">Posts by @<?php echo htmlspecialchars($username); ?></h1>
            
            <?php
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success">';
                if ($_GET['success'] == 'postdeleted') echo 'Post deleted successfully.';
                echo '</div>';
            }
            ?>
            
            <div class="admin-card">
                <?php if (empty($posts)): ?>
                <div class="text-center p-4">
                    <p>This user hasn't posted anything yet.</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Content</th>
                                <th>Posted On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?php echo $post['post_id']; ?></td>
                                <td>
                                    <div class="post-content">
                                        <?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 100) . (strlen($post['content']) > 100 ? '...' : ''))); ?>
                                    </div>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <a href="edit_post.php?id=<?php echo $post['post_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="delete_post.php?id=<?php echo $post['post_id']; ?>" class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
                <div class="mt-3">
                    <a href="users.php" class="btn btn-secondary">Back to Users</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>