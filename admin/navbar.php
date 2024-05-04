<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"> <!-- Inter font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome for icons -->
    <style>
        /* Full-height vertical navigation bar */
        .navbar {
            background-color: #124c8e; /* Dark background */
            font-family: 'Inter', sans-serif;
            width: 0; /* Start collapsed */
            position: fixed; /* Fixed position */
            top: 0; /* Align to the top */
            left: 0; /* Align to the left edge */
            height: 90vh; /* Full height */
            overflow: hidden; /* Prevent overflow */
            transition: width 0.5s ease; /* Smooth transition for width */
            border-radius: 0 10px 10px 0; /* Rounded corners on the right */
            z-index: 1000; /* Keep navbar above other content */
            display: flex; /* Flexbox for even spacing */
            flex-direction: column; /* Vertical arrangement */
            justify-content: space-between; /* Even spacing */
            padding: 20px 0; /* Padding top and bottom */
        }

        /* Navbar when expanded */
        .navbar.expanded {
            width: 220px; /* Expanded width */
        }

        /* Style for navigation links */
        .navbar a {
            display: flex; /* Flex layout for links */
            align-items: center; /* Vertically center text */
            justify-content: flex-start; /* Horizontally align text */
            padding: 15px; /* Padding */
            text-decoration: none; /* No underline */
            color: #f0f0f0; /* Light text color */
            transition: background-color 0.3s; /* Smooth transition */
            font-weight: 600; /* Bold font */
        }

        /* Hover effect for navigation links */
        .navbar a:hover {
            background-color: #4e4e4e; /* Darker on hover */
        }

        /* Icon styling in navbar */
        .navbar i {
            margin-right: 10px; /* Space between icon and text */
        }

        /* Navbar toggle button styling */
        .navbar-toggle {
            background-color: #124c8e; /* Match navbar background */
            color: #f0f0f0; /* Light text */
            padding: 10px; /* Padding */
            border-radius: 0 10px 10px 0; /* Rounded corners */
            position: fixed; /* Fixed position */
            top: 20px; /* Position at the top */
            left: 0; /* Align to the left edge */
            cursor: pointer; /* Pointer cursor */
            transition: background-color 0.3s; /* Smooth transition */
        }

        /* Content area with margin adjustment for navbar */
        .content {
            margin-left: 0; /* No margin when navbar is collapsed */
            transition: margin-left 0.5s; /* Smooth transition */
        }

        .navbar.expanded + .content {
            margin-left: 220px; /* Adjust margin when navbar is expanded */
        }
    </style>
</head>
<body>
    <!-- Toggle button to expand/collapse the navbar -->
    <button class="navbar-toggle" onclick="toggleNavbar()">
        <i class="fas fa-bars"></i> <!-- Hamburger icon -->
    </button>

    <!-- Vertical navigation bar -->
    <div class="navbar" id="navbar">
        <!-- Navigation links with icons -->
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user.php"><i class="fas fa-users"></i> Users</a>
        <a href="diet.php"><i class="fas fa-utensils"></i> Diet</a>
        <a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a>
        <a href="settings.php"><i class="fas fa-cogs"></i> Settings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>


    <!-- JavaScript to toggle the navbar -->
    <script>
    function toggleNavbar() {
        var navbar = document.getElementById("navbar");
        navbar.classList.toggle("expanded"); // Toggle expanded state
    }
    </script>
</body>
</html>
