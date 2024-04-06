<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "12344321";
$dbname = "webtea";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
