<?php
session_start();
// Assuming you have a database connection setup
include '../db/db_connect.php';

// Retrieve the message from the Ajax request
$message = $_POST['message'];
// For testing purposes only, remove this line in production
$_SESSION['msgg'] = $message;

// Assuming $_SESSION['userid'] contains the ID of the sender
$sender_id = $_SESSION['userid'];
$receiver_id = 15; // Assuming the receiver's ID is known and static in this example
$status = 1; // Example status, adjust based on your application's logic

// Prepare the SQL query to insert the new record into the chat_records table
$query = "INSERT INTO chat_records (sender_id, receiver_id, status, chat_msg) VALUES (?, ?, ?, ?)";

// Prepare the statement
if ($stmt = $conn->prepare($query)) {
    // Bind the parameters
    $stmt->bind_param("iiis", $sender_id, $receiver_id, $status, $message);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "Message sent successfully";
    } else {
        echo "Error sending message: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Close the connection
$conn->close();
?>
