<?php
session_start();
include '../db/db_connect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("INSERT INTO notes (tittle, content, by_username, unique_id) VALUES (?, ?, ?, ?)");

    $title = $_POST['title'];
    $content = $_POST['content'];
    $by_role = $_SESSION['role']; // Assuming you have this set in your session
    $unique_id = sprintf("%04d", mt_rand(0, 9999)); // Generates a 4-digit random number

    $stmt->bind_param("ssss", $title, $content, $username, $unique_id);

    // Execute and check
    if ($stmt->execute()) {
        // Success toast message
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'New note added successfully.'];
        header("location: ../../index.php/Portal_Notes"); // Adjust the redirect location as needed
        exit();
    } else {
        // Error toast message
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error adding note: ' . $stmt->error];
        header("location: ../../index.php/Portal_Notes"); // Adjust the redirect location as needed
        exit();
    }

    // Close connections
    $stmt->close();
    $conn->close();
} else {
    // Redirect if the form wasn't submitted
    header("location: ../../index.php/Portal_Notes"); // Adjust the redirect location as needed
    exit();
}
