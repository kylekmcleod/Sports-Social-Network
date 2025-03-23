<?php
session_start();
include_once('../../config/config.php');

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: /COSC360/public/homepage.php");
    exit();
}

// Handle search query if present
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchCondition = '';
$searchParams = [];

if (!empty($search)) {
    $searchCondition = "WHERE p.content LIKE ? OR u.username LIKE ?";
    $searchParams = ["%$search%", "%$search%"];
}

// Get posts from database with user info
$query = "SELECT p.post_id, p.user_id, p.content, p.created_at, u.username 
          FROM posts p 
          LEFT JOIN users u ON p.user_id = u.user_id 
          $searchCondition 
          ORDER BY p.created_at DESC";
          
$stmt = $conn->prepare($query);

if (!empty($searchParams)) {
    $stmt->bind_param(str_repeat('s', count($searchParams)), ...$searchParams);
}

$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Management - Sport Page</title>
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
            <div class="header__search-container">
                <form action="posts.php" method="GET">
                    <input type="text" name="q" class="header__search-input" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>" />
                    <button type="submit" class="header__search-button">
                        <img src="../../assets/svg/search.svg" class="header__search-icon" alt="Search" />
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="layout">
        <?php include_once('../../assets/components/admin/leftSideBar.php'); ?>
        
        <!-- Main content -->
        <div class="layout__main">
            <h1 class="admin-panel__header">Post Management</h1>
            
            <?php
            // Display success/error messages if they exist
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success">';
                if ($_GET['success'] == 'postdeleted') echo 'Post deleted successfully.';
                elseif ($_GET['success'] == 'postupdated') echo 'Post updated successfully.';
                echo '</div>';
            }
            
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger">';
                if ($_GET['error'] == 'notfound') echo 'Post not found.';
                elseif ($_GET['error'] == 'dberror') echo 'Database error occurred.';
                echo '</div>';
            }
            ?>
            
            <div class="admin-card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
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
                                    <a href="user_posts.php?user_id=<?php echo $post['user_id']; ?>">
                                        <?php echo htmlspecialchars($post['username']); ?>
                                    </a>
                                </td>
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
                
                <?php if (empty($posts)): ?>
                <div class="text-center p-4">
                    <p>No posts found<?php echo !empty($search) ? ' matching "' . htmlspecialchars($search) . '"' : ''; ?>.</p>
                </div>
                <?php endif; ?>
                
                <div class="mt-3">
                    <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>