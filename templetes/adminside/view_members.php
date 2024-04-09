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
        <th>Action</th> <!-- Add a new header for action -->
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
                        <button class='btn btn-delete' onclick='confirmDelete(\"" . $row["username"] . "\")'>
                            <span class='mdi mdi-delete mdi-24px'></span>
                            <span class='mdi mdi-delete-empty mdi-24px'></span>
                            <span>Delete</span>
                        </button>
                    </td>
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

<style>
  body {
    background: #DAE2F8;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #D6A4A4, #DAE2F8);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #D6A4A4, #DAE2F8); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

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
  max-width: 800px;
  margin: 0 auto;
}

h1 {
  font-size: 3rem;
  margin-bottom: 2rem;
}

table {
  border-collapse: collapse;
  border: 2px solid #fff;
  width: 80%;
  margin-bottom: 2rem;
}

th, td {
  border: 1px solid #fff;
  padding: 0.5rem;
  text-align: left;
}
table {
    border-collapse: collapse;
    border: none;
    background-color: #CCCCCC; /* Dark color code */
    color: black; /* Text color to contrast with the dark background */
    width: 80%;
    margin-bottom: 2rem;
}


th {
  background-color: rgba(255, 255, 255, 0.5);
}

.delete-icon {
  cursor: pointer;
}

button {
  font-size: 1.5rem;
  padding: 0.5rem 2rem;
  border: none;
  border-radius: 5px;
  background-color: rgba(255, 255, 255, 0.8);
  color: #333;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-bottom: 1rem;
}

button:hover {
  background-color: #f2f2f2;
}


</style>