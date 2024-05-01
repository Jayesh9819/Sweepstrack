<?php
include "./App/db/db_connect.php";
$sql = "SELECT * FROM websetting";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$results = $result->fetch_all(MYSQLI_ASSOC);

// Create an associative array to hold the settings
$settings = array();

// Populate the settings array with name-value pairs from the database
foreach ($results as $row) {
    $settings[$row['name']] = $row['value'];
}

// Now you can access any setting by its name
// Example usage:
// if (isset($settings['name'])) {
//     echo "<h1>{$settings['name']}</h1>"; // Displaying the name
// }
// if (isset($settings['logo'])) {
//     echo "<img src='path/to/logos/{$settings['logo']}' alt='Website Logo'>"; // Displaying the logo
// }
// ?>
