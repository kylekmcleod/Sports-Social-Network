<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'sports_db';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!";
}

// Close the connection
$conn->close();
?>
