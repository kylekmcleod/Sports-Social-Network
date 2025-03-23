<?php
session_start();
include_once('../../config/config.php');

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../homepage.php");
    exit();
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);
    $post_id = (int)$_POST['post_id'];
    
    if (empty($content)) {
        $error = "Post content cannot be empty.";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE posts SET content = ? WHERE post_id = ?");
            $stmt->bind_param("si", $content, $post_id);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                header("Location: posts.php?success=postupdated");
                exit();
            } else {
                $error = "No changes made or post not found.";
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Edit post error: " . $e->getMessage());
            $error = "Database error occurred.";
        }
    }
}

// Get post data
if ($post_id > 0) {
    $stmt = $conn->prepare("
        SELECT p.post_id, p.user_id, p.content, p.created_at, u.username 
        FROM posts p 
        LEFT JOIN users u ON p.user_id = u.user_id 
        WHERE p.post_id = ?
    ");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header("Location: posts.php?error=notfound");
        exit();
    }
    
    $post = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: posts.php?error=missingid");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Sport Page</title>
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
            <h4 class="m-0">Edit Post #<?php echo $post_id; ?></h4>
        </div>
    </header>

    <div class="layout">
        <?php include_once('../../assets/components/admin/leftSideBar.php'); ?>
        
        <!-- Main content -->
        <div class="layout__main">
            <h1 class="admin-panel__header">Edit Post</h1>
            
            <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="admin-card">
                <form method="POST" action="edit_post.php">
                    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Posted By</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($post['username']); ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Posted On</label>
                        <input type="text" class="form-control" value="<?php echo date('M d, Y H:i', strtotime($post['created_at'])); ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="posts.php" class="btn btn-secondary">Cancel</a>
                        <a href="delete_post.php?id=<?php echo $post['post_id']; ?>" class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>