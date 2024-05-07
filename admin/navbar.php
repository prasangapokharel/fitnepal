<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"> <!-- Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->
    <style>
        /* Full-height fixed navigation bar with top margin */
        *{
            margin: 0px;
            padding: 0px;
        }
        .navbar {
            background-color: #DFF5FF; /* Dark blue background */
            font-family: 'Inter', sans-serif; /* Font family */
            width: 200px;
            position: fixed; /* Fixed position */
            /* top: 20px;  */
            left: 0; /* Align to the left edge */
            height: calc(100vh - 20px); /* Fill remaining height with top margin */
            overflow: hidden; /* Prevent overflow */
            /* transition: width 0.5s ease;  */
            /* border-radius: 0 10px 10px 0; */
            z-index: 1000; /* Keep navbar above other content */
            display: flex; /* Flexbox for layout */
            flex-direction: column; /* Vertical alignment of links */
            justify-content: flex-start; /* Align links at the top */
            /* padding-top: 20px; */
        }

        /* Styling for navbar links */
        .navbar a {
            display: flex; /* Flex layout for icons and text */
            align-items: center; /* Vertically center content */
            padding: 15px; /* Padding */
            text-decoration: none; /* No underline */
            color: black; /* Text color */
            /* transition: background-color 0.3s ease;  */
            font-weight: 800;
            margin: 20px 5px 20px 15px;
            padding: 8px 0 8px 0;

        }

        /* Hover effect for links */
        .navbar a:hover {
            background-color: #67C6E3; /* Slightly darker background on hover */
        
        }

        /* Icon styling in navbar */
        .navbar i {
            margin-right: 10px; /* Space between icon and text */
        }

        /* Content with margin adjusted for the navbar */
        .content {
            margin-left: 220px; /* Adjust margin to account for navbar */
            /* transition: margin-left 0.5s ease; */
        }
    </style>
</head>
<body>
    <!-- Fixed navigation bar with top margin -->
    <div class="navbar">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user.php"><i class="fas fa-users"></i> Users</a>
        <a href="meal.php"><i class="fas fa-utensils"></i> Meals</a>
        <a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a>
        <a href="settings.php"><i class="fas fa-cogs"></i> Settings</a>
        <a href="authentication_setup.php"><i class="fas fa-key"></i> Authentication</a>
        <a href="history.php"><i class="fas fa-history"></i> History</a>
    </div>

    
</body>
</html>
