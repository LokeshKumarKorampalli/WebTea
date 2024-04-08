<?php
session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../../index.html");
    exit;
}
if ($_SESSION['role'] !== "admin") {
    header("Location: ../../index.html");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

function logout() {
    session_unset(); 
    session_destroy(); 
    header("Location: ../../index.html"); 
    exit;
}


if (isset($_POST['logout'])) {
    logout(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, <?php echo $username; ?>!</title>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <p>Your role is: <?php echo $role; ?></p>
    <button onclick="window.location.href='registration.php'">Register Members</button>
    <button onclick="window.location.href='view_members.php'">View Members</button>
    <form method="post" action="">
        <button type="submit" name="logout">Sign Out</button>
    </form>
</body>
</html>
