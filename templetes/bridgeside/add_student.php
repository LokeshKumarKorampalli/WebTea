<?php
include '../../db_connection.php'; 
session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../../index.html");
    exit;
}
if ($_SESSION['role'] !== "bridge") {
    header("Location: ../../index.html");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

    $current_date = date("Y_m_d");

   
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Permission Grant</title>
    <script>
        function confirmCreateNewOutingList() {
            if (confirm("Creating a new weekend outing list will prevent adding students to the previous week's list. Are you sure you want to proceed?")) {
                window.location.href = "create_outing_list.php";
            }
        }
    </script>
</head>
<body>

    <form action="add_to_this_week.php">
        <button type="submit">Add Students to This Week's List</button>
    </form>

    <form action="create_outing_list.php" method="post"> 
        <button type="submit">Create new weekend list</button>
    </form>

    <a href="bridge.php"><button>Go Back to Home Page</button></a>
</body>
</html>


