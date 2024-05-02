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

// Function to calculate the number of days since user registration
function daysAgo($timestamp) {
    $current_time = time(); // Current timestamp
    $days = ($current_time - $timestamp) / (60 * 60 * 24); // Calculate days
    return round($days);
}

include 'navbar.php'; // Include the navbar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"> <!-- Inter font -->
    <style>
        body{
            background-color: #FFF; /* Light blue */

        }
        /* Use flexbox for the container */
        .container {
            display: flex;
            background-color: #DFF5FF; /* Light blue */
 /* Use flexbox for layout */
        }

        /* Content area with proper margin to avoid overlapping with the navbar */
        .content {
            flex-grow: 1; /* Allow content to grow */
            margin-left: 220px; /* Avoid overlapping with navbar */
            padding: 20px; /* Padding around content */
            font-family: 'Inter', sans-serif; /* Apply Inter font */
        }

        /* Style the table to display users */
        table {
            width: 100%; /* Full width */
            border-collapse: collapse; /* Collapse table borders */
        }

        /* Style for table headers */
        th {
            background-color: green; /* Dark background */
            color: white; /* White text */
            padding: 10px; /* Padding */
            text-align: left; /* Left alignment */
        }

        /* Style for table rows */
        td {
            padding: 10px; /* Padding */
            border-bottom: 1px solid #ddd; /* Border at the bottom of each row */
        }

        /* Alternate row background for readability */
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Light gray */
        }

        /* Hover effect for rows */
        tr:hover {
            background-color: #ddd; /* Gray on hover */
        }

        /* Style for action buttons (edit, delete) */
        .action-button {
            padding: 5px 10px; /* Padding */
            border-radius: 5px; /* Rounded corners */
            font-size: 14px; /* Font size */
            transition: background-color 0.3s; /* Transition on hover */
            cursor: pointer; /* Pointer cursor */
        }

        /* Edit and delete button styles */
        .edit-button {
            background-color: #4CAF50; /* Green */
            color: white; /* White text */
        }

        .edit-button:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        .delete-button {
            background-color: #f44336; /* Red */
            color: white; /* White text */
        }

        .delete-button:hover {
            background-color: #e53935; /* Darker red on hover */
        }

        /* Modal styles */
        .modal {
            display: none; /* Initially hidden */
            position: fixed; /* Fixed position */
            top: 0;
            left: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            justify-content: center; /* Center the modal horizontally */
            align-items: center; /* Center the modal vertically */
            z-index: 1002; /* Above other content */
        }

        /* Modal content styles */
        .modal-content {
            background-color: white; /* White background */
            padding: 20px; /* Padding */
            border-radius: 10px; /* Rounded corners */
            width: 400px; /* Fixed width */
            text-align: center; /* Center text */
        }

        /* Close button for the modal */
        .close-button {
            background-color: #f44336; /* Red */
            color: white; /* White text */
            padding: 10px; /* Padding */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
        }

        
.main-container {
    padding: 20px; /* Padding for the main container */
    text-align: center; /* Center-align the content */
}

.usr {
    padding: 10px 20px; /* Padding for the button */
    border: none; /* No border */
    border-radius: 4px; /* Rounded corners */
    background-color: green; /* Blue background */
    color: white; /* White text */
    cursor: pointer; /* Pointer cursor */
    transition: background-color 0.3s; 
    float: right;
    display: flex;
    margin: 20px;
}

button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

.modal-overlay {
    display: none; /* Hidden by default */
    position: fixed; /* Fixed position relative to viewport */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    justify-content: center; /* Center the modal content */
    align-items: center; /* Center the modal content vertically */
}

.modal-window {
    background: white;
    color: #333; /* White background for modal content */
    padding: 20px; /* Padding inside the modal */
    border-radius: 8px; /* Rounded corners */
    max-width: 70%; /* Limit width of the modal */
    text-align: left;
    margin: 50px 20%; /* Left-align content */
}

.modal-close {
    color: #333; /* Dark text color */
    float: right; /* Float to the right corner */
    font-size: 1.5rem; /* Larger font size */
    cursor: pointer; /* Pointer cursor */
}

.input-group {
    margin-bottom: 16px; /* Space between input groups */
}

input {
    width: 100%; /* Ensure input fills the group */
    padding: 8px; /* Padding for input fields */
    border: 1px solid #ccc; /* Light border */
    border-radius: 4px; /* Rounded corners */
}

input:focus {
    border-color: #007bff; /* Blue border on focus */
}

label {
    display: block; /* Labels on separate lines */
    margin-bottom: 8px; /* Space between label and input */
}
    </style>
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
