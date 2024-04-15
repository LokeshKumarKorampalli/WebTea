<?php
include '../../db_connection.php'; 
session_start(); 

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../../index.html");
    exit;
}
if ($_SESSION['role'] !== "bridge") {
    header("Location: ../../index.html");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

    $current_date = date("Y_m_d");

   
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Permission Grant</title>
    <script>
        function confirmCreateNewOutingList() {
            if (confirm("Creating a new weekend outing list will prevent adding students to the previous week's list. Are you sure you want to proceed?")) {
                window.location.href = "create_outing_list.php";
            }
        }
    </script>
</head>
<body>
    <button onclick="window.location.href='add_to_this_week.php'">Add Students to This Week's List</button>

    <form action="create_outing_list.php" method="post"> 
      <button onclick="confirmCreateNewOutingList()">Create new weekend list</button>
    </form>
    
    <a href="bridge.php"><button>Go Back to Home Page</button></a>
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

.container {
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
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

a {
  text-decoration: none;
  color: white;
}

a:hover {
  text-decoration: underline;
}

</style>

