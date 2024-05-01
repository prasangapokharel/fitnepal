<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Logout logic
if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: login.php");
    exit;
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "fitness";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Query to fetch user's height, weight, and age
$sql = "SELECT height, weight, age, name, LPAD(user_id, 5, '0') as user_id FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch user data
    $row = $result->fetch_assoc();
    $height = $row["height"];
    $weight = $row["weight"];
    $age = $row["age"];
    $name = $row["name"];
    $user_id = $row["user_id"]; // Now using the corrected user_id field


    // // Define the baseline protein recommendation (0.8 grams per kilogram of body weight)
    $protein_per_kg = 0.8;

    // Calculate the recommended protein intake
    $protein_intake = $weight * $protein_per_kg;

    // // Display the result
    // echo "Recommended daily protein intake for $name (age $age, weight $weight kg, height $height cm): ";
    // echo "$protein_intake grams per day.";

    // Calculate BMI
    if ($height > 0 && $weight > 0) {
        $height_in_meters = $height / 100; // Convert height from cm to meters
        $bmi = $weight / ($height_in_meters * $height_in_meters);
    } else {
        $bmi = "N/A";
    }
    // Determine the BMI category
    if ($bmi < 18.5) {
        $bmi_category = "underweight";
    } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
        $bmi_category = "normal";
    } elseif ($bmi >= 25.0 && $bmi <= 29.9) {
        $bmi_category = "overweight";
    } else {
        $bmi_category = "obese";
    }

    // Calculate ideal weight range
    if ($bmi !== "N/A") {
        $min_ideal_weight = 18.5 * $height_in_meters * $height_in_meters;
        $max_ideal_weight = 24.9 * $height_in_meters * $height_in_meters;
        $ideal_weight_message = "Your ideal weight range is between " . round($min_ideal_weight, 2) . " kg and " . round($max_ideal_weight, 2) . " kg.";
    } else {
        $ideal_weight_message = "Ideal weight cannot be calculated without complete information.";
    }
} else {
    echo "User data not found.";
}


// Query to fetch the total sum of calories consumed by each user
$totalCaloriesQuery = "SELECT user_id, SUM(amount * calories) AS total_calories FROM calorie GROUP BY user_id";
$totalCaloriesResult = $conn->query($totalCaloriesQuery);

// Array to store total calories for each user
$totalCaloriesPerUser = array();

// Populate the array with total calories for each user
if ($totalCaloriesResult) {
    while ($row = $totalCaloriesResult->fetch_assoc()) {
        $totalCaloriesPerUser[$row['user_id']] = $row['total_calories'];
    }
} else {
    echo "Error fetching total calories per user: " . $conn->error;
}




// Get the current month and year
$currentMonth = date('n');
$currentYear = date('Y');

// Get the number of days in the current month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

// Get the first day of the month
$firstDayOfMonth = date('N', strtotime("$currentYear-$currentMonth-01"));

// Initialize the weeks array
$weeks = array();

// Initialize the day counter
$dayCount = 1;

// Loop through the weeks of the month
for ($i = 0; $i < 6; $i++) {
    $week = array();

    for ($j = 1; $j <= 7; $j++) {
        if (($i === 0 && $j < $firstDayOfMonth) || $dayCount > $daysInMonth) {
            $week[] = '';
        } else {
            $week[] = $dayCount++;
        }
    }

    $weeks[] = $week;
}

$protein_query = "SELECT SUM(protein_grams) AS total_protein FROM entries WHERE user_id = ?";
$protein_stmt = $conn->prepare($protein_query);
$protein_stmt->bind_param("s", $user_id);
$protein_stmt->execute();
$protein_result = $protein_stmt->get_result();

if ($protein_result->num_rows > 0) {
    $protein_row = $protein_result->fetch_assoc();
    $total_protein = $protein_row['total_protein'];
} else {
    $total_protein = 0;
}


$conn->close();
?>

