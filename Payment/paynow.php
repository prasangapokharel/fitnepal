<?php
include '../db_connection.php';
include 'session.php';

$khalti_public_key = "test_public_key_78040773c7cd4730922c28578dcd1772";

$error_message = "";
$price = 300; // Fixed price for this example
$user_id = $_SESSION['user_id'];
$token = "";

// Handle MPIN and Mobile number to get Khalti token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mobile']) && isset($_POST['mpin'])) {
        $mobile = $_POST['mobile'];
        $mpin = $_POST['mpin'];

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://khalti.com/api/v2/payment/initiate/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    "public_key" => $khalti_public_key,
                    "mobile" => $mobile,
                    "transaction_pin" => $mpin,
                    "amount" => $price * 100, // Amount in paisa
                    "product_identity" => "fixed_300_payment",
                    "product_name" => "Fixed 300 Payment",
                    "product_url" => "http://localhost/your_product_url/"
                ]),
            )
        );

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $parsed = json_decode($response, true);
        curl_close($curl);

        if ($http_status === 200 && isset($parsed['token'])) {
            $token = $parsed['token'];
        } else {
            // Error message based on the response
            $error_message = "Error with mobile or MPIN. Please check and try again.";
        }
    } elseif (isset($_POST['otp']) && isset($_POST['token'])) {
        $otp = $_POST['otp'];
        $token = $_POST['token'];

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://khalti.com/api/v2/payment/confirm/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    "public_key" => $khalti_public_key,
                    "confirmation_code" => $otp,
                    "token" => $token
                ]),
            )
        );

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $parsed = json_decode($response, true);
        curl_close($curl);

        if ($http_status === 200 && isset($parsed['token'])) {
            $payment_date = date('Y-m-d');
            $payment_mode = "Khalti";

            $sql_insert = "INSERT INTO payment_record (user_id, payment_date, paid_amount, payment_mode) 
                           VALUES (:user_id, :payment_date, :paid_amount, :payment_mode)";
            $stmt = $pdo->prepare($sql_insert);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':payment_date', $payment_date, PDO::PARAM_STR);
            $stmt->bindParam(':paid_amount', $price, PDO::PARAM_STR);
            $stmt->bindParam(':payment_mode', $payment_mode, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $error_message = "<span style='color:green'>Payment success!</span>";
            } else {
                $error_message = "Error recording payment in database.";
            }
        } else {
            $error_message = "Invalid OTP. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Now</title>
    <link rel="stylesheet" href="./CSSpayment/complete.css"> <!-- External CSS -->
</head>
<body>
    <div class="khalticontainer">
        <center>
            <img src="khalti.webp" alt="Khalti Logo" width="200"> <!-- Change the image as needed -->
        </center>
        
        <!-- Show error message if any -->
        <span style="color: red;">
            <?php echo $error_message; ?>
        </span>

        <?php if ($token == ""): ?>
        <form action="paynow.php" method="post">
            <div class="input-group">
                <label for="mobile">Mobile Number:</label>
                <input type="number" name="mobile" min="9800000000" max="9899999999" placeholder="98xxxxxxxx" required>
            </div>
            <div class="input-group">
                <label for="mpin">MPIN:</label>
                <input type="password" name="mpin" minlength="4" maxlength="6" placeholder="xxxx" required>
            </div>
            <button type="submit">Send OTP</button>
        </form>
        <?php else: ?>
        <form action="paynow.php" method="post">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="hidden" name="mpin" value="<?php echo $mpin; ?>">
            <div class="input-group">
                <label for="otp">OTP:</label>
                <input type="number" name="otp" placeholder="xxxx" required>
            </div>
            <button type="submit">Pay Rs. 300</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>


