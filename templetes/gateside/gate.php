<?php
session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../../index.html");
    exit;
}

if ($_SESSION['role'] !== "gate") {
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
    <title>Welcome, <?php echo $role; ?>!</title>

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

        .container {
            text-align: center;
            max-width: 600px;
          
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 3rem;
        }

        p {
            font-size: 1.5rem;
            
        }

        button {
            font-size: 1.5rem;
            padding: 1rem 2rem;
            margin-right: rem;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #339;
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
    <h1>Welcome, <?php echo $role; ?>!</h1>
    <button onclick="window.location.href='view_student.php'">View permitted students</button>
    <button onclick="window.location.href='record_exit.php'">Record Exit</button>
    <button onclick="window.location.href='record_entry.php'">Record Entry</button>

    
    <form method="post" action="">
        <button type="submit" name="logout">Sign Out</button>
    </form>
</body>
</html>
