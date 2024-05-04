<?php
include 'header\header.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./CSS/login.css"> <!-- Adjust this based on your existing styles -->
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form action="send_reset_link.php" method="post">
            <div class="input-wrapper">
                <i class="fa fa-envelope icon"></i>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <input class="log" type="submit" value="Send Reset Link">
        </form>
        <div class="login-link">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</body>
</html>
