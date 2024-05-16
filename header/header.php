<?php
// include './session.php';
include './db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the user is verified
$user_id = $_SESSION['user_id'];
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT status FROM bankpayment WHERE user_id = '$user_id' AND status = 'verified'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $verified = true;
} else {
    $verified = false;
}

// Close the connection
mysqli_close($conn);

// Logout logic
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Destroy the session
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>

<style>
  * {
    font-family: "Poppins", sans-serif;
    font-weight: 400;
    font-style: normal;
    
  }

  body {
    margin: 0;
    padding: 0;
    background-color: white;
  }

  .navbar {
    background-color: #dff5ff;
    display: flex;
    justify-content: space-between;
    overflow: hidden;
    text-align: right;
    padding: 25px 20px;
  }

  .navbar a {
    display: inline-block;
    color: black;
    text-align: center;
    text-decoration: none;
    font-size: 18px;
  }

  .nav-links {
    display: flex;
    gap: 20px;
  }

  .navbar .logo {
    float: left;
    font-size: 22px;
    font-weight: bold;
    text-decoration: none;
  }

</style>

<body>
<header class="navbar">
        <div>
            <a href="dashboard.php" class="logo">FitNepal</a>
        </div>
        <div class="nav-links">
            <a href="profile.php">Profile</a>
            <?php if ($verified): ?>
                <a href="goal.php">Goals</a>
                <a href="workout.php">Workout</a>
            <?php endif; ?>
            <a href="diet.php">Diet</a>
            <a href="?logout=true">Logout</a>
        </div>
    </header>
</body>

</html>