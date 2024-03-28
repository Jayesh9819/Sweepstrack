<?php
session_start();
include '../db/db_connect.php';

$receiver_id = $_POST['receiver_id'];




$sql2 = "INSERT INTO TEST (test1) VALUES ($receiver_id)";
if ($conn->query($sql2) === TRUE) {
    echo "New message inserted successfully";
} else {
    echo "Error inserting message: " . $conn->error;
}

$sender_id = $_SESSION['userid'];
// $query2 =  mysqli_query($conn, "SELECT * FROM user");
// // print_r($query2);
// while ($row = mysqli_fetch_array($query2)) {

// }


// Sort sender and receiver IDs in ascending order
$sorted_ids = [$receiver_id, $sender_id];
sort($sorted_ids);

// Generate chat_id based on sorted IDs
$our_chat_id = implode('', $sorted_ids);


$sql = "SELECT * FROM chat_history WHERE chat_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $our_chat_id);
$chat_id = $our_chat_id;
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Chat_id already exists, so don't insert a new record
    // echo "Chat_id already exists in chat_history";
} else {
    // Chat_id does not exist, so insert a new record
    $sql = "INSERT INTO chat_history (chat_id, sender_id, receiver_id, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $chat_id, $sender_id, $receiver_id);
    
    if ($stmt->execute()) {
        // echo "New message inserted successfully";
    } else {
        // echo "Error: " . $sql . "<br>" . $conn->error;
    }
}





$sql = "INSERT INTO chat_records (chat_id, sender_id, receiver_id, from_user, status, chat_msg, timestamp) VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);

$from_user = $sender_id;
$status = "NotRead";

$message = $_POST['message'];

$stmt->bind_param("iiisss", $chat_id, $sender_id, $receiver_id, $from_user, $status,$message);



if ($stmt->execute()) {
    echo "New message inserted successfully";
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}




$conn->close();
?>