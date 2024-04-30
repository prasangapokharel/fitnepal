<!-- dashboard.php -->
<?php
include 'db_connection.php';

// Ensure $pdo is defined
if (!isset($pdo)) {
    die('Database connection is not established.');
}

// Query to get the total number of users
$query_total_users = "SELECT COUNT(*) as total_users FROM users";
$stmt_total_users = $pdo->prepare($query_total_users);
$stmt_total_users->execute();

// Fetch the results to access them as an associative array
$result_total_users = $stmt_total_users->fetch(PDO::FETCH_ASSOC);
$total_users = $result_total_users['total_users'] ?? 0; // Provide default value if null

// Query to get the number of users registered today
$query_users_today = "SELECT COUNT(*) as users_today FROM users WHERE DATE(FROM_UNIXTIME(user_id)) = CURDATE()";
$stmt_users_today = $pdo->prepare($query_users_today);
$stmt_users_today->execute();

// Fetch the results to access them as an associative array
$result_users_today = $stmt_users_today->fetch(PDO::FETCH_ASSOC);
$users_today = $result_users_today['users_today'] ?? 0; // Provide default value if null

include 'navbar.php'; // Include the navbar
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        /* Fixed position for the toggle button */
      

        /* Container to manage navbar and content */
        .container {
            display: flex; /* Use flexbox for layout */
        }


        /* Content area with flexbox */
        .content {
            flex-grow: 1; /* Allow content to grow */
            margin-left: 220px; /* Margin to avoid overlapping with the navbar */
            padding: 20px; /* Padding around content */
            font-size: 18px; /* Larger font size */
            display: flex; /* Use flexbox */
            justify-content: space-around; /* Space content evenly */
            align-items: center; /* Align content vertically */
        }

        /* Info boxes for content */
        .info-box {
            background-color: #f9f9f9; /* Light gray background */
            padding: 20px; /* Padding */
            border: 1px solid #ddd; /* Border */
            border-radius: 10px; /* Rounded corners */
            text-align: center; /* Center text */
        }
    </style>
</head>
<body>
    

        <!-- Content area -->
        <div class="content">
            <!-- Total registered users -->
            <div class="info-box">
                <h2>Total Users</h2>
                <p><?php echo $total_users; ?></p>
            </div>

            <!-- Users registered today -->
            <div class="info-box">
                <h2>Users Registered Today</h2>
                <p><?php echo $users_today; ?></p>
            </div>
        </div>
    </div>

   
</body>
</html>
