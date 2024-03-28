<?php
// Include your database connection file here
// Example: include 'db_connection.php';
$servername = "193.203.184.53"; // or your server name
$username = "u306273205_CustCount";
$password = "WebluxDigi@@1122";
$dbname = "u306273205_CustCount";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$roles = array("Admin", "User", "Guest");

// Check if the username is provided in the AJAX request
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Fetch all user details based on the provided username
    $query = "SELECT * FROM users WHERE Username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Display user details
        $row = $result->fetch_assoc();
        echo '<form id="updateForm">';
        echo '<input type="hidden" name="username" value="' . htmlspecialchars($row['Username']) . '">';
        echo '<p>User ID: ' . htmlspecialchars($row['UserID']) . '</p>';
        echo '<p>Username: ' . htmlspecialchars($row['Username']) . '</p>';
        echo '<p>Full Name: <input type="text" name="fullname" value="' . htmlspecialchars($row['fullname']) . '"></p>';
        echo '<p>New Password: <input type="password" name="newPassword"></p>';
        echo '<p>Role: <select name="role">';
        foreach ($roles as $role) {
            echo '<option value="' . $role . '"';
            if ($role == $row['Role']) {
                echo ' selected';
            }
            echo '>' . $role . '</option>';
        }
        echo '</select></p>';
        echo '<input type="submit" value="Update">';
        echo '</form>';
    } else {
        echo 'User not found.';
    }
} else {
    echo 'Username not provided.';
}
