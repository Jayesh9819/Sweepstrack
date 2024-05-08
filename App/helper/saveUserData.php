<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
include 'referproc.php';
include '../db/db_connect.php'; 
include './App/db/db_connect.php';

$name = $referCode = $email = "";

// Check if form was submitted
if ($_POST) {
    $name = $_POST['name'];
    $referCode = $_POST['refercode'] ?? null;
    $email = $_POST['email'] ?? null;
    $pagename = 'From Login Page';


} elseif ($_GET) {
    $name = $_GET['user'] ?? null;
    $referCode = $_GET['refer'] ?? null;
    $email = $_GET['email'] ?? null;
    $pagename = 'By Refer of ReferID' .$referCode;
}
if(isset($referCode) && $referCode != ""){
    processReferralCode($conn, $name, $referCode);
}
$role = 'query';

// Check if the user already exists
if ($name) {
    $stmt = $conn->prepare("SELECT * FROM unknown_users WHERE username = ? AND email = ?");
    $stmt->bind_param("ss", $name,$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, fetch details
        $userData = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $userData['username'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['id'] = $userData['id'];
        // Redirect to another page or display user details
        header("Location: ../../index.php/unkno");
        exit();
    } else {
        // No user found, create new user
        $randomNumber = rand(1000, 99999);
        $id = 'UT' . $randomNumber;

        $stmt = $conn->prepare("INSERT INTO unknown_users (username, Refer, id, role, email,pagename, last_seen) VALUES (?, ?, ?, ?,?, ?, NOW())");
        $stmt->bind_param("ssssss", $name, $referCode, $id, $role,$email, $pagename);
        $result = $stmt->execute();

        if ($result) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $name;
            $_SESSION['role'] = $role;
            $_SESSION['user_id'] = $id;
            $_SESSION['id'] = $id;

            // Redirect after successful insertion
            header("Location: ../../index.php/unkno");
            exit();
        } else {
            echo json_encode(['success' => false]);
        }
    }
    $stmt->close();
}

$conn->close();
?>
