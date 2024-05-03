<?php
session_start();

function storeCurrentData() {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $_SESSION['previous_url'] = $_SERVER['HTTP_REFERER'];
    }
    if (!empty($_POST)) {
        $_SESSION['post_data'] = $_POST;
    }
}

function clearStoredData() {
    unset($_SESSION['previous_url']);
    unset($_SESSION['post_data']);
}

// Decide when to store or clear the data based on a condition or specific page visit
if (isset($_GET['return']) && $_GET['return'] == '1') {
    clearStoredData();
    header('Location: ' . ($_SESSION['previous_url'] ?? 'default-page.php'));
    exit;
} else {
    storeCurrentData();
}


?>
