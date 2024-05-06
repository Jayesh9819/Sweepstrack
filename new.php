<?php
// Set appropriate headers for SSE
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Access-Control-Allow-Origin: *'); // Enable CORS if needed

// Database connection
include './App/db/db_connect.php';

// Debugging: Log that the script has started
error_log("Started SSE script");

// Fetch transactions with created_at in the last minute
$sql = "SELECT username, redeem FROM transaction WHERE created_at >= NOW() - INTERVAL 1 MINUTE";
$result = $conn->query($sql);

$notifications = [];

if ($result->num_rows > 0) {
    // Debugging: Log the number of rows fetched
    error_log("Fetched " . $result->num_rows . " rows");

    // Generate notification message for each transaction
    while ($row = $result->fetch_assoc()) {
        $username = $row['username'];
        $redeemAmount = $row['redeem'];
        $notificationMessage = "You have a new redeem request from $username for amount $redeemAmount";
        $notifications[] = $notificationMessage;
    }
} else {
    // Debugging: Log no transactions found
    error_log("No new transactions found");
    $notifications[] = "No new transactions"; // Send a message for testing purposes
}

$conn->close();

foreach ($notifications as $notification) {
    // Debugging: Log each notification being sent
    error_log("Sending notification: " . $notification);

    echo "data: " . json_encode($notification) . "\n\n";
    flush(); // Flush the output buffer to send the response immediately
    sleep(1); // Sleep for 1 second between events (adjust as needed)
}
?>
