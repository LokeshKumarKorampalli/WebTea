<?php
include '../../db_connection.php'; 
session_start(); 

$successMessage = ''; // Variable to store success message

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
    if (!empty($_POST['ugid'])) {
        $ugid = strtoupper($_POST['ugid']); // Convert to uppercase
        // Check if UGID exists in all_students table
        $check_ugid_sql = "SELECT COUNT(*) AS count FROM all_students WHERE UGID = '$ugid'";
        $check_result = $conn->query($check_ugid_sql);
        $ugid_count = $check_result->fetch_assoc()['count'];
        if ($ugid_count > 0) {
            // UGID exists in all_students table, proceed to check if it exists in the latest permitted_students table
            $check_ugid_permitted_sql = "SELECT COUNT(*) AS count FROM $latest_table WHERE UGID = '$ugid'";
            $check_permitted_result = $conn->query($check_ugid_permitted_sql);
            $ugid_permitted_count = $check_permitted_result->fetch_assoc()['count'];
            if ($ugid_permitted_count > 0) {
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
            // UGID does not exist in all_students table
            $successMessage = "UGID '$ugid' does not exist in the All Students table";
        }
    }
} else {
    echo "No permitted_students table found.";
}
$conn->close();
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

form {
  text-align: center;
}

label {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

input[type="text"] {
  padding: 0.5rem;
  font-size: 1.2rem;
  border-radius: 5px;
  border: 1px solid #ccc;
  margin-bottom: 1rem;
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