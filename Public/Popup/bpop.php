<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set appropriate headers for SSE
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Access-Control-Allow-Origin: *'); // Enable CORS if needed

require_once '../../App/db/db_connect.php';

function sendSSEDatasleep($message, $url, $color)
{
    $data = json_encode(['message' => $message, 'url' => $url, 'color' => $color]);
    echo "data: {$data}\n\n";
    flush();
    sleep(1);
}
function sendSSEData($message, $url, $color)
{
    $data = json_encode(['message' => $message, 'url' => $url, 'color' => $color]);
    echo "data: {$data}\n\n";
    flush();
}

function sendSSEDataCust($msgname, $message, $url, $color)
{
    $data = json_encode([$msgname => $message, 'url' => $url, 'color' => $color]);
    echo "data: {$data}\n\n";
    flush(); // Ensure the data is sent in real time
}

if (empty($_SESSION['role']) || empty($_SESSION['user_id'])) {
    error_log("Session variables 'role' or 'user_id' not set");
    exit;
}

$role = $_SESSION['role'];
$userid = $_SESSION['user_id'];
$branch = $_SESSION['branch1'];
$whereClause = '';

if ($role === 'Agent') {
    if (!empty($_SESSION['page1'])) {
        $pagesArray = explode(", ", $_SESSION['page1']);
        $quotedPages = array_map(function ($page) use ($conn) {
            return "'" . mysqli_real_escape_string($conn, $page) . "'";
        }, $pagesArray);

        $whereClause = "AND page IN (" . implode(", ", $quotedPages) . ")";
    }
    $sql = "SELECT username, redeem FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL $whereClause AND approval_status = 0 AND created_at >= NOW() - INTERVAL 5 SECOND";
    $url = "./See_Redeem_Request"; // Example URL for viewing redeem requests
    $color = "low"; // High priority notifications in red
} elseif ($role === 'Manager' || $role === 'Supervisor') {
    $branch = $_SESSION['branch1'] ?? '';
    $sql = "SELECT username, redeem FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0) AND branch = '$branch' AND approval_status = 1 AND updated_at >= NOW() - INTERVAL 5 SECOND";
    $url = "./See_Redeem_Request"; // Example URL for viewing redeem requests
    $color = "low"; // High priority notifications in red
} elseif ($role === 'Admin') {
    $sql = "SELECT username, redeem FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0) AND updated_at >= NOW() - INTERVAL 5 SECOND";
    $url = "./See_Redeem_Request"; // Example URL for viewing redeem requests
    $color = "low"; // High priority notifications in red
}

if (isset($sql) && $result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notificationMessage = "You have a new redeem request from {$row['username']} for amount {$row['redeem']}";
            sendSSEData($notificationMessage, $url, $color);
        }
    }
} else {
    error_log("SQL error: " . $conn->error);
}
$sql = "SELECT * FROM chats WHERE opened = 0 AND to_id = $userid AND created_at >= NOW() - INTERVAL 2 SECOND";
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notificationMessage = "You have a new message. Please check your inbox.";
            $url = "./Portal_Chats"; // Assuming there's a generic inbox URL
            $color = "medium"; // Choosing green for new messages
            sendSSEData($notificationMessage, $url, $color);
        }
    }
} else {
    error_log("SQL error: " . $conn->error);
}
$userIDs = [];

// Fetch user, agent, and manager/supervisor IDs
$stmtUser = $conn->prepare("SELECT id FROM user WHERE username = ?");
$stmtAgent = $conn->prepare("SELECT id FROM user WHERE username = ?");
$stmtManSup = $conn->prepare("SELECT id FROM user WHERE branchname = ? AND (role = 'Manager' OR role = 'Supervisor')");

$sql = "SELECT * FROM transaction WHERE approval_status = 1 AND cashout_status = 1 AND redeem_status = 1 AND branch = '$branch' AND updated_at >= NOW() - INTERVAL 2 SECOND";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notificationMessage = "Transaction successfully done by {$row['username']} for amount {$row['redeem']}.";
        $approvedBy = $row['approved_by'];
        $user = $row['username'];

        // User who requested
        $stmtUser->bind_param("s", $user);
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();
        if ($userRow = $resultUser->fetch_assoc()) {
            $userIDs[] = $userRow['id'];
        }

        // Agent who approved
        $stmtAgent->bind_param("s", $approvedBy);
        $stmtAgent->execute();
        $resultAgent = $stmtAgent->get_result();
        if ($agentRow = $resultAgent->fetch_assoc()) {
            $userIDs[] = $agentRow['id'];
        }

        // Managers and Supervisors
        $stmtManSup->bind_param("s", $branch);
        $stmtManSup->execute();
        $resultManSup = $stmtManSup->get_result();
        while ($manSupRow = $resultManSup->fetch_assoc()) {
            $userIDs[] = $manSupRow['id'];
        }

        foreach ($userIDs as $id) {
            $insertStmt = $conn->prepare("INSERT INTO notification (content, by_id, for_id, created_at) VALUES (?, ?, ?, NOW())");
            $insertStmt->bind_param("sii", $notificationMessage, $userid, $id);
            $insertStmt->execute();
            $insertStmt->close();
        }
        if (in_array($_SESSION['user_id'], $userIDs)) {

            sendSSEDatasleep($notificationMessage, "./Portal_Chats", "low");
        }
    }
} else {
    error_log("SQL error: " . $conn->error);
}

$sql = "SELECT * FROM transaction WHERE approval_status = 2 AND cashout_status = 0 AND redeem_status = 0 AND branch = '$branch' AND updated_at >= NOW()";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notificationMessage = "Redeem Not Done Sucessfully of amount {$row['redeem']} because of {$row['Reject_msg']} ";
        $approvedBy = $row['approved_by'];
        $user = $row['username'];
        $stmtUser->bind_param("s", $user);
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();
        if ($userRow = $resultUser->fetch_assoc()) {
            $userIDs[] = $userRow['id'];
        }

        // Agent who approved
        $stmtAgent->bind_param("s", $approvedBy);
        $stmtAgent->execute();
        $resultAgent = $stmtAgent->get_result();
        if ($agentRow = $resultAgent->fetch_assoc()) {
            $userIDs[] = $agentRow['id'];
        }

        // Managers and Supervisors
        $stmtManSup->bind_param("s", $branch);
        $stmtManSup->execute();
        $resultManSup = $stmtManSup->get_result();
        while ($manSupRow = $resultManSup->fetch_assoc()) {
            $userIDs[] = $manSupRow['id'];
        }

        foreach ($userIDs as $id) {
            $insertStmt = $conn->prepare("INSERT INTO notification (content, by_id, for_id, created_at) VALUES (?, ?, ?, NOW())");
            $insertStmt->bind_param("sii", $notificationMessage, $userid, $id);
            $insertStmt->execute();
            $insertStmt->close();
        }
        if (in_array($_SESSION['user_id'], $userIDs)) {

            sendSSEDatasleep($notificationMessage, "./Portal_Chats", "high");
        }
    }
} else {
    error_log("SQL error: " . $conn->error);
}

$conn->close();
