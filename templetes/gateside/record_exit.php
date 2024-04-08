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

    // Check if 'student_id' exists in the 'permitted_students' list
    $check_student_sql = "SELECT * FROM permitted_students_20240408 WHERE ugid = '$student_id'";
    $student_result = $conn->query($check_student_sql);

    if ($student_result->num_rows == 0) {
        // Student not found in the list
        echo "<script>alert('Record not found for this student ID.')</script>";
    } else {
        // Check if 'entry_time' is already filled for the student
        $check_entry_sql = "SELECT entry_time FROM permitted_students_20240408 WHERE ugid = '$student_id' AND entry_time IS NOT NULL";
        $entry_result = $conn->query($check_entry_sql);

        if ($entry_result->num_rows > 0) {
            // Entry time already recorded
            echo "<script>alert('Entry has already been recorded for this student ID. We cannot accept the record.')</script>";
        } else {
            // Update the 'entry_time' column in the 'permitted_students' table
            $sql = "UPDATE permitted_students_20240408 SET entry_time = CURRENT_TIMESTAMP(6) WHERE ugid = '$student_id'";

            if ($conn->query($sql) === TRUE) {
                echo "Entry recorded successfully!";
            } else {
                echo "Error updating entry: " . $conn->error;
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Entry</title>
</head>
<body>
    <h1>Record Entry</h1>
    
    <form method="post" action="">
        <label for="student_id">Enter Student ID:</label>
        <input type="text" id="student_id" name="student_id" required>
        <button type="submit">Record Entry</button>
    </form>
</body>
</html>

