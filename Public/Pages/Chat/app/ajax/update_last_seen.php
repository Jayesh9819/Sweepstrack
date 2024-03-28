<?php  

session_start();

# check if the user is logged in
if (isset($_SESSION['username'])) {
	
	# database connection file
	include '../db.conn.php';

	# get the logged in user's id and time zone from SESSION
	$id = $_SESSION['user_id'];
	$timeZone = $_SESSION['timezone']; // Assuming you have time zone information in the session

	# SQL to update last_seen and time_zone
	$sql = "UPDATE user
	        SET last_seen = NOW(), 
	            time_zone = ?
	        WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$timeZone, $id]);

}else {
	header("Location: ../../index.php");
	exit;
}
