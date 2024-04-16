<?php
include '../../db_connection.php'; 
session_start(); 

$successMessage = '';
if (!isset($_ENV['DB_NAME']) || empty($_ENV['DB_NAME'])) {
    die("Database name environment variable is not set.");
}

$db_name = $_ENV['DB_NAME'];
$latest_table_sql = "SELECT TABLE_NAME 
                     FROM information_schema.tables 
                     WHERE TABLE_SCHEMA = '$db_name' 
                     AND TABLE_NAME LIKE 'permitted_students_%' 
                     ORDER BY TABLE_NAME DESC 
                     LIMIT 1";
$result = $conn->query($latest_table_sql);
if ($result->num_rows > 0) {
    $latest_table = $result->fetch_assoc()['TABLE_NAME'];
    if (!empty($_POST['ugid'])) {
        $ugid = strtoupper($_POST['ugid']); 
        $check_ugid_sql = "SELECT COUNT(*) AS count FROM all_students WHERE UGID = '$ugid'";
        $check_result = $conn->query($check_ugid_sql);
        $ugid_count = $check_result->fetch_assoc()['count'];
        if ($ugid_count > 0) {
            $check_ugid_permitted_sql = "SELECT COUNT(*) AS count FROM $latest_table WHERE UGID = '$ugid'";
            $check_permitted_result = $conn->query($check_ugid_permitted_sql);
            $ugid_permitted_count = $check_permitted_result->fetch_assoc()['count'];
            if ($ugid_permitted_count > 0) {
                $successMessage = "UGID '$ugid' already exists in $latest_table";
            } else {
                $insert_sql = "INSERT INTO $latest_table (UGID) VALUES ('$ugid')";
                if ($conn->query($insert_sql) === TRUE) {
                    $successMessage = "UGID '$ugid' added successfully to $latest_table";
                } else {
                    echo "Error adding UGID: " . $conn->error;
                }
            }
        } else {
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
    background-image: url('../../AA.png');
    background-size: 105% 130%;
    background-position: center;
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