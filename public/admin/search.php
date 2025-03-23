<?php
session_start();
include_once('../../config/config.php');

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: /COSC360/public/homepage.php");
    exit();
}

// Get search parameters
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : 'users';

if (empty($search)) {
    header("Location: index.php");
    exit();
}

// Perform search based on type
$results = [];

if ($type === 'users') {
    $query = "SELECT user_id, username, email, is_active, is_admin, created_at 
              FROM users 
              WHERE username LIKE ? OR email LIKE ? 
              ORDER BY username ASC";
    $stmt = $conn->prepare($query);
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
} else {
    $query = "SELECT p.post_id, p.user_id, p.content, p.created_at, u.username 
              FROM posts p 
              LEFT JOIN users u ON p.user_id = u.user_id 
              WHERE p.content LIKE ? OR u.username LIKE ? 
              ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($query);
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Sport Page</title>
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
                    <input type="text" name="q" class="header__search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" />
                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>" />
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
            <h1 class="admin-panel__header">Search Results</h1>
            
            <div class="admin-card mb-4">
                <h4>Searching for: "<?php echo htmlspecialchars($search); ?>" in <?php echo $type; ?></h4>
                
                <div class="btn-group mt-2">
                    <a href="search.php?q=<?php echo urlencode($search); ?>&type=users" class="btn btn-<?php echo $type === 'users' ? 'primary' : 'outline-primary'; ?>">Users</a>
                    <a href="search.php?q=<?php echo urlencode($search); ?>&type=posts" class="btn btn-<?php echo $type === 'posts' ? 'primary' : 'outline-primary'; ?>">Posts</a>
                </div>
            </div>
            
            <div class="admin-card">
                <?php if ($type === 'users'): ?>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Admin</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $user): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge <?php echo isset($user['is_active']) && $user['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo isset($user['is_active']) && $user['is_active'] ? 'Active' : 'Disabled'; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $user['is_admin'] ? 'bg-primary' : 'bg-secondary'; ?>">
                                        <?php echo $user['is_admin'] ? 'Yes' : 'No'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="user_posts.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-info btn-sm">Posts</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php else: ?>
                
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
                            <?php foreach ($results as $post): ?>
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
                
                <?php endif; ?>
                
                <?php if (empty($results)): ?>
                <div class="text-center p-4">
                    <p>No results found matching "<?php echo htmlspecialchars($search); ?>" in <?php echo $type; ?>.</p>
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