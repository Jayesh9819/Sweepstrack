<?php
session_start();
include '../db/db_connect.php';

if (isset($_GET['id'])) {
    $noteId = $conn->real_escape_string($_GET['id']);

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM notes WHERE nid = ?");
    $stmt->bind_param("i", $noteId);

    // Execute and check
    if ($stmt->execute()) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Note successfully deleted'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error deleting note'];
    }

    // Close connections
    $stmt->close();
    $conn->close();
} else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid request'];
}

header("Location: ../../index.php/Portal_Notes"); // Redirect back to the notes page
exit();
