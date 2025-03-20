<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (basename($_SERVER['PHP_SELF']) === 'config.php') {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Database Connection Test</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            .info { color: blue; }
            .container { border: 1px solid #ccc; padding: 15px; margin: 10px 0; border-radius: 5px; }
        </style>
    </head>
    <body>
        <h1>Database Connection Diagnostic</h1>
        <div class='container'>";
    
    echo "<p class='info'>Testing database connection...</p>";
}

/*
    This is the connection file for the database.
    Any new content that needs to connect to the database should include this file.
*/

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'sports_db';

function logMessage($message, $type = 'info') {
    if (basename($_SERVER['PHP_SELF']) === 'config.php') {
        echo "<p class='$type'>$message</p>";
    }
}

function getDBConnection() {
    global $host, $username, $password, $dbname;
    
    try {
        logMessage("Attempting to connect to MySQL ($host)...");
        $conn = new mysqli($host, $username, $password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            logMessage("Connection failed: " . $conn->connect_error, "error");
            return false;
        }
        
        logMessage("Connection successful!", "success");
        return $conn;
    } catch (Exception $e) {
        logMessage("Connection exception: " . $e->getMessage(), "error");
        return false;
    }
}

try {
    logMessage("Attempting global connection...");
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        logMessage("Global connection failed: " . $conn->connect_error, "error");
        logMessage("Error number: " . $conn->connect_errno, "error");
        
        $socket = @fsockopen($host, 3306, $errno, $errstr, 5);
        if (!$socket) {
            logMessage("MySQL server appears to be offline or not accepting connections on port 3306.", "error");
        } else {
            fclose($socket);
            logMessage("MySQL server is running but connection failed. Check credentials or database name.", "error");
        }
        
        if ($dbname !== 'sports_db') {
            logMessage("Warning: You're using '$dbname' instead of the expected 'sports_db'", "error");
        }
    } else {
        logMessage("Global connection established successfully!", "success");
        
        $testQuery = $conn->query("SHOW TABLES");
        if ($testQuery) {
            logMessage("Database query test successful!", "success");
            logMessage("Tables in database:", "info");
            echo "<ul>";
            while ($table = $testQuery->fetch_array()) {
                echo "<li>" . $table[0] . "</li>";
            }
            echo "</ul>";
        } else {
            logMessage("Database query failed: " . $conn->error, "error");
        }
    }
} catch (Exception $e) {
    logMessage("Exception occurred: " . $e->getMessage(), "error");
}

if (basename($_SERVER['PHP_SELF']) === 'config.php') {
    echo "</div>
    <div class='container'>
        <h3>Database Connection Info:</h3>
        <p>Host: $host</p>
        <p>Username: $username</p>
        <p>Database: $dbname</p>
        <p>PHP Version: " . phpversion() . "</p>
        <p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>
    </div>
    </body>
    </html>";
}
?>
