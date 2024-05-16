<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: rgb(55, 19, 138);
            margin: 0;
            padding: 0;
            justify-content: center;
            align-items: center;
           
        }

        .container {
            margin: 5% 20%;
            width: 900px;
            background-color: #ffffff;
            border: 0px solid rgb(55, 19, 138);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 20px;
        }

        .left-panel, .right-panel {
            flex: 1;
        }

        .left-panel {
            display: flex;
            flex-direction: column;
        }

        .left-panel h1 {
            color: rgb(55, 19, 138);
            text-align: center;
            margin-bottom: 20px;
        }

        .left-panel p {
            color: #378ce7;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
        }

        .form-group input {
            width: 400px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin: 0 40px;
        }

        .btn {
            background-color: #378ce7;
            color: #ffffff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: calc(100% - 20px);
            font-size: 16px;
            margin: 10px 10px 0 10px;
        }

        .btn:hover {
            background-color: rgb(55, 19, 138);
        }

        .right-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .result, .indicator {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background-color: #f3f9ff;
            border-left: 4px solid;
        }

        .result {
            border-color: #378ce7;
        }

        .indicator {
            border-color: rgb(55, 19, 138);
        }
        p{
            margin: auto 20%;
        }
    </style>
</head>
<body>
<?php
include 'navbar.php';
?>
    <div class="container">
        <div class="left-panel">
            <!-- <h1>BMI Calculator</h1> -->
            <form method="POST">
                <div class="form-group">
                    <label for="weight">Weight (kg):</label>
                    <input type="number" id="weight" name="weight" step="0.1" required>
                </div>
                <div class="form-group">
                    <label for="height">Height (cm):</label>
                    <input type="number" id="height" name="height" step="0.1" required>
                </div>
                <button type="submit" class="btn">Calculate BMI</button>
            </form>
        </div>

        <div class="right-panel">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $weight = $_POST['weight'];
                $height = $_POST['height'];

                // BMI Calculation
                $heightInMeters = $height / 100;
                $bmi = $weight / ($heightInMeters * $heightInMeters);

                // BMI Category
                $category = "";
                if ($bmi < 18.5) {
                    $category = "Underweight";
                } elseif ($bmi >= 18.5 && $bmi < 24.9) {
                    $category = "Normal weight";
                } elseif ($bmi >= 25 && $bmi < 29.9) {
                    $category = "Overweight";
                } else {
                    $category = "Obesity";
                }

                echo "<div class='result'>";
                echo "<h2>Your BMI: " . round($bmi, 1) . "</h2>";
                echo "<p>Category: " . $category . "</p>";
                echo "</div>";

                echo "<div class='indicator'>";
                echo "<h3>BMI Categories:</h3>";
                echo "<ul>";
                echo "<li>Underweight: BMI less than 18.5</li>";
                echo "<li>Normal weight: BMI 18.5–24.9</li>";
                echo "<li>Overweight: BMI 25–29.9</li>";
                echo "<li>Obesity: BMI 30 or greater</li>";
                echo "</ul>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <p>A BMI (Body Mass Index) calculator is a tool used to estimate a person’s body fat based on their height and weight. It is a widely used indicator of whether a person is underweight, normal weight, overweight, or obese.</p>
</body>
</html>
