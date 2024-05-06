<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../db/db_connect.php'; // Ensure you have this file with proper DB connection

header('Content-Type: application/json');

// Assuming you have a user session with user_id stored
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM notification WHERE for_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

if (count($notifications) > 0) {
    echo json_encode($notifications);
} else {
    echo json_encode(['content' => 'No new notification']);
}
