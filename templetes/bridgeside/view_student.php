<?php
// Connect to your MySQL database
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "webtea"; // Replace with your MySQL database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the permitted_students table
$sql = "SELECT * FROM permitted_students_2024_04_02";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "UGID: " . $row["ugid"]. "<br>";
        echo "Entry Time: " . $row["entry_time"]. "<br>";
        echo "Exit Time: " . $row["exit_time"]. "<br><br>";
    }
} else {
    echo "No permitted students found.";
}

// Close the database connection
$conn->close();
?>
