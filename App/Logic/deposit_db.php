<?php
session_start(); // Start the session

include '../db/db_connect.php'; // Include your database connection

// Function to set a toast message
function setToast($type, $message)
{
    $_SESSION['toast'] = ['type' => $type, 'message' => $message];
}

// Default redirect location set to the deposit page for reattempt
$redirectTo = '../../index.php/Portal_Add_Deposit'; // Adjust the path as needed

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = trim($_POST['inputname']);
    $userId = trim($_POST['inputid']);
    $reflectAmount = trim($_POST['inputreflect']);
    $bonusAmount = trim($_POST['inputBonus']);
    $platform = trim($_POST['inputPlatform']);
    $password = trim($_POST['inputPassword4']); // Remember to hash before inserting into the database
    $money = trim($_POST['money']); // Assuming 'money' is the name attribute for the money input
    $byUsername = $_SESSION['username'];
    $byRole = $_SESSION['role'];

    // Validate inputs are not empty
    if (empty($name) || empty($userId) || empty($reflectAmount) || empty($bonusAmount) || empty($platform) || empty($password) || empty($money)) {
        setToast('error', 'Please fill in all fields.');
        $_SESSION['form_values'] = $_POST;
        header('Location: ' . $redirectTo);
        exit();
    }

    // Additional validation can go here (e.g., password strength, valid username/email)

    // Proceed with database insertion since validation passed
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $sql = "INSERT INTO deposits (name, user_id, reflect_amount, bonus_amount, platform, password, money, by_username, by_role, added_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssss", $name, $userId, $reflectAmount, $bonusAmount, $platform, $password, $money, $byUsername, $byRole);

        if ($stmt->execute()) {
            setToast('success', 'Deposit successful.');
            $redirectTo = '../../index.php/Portal_Add_Deposit'; // Adjust as needed, perhaps to a success page or dashboard
        } else {
            setToast('error', 'Error: ' . $stmt->error);
        }
        $stmt->close();
    } else {
        setToast('error', 'Error preparing statement: ' . $conn->error);
    }
    $conn->close();
} else {
    setToast('error', 'Invalid request method.');
}

// Redirect based on the outcome
header('Location: ' . $redirectTo);
exit();
