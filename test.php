<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page with Vertical Navbar</title>
    <!-- Import the Inter font from Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap">
    <style>
        /* Apply the imported font to the entire body */
        body {
            font-family: 'Inter', sans-serif; /* Use the Inter font */
        }

        /* Style for the fixed navbar toggle button */
        .toggle-button {
            position: fixed; /* Keep it in a fixed position */
            top: 20px; /* Near the top of the page */
            left: 10px; /* Slight offset */
            background-color: #333; /* Dark background */
            color: white; /* White text */
            padding: 10px; /* Padding */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            z-index: 1001; /* Ensure it's above other elements */
            font-size: 18px; /* Increase font size */
        }

        /* Container to manage the navbar and content layout */
        .container {
            display: flex; /* Use flexbox for layout */
        }

        /* Vertical navigation bar */
        .navbar {
            background-color: #DFF5FF; /* Light blue background */
            width: 200px; /* Fixed width */
            border: 1px solid #aaa; /* Border around the navbar */
            border-radius: 0 10px 10px 0; /* Rounded right side */
            padding-top: 90px; /* Padding at the top */
            position: fixed; /* Fixed position on the left side */
            top: 0; /* From the top of the page */
            left: -200px; /* Initially hidden (to slide in/out) */
            height: 100%; /* Full height of the viewport */
            transition: left 0.5s; /* Smooth transition for sliding */
            font-size: 18px; /* Increase font size */
            overflow-x: hidden; /* No horizontal overflow */
            text-align: center; /* Center text within the navbar */
        }

        /* Make navbar open on click */
        .navbar.open {
            left: 0; /* When open, it's fully visible */
        }

        /* Style for links in the navbar */
        .navbar a {
            margin-top: 30px;
            display: block; /* Make links block-level */
            padding: 15px; /* Increase padding */
            text-decoration: none; /* No underline */
            color: #000; /* Black text color */
            text-align: center; /* Center the text */
            font-weight: 600; /* Bold font weight */
        }

        /* Hover effect for links in the navbar */
        .navbar a:hover {
            background-color: #cceeff; /* Lighter blue background on hover */
        }

        /* Content area with margin top and adjustment to avoid overlap with navbar */
        .content {
            margin-left: 220px; /* Margin to account for the navbar width */
            margin-top: 60px; /* Add margin to avoid overlap with toggle button */
            padding: 20px; /* Additional padding */
            font-size: 18px; /* Increase font size */
        }
    </style>
</head>
<body>
    <!-- Button to toggle the navbar -->
    <button class="toggle-button" onclick="toggleNavbar()">☰</button>

    <!-- Main container for layout -->
    <div class="container">
        <!-- Vertical navigation bar -->
        <div class="navbar" id="myNavbar">
            <a href="#home">Home</a>
            <a href="#meal">Meal</a>
            <a href="#user">User</a>
            <a href="#contactus">Contact Us</a>
        </div>

        <!-- Main content area -->
        <div class="content">
           
        </div>
    </div>

    <script>
        function toggleNavbar() {
            var navbar = document.getElementById("myNavbar");
            navbar.classList.toggle("open"); // Toggle the open class to show/hide
        }
    </script>
</body>
</html>
