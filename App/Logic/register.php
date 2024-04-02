<?php
ob_start();

session_start(); // Start the session
include "creation.php";

function setToast($type, $message)
{
    $_SESSION['toast'] = ['type' => $type, 'message' => $message];
}

include '../db/db_connect.php';

// Default redirect location set to the registration page for reattempt
$redirectTo = '../../index.php/Register_to_CustCount';
$action = $_GET['action'];
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && $action == "register") {
    print_r($_POST);
    // Retrieve and sanitize form data
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $ref = isset($_POST['rfc']) ? $_POST['rfc'] : null;
    $refercode = generateReferralCode($fullname, $conn);
    
    $role = trim($_POST['role']);
    $referby=getUsernameByReferralCode($conn,$ref);

    $termsAccepted = isset($_POST['terms']) && $_POST['terms'] == 'on';

    // Additional fields
    $fbLink = isset($_POST['fb_link']) ? $_POST['fb_link'] : null;
    $pageId = isset($_POST['page']) ? $_POST['page'] : null;
    // $branchname = trim($_POST['branchname']);
    $by_u = $_SESSION['username'];
    $branchId = "";
    if (isset($_POST['branchname']) && $_POST['branchname'] !== '') {
        // If branchname is provided, sanitize and set the branchId
        $branchId = $_POST['branchname'];
    } else {
        // If branchname is not provided, fetch the branchId based on pageId
        $creationInstance = new Creation($conn);
        $branchId = $creationInstance->getBranchNameByPageName($pageId, $conn);
    }
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Validate inputs are not empty
    if (empty($fullname) || empty($username) || empty($role)) {
        // Set error message and retain form values
        setToast('error', 'Please fill in all required fields and accept the terms.');
        $_SESSION['form_values'] = $_POST;
        print_r($_POST);
        header('Location: ' . $redirectTo);
        exit();
    }

    // Check if username already exists
    $checkUsernameSql = "SELECT username FROM user WHERE username = ?";
    if ($stmt = $conn->prepare($checkUsernameSql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            setToast('error', 'Username already taken. Please choose another.');
            $_SESSION['form_values'] = $_POST;
            print_r($_POST);

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

    // Define additional fields' values
    $status = '1'; // Replace with actual value
    // Replace with actual value

    // Proceed with database insertion since validation passed
    $sql = "INSERT INTO user (name, username, `Fb-link`,refer_by, pagename, branchname, ip_address, password,refer_code, status, role, `by`, last_login, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?,?, ?,?, ?, ?, ?, ?, NOW(), NOW(), NOW())";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssssssss", $fullname, $username, $fbLink,$referby, $pageId, $branchId, $ipAddress, $password, $refercode, $status, $role, $by_u);

        if ($stmt->execute()) {
            setToast('success', 'New record created successfully.');
            if($role=='User'){
            processReferralCode($conn,$username,$ref);}

            $redirectTo = '../../index.php/Portal'; // Success: Redirect to the home page or dashboard
        } else {
            setToast('error', 'Error: ' . $stmt->error);
        }
        $stmt->close();
    } else {
        setToast('error', 'Error preparing statement: ' . $conn->error);
    }
    $conn->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $action == "editregister") {
    print_r($_POST);
    $fullname = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $role = trim($_POST['role']);
    $termsAccepted = isset($_POST['terms']) && $_POST['terms'] == 'on';

    // Additional fields
    $fbLink = trim($_POST['fb_link']);
    $pageId = trim($_POST['page']);
    // $branchname = trim($_POST['branchname']);
    $by_u = $_SESSION['username'];

    if (isset($_POST['branchname']) && $_POST['branchname'] !== '') {
        // If branchname is provided, sanitize and set the branchId
        $branchId = $_POST['branchname'];
    } else {
        // If branchname is not provided, fetch the branchId based on pageId
        $creationInstance = new Creation($conn);
        $branchId = $creationInstance->getBranchNameByPageName($pageId, $conn);
    }
    // Get the user's IP address
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $condition_value = $username;

    // Validate inputs are not empty
    if (empty($fullname) || empty($username) || empty($role)) {
        // Set error message and retain form values
        setToast('error', 'Please fill in all required fields and accept the terms.');
        $_SESSION['form_values'] = $_POST;
        print_r($_POST);
        header('Location: ' . $redirectTo);
        exit();
    }


    $status = '1'; // Replace with actual value
    $update_sql = "UPDATE user SET name = ?, `Fb-link` = ?, pagename = ?, branchname = ?, ip_address = ?, password = ?, status = ?, role = ?, `by` = ?, updated_at = NOW() WHERE username = ?";

    if ($update_stmt = $conn->prepare($update_sql)) {
        // Assuming $condition_value holds the value for the condition
        $update_stmt->bind_param("ssssssssss", $fullname, $fbLink, $pageId, $branchId, $ipAddress, $password, $status, $role, $by_u, $condition_value);

        if ($update_stmt->execute()) {
            setToast('success', 'Record updated successfully.');
            $redirectTo = '../../index.php/Portal_User_Management'; // Success: Redirect to the home page or dashboard
        } else {
            setToast('error', 'Error: ' . $update_stmt->error);
        }
        $update_stmt->close();
    } else {
        setToast('error', 'Error: ' . $conn->error);
    }
    $conn->close();
} else {
    setToast('error', 'Invalid request method.');
}

// Redirect based on the outcome
header('Location: ' . $redirectTo);
exit();
function generateReferralCode($name, $conn)
{
    // Get current date
    $currentDate = date('ymd'); // Format: yymmdd

    // Get the first three letters of the name
    $namePrefix = substr(strtoupper($name), 0, 3); // Convert name to uppercase and get first three letters

    // Initialize count
    $count = 1; // Default count
    $referralCode = "{$namePrefix}{$currentDate}"; // Initial referral code

    // Check if referral code already exists for the current date
    while (referralCodeExists($referralCode, $conn)) {
        // Increment count and append it to the referral code
        $count++;
        $referralCode = "{$namePrefix}{$currentDate}{$count}";
    }


    return $referralCode;
}

// Function to check if a referral code already exists in the database
function referralCodeExists($referralCode, $conn)
{
    $sql = "SELECT COUNT(*) AS count FROM user WHERE refer_code = '$referralCode'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'] > 0; // If count > 0, referral code exists; otherwise, it doesn't
}
function getUsernameByReferralCode($conn, $referralCode)
{
    $referralCode = mysqli_real_escape_string($conn, $referralCode);
    $query = "SELECT username FROM user WHERE refer_code = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $referralCode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['username']; // Return the username for the given referral code
    } else {
        return null; // Referral code does not exist
    }
}

