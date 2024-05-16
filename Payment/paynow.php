<?php
// Include necessary files
include '../db_connection.php';
include 'session.php';

// Khalti keys
$khalti_public_key = "test_public_key_09903c0b42f0481e9f450aab5b14300b"; // Your test public key
$khalti_secret_key = "test_secret_key_7da14cd84ffe4be2b188ba28c84cb52f"; // Your test secret key

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
        // Handle OTP verification
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
                    'Authorization: Key ' . $khalti_secret_key, // Secret key for confirmation
                ),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    "public_key" => $khalti_public_key,
                    "confirmation_code" => $otp,
                    "token" => $token,
                ]),
            )
        );

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $parsed = json_decode($response, true);
        curl_close($curl);

        if ($http_status === 200 && isset($parsed['token'])) {
            // Success - insert into payment_record table
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
<?php
// include '../header/header.php';

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
        
        <!-- Display error or success message -->
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
            <div class="input-group">
                <label for="otp">OTP:</label>
                <input type="number" name="otp" placeholder="xxxx" required>
            </div>
            <button type="submit">Pay Rs. 300</button>
        </form><?php
// Include necessary files
include '../db_connection.php';
include 'session.php';

// Khalti public key for payment initiation
$khalti_public_key = "test_public_key_09903c0b42f0481e9f450aab5b14300b"; // Replace with your Khalti public key

$error_message = "";
$success_message = "";
$price = 300; // Fixed price in rupees
$amount_in_paisa = $price * 100; // Khalti requires amount in paisa
$user_id = $_SESSION['user_id']; // User ID from session

// Initiate Khalti payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['initiate_payment'])) {
        $curl = curl_init();
        
        // Create a unique product ID for this transaction
        $purchase_order_id = "Order_" . time(); // Unique identifier for this payment
        $product_name = "Service Fee"; // Name of the product/service
        $return_url = "http://localhost/your_success_page/"; // URL to redirect after successful payment
        $website_url = "http://localhost/your_website/"; // Your website URL

        // Customer information (can be retrieved from the database)
        $customer_info = [
            "name" => "Customer Name", // Customer's name
            "email" => "customer@example.com", // Customer's email
            "phone" => "9800000001" // Customer's phone number
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Key ' . $khalti_public_key // Use Khalti public key
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                "return_url" => $return_url,
                "website_url" => $website_url,
                "amount" => $amount_in_paisa,
                "purchase_order_id" => $purchase_order_id,
                "purchase_order_name" => $product_name,
                "customer_info" => $customer_info
            ])
        ]);

        $response = curl_exec($curl);
        $parsed = json_decode($response, true);
        curl_close($curl);

        if (isset($parsed['payment_url'])) {
            // Redirect user to the payment URL
            header("Location: " . $parsed['payment_url']);
            exit;
        } else {
            // Handle errors
            $error_message = isset($parsed['detail']) ? $parsed['detail'] : "Error initiating payment. Please try again.";
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
            <img src="khalti.webp" alt="Khalti Logo" width="200"> <!-- Khalti logo -->
        </center>
        
        <!-- Display error message if any -->
        <span style="color: red;">
            <?php echo $error_message; ?>
        </span>
        
        <!-- Display success message if any -->
        <span style="color: green;">
            <?php echo $success_message; ?>
        </span>
        
        <!-- Payment initiation form -->
        <form action="paynow.php" method="post">
            <input type="hidden" name="initiate_payment" value="1"> <!-- Hidden input to trigger payment -->
            <button type="submit">Initiate Khalti Payment</button>
        </form>
    </div>
</body>
</html>

        <?php endif; ?>
    </div>
</body>
</html>
