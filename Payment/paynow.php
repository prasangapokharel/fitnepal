<?php
// Include necessary files
include '../db_connection.php';
include 'session.php';

// Khalti keys
$khalti_public_key = "test_public_key_09903c0b42f0481e9f450aab5b14300b"; // Your test public key
$khalti_secret_key = "test_secret_key_7da14cd84ffe4be2b188ba28c84cb52f"; // Your test secret key

$error_message = "";
$success_message = "";
$price = 10; // Fixed price for this example
$amount_in_paisa = $price * 100; // Khalti requires amount in paisa
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
                    "amount" => $amount_in_paisa,
                    "product_identity" => "fixed_10_payment",
                    "product_name" => "Fixed 10 Payment",
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
            $error_message = isset($parsed['detail']) ? $parsed['detail'] : "Error with mobile or MPIN. Please check and try again.";
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
<<<<<<< HEAD
            // Success - update bankpayment table
=======
            // Success - insert into payment_record table
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
            $payment_date = date('Y-m-d');
            $status = "verified";
            $sql_update = "UPDATE bankpayment SET status = :status WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql_update);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $success_message = "<span style='color:green'>Payment success!</span>";
            } else {
                $error_message = "Error updating payment status in database.";
            }
        } else {
            $error_message = isset($parsed['detail']) ? $parsed['detail'] : "Invalid OTP. Please try again.";
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
    <link rel="stylesheet" href="./CSSpayment/cmplete.css"> <!-- External CSS -->
    <style>
        /* External CSS for better UI */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .khalticontainer {
            width: 500px; /* Increased width */
            border: 2px solid #5C2D91; /* Khalti's brand color */
            margin: 50px auto; /* Center the form horizontally with top margin */
            padding: 30px; /* Increased padding inside the container */
            border-radius: 10px; /* Rounded corners */
            background-color: white; /* White background */
            text-align: center; /* Center the content */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Shadow for better visual effect */
        }
        label {
            text-align: left;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .input-group {
            margin-bottom: 20px; /* Increase space between input groups */
            text-align: left; /* Align text to the left */
        }
        .input-group input {
            width: 100%; /* Full width */
            padding: 12px; /* Increased padding */
            border: 1px solid #ccc; /* Light border */
            border-radius: 5px; /* Rounded corners */
            transition: border-color 0.3s; /* Smooth transition */
            font-size: 16px; /* Larger font size for better readability */
        }
        .input-group input:focus {
            border-color: #5C2D91; /* Khalti's brand color on focus */
            outline: none; /* Remove default outline */
        }
        button {
            padding: 12px 24px; /* Increased padding for the button */
            background-color: #5C2D91; /* Khalti's brand color */
            color: white; /* White text */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
            transition: background-color 0.3s; /* Smooth transition */
        }
        button:hover {
            background-color: #4b237a; /* Darker Khalti brand color on hover */
        }
        button:disabled {
            background-color: #ddd; /* Disabled button color */
            cursor: not-allowed; /* Cursor for disabled button */
        }
        .error-message {
            color: red; /* Error color */
            margin-bottom: 10px; /* Space before the form */
        }
        .success-message {
            color: green; /* Success color */
            margin-bottom: 10px; /* Space before the form */
        }
        .amount-display {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="khalticontainer">
        <center>
            <img src="khalti.webp" alt="Khalti Logo" width="200"> <!-- Khalti logo -->
        </center>
        
        <!-- Display error or success message -->
<<<<<<< HEAD
        <span class="error-message">
=======
        <span style="color: red;">
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
            <?php echo $error_message; ?>
        </span>
        <span class="success-message">
            <?php echo $success_message; ?>
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
            <div class="amount-display">Amount: Rs. 10</div>
            <button type="submit">Send OTP</button>
        </form>
        <?php else: ?>
        <form action="paynow.php" method="post">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <div class="input-group">
                <label for="otp">OTP:</label>
                <input type="number" name="otp" placeholder="xxxx" required>
            </div>
<<<<<<< HEAD
            <div class="amount-display">Amount: Rs. 10</div>
            <button type="submit">Pay Rs. 10</button>
=======
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
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
        </form>
    </div>
</body>
</html>
<<<<<<< HEAD
=======

        <?php endif; ?>
    </div>
</body>
</html>
>>>>>>> 82656c606da72bb3beed5af550667ce76c420839
