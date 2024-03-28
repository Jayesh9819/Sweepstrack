<?php

session_start();
function linkify($text)
{
	$urlPattern = '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i';
	$text = preg_replace($urlPattern, '<a class="rtext" href="$0" target="_blank">$0</a>', $text);
	return $text;
}


# check if the user is logged in
if (isset($_SESSION['username'])) {

	if (
		isset($_POST['message']) &&
		isset($_POST['to_id'])
	) {

		# database connection file
		include '../db.conn.php';

		# get data from XHR request and store them in var
		$message = $_POST['message'];
		$to_id = $_POST['to_id'];

		# get the logged in user's username from the SESSION
		$from_id = $_SESSION['user_id'];
		// Get the root directory and append the target directory
		$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

		// Check if the uploads directory exists, if not try to create it
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true); // Use true for recursive creation if needed
		}

		$attachmentPath = null;

		if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
			$fileName = time() . '-' . basename($_FILES['attachment']['name']);
			$targetFilePath = $uploadDir . $fileName;

			// Move the uploaded file to the target directory
			if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFilePath)) {
				$attachmentPath = $fileName; // You might want to save only the file name or a relative path
			} else {
				echo "Error uploading file.";
				exit;
			}
		}


		$sql = "INSERT INTO 
	       chats (from_id, to_id, message,attachment) 
	       VALUES (?, ?, ?,?)";
		$stmt = $conn->prepare($sql);
		$res  = $stmt->execute([$from_id, $to_id, $message, $attachmentPath]);

		# if the message inserted
		if ($res) {
			$sql2 = "SELECT * FROM conversations
               WHERE (user_1=? AND user_2=?)
               OR    (user_2=? AND user_1=?)";
			$stmt2 = $conn->prepare($sql2);
			$stmt2->execute([$from_id, $to_id, $from_id, $to_id]);

			date_default_timezone_set(str_replace(' ', '', $_SESSION['timezone']));
			$time = date("h:i:s a");

			if ($stmt2->rowCount() == 0) {
				# insert them into conversations table 
				$sql3 = "INSERT INTO 
			         conversations(user_1, user_2)
			         VALUES (?,?)";
				$stmt3 = $conn->prepare($sql3);
				$stmt3->execute([$from_id, $to_id]);
			}
?>
		<?php

			echo '<p class="rtext align-self-end border rounded p-2 mb-1">';
			echo linkify($message);;
			if ($attachmentPath) {
				echo '<img src="../uploads/' . htmlspecialchars($attachmentPath) . '" alt="Attachment" style="max-width:100%;display:block;">';
			}
			echo '<small class="d-block">' . date("h:i:s a") . '</small>';
			echo '</p>';
		}
	}
} else {
	header("Location: ../../index.php");
	exit;
}
