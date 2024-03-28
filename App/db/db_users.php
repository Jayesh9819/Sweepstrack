<?php
if ($role === 'Admin') {
    $sql = "SELECT * FROM users";
    // No parameters needed for Admin
} elseif ($role === 'Manager') {
    $sql = "SELECT * FROM users WHERE Role IN ('Agent', 'User', 'Supervisor')";
    $params = [];
} elseif ($role === 'Supervisor') {
    $sql = "SELECT * FROM users WHERE Role IN ('Agent', 'User')";
    $params = [];
} elseif ($role === 'Agent') {
    $sql = "SELECT * FROM users WHERE Role = 'User'";
    $params = [];
}
