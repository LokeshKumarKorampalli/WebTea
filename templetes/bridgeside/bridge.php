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
    <button onclick="window.location.href='add_student.php'">Add Students</button>
    <button onclick="window.location.href='view_student.php'">View permitted students</button>
    <form method="post" action="">
        <button type="submit" name="logout">Sign Out</button>
    </form>

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

h1 {
  font-size: 3rem;
  margin-bottom: 2rem;
}

p {
  font-size: 1.5rem;
  margin-bottom: 2rem;
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

