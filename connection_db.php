<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = ""; // Use your actual database password here
$dbname = "ODBC_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
