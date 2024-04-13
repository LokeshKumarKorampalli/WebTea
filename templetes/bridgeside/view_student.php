<?php
include '../../db_connection.php'; 
session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "bridge") {
    header("Location: ../../index.html");
    exit;
}

$tables_sql = "SELECT TABLE_NAME 
               FROM information_schema.tables 
               WHERE TABLE_SCHEMA = 'webtea' 
               AND TABLE_NAME LIKE 'permitted_students_%' 
               ORDER BY TABLE_NAME DESC";
$result = $conn->query($tables_sql);
$tables = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tables[] = $row['TABLE_NAME'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_table'])) {
    $selected_table = $_POST['selected_table'];
    
    // Extract the date suffix from the selected table name
    $date_suffix = substr($selected_table, strlen('permitted_students_'));

    // Construct the SQL query to join 'permitted_students' with 'all_students'
    $ugids_sql = "SELECT a.UGID, a.Name, a.Course, a.email, p.entry_time, p.exit_time
                  FROM all_students a 
                  JOIN $selected_table p ON a.UGID = p.UGID";

    $ugids_result = $conn->query($ugids_sql);
    $ugids = [];
    
    if ($ugids_result->num_rows > 0) {
        while ($row = $ugids_result->fetch_assoc()) {
            $ugids[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
</head>
<body>
    <h2>Select a table to view students:</h2>
    <form action="view_student.php" method="post">
        <select name="selected_table">
            <?php foreach ($tables as $table) : ?>
                <option value="<?php echo $table; ?>"><?php echo $table; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">View Students</button>
    </form>
    <?php if (isset($ugids)) : ?>
        <h2>Students Information:</h2>
        <table border="1">
            <tr>
                <th>UGID</th>
                <th>Name</th>
                <th>Course</th>
                <th>Email</th>
                <th>Exit Time</th>
                <th>Entry Time</th>
            </tr>
            <?php foreach ($ugids as $student) : ?>
                <tr>
                    <td><?php echo $student['UGID']; ?></td>
                    <td><?php echo $student['Name']; ?></td>
                    <td><?php echo $student['Course']; ?></td>
                    <td><?php echo $student['email']; ?></td>
                    <td><?php echo $student['exit_time']; ?></td>
                    <td><?php echo $student['entry_time']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
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

h2 {
  font-size: 2rem;
  margin-bottom: 2rem;
}

select, button {
  font-size: 1.5rem;
  padding: 0.5rem 1rem;
  margin-bottom: 1rem;
  border: none;
  border-radius: 5px;
  background-color: rgba(255, 255, 255, 0.8);
  color: #333;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

select:hover, button:hover {
  background-color: #f2f2f2;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  margin-bottom: 2rem;
}

strong {
  font-weight: bold;
}
</style>