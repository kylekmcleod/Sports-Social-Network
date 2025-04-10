<?php
require_once(__DIR__ . '/../../config/config.php');

function getTrendingTags() {
    global $conn;
    
    $query = "
        SELECT tag, COUNT(*) as count
        FROM (
            SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(tags, ',', n.n), ',', -1)) as tag
            FROM posts
            CROSS JOIN (
                SELECT a.N + b.N * 10 + 1 as n
                FROM (SELECT 0 as N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
                     (SELECT 0 as N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b
                ORDER BY n
            ) n
            WHERE n.n <= 1 + (LENGTH(tags) - LENGTH(REPLACE(tags, ',', '')))
            AND tags IS NOT NULL
            AND tags != ''
        ) as split_tags
        WHERE tag != ''
        GROUP BY tag
        ORDER BY count DESC, tag
        LIMIT 3";

    $result = $conn->query($query);
    $trending = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $trending[] = [
                'tag' => $row['tag'],
                'count' => $row['count']
            ];
        }
    }
    
    if (isset($_GET['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode($trending);
        exit;
    }
    
    return $trending;
}
