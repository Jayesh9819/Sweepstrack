<?php
ob_start();

$servername = "199.231.187.147"; // or your server name
$username = "sweeps_trac";
$password = "Weblux@@1122";
$dbname = "sweeps_trac";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    return $conn;
}
