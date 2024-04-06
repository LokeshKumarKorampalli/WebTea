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
        .delete-icon {
            cursor: pointer;
        }
        table {
            border-collapse: collapse;
            border: none;
            
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
                        <td><img src='delete_icon.png' alt='Delete' class='delete-icon' onclick='confirmDelete(\"" . $row["username"] . "\")'></td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>0 results</td></tr>";
        }
        ?>
    </table>
    <br>
    <button onclick="window.location.href='admin.php'">Back to Admin</button>

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
