<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set appropriate headers for SSE
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Access-Control-Allow-Origin: *'); // Enable CORS if needed

require_once '../../App/db/db_connect.php';

function sendSSEData($message, $url, $color,$sleep) {
    $data = json_encode(['message' => $message, 'url' => $url, 'color' => $color]);
    echo "data: {$data}\n\n";
    flush();
    sleep($sleep); // Consider adjusting or removing sleep for performance
}

if (empty($_SESSION['role']) || empty($_SESSION['user_id'])) {
    error_log("Session variables 'role' or 'user_id' not set");
    exit;
}

$role = $_SESSION['role'];
$userid = $_SESSION['user_id'];
$whereClause = '';

if ($role === 'Agent') {
    if (!empty($_SESSION['page1'])) {
        $pagesArray = explode(", ", $_SESSION['page1']);
        $quotedPages = array_map(function($page) use ($conn) {
            return "'" . mysqli_real_escape_string($conn, $page) . "'";
        }, $pagesArray);

        $whereClause = "AND page IN (" . implode(", ", $quotedPages) . ")";
    }
    $sql = "SELECT username, redeem FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL $whereClause AND approval_status = 0 AND created_at >= NOW() - INTERVAL 5 SECOND";
    $url = "./See_Redeem_Request"; // Example URL for viewing redeem requests
    $color = "red"; // High priority notifications in red
} elseif ($role === 'Manager' || $role === 'Supervisor') {
    $branch = $_SESSION['branch1'] ?? '';
    $sql = "SELECT username, redeem FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0) AND branch = '$branch' AND approval_status = 1 AND updated_at >= NOW() - INTERVAL 5 SECOND";
    $url = "./See_Redeem_Request"; // Example URL for viewing redeem requests
    $color = "red"; // High priority notifications in red
} elseif ($role === 'Admin') {
    $sql = "SELECT username, redeem FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0) AND updated_at >= NOW() - INTERVAL 5 SECOND";
    $url = "./See_Redeem_Request"; // Example URL for viewing redeem requests
    $color = "red"; // High priority notifications in red
}

if (isset($sql) && $result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notificationMessage = "You have a new redeem request from {$row['username']} for amount {$row['redeem']}";
            $seleep=3;
            sendSSEData($notificationMessage, $url, $color,$seleep);
        }
    } 
} else {
    error_log("SQL error: " . $conn->error);
}
$sql = "SELECT * FROM chats WHERE opened = 0 AND to_id = $userid";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notificationMessage = "You have a new message. Please check your inbox.";
            $url = "./Portal_Chats"; // Assuming there's a generic inbox URL
            $color = "green"; // Choosing green for new messages
            sendSSEData($notificationMessage, $url, $color,60);
        }
    } 
} else {
    error_log("SQL error: " . $conn->error);
}
$sql = "SELECT * FROM transaction WHERE approval_status =1 AND cashout_status=1 AND redeem_status=1 AND branch = '$branch' AND created_at=NOW() - INTERVAL 5 SECOND";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notificationMessage = "Transaction successfully Done Of the User {$row['username']} of Amount {$row['redeem']} .";
            $url = "./Portal_Chats"; // Assuming there's a generic inbox URL
            $color = "green"; // Choosing green for new messages
            sendSSEData($notificationMessage, $url, $color,3);
        }
    } 
} else {
    error_log("SQL error: " . $conn->error);
}

$conn->close();
?>