<?php
include 'header\header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: black;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #DFF5FF;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .container h2 {
            color: #fff;
            margin-bottom: 10px;
        }

        .container p {
            margin-bottom: 10px;
            padding: 9px 12px;
            background-color: white;
            font-size: 20px;
            border-radius: 7px;
            border: 2px;
            /* font-weight: 700; */
        }

        .weekdays {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-around;
            background-color: #1F6FFF;
            color: white;
            font-weight: bold;
            border-radius: 12px;
            overflow: hidden;
        }

        ul li {
            display: flex;
            margin-left: 90px;
            /* margin: 50px; */
            padding: 8px 0px;
            float: left;
            border: 2px;
            border-radius: 8px;
        }

        .weekdays li {
            text-align: center;
            padding: 10px 0;
            flex: 1;
        }

        .days {
            padding: 15px;
            list-style-type: none;
            margin: 20px 0 0 0;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .days li {
            text-align: center;
            padding: 20px 0;
            border-radius: 18px;
            background-color: #fff;
            border: 2px solid #1F6FFF;
            color: #1F6FFF;
            font-weight: bold;
        }

        .days li span.active {
            color: #1F6FFF;
        }

        .data {
            border: 2px;
            border-radius: 12px;
            background-color: #1F6FFF;
            margin: 30px 5px;
            margin-top: 100px;
        }

        span {
            font-size: 18px;
        }

        .underweight {
            color: blue;
        }

        .normal {
            color: green;
        }

        .overweight {
            color: orange;
        }

        .obese {
            color: red;
        }

        .content {
            justify-content: center;
            margin-left: 10px;
            font-size: 20px;
        }

        .container {
            display: flex;
            justify-content: space-around;
        }

        .image {
            margin: 0 50px 0 100px;
            height: 40%;
            width: 40%;
            border: 2px;
            border-radius: 9px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .underweight {
            background-color: #f0f8ff;
        }

        .normal {
            background-color: #d1e7dd;
        }

        .overweight {
            background-color: #ffe6b2;
        }

        .obese {
            background-color: #f5c6cb;
        }

        .range h2{
            font-weight: 700;
            color: #67C6E3;
            size: 15px;
            margin-bottom: 0px;
            display: flex;
            justify-content: center;
        }

        .range {
            height: 60%;
            margin: 50px 10%;
            /* background-color: #DFF5FF; */
            border: 2px;
            border-radius: 9px;

        }

        /* Styling for the button */
        .styled-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .styled-button:hover {
            background-color: #45a049;
        }

        /* Styling for the modal (pop-up box) */
        .modal {
            display: none;
            /* Initially hidden */
            position: fixed;
            /* Stay fixed in place */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black background */
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
            z-index: 1;
            /* Ensure it appears above other content */
        }

        /* Styling for the modal content */
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
            /* Fixed width */
        }

        /* Close button for the modal */
        .close-button {
            background-color: #f44336;
            /* Red */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .close-button:hover {
            background-color: #e53935;
            /* Darker red on hover */
        }

        .part2 {
            margin-top: 20px;
            display: flex;
            float: center;
            margin: auto 40%;
        }

        img {
            height: 600px;
            width: 1000px;
            margin: 10% 10%;
            background-color: green;
            border: 2px;
            border-radius: 6px;

        }



        .container2 {
            display: flex;
            /* Use Flexbox to create a row */
            justify-content: space-between;
            /* Evenly space the cards */
            gap: 16px;
            /* Add space between cards */
            padding: 20px;
        }

        .card {
            flex: 1;
            /* Each card takes an equal portion of the row */
            border: 1px solid #ccc;
            /* Light gray border */
            border-radius: 8px;
            /* Rounded corners */
            background-color: #f9f9f9;
            /* Light background color */
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1);
            /* Soft shadow for depth */
            padding: 16px;
            /* Padding within the card */
            text-align: center;
            /* Center-align text content */
        }

        .card iframe {
            width: 100%;
            /* The video takes the full width of the card */
            height: 200px;
            /* Fixed height for consistency */
            border: none;
            /* Remove border around the iframe */
            border-radius: 8px;
            /* Matches card's border radius */
        }

        .card h3 {
            margin: 12px 0;
            /* Space above and below the title */
            color: #333;
            /* Title text color */
        }

        .card p {
            margin: 0;
            color: #666;
        }

        button,
        a {
            text-decoration: none;
            color: black;
        }

        .video h2 {
            margin-left: 24px;
            margin-bottom: 0px;
        }

    </style>
</head>

<body>

    <div class="container">

        <div class="content">
            <p><strong>BMI :</strong> <span class="<?php echo $bmi_category; ?>"><?php echo $bmi; ?></span></p>
            <p>You are in the <span class="<?php echo $bmi_category; ?>"><?php echo ucfirst($bmi_category); ?></span> category.</p>

            <p>Protein Goal : <?php echo $protein_intake; ?></p>
        </div>

        <div id="chart">
            <canvas id="proteinGoalChart" height="220px" width="220px"></canvas>
        </div>
        <div class="second">
            <!-- Button that triggers the modal -->
            <button class="styled-button" onclick="openModal()">Open Pop-Up</button>

            <!-- The modal -->
            <div class="modal" id="myModal">
                <!-- Modal content -->
                <div class="modal-content">

                    <p> <?php echo $name; ?></p>
                    <p> <a href="diet.php">view diet</a></p>
                    <button class="close-button" onclick="closeModal()">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div class="part2">
        <button class="styled-button"><a href="goal.php">Start tracking</a></button>

    </div>

    
    <div class="range">
    <h2>BMI CHART</h2>
    <ul>
            <li><span class="underweight">Underweight</span></li>
            <li><span class="normal">Normal</span></li>
            <li><span class="overweight">Overweight</span></li>
            <li><span class="obese">Obese</span></li>
        </ul>
        <table>
            <thead>
                <tr>
                    <th>Height (in/cm)</th>
                    <th>Weight (lbs)</th>
                    <th>Weight (kgs)</th>
                    <th>BMI Range</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example row for 5'0" -->
                <tr class="underweight">
                    <td>5'0" / 152.4 cm</td>
                    <td>100 - 115</td>
                    <td>45.5 - 52.3</td>
                    <td>Underweight</td>
                </tr>
                <tr class="normal">
                    <td>5'0" / 152.4 cm</td>
                    <td>120 - 140</td>
                    <td>54.5 - 63.6</td>
                    <td>Normal</td>
                </tr>
                <tr class="overweight">
                    <td>5'0" / 152.4 cm</td>
                    <td>145 - 160</td>
                    <td>65.9 - 72.7</td>
                    <td>Overweight</td>
                </tr>
                <tr class="obese">
                    <td>5'0" / 152.4 cm</td>
                    <td>165 - 205</td>
                    <td>74.8 - 93.2</td>
                    <td>Obese</td>
                </tr>
                <!-- Example row for 5'1" -->
                <tr class="underweight">
                    <td>5'1" / 154.9 cm</td>
                    <td>100 - 115</td>
                    <td>45.5 - 52.3</td>
                    <td>Underweight</td>
                </tr>
                <tr class="normal">
                    <td>5'1" / 154.9 cm</td>
                    <td>120 - 145</td>
                    <td>54.5 - 65.9</td>
                    <td>Normal</td>
                </tr>
                <tr class="overweight">
                    <td>5'1" / 154.9 cm</td>
                    <td>150 - 165</td>
                    <td>68.2 - 74.8</td>
                    <td>Overweight</td>
                </tr>
                <tr class="obese">
                    <td>5'1" / 154.9 cm</td>
                    <td>170 - 205</td>
                    <td>77.3 - 93.2</td>
                    <td>Obese</td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

    <!-- <div class="bmi_image">
        <img src="./img.png" alt="image">
    </div> -->

    <div class="video">
        <h2>Video Demonstration</h2>
    </div>
    <div class="container2">
        <div class="card">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/9o0UPuDBM8M?si=EAOOICuhg_9SpJQ2" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <h3>Full Body Workout for Men and Women</h3>
            <p>This is the first video card with embedded YouTube content.</p>
        </div>

        <div class="card">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/YnUmJkicK3c?si=TyRb76qcRCc7YiuQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <h3>Best Exercises To Reduce Belly Fat</h3>
            <p>This is the second video card with embedded YouTube content.</p>
        </div>

        <div class="card">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/-hSERcBUsGY?si=nPAJz2LDFO5xORhp" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <h3>HOW TO DO THE 16-8 INTERMITTENT FASTING DIET | Weight loss, blood sugar control</h3>
            <p>This is the third video card with embedded YouTube content.</p>
        </div>
    </div>
    <script>
        function openModal() {
            document.getElementById("myModal").style.display = "flex"; // Show the modal
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none"; // Hide the modal
        }

        // Close the modal when clicking outside the content
        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }



        // Data for the protein goal
        const proteinGoal = <?php echo $protein_intake; ?>; // Set your total protein goal
        const currentProtein = 10; // Current protein intake
        const remainingProtein = proteinGoal - currentProtein;

        // Calculate the percentage completed
        const percentageCompleted = (currentProtein / proteinGoal) * 100;

        // Create a pie chart
        const ctx = document.getElementById('proteinGoalChart').getContext('2d');
        const proteinGoalChart = new Chart(ctx, {
            type: 'pie', // Pie chart type
            data: {
                labels: ['Completed', 'Remaining'], // Labels for the pie chart
                datasets: [{
                    data: [currentProtein, remainingProtein], // Data for the pie chart
                    backgroundColor: ['#4CAF50', '#FF5722'], // Colors for the segments
                    hoverBackgroundColor: ['#66BB6A', '#FF7043'] // Hover colors
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Position of the legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.chart._metasets[context.datasetIndex].total;
                                const percentage = ((value / total) * 100).toFixed(2);
                                return `${label}: ${value} grams (${percentage}%)`;
                            },
                        },
                    },
                },
            },
        });
    </script>
</body>

</html>