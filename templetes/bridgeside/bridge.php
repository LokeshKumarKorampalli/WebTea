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

