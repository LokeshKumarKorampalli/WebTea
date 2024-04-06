<?php
include 'db_connection.php'; // Include the database connection file

session_start(); // Start the session

// Retrieve username and password from form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check if the provided credentials are valid
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // If a matching row is found
    if ($result->num_rows > 0) {
        // Fetching user data
        $row = $result->fetch_assoc();
        $role = $row['role'];

        // Store user data in session variables
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on user role
        switch ($role) {
            case 'admin':
                header("Location: templetes/adminside/admin.php");
                break;
            case 'bridge':
                header("Location: templetes/bridgeside/bridge.php");
                break;
            case 'gate':
                header("Location: templetes/gateside/gate.php");
                break;
            default:
                header("Location: templetes/default.php");
        }
        exit;
    } else {
        echo "Invalid username or password.";
    }
}

$conn->close();
?>
