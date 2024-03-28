<?php
session_start();

// Check if the user is logged in and has a role
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['role'])) {
    header('Location: http://localhost/Financy/index.php/Login_to_Financy'); // Redirect to login page
    exit;
}

// Redirect users based on their role
switch ($_SESSION['role']) {
    case 'Agent':
        header('Location: ../../../index.php/Hey_Agent_:) ');
        break;
    case 'User':
        header('Location: ');
        break;
    case 'Supervisor':
        header('Location: ');
        break;
    case 'Manager':
        // Redirect to the manager's page
        header('Location: ');
        exit;
    default:
        // Redirect any other roles to a general page or dashboard
        header('Location: ../Error/no_dash.php ');
        exit;
}
