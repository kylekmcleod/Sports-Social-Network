<?php
session_start();
include_once('../../config/config.php');
include_once('../../src/controllers/auth.php');

// Redirect if not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../homepage.php");
    exit();
}

// Handle filtering parameters
$timeFrame = isset($_GET['timeframe']) ? $_GET['timeframe'] : 'all';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$reportType = isset($_GET['report_type']) ? $_GET['report_type'] : 'signups';

// Set time period conditions based on filter
$timeCondition = '';
$params = [];

if ($timeFrame === 'today') {
    $timeCondition = "WHERE DATE(created_at) = CURDATE()";
} elseif ($timeFrame === 'yesterday') {
    $timeCondition = "WHERE DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
} elseif ($timeFrame === 'week') {
    $timeCondition = "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
} elseif ($timeFrame === 'month') {
    $timeCondition = "WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
} elseif ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
    $timeCondition = "WHERE DATE(created_at) BETWEEN ? AND ?";
    $params = [$startDate, $endDate];
}

// Get report data based on report type
$reportData = [];
$chartLabels = [];
$chartValues = [];

if ($reportType === 'signups') {
    // User signups report
    if ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
        $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM users $timeCondition GROUP BY DATE(created_at) ORDER BY date";
        $stmt = $conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param("ss", $params[0], $params[1]);
        }
    } else {
        $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM users $timeCondition GROUP BY DATE(created_at) ORDER BY date";
        $stmt = $conn->prepare($query);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $reportData[] = $row;
        $chartLabels[] = $row['date'];
        $chartValues[] = $row['count'];
    }
    $stmt->close();
    
    // Get total count for the period
    if ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
        $countQuery = "SELECT COUNT(*) as total FROM users $timeCondition";
        $countStmt = $conn->prepare($countQuery);
        $countStmt->bind_param("ss", $params[0], $params[1]);
    } else {
        $countQuery = "SELECT COUNT(*) as total FROM users $timeCondition";
        $countStmt = $conn->prepare($countQuery);
    }
    
    $countStmt->execute();
    $totalResult = $countStmt->get_result();
    $totalCount = $totalResult->fetch_assoc()['total'];
    $countStmt->close();
    
} elseif ($reportType === 'posts') {
    // Posts report
    if ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
        $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM posts $timeCondition GROUP BY DATE(created_at) ORDER BY date";
        $stmt = $conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param("ss", $params[0], $params[1]);
        }
    } else {
        $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM posts $timeCondition GROUP BY DATE(created_at) ORDER BY date";
        $stmt = $conn->prepare($query);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $reportData[] = $row;
        $chartLabels[] = $row['date'];
        $chartValues[] = $row['count'];
    }
    $stmt->close();
    
    // Get total count for the period
    if ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
        $countQuery = "SELECT COUNT(*) as total FROM posts $timeCondition";
        $countStmt = $conn->prepare($countQuery);
        $countStmt->bind_param("ss", $params[0], $params[1]);
    } else {
        $countQuery = "SELECT COUNT(*) as total FROM posts $timeCondition";
        $countStmt = $conn->prepare($countQuery);
    }
    
    $countStmt->execute();
    $totalResult = $countStmt->get_result();
    $totalCount = $totalResult->fetch_assoc()['total'];
    $countStmt->close();
    
} elseif ($reportType === 'comments') {
    // Check if comments table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'comments'");
    if ($checkTable->num_rows > 0) {
        if ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
            $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM comments $timeCondition GROUP BY DATE(created_at) ORDER BY date";
            $stmt = $conn->prepare($query);
            if (!empty($params)) {
                $stmt->bind_param("ss", $params[0], $params[1]);
            }
        } else {
            $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM comments $timeCondition GROUP BY DATE(created_at) ORDER BY date";
            $stmt = $conn->prepare($query);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $reportData[] = $row;
            $chartLabels[] = $row['date'];
            $chartValues[] = $row['count'];
        }
        $stmt->close();
        
        // Get total count for the period
        if ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
            $countQuery = "SELECT COUNT(*) as total FROM comments $timeCondition";
            $countStmt = $conn->prepare($countQuery);
            $countStmt->bind_param("ss", $params[0], $params[1]);
        } else {
            $countQuery = "SELECT COUNT(*) as total FROM comments $timeCondition";
            $countStmt = $conn->prepare($countQuery);
        }
        
        $countStmt->execute();
        $totalResult = $countStmt->get_result();
        $totalCount = $totalResult->fetch_assoc()['total'];
        $countStmt->close();
    } else {
        $totalCount = 0;
    }
} elseif ($reportType === 'likes') {
    // Check if likes table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'likes'");
    if ($checkTable->num_rows > 0) {
        if ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
            $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM likes $timeCondition GROUP BY DATE(created_at) ORDER BY date";
            $stmt = $conn->prepare($query);
            if (!empty($params)) {
                $stmt->bind_param("ss", $params[0], $params[1]);
            }
        } else {
            $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM likes $timeCondition GROUP BY DATE(created_at) ORDER BY date";
            $stmt = $conn->prepare($query);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $reportData[] = $row;
            $chartLabels[] = $row['date'];
            $chartValues[] = $row['count'];
        }
        $stmt->close();
        
        // Get total count for the period
        if ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
            $countQuery = "SELECT COUNT(*) as total FROM likes $timeCondition";
            $countStmt = $conn->prepare($countQuery);
            $countStmt->bind_param("ss", $params[0], $params[1]);
        } else {
            $countQuery = "SELECT COUNT(*) as total FROM likes $timeCondition";
            $countStmt = $conn->prepare($countQuery);
        }
        
        $countStmt->execute();
        $totalResult = $countStmt->get_result();
        $totalCount = $totalResult->fetch_assoc()['total'];
        $countStmt->close();
    } else {
        $totalCount = 0;
    }
} elseif ($reportType === 'active_users') {
    // Active users based on post activity
    if ($timeFrame === 'custom' && !empty($startDate) && !empty($endDate)) {
        $query = "SELECT u.username, COUNT(p.post_id) as post_count 
                  FROM users u 
                  LEFT JOIN posts p ON u.user_id = p.user_id 
                  AND DATE(p.created_at) BETWEEN ? AND ? 
                  GROUP BY u.user_id
                  ORDER BY post_count DESC
                  LIMIT 10";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $params[0], $params[1]);
    } else {
        $timeConditionJoin = '';
        if ($timeFrame === 'today') {
            $timeConditionJoin = "AND DATE(p.created_at) = CURDATE()";
        } elseif ($timeFrame === 'yesterday') {
            $timeConditionJoin = "AND DATE(p.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        } elseif ($timeFrame === 'week') {
            $timeConditionJoin = "AND p.created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
        } elseif ($timeFrame === 'month') {
            $timeConditionJoin = "AND p.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        }
        
        $query = "SELECT u.username, COUNT(p.post_id) as post_count 
                  FROM users u 
                  LEFT JOIN posts p ON u.user_id = p.user_id $timeConditionJoin
                  GROUP BY u.user_id
                  ORDER BY post_count DESC
                  LIMIT 10";
        $stmt = $conn->prepare($query);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $reportData[] = $row;
        $chartLabels[] = $row['username'];
        $chartValues[] = $row['post_count'];
    }
    $stmt->close();
    
    // No total count for active users report
    $totalCount = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usage Reports - Sport Page</title>
    <link rel="stylesheet" href="../../assets/css/globals.css" />
    <link rel="stylesheet" href="../../assets/css/homepage/brand.css" />
    <link rel="stylesheet" href="../../assets/css/homepage/layout.css" />
    <link rel="stylesheet" href="../../assets/css/nav/sidebar-menu.css" />
    <link rel="stylesheet" href="../../assets/css/homepage/header.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin/admin.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header__content">
            <h4 class="m-0">Usage Reports</h4>
        </div>
    </header>

    <div class="layout">
        <?php include_once('../../assets/components/admin/leftSideBar.php'); ?>
        
        <!-- Main content -->
        <div class="layout__main">
            <h1 class="admin-panel__header">Usage Reports</h1>
            
            <!-- Filters Form -->
            <div class="admin-card mb-4">
                <form action="reports.php" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="report_type" class="form-label">Report Type</label>
                        <select id="report_type" name="report_type" class="form-select">
                            <option value="signups" <?php echo $reportType === 'signups' ? 'selected' : ''; ?>>User Sign-ups</option>
                            <option value="posts" <?php echo $reportType === 'posts' ? 'selected' : ''; ?>>Posts</option>
                            <option value="comments" <?php echo $reportType === 'comments' ? 'selected' : ''; ?>>Comments</option>
                            <option value="likes" <?php echo $reportType === 'likes' ? 'selected' : ''; ?>>Likes</option>
                            <option value="active_users" <?php echo $reportType === 'active_users' ? 'selected' : ''; ?>>Most Active Users</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="timeframe" class="form-label">Time Period</label>
                        <select id="timeframe" name="timeframe" class="form-select" onchange="toggleCustomDateFields()">
                            <option value="all" <?php echo $timeFrame === 'all' ? 'selected' : ''; ?>>All Time</option>
                            <option value="today" <?php echo $timeFrame === 'today' ? 'selected' : ''; ?>>Today</option>
                            <option value="yesterday" <?php echo $timeFrame === 'yesterday' ? 'selected' : ''; ?>>Yesterday</option>
                            <option value="week" <?php echo $timeFrame === 'week' ? 'selected' : ''; ?>>Last 7 Days</option>
                            <option value="month" <?php echo $timeFrame === 'month' ? 'selected' : ''; ?>>Last 30 Days</option>
                            <option value="custom" <?php echo $timeFrame === 'custom' ? 'selected' : ''; ?>>Custom Range</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 custom-date-fields" <?php echo $timeFrame !== 'custom' ? 'style="display:none;"' : ''; ?>>
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo $startDate; ?>">
                    </div>
                    
                    <div class="col-md-3 custom-date-fields" <?php echo $timeFrame !== 'custom' ? 'style="display:none;"' : ''; ?>>
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $endDate; ?>">
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </div>
                </form>
            </div>
            
            <!-- Report Results -->
            <div class="admin-card">
                <h4 class="mb-4">
                    <?php
                    $reportTitle = '';
                    if ($reportType === 'signups') $reportTitle = 'User Sign-ups';
                    elseif ($reportType === 'posts') $reportTitle = 'Posts';
                    elseif ($reportType === 'comments') $reportTitle = 'Comments';
                    elseif ($reportType === 'likes') $reportTitle = 'Likes';
                    elseif ($reportType === 'active_users') $reportTitle = 'Most Active Users';
                    
                    $timeFrameText = '';
                    if ($timeFrame === 'all') $timeFrameText = 'All Time';
                    elseif ($timeFrame === 'today') $timeFrameText = 'Today';
                    elseif ($timeFrame === 'yesterday') $timeFrameText = 'Yesterday';
                    elseif ($timeFrame === 'week') $timeFrameText = 'Last 7 Days';
                    elseif ($timeFrame === 'month') $timeFrameText = 'Last 30 Days';
                    elseif ($timeFrame === 'custom') $timeFrameText = "From $startDate to $endDate";
                    
                    echo "$reportTitle Report - $timeFrameText";
                    ?>
                </h4>
                
                <?php if ($totalCount !== null): ?>
                <div class="alert alert-info mb-4">
                    Total <?php echo strtolower($reportTitle); ?> for this period: <strong><?php echo $totalCount; ?></strong>
                </div>
                <?php endif; ?>
                
                <?php if (empty($reportData)): ?>
                <div class="alert alert-warning">
                    No data available for the selected criteria.
                </div>
                <?php else: ?>
                
                <!-- Chart Display -->
                <div style="height: 400px;" class="mb-5">
                    <canvas id="reportChart"></canvas>
                </div>
                
                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <?php if ($reportType === 'active_users'): ?>
                                <th>Username</th>
                                <th>Post Count</th>
                                <?php else: ?>
                                <th>Date</th>
                                <th>Count</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reportData as $row): ?>
                            <tr>
                                <?php if ($reportType === 'active_users'): ?>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo $row['post_count']; ?></td>
                                <?php else: ?>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['count']; ?></td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleCustomDateFields() {
            const timeframe = document.getElementById('timeframe').value;
            const customDateFields = document.querySelectorAll('.custom-date-fields');
            
            customDateFields.forEach(field => {
                field.style.display = (timeframe === 'custom') ? 'block' : 'none';
            });
        }
        
        // Create chart
        window.onload = function() {
            <?php if (!empty($chartLabels) && !empty($chartValues)): ?>
            const ctx = document.getElementById('reportChart').getContext('2d');
            
            const chartType = '<?php echo $reportType === 'active_users' ? 'bar' : 'line'; ?>';
            const chartTitle = '<?php echo $reportTitle; ?>';
            
            new Chart(ctx, {
                type: chartType,
                data: {
                    labels: <?php echo json_encode($chartLabels); ?>,
                    datasets: [{
                        label: chartTitle,
                        data: <?php echo json_encode($chartValues); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            <?php endif; ?>
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
