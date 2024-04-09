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



.container {
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
}

h1 {
  font-size: 3rem;
  margin-bottom: 0rem;
}

p {
  font-size: 1.5rem;
  margin-bottom: 2rem;
}

button {
  font-size: 1.5rem;
  padding: 0.5rem 2rem;
  margin-right: 0rem;
  border: none;
  border-radius: 5px;
  background-color: rgba(255, 255, 255, 0.8);
  color: #333;
  cursor: pointer;
  transition: background-color 0.3s ease;
}


.button-container {
  display: flex;
  flex-direction: column; 
  align-items: center; 
  margin-bottom: 2rem;
}

.my-button {
  background-color: #CCCCCC;
  color:#333;
  border-radius: 5px;
  padding: 0.5rem 2rem;
  font-size: 1.5rem;
  margin-right: 0rem; 
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-bottom: 1rem; 
}

.my-button:hover {
  background-color: #f2f2f2;
}

form {
  margin-top: 2rem;
}
</style>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <p>Your role is: <?php echo $role; ?></p>
    <button onclick="window.location.href='registration.php'">Register Members</button><br>
    <button onclick="window.location.href='view_members.php'">View Members</button>
    <form method="post" action="">
        <button type="submit" name="logout">Sign Out</button>
    </form>
</body>
</html>
