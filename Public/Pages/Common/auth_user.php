<?php
session_start(); // Ensure the session is started
// //print_r($_SESSION);
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 1) {
    // The user is not logged in, redirect them to the login page, or handle accordingly
    header('Location: ../index.php/Login_to_CustCount'); // Redirect to the login page
    exit; // Prevent further script execution after redirect
}
