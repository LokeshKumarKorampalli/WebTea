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

<style>
  body {
    background-image: url('https://scontent-maa2-2.xx.fbcdn.net/v/t39.30808-6/275016854_2024762714364261_334990563190869262_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=5f2048&_nc_ohc=3gDRzU_bqFYAb7uypVn&_nc_ht=scontent-maa2-2.xx&oh=00_AfBwBhWlzRlaudg14xz0IwETJ3_3IWPVaTesWy4ENdR01A&oe=661AE7D3');
    background-size: cover;
    background-position: center top;
    font-family: 'Roboto', sans-serif;
    color: black;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh; 
}

.warning-message, .success-message {
  font-size: 1.5rem;
  margin-bottom: 2rem;
  background-color: rgba(255, 255, 255, 0.8);
  padding: 1rem 2rem;
  border-radius: 5px;
  text-align: center;
}

.success-message a {
  color: #333;
  text-decoration: underline;
}

button {
  font-size: 1.5rem;
  padding: 0.5rem 2rem;
  margin: 0.5rem;
  border: none;
  border-radius: 5px;
  background-color: rgba(255, 255, 255, 0.8);
  color: #333;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #f2f2f2;
}
</style>
