<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Assuming you already have a connection to your database
include '../db/db_connect.php'; // Ensure you have this file with proper DB connection

$name = $_POST['name'];
$referCode = $_POST['refercode'] ?? null; // Using null coalescing operator for optional field
$role = 'query';
$pagename = 'From Login Page';
// Generate random 3-digit number
$randomNumber = rand(1000, 99999);
$id = 'UT' . $randomNumber;

// SQL to insert data
$stmt = $conn->prepare("INSERT INTO unknown_users (username,Refer, id, role, pagename, last_seen) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssss", $name, $referCode, $id, $role, $pagename);
$result = $stmt->execute();

if ($result) {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $name;
    $_SESSION['role'] = $role;
    $_SESSION['user_id'] = $id;
    $_SESSION['id'] = $id;

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
