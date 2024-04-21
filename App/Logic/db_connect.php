<?php
ob_start();

$servername = "localhost"; // or your server name
$username = "sweepstrac";
$password = "12345678";
$dbname = "sweepstrac";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    return $conn;
}