function processReferralCode($conn, $name, $referralCode) {
    // Fetch the username of the person who is registering
    $userName = mysqli_real_escape_string($conn, $name);

    // Get the username who referred the registering user
    $referredByUserName = getUsernameByReferralCode($conn, $referralCode);

    if ($referredByUserName) {
        // Now, let's check if the referrer was also referred by someone (to find the affiliate)
        $affiliateQuery = "SELECT refered_by FROM refferal WHERE name = '$referredByUserName'";
        $affiliateResult = mysqli_query($conn, $affiliateQuery);
        $affiliateUserName = null; // Default to null if no affiliate exists
        
        if ($affiliateRow = mysqli_fetch_assoc($affiliateResult)) {
            $affiliateUserName = $affiliateRow['refered_by'];
        }

        // Insert new entry into referral table including the affiliate (if exists)
        $insertQuery = "INSERT INTO refferal (name, refered_by, afilated_by) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sss", $userName, $referredByUserName, $affiliateUserName);
        if (mysqli_stmt_execute($stmt)) {
            // Set success toast message
            setToast('success', 'Referral code added successfully!');
        } else {
            // Set error toast message
            setToast('error', 'Error adding referral code: ' . mysqli_error($conn));
        }
    } else {
        // Set error toast message if referral code does not exist
        setToast('error', 'Referral code does not exist!');
    }
}
