<?php 
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Assuming you already have a connection to your database
include '../db/db_connect.php'; // Ensure you have this file with proper DB connection

$name = $_POST['name'];
$referCode = $_POST['refercode'] ?? null; // Using null coalescing operator for optional field

// Generate random 3-digit number
$randomNumber = rand(100, 999);

// SQL to insert data
$stmt = $conn->prepare("INSERT INTO unknown_users (username, Refer) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $referCode);
$result = $stmt->execute();

if ($result) {
    
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $name;
    $_SESSION['role'] = 'query';
    $_SESSION['user_id'] = $randomNumber;
    $_SESSION['id'] = $randomNumber;
    $_SESSION['username'] = $name;

    // Store the random number in session

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
