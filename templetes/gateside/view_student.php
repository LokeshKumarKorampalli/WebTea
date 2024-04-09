<?php
include '../../db_connection.php'; 
session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "gate") {
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
        <ul>
            <?php foreach ($ugids as $student) : ?>
                <li>
                    <strong>UGID:</strong> <?php echo $student['UGID']; ?><br>
                    <strong>Name:</strong> <?php echo $student['Name']; ?><br>
                    <strong>Course:</strong> <?php echo $student['Course']; ?><br>
                    <strong>Email:</strong> <?php echo $student['email']; ?><br>
                    <strong>Entry Time:</strong> <?php echo $student['entry_time']; ?><br>
                    <strong>Exit Time:</strong> <?php echo $student['exit_time']; ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <a href="gate.php"><button>Go Back to Home Page</button></a>
</body>
</html>
