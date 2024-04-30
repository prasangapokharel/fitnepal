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
        /* Use flexbox for the container */
        .container {
            display: flex; /* Use flexbox for layout */
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
            background-color: #333; /* Dark background */
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
    </style>
</head>
<body>
    <div class="container"> <!-- Flexbox layout -->
        <!-- Content area for user management -->
        <div class="content">
            <h1>User Management</h1>
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
                                <button class="action-button delete-button">Delete</button>
                            </td>
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