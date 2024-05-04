<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    exit("User not logged in");
}

// Logout logic
if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
}

// Check if delete action is requested
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    // Sanitize the input
    $delete_id = intval($_GET['delete_id']);

    // Prepare and execute SQL query to delete the diet item
    $sql = "DELETE FROM diet_items WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $delete_id, $_SESSION['user_id']);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "Diet item deleted successfully!";
        exit;
    } else {
        echo "Error deleting diet item!";
        exit;
    }
}

// Check if form is submitted to add a new diet item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user ID from the session
    $user_id = $_SESSION['user_id'];

    // Calculate total calories for the user
$totalCalories = 0;

// Fetch the sum of calories for the logged-in user
$total_calories_sql = "SELECT SUM(calories * serving) AS total_calories FROM diet_items WHERE user_id = ?";
$total_calories_stmt = $conn->prepare(total_calories_sql);
$total_calories_stmt->bind_param("i", $user_id);
$total_calories_stmt->execute();
$total_calories_result = $total_calories_stmt->get_result();
if ($total_calories_result->num_rows > 0) {
    $row = $total_calories_result->fetch_assoc();
    $totalCalories = $row['total_calories'] ?? 0; // If null, set to 0
}

    // Retrieve form data
    $foodName = $_POST["foodName"];
    $category = $_POST["category"];
    $calories = $_POST["calories"];
    $serving = $_POST["serving"];
    $description = $_POST["description"];

    // Prepare and execute SQL query to insert data into the database
    $sql = "INSERT INTO diet_items (user_id, food_name, category, calories, serving, description)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssds", $user_id, $foodName, $category, $calories, $serving, $description);
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows > 0) {
        echo "Diet item added successfully!";
        exit;
    } else {
        echo "Error adding diet item: " . $stmt->error;
        exit;
    }
}

// Fetch all diet items of the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM diet_items WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php
include 'header\header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calorie Tracker</title>
    <link rel="stylesheet" href="./CSS/diet.css">
</head>

<body>

    <div class="container">
        <button id="showMyDietBtn">My Diet</button>
        <button id="showInputBtn">Create Diet+</button>
        <div class="input-section" id="inputSection">
            <form id="dietForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="text" name="foodName" placeholder="Name of food">
                <select name="category">
                    <option value="">Select Category</option>
                    <option value="Veg">Veg</option>
                    <option value="Non-Veg">Non-Veg</option>
                    <option value="Ayurvedic">Ayurvedic</option>
                    <option value="Dairy">Dairy</option>
                </select>
                <input type="number" name="calories" placeholder="Calories">
                <input type="number" name="serving" placeholder="Serving">
                <input type="text" name="description" placeholder="Description">
                <button type="submit">Create</button>
                <button type="button" class="close-btn" id="closeInputBtn">Close</button>
            </form>
        </div>
        <div id="myDietSection" style="display: none;">
            <div class="total-calories">Total Calories: <?php echo $totalCalories; ?></div>

            <h2>My Diet</h2>

            <table>
                <thead>
                    <tr>
                        <th>Food Name</th>
                        <th>Category</th>
                        <th>Calories</th>
                        <th>Serving</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['food_name']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['calories']; ?></td>
                            <td><?php echo $row['serving']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td>
                                <button class="delete" data-id="<?php echo $row['id']; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('showInputBtn').addEventListener('click', function() {
            document.getElementById('inputSection').classList.toggle('active');
        });

        document.getElementById('showMyDietBtn').addEventListener('click', function() {
            document.getElementById('myDietSection').style.display = 'block';
        });

        // Attach click event listener to delete buttons
        var deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var delete_id = this.getAttribute('data-id');
                if (confirm("Are you sure you want to delete this item?")) {
                    // Send AJAX request to delete the diet item
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?delete_id=" + delete_id, true);
                    xhr.onload = function() {
                        if (xhr.readyState === xhr.DONE) {
                            if (xhr.status === 200) {
                                alert(xhr.responseText);
                                // Refresh the page or update the UI as needed
                                location.reload(); // You may want to update the UI without reloading the page
                            } else {
                                alert('Error deleting diet item');
                            }
                        }
                    };
                    xhr.send();
                }
            });
        });
    </script>
</body>

</html>