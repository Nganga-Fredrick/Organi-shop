<?php
// Database Configuration
$databaseHost = 'localhost'; // Replace with your database host
$databaseName = 'Allpoint'; // Replace with your database name
$databaseUsername = 'root'; // Replace with your database username
$databasePassword = ''; // Replace with your database password

// Create a database connection
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else echo "connected";
?>
