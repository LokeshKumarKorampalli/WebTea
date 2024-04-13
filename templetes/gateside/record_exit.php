<?php
include '../../db_connection.php'; 

session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../../index.html");
    exit;
}

// Check if the user's role is "gate"
if ($_SESSION['role'] !== "gate") {
    header("Location: ../../index.html");
    exit;
}

// Check if the 'student_id' is submitted
if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Validate and sanitize the input (you can add more validation as needed)
    $student_id = filter_var($student_id, FILTER_SANITIZE_STRING);

    // Construct the SQL query to select the most recent table
    $latest_table_sql = "SELECT TABLE_NAME 
                         FROM information_schema.tables 
                         WHERE TABLE_SCHEMA = 'webtea' 
                         AND TABLE_NAME LIKE 'permitted_students_%' 
                         ORDER BY TABLE_NAME DESC LIMIT 1";

    // Execute the SQL query to get the most recent table
    $latest_table_result = $conn->query($latest_table_sql);

    if ($latest_table_result->num_rows > 0) {
        $row = $latest_table_result->fetch_assoc();
        $latest_table_name = $row['TABLE_NAME'];

        // Check if the student ID exists in the table
        $check_student_sql = "SELECT * FROM $latest_table_name WHERE ugid = '$student_id'";
        $check_student_result = $conn->query($check_student_sql);

        if ($check_student_result->num_rows > 0) {
            // Student exists in the table
            $student_data = $check_student_result->fetch_assoc();
            
            if ($student_data['exit_time'] === NULL) {
                // Update the 'exit_time' column in the most recent table
                $sql = "UPDATE $latest_table_name SET exit_time = CURRENT_TIMESTAMP(6) WHERE ugid = '$student_id'";

                if ($conn->query($sql) === TRUE) {
                    echo "Exit recorded successfully!";
                } else {
                    echo "Error updating exit: " . $conn->error;
                }
            } else {
                // Exit time already recorded
                echo "<script>alert('Exit time already recorded for this student.')</script>";
            }
        } else {
            // Student not found in the permitted students table
            echo "<script>alert('Student does not exist in the permitted students table.')</script>";
        }
    } else {
        echo "No permitted_students table found.";
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Exit</title>
</head>
<body>
    <h1>Record Exit</h1>
    
    <form method="post" action="">
        <label for="student_id">Enter Student ID:</label>
        <input type="text" id="student_id" name="student_id" required>
        <button type="submit">Record Exit</button>
    </form>
    <a href="gate.php"><button>Go Back to Home Page</button></a>

</body>
</html>
