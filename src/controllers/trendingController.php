<?php
require_once(__DIR__ . '/../../config/config.php');

function getTrendingTags() {
    global $conn;
    
    try {
        // SQL query to split tags and count their occurrences
        $query = "
            SELECT TRIM(tag) as tag, COUNT(*) as count 
            FROM (
                SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(p.tags, ',', numbers.n), ',', -1) as tag
                FROM posts p
                CROSS JOIN (
                    SELECT a.N + b.N * 10 + 1 n
                    FROM 
                        (SELECT 0 as N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a,
                        (SELECT 0 as N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
                    ORDER BY n
                ) numbers
                WHERE numbers.n <= 1 + (LENGTH(p.tags) - LENGTH(REPLACE(p.tags, ',', '')))
            ) tags
            WHERE tag != ''
            GROUP BY tag
            ORDER BY count DESC, tag
            LIMIT 3";

        $result = $conn->query($query);
        
        if (!$result) {
            throw new Exception($conn->error);
        }

        $trending = [];
        while ($row = $result->fetch_assoc()) {
            $trending[] = [
                'tag' => $row['tag'],
                'count' => $row['count']
            ];
        }

        // If this is an AJAX request, return JSON
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($trending);
            exit;
        }

        return $trending;
    } catch (Exception $e) {
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
        return [];
    }
}

// Handle direct requests to this file
if ($_SERVER['SCRIPT_NAME'] === __FILE__) {
    getTrendingTags();
}
