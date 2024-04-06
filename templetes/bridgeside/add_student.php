<?php
// Check if the 'Add Students' button is pressed
if(isset($_POST['add_student'])) {
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

    // Get the current date
    $current_date = date("Y_m_d");

    // SQL query to create the table
    $sql = "CREATE TABLE IF NOT EXISTS permitted_students_$current_date (
        ugid VARCHAR(6),
        entry_time DATETIME(6),
        exit_time DATETIME(6)
    )";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully!";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

// Function to generate a button for entering ugid
function generateUgidButton() {
    echo '<form method="post" action="enter_ugid.php">';
    echo '<input type="text" name="ugid" placeholder="Enter ugid">';
    echo '<button type="submit">Enter ugid</button>';
    echo '</form>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Permission Grant</title>
</head>
<body>
    <!-- Call the function to generate the button for entering ugid -->
    <?php generateUgidButton(); ?>
</body>
</html>
