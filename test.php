<?php
// Start the session
session_start();

// Function to echo the script for toastr
function echoToastScript($type, $message)
{
    echo "<script type='text/javascript'>document.addEventListener('DOMContentLoaded', function() { toastr['$type']('$message'); });</script>";
}

// Check if there's a toast message set in session, display it, then unset
if (isset($_SESSION['toast'])) {
    $toast = $_SESSION['toast'];
    echoToastScript($toast['type'], $toast['message']);
    unset($_SESSION['toast']); // Clear the toast message from session
}

// Check if user is logged in and has required role
$role = $_SESSION['role'];
if (!in_array($role, ['Agent', 'Supervisor', 'Manager', 'Admin'])) {
    // Redirect user to login page if not logged in or does not have required role
    header('Location: ./Login_to_CustCount'); // Replace 'login.php' with the path to your login page
    exit(); // Prevent further execution of the script
}

// Handle form submission to change timezone
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['timezone'])) {
    $selectedTimezone = $_POST['timezone'];
    // Set the default timezone to the selected timezone
    date_default_timezone_set($selectedTimezone);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timestamps in Selected Timezone</title>
</head>
<body>
    <!-- Your HTML content goes here -->
    <h1>Timestamps in Selected Timezone</h1>
    
    <!-- Form to select timezone -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="timezone">Select Timezone:</label>
        <select name="timezone" id="timezone">
            <option value="America/New_York">America/New York</option>
            <option value="Europe/London">Europe/London</option>
            <option value="Asia/Tokyo">Asia/Tokyo</option>
            <!-- Add more timezone options as needed -->
        </select>
        <button type="submit">Set Timezone</button>
    </form>
    
    <!-- Display timestamps converted to selected timezone -->
    <?php
    // Example timestamps (replace with your actual timestamps)
    $timestamps = array('2024-03-15 12:30:00', '2024-03-16 08:45:00', '2024-03-17 15:20:00');

    // Iterate over each timestamp and convert it to selected timezone
    foreach ($timestamps as $timestamp) {
        // Create a DateTime object with the original timestamp and set the timezone
        $dateTime = new DateTime($timestamp, new DateTimeZone('UTC'));
        $dateTime->setTimezone(new DateTimeZone($selectedTimezone));
        
        // Get the converted timestamp
        $convertedTimestamp = $dateTime->format('Y-m-d H:i:s');
        
        // Output original and converted timestamps
        echo "<p>Original Timestamp: $timestamp</p>";
        echo "<p>Converted Timestamp (Selected Timezone): $convertedTimestamp</p>";
    }
    ?>
</body>
</html>
