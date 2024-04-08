<?php
include '../../db_connection.php'; 
session_start(); 

$showSuccessMessage = true; // Flag to determine if success message should be shown
$warningMessage = ''; // Variable to store the warning message

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "bridge") {
    header("Location: ../../index.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_date = date("Ymd"); // Format date without hyphens

    // Check if the table already exists
    $check_table_sql = "SHOW TABLES LIKE 'permitted_students_$current_date'";
    $result = $conn->query($check_table_sql);
    if ($result->num_rows > 0) {
        // Table already exists, set warning message
        $warningMessage = "Table already exists for the current date.";
        $showSuccessMessage = false; // Set flag to false
    } else {
        // Table does not exist, proceed to create it
        $sql = "CREATE TABLE `permitted_students_$current_date` (
                    UGID CHAR(15),
                    exit_time DATETIME(6),
                    entry_time DATETIME(6)
                )";

        if ($conn->query($sql) === TRUE) {
            // Only display success message if the table creation was successful
            echo "New outing list created successfully";
        } else {
            echo "Error creating table: " . $conn->error;
            $showSuccessMessage = false; // Set flag to false
        }
    }

    $conn->close();
}
?>
<html>
    <head>
        <title>New weekend list</title>
    </head>
    <body>
        <?php if ($warningMessage): ?>
            <div class="warning-message"><?php echo $warningMessage; ?></div>
        <?php endif; ?>

        <?php if ($showSuccessMessage): ?>
            <div class="success-message">New weekend table has been created. Please enter UGID's here
            <a href="add_to_this_week.php">click here</a></div>
        <?php endif; ?>
        <a href="bridge.php"><button>Go Back to Home Page</button></a>
    </body>
<html>
