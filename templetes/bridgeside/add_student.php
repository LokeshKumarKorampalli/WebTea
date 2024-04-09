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

    <form action="add_to_this_week.php">
        <button type="submit">Add Students to This Week's List</button>
    </form>

    <form action="create_outing_list.php" method="post"> 
        <button type="submit">Create new weekend list</button>
    </form>

    <a href="bridge.php"><button>Go Back to Home Page</button></a>
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

