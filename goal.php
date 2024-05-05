<?php
include 'session.php'; // Include the session check
include 'db_connection.php';
?>

<?php
include 'header\header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protein Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./CSS/goal.css">

</head>


<body>

    <div class="container" id="container">
        <h2 style="text-align: center; color: black;">Protein Tracker</h2>

        <form id="proteinForm">
            <label for="mealName">Meal Name:</label>
            <!-- Updated meal name input field -->
            <input type="text" id="mealName" name="mealName" onclick="populateFoodOptions(); fetchProteinGrams();" required>

            <label for="proteinGrams">Protein Grams:</label>
            <input type="text" id="proteinGrams" name="proteinGrams" required>

            <label for="mealTime">Meal Time:</label>
            <input type="time" id="mealTime" name="mealTime" required>

            <button type="button" onclick="addEntry()">Add Entry</button>
            <button type="button" onclick="saveToPDF()">Save to PDF</button>
        </form>

        <br>
        <hr style="border-color: #67C6E3;">

        <table>
            <thead>
                <tr>
                    <th>Meal Name</th>
                    <th>Protein Grams</th>
                    <th>Meal Time</th>
                </tr>
            </thead>
            <tbody id="trackerTableBody">
                <!-- Entries will be added here -->
            </tbody>
        </table>

        <p>Total Protein Grams: <span id="totalProteinGrams">0</span></p>

    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
    <script>
        // Function to populate food options when meal name input field is clicked
        function populateFoodOptions() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var foodOptions = JSON.parse(xhr.responseText);
                    var mealNameInput = document.getElementById('mealName');
                    mealNameInput.setAttribute('list', 'foodList');
                    var datalist = document.createElement('datalist');
                    datalist.id = 'foodList';
                    for (var i = 0; i < foodOptions.length; i++) {
                        var option = document.createElement('option');
                        option.value = foodOptions[i].Food_name;
                        datalist.appendChild(option);
                    }
                    mealNameInput.parentNode.appendChild(datalist);
                }
            };
            xhr.open("GET", "food.php", true);
            xhr.send();
        }
        // Function to fetch protein grams when a food item is selected
        function fetchProteinGrams() {
            var selectedFood = document.getElementById('mealName').value;
            if (selectedFood) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var foodData = JSON.parse(xhr.responseText);
                        var proteinGramsInput = document.getElementById('proteinGrams');
                        // Find the selected food item in the food data
                        var selectedFoodData = foodData.find(function(item) {
                            return item.Food_name === selectedFood;
                        });
                        // Update the protein grams input field with the protein value of the selected food
                        if (selectedFoodData) {
                            proteinGramsInput.value = selectedFoodData.Protein;
                        } else {
                            // If the selected food is not found in the data, clear the input field
                            proteinGramsInput.value = '';
                        }
                    }
                };
                xhr.open("GET", "food.php", true);
                xhr.send();
            }
        }




        var totalProteinGrams = 0;

        function addEntry() {
            var mealName = document.getElementById('mealName').value;
            var proteinGrams = parseFloat(document.getElementById('proteinGrams').value) || 0;
            var mealTime = document.getElementById('mealTime').value;

            if (mealName && mealTime) {
                var tableBody = document.getElementById('trackerTableBody');
                var newRow = tableBody.insertRow(tableBody.rows.length);

                var cell1 = newRow.insertCell(0);
                var cell2 = newRow.insertCell(1);
                var cell3 = newRow.insertCell(2);

                cell1.innerHTML = mealName;
                cell2.innerHTML = proteinGrams;
                cell3.innerHTML = mealTime;

                totalProteinGrams += proteinGrams;
                document.getElementById('totalProteinGrams').textContent = totalProteinGrams;

                document.getElementById('mealName').value = '';
                document.getElementById('proteinGrams').value = '';
                document.getElementById('mealTime').value = '';

                // Send the data to the server to save the entry
                saveEntryToServer(mealName, proteinGrams, mealTime);
            } else {
                alert('Please enter meal name and meal time.');
            }
        }

        function saveEntryToServer(mealName, proteinGrams, mealTime) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "save_entry.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Handle the response if needed
                    console.log(xhr.responseText);
                }
            };
            var params = "mealName=" + encodeURIComponent(mealName) +
                "&proteinGrams=" + proteinGrams +
                "&mealTime=" + encodeURIComponent(mealTime);
            xhr.send(params);
        }

        function saveToPDF() {
            var element = document.querySelector('table');
            html2pdf().from(element).save('prasanga_pdf');
        }
    </script>

</body>

</html>