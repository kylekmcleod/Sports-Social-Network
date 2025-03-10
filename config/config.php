<?php
// Database connection settings
$host = 'localhost';        // Database host
$username = 'root';         // Database username
$password = '';             // Database password (empty for local development)
$dbname = 'sports_db';      // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get DB connection
function getDBConnection() {
    global $host, $username, $password, $dbname;
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
