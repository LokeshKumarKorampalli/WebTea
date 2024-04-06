<?php
session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../../index.html");
    exit;
}

// Check if the user's role is "bridge"
if ($_SESSION['role'] !== "bridge") {
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
    <button onclick="addStudent()">Add Students</button>
    <button onclick="window.location.href='view_student.php'">View permitted students</button>
    <form method="post" action="">
        <button type="submit" name="logout">Sign Out</button>
    </form>

    <!-- JavaScript to submit the form when "Add Students" button is clicked -->
    <script>
        function addStudent() {
            document.getElementById("addStudentForm").submit();
        }
    </script>

    <!-- Form to submit the 'Add Students' button -->
    <form id="addStudentForm" method="post" action="add_student.php">
        <input type="hidden" name="add_student">
    </form>
</body>
</html>
