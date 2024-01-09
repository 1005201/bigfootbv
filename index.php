<?php
include('database.php');

$csv = file_get_contents('bigfootbv.csv');
$lines = explode(PHP_EOL, $csv);

// Initialize arrays to store the separated categories
$brands = [];
$models = [];
$sizes = [];

// Iterate through the array starting from the second element (skip the header).
for ($i = 1; $i < count($lines); $i++) {
    $parts = explode('%', $lines[$i]);

    if (count($parts) >= 3) {
        // Extract brand, model, and size
        $brand = $parts[0];
        $model = $parts[1];
        $size = $parts[2];

        // Add each category to its respective array
        $brands[] = $brand;
        $models[] = $model;
        $sizes[] = $size;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bigfootbv</title>
    <style>
        * {
            box-sizing: border-box;
        }
    </style>
    <script>
        // JavaScript function to populate the model dropdown based on the selected brand
        function populateModels() {
            var brandSelect = document.getElementById("brandSelect");
            var modelSelect = document.getElementById("modelSelect");

            // Clear the existing options
            modelSelect.innerHTML = "<option value=''>Select a Model</option>";

            // Get the selected brand
            var selectedBrand = brandSelect.value;

            // Populate the model dropdown with models corresponding to the selected brand
            for (var i = 0; i < <?php echo json_encode($brands); ?>.length; i++) {
                if (<?php echo json_encode($brands); ?>[i] === selectedBrand) {
                    var modelOption = document.createElement("option");
                    modelOption.text = <?php echo json_encode($models); ?>[i];
                    modelOption.value = <?php echo json_encode($models); ?>[i];
                    modelSelect.appendChild(modelOption);
                }
            }
        }
    </script>
</head>
<body>
    <form action="" method="post">
        <label for="brandSelect">Kies een merk:</label>
        <select id="brandSelect" name="selectedBrand" onchange="populateModels()">
            <option value="">Kies een merk</option>
            <?php
            $uniqueBrands = array_unique($brands);
            foreach ($uniqueBrands as $brand) {
                echo "<option value='" . $brand . "'>" . $brand . "</option>";
            }
            ?>
        </select>

        <label for="modelSelect">Kies een model:</label>
        <select id="modelSelect" name="selectedModel">
            <option value="">Kies een model</option>
        </select>
        <input type="submit" value="Verzend">
    </form>
    <br>
    <!-- Display selected sizes -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedBrand = $_POST["selectedBrand"];
        $selectedModel = $_POST["selectedModel"];

        echo "<div>Sizes for Brand: $selectedBrand and Model: $selectedModel</div>";
        echo "<ul>";
        for ($i = 0; $i < count($sizes); $i++) {
            if ($brands[$i] == $selectedBrand && $models[$i] == $selectedModel) {
                echo "<li>" . $sizes[$i] . "</li>";
            }
        }
        echo "</ul>";
    }
    ?>
</body>
</html>
