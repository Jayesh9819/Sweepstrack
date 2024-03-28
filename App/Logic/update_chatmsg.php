<?php
session_start();
include '../db/db_connect.php';

// $message = $_POST['message'];
$message = $_POST['receiverId'];




$receiver_id = 1;
$sender_id = 2;

// Sort sender and receiver IDs in ascending order
$sorted_ids = [$receiver_id, $sender_id];
sort($sorted_ids);

// Generate chat_id based on sorted IDs
$chat_id = implode('', $sorted_ids);

// echo "Chat ID: " . $chat_id;


// $chat_id= 12;
$reciever_id = 2;
$sender_id =1;
$from_user = 1; //SENDER ID
$status = 0; 
$chat_msg = $message;





// $chat_id= 12;
$recieveer_id = 2;
$sender_id =1;


$sql = "INSERT INTO TEST (test1) VALUES ('$message')";

if ($conn->query($sql) === TRUE) {
    echo "New message inserted successfully";
} else {
    echo "Error inserting message: " . $conn->error;
}


$conn->close();
?>