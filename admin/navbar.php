<!-- navbar.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"> <!-- Import Inter font -->
    <style>
        /* Style for the vertical navigation bar */
        .navbar {
            background-color: #DFF5FF; /* Light blue */
            width: 200px; /* Fixed width */
            border: 1px solid #aaa; /* Border */
            border-radius: 0 10px 10px 0; /* Rounded right edge */
            position: fixed; /* Fixed position */
            top: 0; /* Start at the top */
            left: 0; /* Left edge */
            height: 100%; /* Full height of the viewport */
            padding-top: 20px; /* Padding at the top */
            overflow-x: hidden; /* Prevent horizontal overflow */
            text-align: center; /* Centered text */
            font-family: 'Inter', sans-serif; /* Apply Inter font */
        }

        /* Style for links in the navbar */
        .navbar a {
            display: block; /* Make links block-level */
            padding: 15px; /* Padding */
            text-decoration: none; /* No underline */
            color: #000; /* Black text */
            text-align: center; /* Center text */
            font-weight: 600; /* Bold font weight */
        }

        /* Hover effect for links in the navbar */
        .navbar a:hover {
            background-color: #cceeff; /* Light blue on hover */
        }
    </style>
</head>
<body>
    <!-- Vertical navigation bar -->
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="user.php">Users</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
