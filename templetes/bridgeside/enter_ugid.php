<?php
// Check if the form is submitted
if(isset($_POST['ugid'])) {
    $ugid = $_POST['ugid'];

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

    // Prepare and bind the SQL statement with a placeholder for the table name
    $sql = "INSERT INTO permitted_students_{$current_date} (ugid) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ugid);

    // Execute the statement
    if ($stmt->execute()) {
        echo "UGID added successfully!";
    } else {
        echo "Error adding UGID: " . $conn->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enter UGID</title>
</head>
<body>
    <h1>Enter UGID</h1>
    <form method="post" action="">
        <input type="text" name="ugid" placeholder="Enter ugid">
        <button type="submit">Enter ugid</button>
    </form>
</body>
</html>


