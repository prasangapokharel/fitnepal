<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  <!-- <link rel="stylesheet" href="./header.css"> -->
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
    padding: 7px 9px;
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
      <a href="#" class="logo">FitNepal</a>

    </div>
    <div class="nav-links">
      <a href="profile.php">Profile</a>
      <a href="goal.php">Goals</a>
      <a href="diet.php">Diet</a>
      <a href="index.php">Workout</a>
      <a href="?logout=true">Logout</a>

    </div>

  </header>
</body>

</html>