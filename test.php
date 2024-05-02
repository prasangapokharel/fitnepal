
<?php
include 'header\header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comparison Cards</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            display: flex; /* Use Flexbox for horizontal layout */
            justify-content: space-between; /* Ensure space between the cards */
            gap: 16px; /* Space between the cards */
            padding: 20px; /* Padding around the container */
        }

        .card {
            flex: 1; /* Equal width for both cards */
            border: 1px solid #ccc; /* Light gray border */
            border-radius: 8px; /* Rounded corners */
            background-color: #f9f9f9; /* Light background color */
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
            padding: 20px; /* Padding inside each card */
            text-align: left; /* Left-align text content */
        }

        .card h3 {
            margin: 0 0 12px 0; /* Space below the title */
            color: #333; /* Title text color */
            font-size: 1.5rem; /* Increase font size */
        }

        .card ul {
            list-style-type: disc; /* Bullet points for list items */
            padding-left: 20px; /* Indent for the bullet points */
        }

        .card li {
            margin-bottom: 8px; /* Space between list items */
            color: #555; /* Slightly darker text for list items */
        }

        .card button {
            margin-top: 16px; /* Space above the button */
            padding: 10px 20px; /* Padding around the button */
            border: none; /* No border */
            border-radius: 4px; /* Slightly rounded corners */
            background-color: #007bff; /* Blue background */
            color: white; /* White text */
            font-size: 1rem; /* Button text size */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Smooth transition */
        }

        .card button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .card .free-symbol {
            color: green; /* Green color for 'Free' text */
            font-weight: bold; /* Bold text */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h3>Free Plan</h3>
            <ul>
                <li>Basic Features</li>
                <li>Limited Support</li>
                <li>5 GB Storage</li>
            </ul>
            <button>
                <span class="free-symbol">Free</span>
            </button>
        </div>

        <div class="card">
            <h3>Pro Plan</h3>
            <ul>
                <li>All Free Features</li>
                <li>24/7 Support</li>
                <li>Unlimited Storage</li>
                <li>Advanced Analytics</li>
            </ul>
            <button>
                Upgrade Now
            </button>
        </div>
    </div>
</body>
</html>
