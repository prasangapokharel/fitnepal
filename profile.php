<?php
include 'session.php'; // Include session check
include 'db_connection.php'; // Include database connection
require 'vendor/autoload.php'; // Ensure this path is correct
use PragmaRX\Google2FA\Google2FA; // Google2FA library

$google2fa = new Google2FA();
$user_id = $_SESSION['user_id'];

// Fetch the user's Google Authenticator secret key
$sql = "SELECT google_auth_secret FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$google_auth_secret = $result->fetch_assoc()['google_auth_secret'];

// Function to validate email format
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Function to hash passwords
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Fetch user information
$sql = "SELECT name, email, age, weight, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

$name = $user_data["name"];
$email = $user_data["email"];
$age = $user_data["age"];
$weight = $user_data["weight"];
$profile_picture = $user_data["profile_picture"] ?? 'blank_profile_picture.jpg';

$update_email_msg = '';
$update_password_msg = '';
$update_profile_picture_msg = '';
$otp_message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_email"])) {
    $new_email = $_POST["new_email"];
    $otp = $_POST["otp"];
    if (validate_email($new_email) && $google2fa->verifyKey($google_auth_secret, $otp)) {
        $sql_update = "UPDATE users SET email = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_email, $user_id);
        if ($stmt_update->execute()) {
            $update_email_msg = "Email updated successfully.";
            $email = $new_email;
        } else {
            $update_email_msg = "Error updating email in the database.";
        }
    } else {
        $update_email_msg = "Invalid email format or OTP.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["change_password"])) {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];
    $otp = $_POST["otp"];

    if ($google2fa->verifyKey($google_auth_secret, $otp)) {
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $hashed_password = $result->fetch_assoc()["password"];

        if (password_verify($current_password, $hashed_password)) {
            if ($new_password === $confirm_password) {
                $hashed_new_password = hash_password($new_password);
                $sql_update = "UPDATE users SET password = ? WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("si", $hashed_new_password, $user_id);
                if ($stmt_update->execute()) {
                    $update_password_msg = "Password changed successfully.";
                } else {
                    $update_password_msg = "Error updating password in the database.";
                }
            } else {
                $update_password_msg = "New password and confirm password do not match.";
            }
        } else {
            $update_password_msg = "Current password is incorrect.";
        }
    } else {
        $otp_message = "Invalid OTP. Please try again.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['profile_picture'])) {
    // Profile picture upload logic here
}

include 'header/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="CSS/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<section class="hero-section">
<a href="2fa.php" class="btn-2fa"><i class="fas fa-shield-alt"></i> Enable 2FA</a>
    <div class="container">
        <div class="profile-card">
            <div class="profile-header text-center">
=======
    <link rel="stylesheet" href="./CSS/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* Add styles for dropdown sections */
        .dropdown-section {
            display: none;
        }
    </style>
</head>
<body>
<section class="hero-section">
    <a href="2fa.php" class="btn-2fa"><i class="fas fa-shield-alt"></i>Enable 2FA</a>
    <div class="container">
        <div class="profile-info">
            <div class="profile-pic-wrapper">
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
                <img src="<?php echo $profile_picture; ?>" id="profile-picture-display" alt="Profile Picture" class="profile-picture">
                <form id="profile-picture-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <input type="file" id="profile_picture_input" name="profile_picture" accept="image/*" required>
                    <input type="submit" id="submit_button" value="Upload Profile Picture">
                </form>
            </div>
<<<<<<< HEAD
            <div class="profile-details">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" value="<?php echo $age; ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="weight">Weight:</label>
                        <input type="number" id="weight" step="0.01" name="weight" value="<?php echo $weight; ?>" class="form-control" required>
                    </div>
                    <button type="submit" class="btn update-btn">Update Profile</button>
                </form>
            </div>
            <hr>
            <div class="profile-action">
                <button class="btn action-btn" onclick="toggleSection('update-email')">Update Email</button>
                <div id="update-email" class="action-section">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="new_email">New Email:</label>
                            <input type="email" id="new_email" name="new_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="otp">OTP:</label>
                            <input type="text" id="otp" name="otp" class="form-control" required>
                        </div>
                        <input type="hidden" name="update_email" value="1">
                        <button type="submit" class="btn action-btn">Update Email</button>
                        <p class="text-center"><?php echo $update_email_msg; ?></p>
                    </form>
                </div>
            </div>
            <hr>
            <div class="profile-action">
                <button class="btn action-btn" onclick="toggleSection('change-password')">Change Password</button>
                <div id="change-password" class="action-section">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="current_password">Current Password:</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password:</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="otp">OTP:</label>
                            <input type="text" id="otp" name="otp" class="form-control" required>
                        </div>
                        <input type="hidden" name="change_password" value="1">
                        <button type="submit" class="btn action-btn">Change Password</button>
                        <p class="text-center"><?php echo $update_password_msg; ?></p>
                        <p class="text-center"><?php echo $otp_message; ?></p>
                    </form>
                </div>
=======
            <p id="upload-msg"><?php echo $update_profile_picture_msg; ?></p>
            <hr>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo $age; ?>" required>
                <label for="weight">Weight:</label>
                <input type="number" id="weight" step="0.01" value="<?php echo $weight; ?>" required>
            </form>
            <hr>
            <button onclick="toggleSection('update-email-section')">Update Email</button>
            <div id="update-email-section" class="dropdown-section">
                <h3>Update Email</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="new_email">New Email:</label>
                    <input type="email" id="new_email" name="new_email" required>
                    <label for="otp">OTP:</label>
                    <input type="text" id="otp" name="otp" required>
                    <input type="hidden" name="update_email" value="1">
                    <button class="prof" type="submit">Update Email</button>
                </form>
                <p><?php echo $update_email_msg; ?></p>
            </div>
            <hr>
            <button onclick="toggleSection('change-password-section')">Change Password</button>
            <div id="change-password-section" class="dropdown-section">
                <h3>Change Password</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required>
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <label for="otp">OTP:</label>
                    <input type="text" id="otp" name="otp" required>
                    <input type="hidden" name="change_password" value="1">
                    <button class="prof" type="submit">Change Password</button>
                </form>
                <p><?php echo $update_password_msg; ?></p>
                <p><?php echo $otp_message; ?></p>
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
            </div>
        </div>
    </div>
</section>
<script>
function toggleSection(sectionId) {
    var section = document.getElementById(sectionId);
<<<<<<< HEAD
    section.style.display = section.style.display === 'block' ? 'none' : 'block';
}

=======
    if (section.style.display === "none" || section.style.display === "") {
        section.style.display = "block";
    } else {
        section.style.display = "none";
    }
}

// Trigger file input when profile picture is clicked
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
document.addEventListener('DOMContentLoaded', function() {
    var profilePicture = document.getElementById('profile-picture-display');
    var profilePictureInput = document.getElementById('profile_picture_input');
    var submitButton = document.getElementById('submit_button');
    
    profilePicture.addEventListener('click', function() {
        profilePictureInput.click();
    });

    profilePictureInput.addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();
        
        reader.onload = function(e) {
            profilePicture.src = e.target.result;
        };
        
        if (file) {
            reader.readAsDataURL(file);
            submitButton.click();
        }
    });
});
</script>
</body>
</html>
