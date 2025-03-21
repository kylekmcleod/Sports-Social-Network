<?php
$file = $_GET['file'];

// Correcting the path to the uploads folder by adding the '/' after __DIR__
$uploadsDir = __DIR__ . '/../../uploads/';

$filePath = $uploadsDir . basename($file);

if (file_exists($filePath)) {
    header('Content-Type: image/jpeg');
    readfile($filePath);
    exit;
} else {
    http_response_code(404);
    echo "File not found.";
}
?>
