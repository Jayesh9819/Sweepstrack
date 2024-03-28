<?php
$servername = "89.116.139.118"; // or your server name
$username = "jd";
$password = "Jayesh8169";
$dbname = "cc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    return $conn;
    // echo "success";
}
