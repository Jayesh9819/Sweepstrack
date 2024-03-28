<?php
session_start(); // Start the session

function setToast($type, $message)
{
    $_SESSION['toast'] = ['type' => $type, 'message' => $message];
}

include '../db/db_connect.php';

// Default redirect location set to the registration page for reattempt
$redirectTo = '../../index.php/Portal_Add_Users';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    $termsAccepted = isset($_POST['terms']) && $_POST['terms'] == 'on';

    // Validate inputs are not empty
    if (empty($fullname) || empty($username) || empty($password) || empty($role) || !$termsAccepted) {
        // Set error message and retain form values
        setToast('error', 'Please fill in all fields and accept the terms.');
        $_SESSION['form_values'] = $_POST;
        header('Location: ' . $redirectTo);
        exit();
    }

    // Check if username already exists
    $checkUsernameSql = "SELECT Username FROM users WHERE Username = ?";
    if ($stmt = $conn->prepare($checkUsernameSql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Username already exists
            setToast('error', 'Username already taken. Please choose another.');
            $_SESSION['form_values'] = $_POST;
            $stmt->close();
            header('Location: ' . $redirectTo);
            exit();
        }
        $stmt->close();
    } else {
        setToast('error', 'Error preparing statement: ' . $conn->error);
        header('Location: ' . $redirectTo);
        exit();
    }

    // Proceed with database insertion since validation passed
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (Username, Fullname, Password, RawPass, Role, CreatedAt, LastLogin) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $username, $fullname, $hashed_password, $password, $role);

        if ($stmt->execute()) {
            setToast('success', 'New record created successfully.');
            $redirectTo = '../../index.php/Portal_Add_Users'; // Success: Redirect to the home page or dashboard
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
