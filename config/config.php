<?php
/*
    This is the connetion file for the database.

    Any new content that needs to connect to the database should include this file.

    Example Usage:
    include_once('../../config/config.php');
*/
?>
<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'sports_db';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getDBConnection() {
    global $host, $username, $password, $dbname;
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
