<?php
include '../../db_connection.php'; 

session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../../index.html");
    exit;
}
if ($_SESSION['role'] !== "admin") {
    header("Location: ../../index.html");
    exit;
}

// Function to delete user record
function deleteUser($username) {
    global $conn;
    $sql = "DELETE FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            return true; // Record deleted successfully
        } else {
            return false; // Error deleting record
        }
    } else {
        return false; // Error in prepared statement
    }
}

// Check if delete button is clicked
if (isset($_POST['delete_user'])) {
    $username = $_POST['username'];
    if (deleteUser($username)) {
        echo "<script>alert('User record deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting user record.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Members</title>
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

        /* Additional styling for table and buttons */
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 0 auto; /* Center the table horizontally */
        }

        button {
            font-size: 16px;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #ccc; /* Light grey color */
            color: #333; /* Dark text color */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #bbb; /* Darker grey on hover */
        }

        .container {
            text-align: center;
            margin-top: 20px;
        }
        .adm {
            margin-top: 20px;
        }
        .act{
            width: 100px;
        }

    </style>
</head>
<body>
    <h1>View Members</h1>
    <table border='1'>
        <tr>
            <th>Username</th>
            <th>Password</th>
            <th>Role</th>
            <th class="act">Action</th> <!-- Add a new header for action -->
        </tr>
        <?php
        $sql = "SELECT username, password, role FROM users";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["username"] . "</td>
                        <td>" . $row["password"] . "</td>
                        <td>" . $row["role"] . "</td>
                        <td>
                            <button onclick='confirmDelete(\"" . $row["username"] . "\")'>
                                Delete
                            </button>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>0 results</td></tr>";
        }
        ?>
    </table>
    <div class="container">
        <button class="adm" onclick="window.location.href='admin.php'">Back to Admin</button>
    </div>

    <script>
        function confirmDelete(username) {
            if (confirm("Are you sure you want to delete user '" + username + "'?")) {
                document.getElementById('delete_user_form_' + username).submit();
            }
        }
    </script>
    
    <!-- Hidden forms for each user to perform delete action -->
    <?php
    $sql = "SELECT username FROM users";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<form id='delete_user_form_" . $row["username"] . "' method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
                    <input type='hidden' name='username' value='" . $row["username"] . "'>
                    <input type='hidden' name='delete_user'>
                  </form>";
        }
    }
    ?>
</body>
</html>