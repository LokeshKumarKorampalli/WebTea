<?php
include '../../db_connection.php'; 
session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "bridge") {
    header("Location: ../../index.html");
    exit;
}

// Retrieve tables starting with "permitted_students"
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_table'])) {
    $selected_table = $_POST['selected_table'];
    // Get list of UGIDs from the selected table
    $ugids_sql = "SELECT UGID FROM $selected_table";
    $ugids_result = $conn->query($ugids_sql);
    $ugids = [];
    if ($ugids_result->num_rows > 0) {
        while ($row = $ugids_result->fetch_assoc()) {
            $ugids[] = $row['UGID'];
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
    <form action="view_students.php" method="post">
        <select name="selected_table">
            <?php foreach ($tables as $table) : ?>
                <option value="<?php echo $table; ?>"><?php echo $table; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">View Students</button>
    </form>
    <?php if (isset($ugids)) : ?>
        <h2>UGIDs in <?php echo $selected_table; ?>:</h2>
        <ul>
            <?php foreach ($ugids as $ugid) : ?>
                <li><?php echo $ugid; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
