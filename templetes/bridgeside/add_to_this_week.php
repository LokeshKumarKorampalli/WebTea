<?php
include '../../db_connection.php'; 
session_start(); 

$successMessage = ''; // Variable to store success message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the latest table name from the database
    $latest_table_sql = "SELECT TABLE_NAME 
                         FROM information_schema.tables 
                         WHERE TABLE_SCHEMA = 'webtea' 
                         AND TABLE_NAME LIKE 'permitted_students_%' 
                         ORDER BY TABLE_NAME DESC 
                         LIMIT 1";
    $result = $conn->query($latest_table_sql);
    if ($result->num_rows > 0) {
        $latest_table = $result->fetch_assoc()['TABLE_NAME'];
        // Check if UGID already exists in the latest table
        $ugid = strtoupper($_POST['ugid']); // Convert to uppercase
        $check_ugid_sql = "SELECT COUNT(*) AS count FROM $latest_table WHERE UGID = '$ugid'";
        $check_result = $conn->query($check_ugid_sql);
        $ugid_count = $check_result->fetch_assoc()['count'];
        if ($ugid_count > 0) {
            $successMessage = "UGID '$ugid' already exists in $latest_table";
        } else {
            // Insert the UGID into the latest table
            $insert_sql = "INSERT INTO $latest_table (UGID) VALUES ('$ugid')";
            if ($conn->query($insert_sql) === TRUE) {
                $successMessage = "UGID '$ugid' added successfully to $latest_table";
            } else {
                echo "Error adding UGID: " . $conn->error;
            }
        }
    } else {
        echo "No permitted_students table found.";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Student to This Week's List</title>
    <script>
        function validateForm() {
            var ugid = document.getElementById("ugid").value;
            // Check if the UGID has exactly 11 characters
            if (ugid.length !== 11) {
                alert("UGID must have exactly 11 characters.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <form action="add_to_this_week.php" method="post" onsubmit="return validateForm()">
        <label for="ugid">UGID:</label>
        <input type="text" id="ugid" name="ugid" required>
        <button type="submit">Add Student</button>
    </form>
    <?php if (!empty($successMessage)) : ?>
        <div><?php echo $successMessage; ?></div>
    <?php endif; ?>
    <a href="bridge.php"><button>Go Back to Home Page</button></a>
</body>
</html>
