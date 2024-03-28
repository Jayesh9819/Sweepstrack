<?php
$servername = "193.203.184.53"; // or your server name
$username = "u306273205_cc";
$password = "Weblux@@1122";
$dbname = "u306273205_cc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    return $conn;
    // echo "success";
}
