<?php
include 'db_connection.php';

// Query to get the total number of users
$query_total_users = "SELECT COUNT(*) as total_users FROM users";
$stmt_total_users = $pdo->prepare($query_total_users);
$stmt_total_users->execute();
$result_total_users = $stmt_total_users->fetch(PDO::FETCH_ASSOC);
$total_users = $result_total_users['total_users'] ?? 0;

// Query to get the number of users registered today
$query_users_today = "SELECT COUNT(*) as users_today FROM users WHERE DATE(FROM_UNIXTIME(user_id)) = CURDATE()";
$stmt_users_today = $pdo->prepare($query_users_today);
$stmt_users_today->execute();
$result_users_today = $stmt_users_today->fetch(PDO::FETCH_ASSOC);
$users_today = $result_users_today['users_today'] ?? 0;

// Query to get user registrations for the past 7 days
$query_weekly_users = "
SELECT DATE(FROM_UNIXTIME(user_id)) as registration_date, COUNT(*) as user_count
FROM users
WHERE DATE(FROM_UNIXTIME(user_id)) >= CURDATE() - INTERVAL 6 DAY
GROUP BY registration_date
ORDER BY registration_date;
";

$stmt_weekly_users = $pdo->prepare($query_weekly_users);
$stmt_weekly_users->execute();
$weekly_users_data = $stmt_weekly_users->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for Chart.js
$dates = array_map(function ($data) {
    return date('Y-m-d', strtotime($data['registration_date'])); // Ensure consistent date format
}, $weekly_users_data);

$user_counts = array_map(function ($data) {
    return $data['user_count'];
}, $weekly_users_data);

include 'navbar.php'; // Include the navbar
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"> <!-- Inter font -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
    <style>
        /* Futuristic styling for the page */
        body {
            background-color: #1e1e1e;
            /* Dark background */
            color: #f0f0f0;
            /* Light text */
            font-family: 'Inter', sans-serif;
            /* Use Inter font */
            padding: 20px;
            /* Add some padding */
        }

        /* Flexbox container for the navbar and content */
        .container {
            display: flex;
            /* Use flexbox */
            background-color: #1e1e1e;
            /* Match body background */
        }

        /* Content area with margin to avoid navbar overlap */
        .content {
            flex-grow: 1;
            /* Allow content to grow */
            margin: 0px 10%;
            /* Adjust margin for navbar */
            padding: 20px;
            /* Padding around content */
            display: flex;
            /* Use flexbox */
            flex-direction: column;
            /* Vertical layout */
            justify-content: flex-start;
            /* Align elements at the top */
            align-items: center;
            /* Center content */
        }

        /* Content row to align info boxes in a row */
        .content-row {
            display: flex;
            /* Flexbox for row layout */
            justify-content: space-between;
            /* Evenly space elements */
            align-items: center;
            /* Align vertically */
            margin-bottom: 20px;
            /* Space between rows */
            width: 80%;
            /* Reduce the width */
            margin-left: 0%;
            /* Center the row */
        }

        /* Info boxes for consistent styling */
        .info-box {
            background-color: #2c2c2c;
            /* Dark background */
            padding: 20px;
            /* Padding */
            border: 1px solid #4e4e4e;
            /* Border */
            border-radius: 10px;
            /* Rounded corners */
            text-align: center;
            /* Center text */
            flex: 1;
            /* Allow flex growth */
            margin-right: 10px;
            /* Space between boxes */
            color: #f0f0f0;
            /* Light text */
        }

        /* Bar graph styling */
        .bar-graph {
            width: 80%;
            /* Full width */
            height: 300px;
            /* Fixed height */
            margin-left: 10%;
            /* Center the graph */
            padding-bottom: 20px;
            /* Padding at the bottom */
        }

        /* Button styling with hover effects */
        .btn {
            padding: 10px 20px;
            /* Padding */
            background-color: #3e3e3e;
            /* Dark background */
            color: #f0f0f0;
            /* Light text */
            border: none;
            /* No border */
            border-radius: 5px;
            /* Rounded corners */
            cursor: pointer;
            /* Pointer cursor */
            transition: background-color 0.3s;
            /* Transition effect */
        }

        .btn:hover {
            background-color: #5e5e5e;
            /* Darker on hover */
        }
    </style>
</head>

<body>
    <div class="container"> <!-- Navbar and content container -->
        <?php include 'navbar.php'; ?> <!-- Include the navbar -->

        <!-- Content area -->
        <div class="content">
            <!-- Content boxes in a row -->
            <div class="content-row">
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

                <!-- Additional content box -->
                <div class="info-box">
                    <h2>Additional Info</h2>
                    <p>...</p> <!-- Placeholder content -->
                </div>
            </div>

            <!-- Bar graph for weekly user registrations -->
            <div class="bar-graph">
                <canvas id="userRegistrationChart"></canvas> <!-- Chart.js placeholder -->
            </div>
        </div>
    </div>

    <!-- JavaScript to initialize the bar graph with Chart.js -->
    <script>
        var ctx = document.getElementById("userRegistrationChart").getContext("2d");
        var userRegistrationChart = new Chart(ctx, {
            type: "bar", // Bar graph
            data: {
                labels: <?php echo json_encode($dates); ?>, // X-axis labels (dates)
                datasets: [{
                    label: "Users Registered",
                    data: <?php echo json_encode($user_counts); ?>, // Y-axis data (user counts)
                    backgroundColor: ["#3b82f6", "#ef4444", "#f59e0b", "#3b82f6", "#ef4444", "#f59e0b"], // Bar colors
                    borderColor: ["#2563eb", "#dc2626", "#d97706"], // Border colors
                    borderWidth: 1, // Border width
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true, // Start y-axis at zero
                        title: {
                            display: true,
                            text: "Number of Users", // Y-axis title
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: "Registration Date", // X-axis title
                        },
                    },
                },
                plugins: {
                    legend: {
                        position: "bottom", // Move legend to the bottom
                    },
                },
            },
        });
    </script>
</body>

</html>