<?php
include '../../db_connection.php'; // Include the database connection file

// Start the session
session_start();

// Check if user is logged in as admin, if not redirect to login page
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.html");
    exit;
}

// Define variables and initialize with empty values
$username = $password = $confirm_password = $role = "";
$username_err = $password_err = $confirm_password_err = $role_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT username FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate role
    if (empty(trim($_POST["role"]))) {
        $role_err = "Please enter a role.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_password, $param_role);

            // Set parameters
            $param_username = $username;
            $param_password = $password; // Store password as plain text
            $param_role = $role;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to admin.php page
                header("location: admin.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
        <h2>User Registration</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                <label>Role</label>
                <input type="text" name="role" class="form-control" value="<?php echo $role; ?>">
                <span class="help-block"><?php echo $role_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
        <button class="btn btn-secondary" onclick="window.location.href='admin.php'">Back to Admin</button> <!-- Button to go back to admin.php -->
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

.container {
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
}

h2 {
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
  background-color: #fff;
  color: #333;
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

.form-group {
  margin-bottom: 1.5rem;
}

.form-control {
  width: 100%;
  padding: 0.5rem;
  font-size: 1.2rem;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.has-error .form-control {
  border-color: #ff0000;
}

.help-block {
  color: #ff0000;
  font-size: 1rem;
}
</style>