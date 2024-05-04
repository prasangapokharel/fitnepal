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
    <link rel="stylesheet" href="./cssadmin/dashboard.css"> <!-- Custom CSS -->

</head>

<body>
<button class="logout-button" id="openRegisterModal" onclick="openRegisterModal()">Logout</button>

    <div class="container"> <!-- Navbar and content container -->

        <?php include 'navbar.php'; ?> <!-- Include the navbar -->

        <div class="content"> <!-- Content area -->
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

            <!-- Additional information -->
            <div class="info-box">
                <h2>Additional Info</h2>
                <p>Content</p> <!-- Example content -->
            </div>
        </div>

        <!-- Bar graph for weekly user registrations -->
        <div class="bar-graph">
            <canvas id="userRegistrationChart"></canvas> <!-- Chart.js placeholder -->
        </div>
    </div>
</div>

<!-- Initialize the bar graph with Chart.js -->
<script>
var ctx = document.getElementById("userRegistrationChart").getContext("2d");
var userRegistrationChart = new Chart(ctx, {
    type: "bar", // Bar graph
    data: {
        labels: <?php echo json_encode($dates); ?>, // X-axis labels
        datasets: [{
            label: "Users Registered",
            data: <?php echo json_encode($user_counts); ?>, // User count data
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
                position: "bottom", // Legend at the bottom
            },
        },
    },
});
</script>
