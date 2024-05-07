<?php
include 'db_connection.php'; // Include PDO connection
include 'navbar.php'; // Include navbar

// Fetch the user data and their total calories
$sql = "SELECT users.user_id, users.name, SUM(calorie.calories) AS total_calories 
        FROM users 
        LEFT JOIN calorie ON users.user_id = calorie.user_id 
        GROUP BY users.user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$user_calories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calories</title>
    <link rel="stylesheet" href="./cssadmin/calories.css"> <!-- External CSS file -->
</head>
<body>
    <div class="container">
        <h2>Calories Tracker</h2>

        <table>
            <tr>
                <th>Username</th>
                <th>Total Calories</th>
            </tr>
            <?php foreach ($user_calories as $user): ?>
                <tr onclick="showCalories('<?php echo $user['user_id']; ?>')"> <!-- Pass user_id to the JS function -->
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['total_calories']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Modal to display calorie details -->
        <div id="calorie-modal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="closeModal()">x</span> <!-- Close button -->
                <h3>Calorie Details</h3>
                <div id="calorie-details"></div> <!-- Div to load calorie details -->
            </div>
        </div>
    </div>

    <!-- Script to handle modal and fetch calorie details -->
    <script>
    // Function to open the modal and load calorie details
    function showCalories(userId) {
        var modal = document.getElementById("calorie-modal");
        modal.style.display = "flex"; // Show modal
        fetchCalorieDetails(userId); // Fetch calorie details
    }

    // Function to close the modal
    function closeModal() {
        var modal = document.getElementById("calorie-modal");
        modal.style.display = "none"; // Hide modal
    }

    // Function to fetch calorie details for a user
    function fetchCalorieDetails(userId) {
        var calorieDetails = document.getElementById("calorie-details");
        calorieDetails.innerHTML = "Loading..."; // Placeholder text

        // AJAX request to fetch calorie details
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_calorie_details.php?user_id=" + userId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                calorieDetails.innerHTML = xhr.responseText; // Display response in modal
            }
        };
        xhr.send();
    }
    </script>
</body>
</html>
