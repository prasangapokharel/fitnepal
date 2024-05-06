<!-- checkoutpage.php -->
<?php
include '../db_connection.php';

include '../header/header.php'; // Include your site's header ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="./CSSpayment/details.css"> <!-- External CSS -->
</head>
<body>
    <div class="container">
        <!-- Fixed public account information section -->
        <div class="public-account">
            <h2>Company Public Account Information</h2>
            <div class="account-details">
                <p><strong>Account Name:</strong> Ashish Khadka</p>
                <p><strong>Account No:</strong> 8856798223</p>
                <p><strong>Branch:</strong> Itahari</p>
            </div>
        </div>

        <hr> <!-- Horizontal line to separate sections -->

        <!-- User input section -->
        <div class="user-input">
            <h3>Transaction Information</h3>
            <form method="post" enctype="multipart/form-data">
                <label for="holderName">Account Holder Name:</label>
                <input type="text" id="holderName" name="holderName" placeholder="Enter your name" required>

                <label for="accountNumber">Account Number:</label>
                <input type="text" id="accountNumber" name="accountNumber" placeholder="Your account number" required>

                <label for="branch">Branch:</label>
                <input type="text" id="branch" name="branch" placeholder="Enter your branch" required>

                <label for="transactionHash">Transaction Hash:</label>
                <input type="text" id="transactionHash" name="transactionHash" placeholder="Enter transaction hash" required>

                <label for="transactionScreenshot">Transaction Screenshot:</label>
                <input type="file" id="transactionScreenshot" name="transactionScreenshot" accept="image/*" required>

                <p><strong>Amount:</strong> 300 Rs</p> <!-- Fixed amount -->

                <button type="submit" class="purchase-button">Purchase</button>
            </form>
        </div>
    </div>
</body>
</html>
