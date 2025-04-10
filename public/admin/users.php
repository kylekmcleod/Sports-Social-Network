<?php
session_start();
include_once('../../config/config.php');
include_once('../../src/controllers/auth.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../homepage.php");
    exit();
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchCondition = '';
$searchParams = [];

if (!empty($search)) {
    $searchCondition = "WHERE username LIKE ? OR email LIKE ?";
    $searchParams = ["%$search%", "%$search%"];
}

$query = "SELECT user_id, username, email, first_name, last_name, is_active, is_admin, created_at 
          FROM users 
          $searchCondition 
          ORDER BY username ASC";
          
$stmt = $conn->prepare($query);

if (!empty($searchParams)) {
    $stmt->bind_param(str_repeat('s', count($searchParams)), ...$searchParams);
}

$stmt->execute();
$result = $stmt->get_result();
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Sport Page</title>
    <link rel="stylesheet" href="../../assets/css/globals.css" />
    <link rel="stylesheet" href="../../assets/css/homepage/brand.css" />
    <link rel="stylesheet" href="../../assets/css/homepage/layout.css" />
    <link rel="stylesheet" href="../../assets/css/nav/sidebar-menu.css" />
    <link rel="stylesheet" href="../../assets/css/homepage/header.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin/admin.css" />
</head>
<body>
    <header class="header">
        <div class="header__content">
            <div class="header__search-container">
                <form action="users.php" method="GET">
                    <input type="text" name="q" class="header__search-input" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>" />
                    <button type="submit" class="header__search-button">
                        <img src="../../assets/svg/search.svg" class="header__search-icon" alt="Search" />
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="layout">
        <?php include_once('../../assets/components/admin/leftSideBar.php'); ?>
        
        <div class="layout__main">
            <h1 class="admin-panel__header">User Management</h1>
            
            <?php
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success">';
                if ($_GET['success'] == 'userdeleted') echo 'User deleted successfully.';
                elseif ($_GET['success'] == 'userstatus') echo 'User status updated successfully.';
                elseif ($_GET['success'] == 'adminstatus') echo 'Admin status updated successfully.';
                echo '</div>';
            }
            
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger">';
                if ($_GET['error'] == 'notfound') echo 'User not found.';
                elseif ($_GET['error'] == 'dberror') echo 'Database error occurred.';
                elseif ($_GET['error'] == 'selfdelete') echo 'You cannot delete your own account from here.';
                elseif ($_GET['error'] == 'selfadmin') echo 'You cannot change your own admin status.';
                echo '</div>';
            }
            ?>
            
            <div class="admin-card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Admin</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge <?php echo isset($user['is_active']) && $user['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo isset($user['is_active']) && $user['is_active'] ? 'Active' : 'Disabled'; ?>
                                    </span>
                                    <form method="POST" action="toggle_user_status.php" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-<?php echo isset($user['is_active']) && $user['is_active'] ? 'danger' : 'success'; ?>">
                                            <?php echo isset($user['is_active']) && $user['is_active'] ? 'Disable' : 'Enable'; ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge <?php echo $user['is_admin'] ? 'bg-primary' : 'bg-secondary'; ?>">
                                        <?php echo $user['is_admin'] ? 'Yes' : 'No'; ?>
                                    </span>
                                    <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                    <form method="POST" action="toggle_admin_status.php" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-<?php echo $user['is_admin'] ? 'secondary' : 'primary'; ?>">
                                            <?php echo $user['is_admin'] ? 'Remove Admin' : 'Make Admin'; ?>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <a href="user_posts.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-info btn-sm">Posts</a>
                                    
                                    <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                    <a href="delete_user.php?user_id=<?php echo $user['user_id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete user <?php echo htmlspecialchars($user['username']); ?>? This action cannot be undone.')">
                                        Delete
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (empty($users)): ?>
                <div class="text-center p-4">
                    <p>No users found<?php echo !empty($search) ? ' matching "' . htmlspecialchars($search) . '"' : ''; ?>.</p>
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
