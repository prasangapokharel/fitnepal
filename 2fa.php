<?php
include 'session.php'; // Include session check
<<<<<<< HEAD
include 'header/header.php'; // Include header
=======
include 'header/header.php';
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
include 'admin/db_connection.php'; // Include PDO connection
require 'vendor/autoload.php'; // Ensure Composer autoload works
use PragmaRX\Google2FA\Google2FA; // Google2FA library

$google2fa = new Google2FA();

$user_id = $_SESSION['user_id']; // Fetch the user ID from the session

// Check if the user has a Google Authenticator secret key
$sql = "SELECT google_auth_secret FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

$google_auth_secret = $user_data['google_auth_secret'] ?? null; // Fetch secret key from database

if ($google_auth_secret === null) {
    // Generate a new secret key
    $google_auth_secret = $google2fa->generateSecretKey(); // Generate unique secret key
<<<<<<< HEAD
=======
    $company_name = "Fitnepal"; // Company name for QR code
    $qr_code_data = urlencode("otpauth://totp/{$company_name}:user_{$user_id}?secret={$google_auth_secret}&issuer={$company_name}");
    $qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={$qr_code_data}"; // Generate QR code URL
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839

    // Save the secret key to the database
    $sql_update = "UPDATE users SET google_auth_secret = :google_auth_secret WHERE id = :user_id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':google_auth_secret', $google_auth_secret, PDO::PARAM_STR);
    $stmt_update->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if (!$stmt_update->execute()) {
        die("Error storing Google Authentication secret key.");
    }
<<<<<<< HEAD
}

$company_name = "Fitnepal"; // Company name for QR code
$qr_code_url = $google2fa->getQRCodeUrl($company_name, "user_{$user_id}", $google_auth_secret); // Generate QR code URL

// Debug output
?>
=======
} else {
    $company_name = "Fitnepal"; // Reuse company name
    $qr_code_data = urlencode("otpauth://totp/{$company_name}:user_{$user_id}?secret={$google_auth_secret}&issuer={$company_name}");
    $qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={$qr_code_data}"; // Generate QR code URL from the secret key
}

// Debug output
?>

>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Google Authentication Setup</title>
    <link rel="stylesheet" href="./CSS/authentication_setup.css"> <!-- External CSS -->
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
=======
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert("Secret key copied to clipboard!");
            }).catch(err => {
                alert("Failed to copy text: " + err);
            });
        }
    </script>
<<<<<<< HEAD
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- jQuery library -->
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script> <!-- QRCode library -->
=======
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
</head>
<body>
    <div class="container">
        <h2>Google Authentication Setup</h2>
<<<<<<< HEAD
        <p>Scan the QR code with Google Authenticator, then enter the generated OTP below:</p>
        <?php if (isset($qr_code_url)): ?>
            <div id="qrcode"></div>
            <script type="text/javascript">
                var qrcode = new QRCode(document.getElementById("qrcode"), {
                    text: "<?php echo $qr_code_url; ?>",
                    width: 200,
                    height: 200,
                });
            </script>
            <p>Your secret key: <strong><?php echo htmlspecialchars($google_auth_secret); ?></strong>
            <button onclick="copyToClipboard('<?php echo htmlspecialchars($google_auth_secret); ?>')">Copy</button></p> <!-- Display secret key with copy button -->
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="otp">One-Time Password (OTP):</label>
            <input type="text" id="otp" name="otp" required>
            <button type="submit">Verify OTP</button>
        </form>
=======
        <br>
        <p>Scan the QR code with Google Authenticator, then enter the generated OTP below:</p>
        <br>
        <?php if (isset($qr_code_url)): ?>
            <img src="<?php echo htmlspecialchars($qr_code_url); ?>" alt="QR Code">
            <p>Your secret key: <strong><?php echo htmlspecialchars($google_auth_secret); ?></strong>
            <button onclick="copyToClipboard('<?php echo htmlspecialchars($google_auth_secret); ?>')">Copy</button></p> <!-- Display secret key with copy button -->
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="otp">One-Time Password (OTP):</label>
            <input type="text" id="otp" name="otp" required>
            <br>
            <button type="submit">Verify OTP</button>
        </form>

>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
        <?php
        // Handling OTP verification
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["otp"])) {
            $otp = $_POST["otp"];
            $isValid = $google2fa->verifyKey($google_auth_secret, $otp); // Verify OTP
            
            if ($isValid) {
                echo "<p>Google Authentication setup successfully!</p>"; // Success message
            } else {
                echo "<p>Invalid OTP. Please try again.</p>"; // Error message
            }
        }
        ?>
    </div>
</body>
</html>
