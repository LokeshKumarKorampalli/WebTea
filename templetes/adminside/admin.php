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

// Display the username and role
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Function to log out the user
function logout() {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: ../../index.html"); // Redirect to the login page
    exit;
}

// Check if the logout button is clicked
if (isset($_POST['logout'])) {
    logout(); // Call the logout function
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
