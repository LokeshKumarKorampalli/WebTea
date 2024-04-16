<?php
include '../../db_connection.php'; 

session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../../index.html");
    exit;
}

if ($_SESSION['role'] !== "gate") {
    header("Location: ../../index.html");
    exit;
}

if (!isset($_ENV['DB_NAME']) || empty($_ENV['DB_NAME'])) {
    die("Database name environment variable is not set.");
}

$db_name = $_ENV['DB_NAME'];

if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    $student_id = filter_var($student_id, FILTER_SANITIZE_STRING);

    $latest_table_sql = "SELECT TABLE_NAME 
                         FROM information_schema.tables 
                         WHERE TABLE_SCHEMA = '$db_name' 
                         AND TABLE_NAME LIKE 'permitted_students_%' 
                         ORDER BY TABLE_NAME DESC LIMIT 1";

    $latest_table_result = $conn->query($latest_table_sql);

    if ($latest_table_result->num_rows > 0) {
        $row = $latest_table_result->fetch_assoc();
        $latest_table_name = $row['TABLE_NAME'];

        $check_student_sql = "SELECT * FROM $latest_table_name WHERE ugid = '$student_id'";
        $check_student_result = $conn->query($check_student_sql);

        if ($check_student_result->num_rows > 0) {
            $sql = "UPDATE $latest_table_name SET entry_time = CURRENT_TIMESTAMP(6) WHERE ugid = '$student_id'";

            if ($conn->query($sql) === TRUE) {
                echo "Entry recorded successfully!";
            } else {
                echo "Error updating entry: " . $conn->error;
            }
        } else {
            $previous_tables_sql = "SELECT TABLE_NAME 
                                    FROM information_schema.tables 
                                    WHERE TABLE_SCHEMA = '$db_name' 
                                    AND TABLE_NAME LIKE 'permitted_students_%' 
                                    AND TABLE_NAME < '$latest_table_name' 
                                    ORDER BY TABLE_NAME DESC";

            $previous_tables_result = $conn->query($previous_tables_sql);

            while ($previous_table_row = $previous_tables_result->fetch_assoc()) {
                $previous_table_name = $previous_table_row['TABLE_NAME'];

                // Check if the student ID exists in any previous table
                $check_previous_student_sql = "SELECT * FROM $previous_table_name WHERE ugid = '$student_id'";
                $check_previous_student_result = $conn->query($check_previous_student_sql);

                if ($check_previous_student_result->num_rows > 0) {
                    // Student exists in a previous table, update entry time and break the loop
                    $sql = "UPDATE $previous_table_name SET entry_time = CURRENT_TIMESTAMP(6) WHERE ugid = '$student_id'";

                    if ($conn->query($sql) === TRUE) {
                        echo "Entry recorded successfully!";
                    } else {
                        echo "Error ";
                    }
                    break;
                }
            }
        }
    } else {
        echo "No permitted_students table found.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Entry</title>
    <style>
        body {
            background-image: url('../../AA.png');
            background-size: 105% 130%;
            background-position: center ;
            font-family: 'Roboto', sans-serif;
            color: black;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            
        }
    
        .container {
            max-width: 500px;
            width: 100%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        input[type="text"] {
            padding: 0.5rem;
            font-size: 1.2rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 1rem;
            width: 100%;
            box-sizing: border-box;
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


        a button {
            background-color: #f2f2f2;
            color: #333;
            align-self: center;
        }

        a button:hover {
            background-color: #e2e2e2;
        }
a {
    display: flex;
    justify-content: center;
    margin-top: 10px;
}

a button {
    background-color: #f2f2f2;
    color: #333;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Record Entry</h1>
        
        <form method="post" action="">
            <label for="student_id">Enter Student ID:</label>
            <input type="text" id="student_id" name="student_id" required>
            <button type="submit">Record Entry</button>
        </form>
        <a href="gate.php"><button>Go Back to Home Page</button></a>
    </div>
</body>
</html>
