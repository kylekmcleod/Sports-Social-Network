<?php
session_start();
include_once('../../config/config.php');

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../homepage.php");
    exit();
}

// Get dashboard summary data
$dashboardData = [];

// Total users
$query = "SELECT COUNT(*) as count FROM users";
$result = $conn->query($query);
$dashboardData['users'] = $result->fetch_assoc()['count'];

// New users in the last 7 days
$query = "SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$result = $conn->query($query);
$dashboardData['new_users'] = $result->fetch_assoc()['count'];

// Total posts
$query = "SELECT COUNT(*) as count FROM posts";
$result = $conn->query($query);
$dashboardData['posts'] = $result->fetch_assoc()['count'];

// New posts in the last 7 days
$query = "SELECT COUNT(*) as count FROM posts WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$result = $conn->query($query);
$dashboardData['new_posts'] = $result->fetch_assoc()['count'];

// Total comments (if table exists)
$dashboardData['comments'] = 0;
$dashboardData['new_comments'] = 0;
$checkTable = $conn->query("SHOW TABLES LIKE 'comments'");
if ($checkTable->num_rows > 0) {
    $query = "SELECT COUNT(*) as count FROM comments";
    $result = $conn->query($query);
    $dashboardData['comments'] = $result->fetch_assoc()['count'];
    
    $query = "SELECT COUNT(*) as count FROM comments WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $result = $conn->query($query);
    $dashboardData['new_comments'] = $result->fetch_assoc()['count'];
}

// Total likes (if table exists)
$dashboardData['likes'] = 0;
$dashboardData['new_likes'] = 0;
$checkTable = $conn->query("SHOW TABLES LIKE 'likes'");
if ($checkTable->num_rows > 0) {
    $query = "SELECT COUNT(*) as count FROM likes";
    $result = $conn->query($query);
    $dashboardData['likes'] = $result->fetch_assoc()['count'];
    
    $query = "SELECT COUNT(*) as count FROM likes WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $result = $conn->query($query);
    $dashboardData['new_likes'] = $result->fetch_assoc()['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sport Page</title>
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
                <form action="search.php" method="GET">
                    <input type="text" name="q" class="header__search-input" placeholder="Search users or posts..." />
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
            <h1 class="admin-panel__header">Admin Dashboard</h1>
            
            <div class="admin-card-big mb-4">
                <h4 class="card-title mb-3">Quick Summary</h4>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Users</h5>
                                <p class="card-text display-6"><?php echo $dashboardData['users']; ?></p>
                                <p class="card-text">New (7d): <?php echo $dashboardData['new_users']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Posts</h5>
                                <p class="card-text display-6"><?php echo $dashboardData['posts']; ?></p>
                                <p class="card-text">New (7d): <?php echo $dashboardData['new_posts']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Comments</h5>
                                <p class="card-text display-6"><?php echo $dashboardData['comments']; ?></p>
                                <p class="card-text">New (7d): <?php echo $dashboardData['new_comments']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">Likes</h5>
                                <p class="card-text display-6"><?php echo $dashboardData['likes']; ?></p>
                                <p class="card-text">New (7d): <?php echo $dashboardData['new_likes']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="admin-card mb-4">
                <h4 class="card-title mb-3">Admin Actions</h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">User Management</h5>
                                <p class="card-text">View, edit, and manage user accounts.</p>
                                <a href="users.php" class="btn btn-primary">Manage Users</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Post Management</h5>
                                <p class="card-text">Review, edit, and moderate content.</p>
                                <a href="posts.php" class="btn btn-primary">Manage Posts</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Usage Reports</h5>
                                <p class="card-text">View detailed site activity reports.</p>
                                <a href="reports.php" class="btn btn-primary">View Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="admin-card">
                <h4 class="card-title mb-3">Recent Activity</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                New Users (Last 5)
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Joined</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $query = "SELECT user_id, username, created_at FROM users ORDER BY created_at DESC LIMIT 5";
                                            $result = $conn->query($query);
                                            
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                                                    echo '<td>' . date('M d, Y', strtotime($row['created_at'])) . '</td>';
                                                    echo '<td><a href="user_posts.php?user_id=' . $row['user_id'] . '" class="btn btn-sm btn-info">View</a></td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="3" class="text-center">No users found</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                Recent Posts (Last 5)
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Content</th>
                                                <th>Posted</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $query = "SELECT p.post_id, p.content, p.created_at 
                                                      FROM posts p 
                                                      ORDER BY p.created_at DESC LIMIT 5";
                                            $result = $conn->query($query);
                                            
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td>' . htmlspecialchars(substr($row['content'], 0, 30)) . '...</td>';
                                                    echo '<td>' . date('M d, Y', strtotime($row['created_at'])) . '</td>';
                                                    echo '<td><a href="edit_post.php?id=' . $row['post_id'] . '" class="btn btn-sm btn-info">Edit</a></td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="3" class="text-center">No posts found</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>