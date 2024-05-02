<!-- user.php -->
<?php
include 'db_connection.php';

// Handle form submission for user update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update the user in the database
    $query_update = "UPDATE users SET name = :name, email = :email WHERE user_id = :user_id";
    $stmt_update = $pdo->prepare($query_update);
    $stmt_update->execute([
        ':name' => $name,
        ':email' => $email,
        ':user_id' => $user_id
    ]);
}

// Query to fetch all users
$query_users = "SELECT * FROM users ORDER BY user_id DESC"; 
$stmt_users = $pdo->prepare($query_users);
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

function daysAgo($timestamp) {
    $current_time = time(); // Current timestamp
    $days = ($current_time - $timestamp) / (60 * 60 * 24); // Calculate days
    return round($days);
}


// include 'navbar.php'; // Include the navbar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"> <!-- Inter font -->
    <link rel="stylesheet" href="./cssadmin/user.css">

</head>
<body>
<div class="main-container">
        <!-- Button to open the registration modal -->

        <div id="userRegistrationModal" class="modal-overlay">
    <div class="modal-window">
        <span class="modal-close">&times;</span> <!-- Close button -->

        <h2>Register a New User</h2>
        <form action="register.php" method="post"> <!-- Link to your register.php -->
                <div class="input-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" required>
                </div>
                <div class="input-group">
                    <label for="weight">Weight (kg):</label>
                    <input type="number" id="weight" name="weight" step="0.01" required>
                </div>
                <div class="input-group">
                    <label for="height">Height (cm):</label>
                    <input type="number" id="height" name="height" required>
                </div>

                <div class="input-group">
                <label for="activity">Weekly Activity Level:</label>
                <select id="activity" name="activity" required>
                    <option value="normal">Normal</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="highly active">Highly Active</option>
                </select>
                </div>

              
             
                <button type="submit">Register</button> <!-- Submit Button -->
            </form>

    </div>
        </div>
    </div>
    <div class="container"> <!-- Flexbox layout -->
        <!-- Content area for user management -->

        <div class="content">
            <h1>User Management</h1>
            <button class="usr"id="openRegisterModal">Create User</button>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through users and display them -->
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo daysAgo($user['user_id']) . ' days ago'; ?></td>
                            <td>
                                <!-- Edit button triggers modal -->
                                <button class="action-button edit-button" onclick="openEditModal(<?php echo $user['user_id']; ?>, '<?php echo htmlspecialchars($user['name']); ?>', '<?php echo htmlspecialchars($user['email']); ?>')">Edit</button>
                                <a class="action-button delete-button" href="userdelete.php?user_id=<?php echo htmlspecialchars($user['user_id']); ?>">Delete</a> <!-- Link to userdelete.php -->                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal for editing user details -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <h2>Edit User</h2>
                <form method="POST">
                    <input type="hidden" id="user_id" name="user_id" value=""> <!-- User ID -->
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="" required><br><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="" required><br><br>

                    <button type="submit">Update</button>
                    <button type="button" class="close-button" onclick="closeEditModal()">Close</button>
                </form>
            </div>
        </div>
    </div>

    <script>

function registerUser() {
    var form = document.getElementById("registrationForm");
    var formData = new FormData(form); // Collect form data

    // AJAX request to submit the form asynchronously
    fetch("register.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Expect JSON response
    .then(data => {
        if (data.success) {
            // Show success message in a popup or modal
            alert("Registration successful!");
            // Optionally close the modal
            closeModal();
        } else {
            // Display error message from the server
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An unexpected error occurred. Please try again.");
    });
}

var modal = document.getElementById("userRegistrationModal");
        var openButton = document.getElementById("openRegisterModal");
        var closeButton = modal.querySelector(".modal-close");

        // Open modal when button is clicked
        openButton.onclick = function() {
            modal.style.display = "block";
        };

        // Close modal when 'X' or outside area is clicked
        closeButton.onclick = function() {
            modal.style.display = "none";
        };

        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };
        // Function to open the edit modal
        function openEditModal(userId, name, email) {
            var modal = document.getElementById("editModal");
            modal.style.display = "flex"; // Display the modal

            // Set the user ID and other details in the form
            document.getElementById("user_id").value = userId;
            document.getElementById("name").value = name;
            document.getElementById("email").value = email;
        }

        // Function to close the modal
        function closeEditModal() {
            var modal = document.getElementById("editModal");
            modal.style.display = "none"; // Hide the modal
        }

        // Close the modal when clicking outside the content
        window.onclick = function(event) {
            var modal = document.getElementById("editModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
</body>
</html>
