<!doctype html>
<html lang="en" dir="ltr">

<head>
	<?php
	include("./Public/Pages/Common/header.php");
	include "./Public/Pages/Common/auth_user.php";

	// Function to echo the script for toastr
	function echoToastScript($type, $message)
	{
		echo "<script type='text/javascript'>document.addEventListener('DOMContentLoaded', function() { toastr['$type']('$message'); });</script>";
	}


	if (isset($_SESSION['toast'])) {
		$toast = $_SESSION['toast'];
		echoToastScript($toast['type'], $toast['message']);
		unset($_SESSION['toast']); // Clear the toast message from session
	}

	if (session_status() !== PHP_SESSION_ACTIVE) session_start();

	// Display error message if available
	if (isset($_SESSION['login_error'])) {
		echo '<p class="error">' . $_SESSION['login_error'] . '</p>';
		unset($_SESSION['login_error']); // Clear the error message
	}
	if (isset($_SESSION['username'])) {
		# database connection file
		include 'app/db.conn.php';
		// include './Public/Pages/Chat/./Public/Pages/Chat/app/';
		include 'app/helpers/user.php';
		include 'app/helpers/conversations.php';
		include 'app/helpers/timeAgo.php';
		include 'app/helpers/last_chat.php';
		if ($_SESSION['role'] == 'User') {
			// Fetch online agents in the same page
			$pagename = $_SESSION['page'];
			$sql = "SELECT * FROM user WHERE role = 'Agent' AND last_seen(last_seen) = 'Active' AND pagename = ?";
			$stmt = $conn->prepare($sql);
			$stmt->execute([$pagename]);
			$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$user = getUser($_SESSION['username'], $conn);

			# Getting User conversations
			$conversations = getConversation($user['id'], $conn);
		} else {
			$user = getUser($_SESSION['username'], $conn);

			# Getting User conversations
			$conversations = getConversation($user['id'], $conn);
		}
	}


	?>
	<style>
		.vh-100 {
			min-height: 100vh;
		}

		.w-400 {
			width: 800px;
		}

		.fs-xs {
			font-size: 1rem;
		}

		.w-10 {
			width: 10%;
		}

		a {
			text-decoration: none;
		}

		.fs-big {
			font-size: 5rem !important;
		}

		.online {
			width: 10px;
			height: 10px;
			background: green;
			border-radius: 50%;
		}

		.w-15 {
			width: 10%;
		}

		.fs-sm {
			font-size: 2rem;
		}

		.display-4 {
			font-size: 1.5rem !important;
		}

		small {
			color: #bbb;
			font-size: 0.7rem;
			text-align: right;
		}

		.chat-box {
			overflow-y: auto;
			overflow-x: hidden;
			max-height: 50vh;
		}

		.rtext {
			width: 65%;
			background: #f8f9fa;
			color: #444;
		}

		.ltext {
			width: 65%;
			background: #3289c8;
			color: #fff;
		}

		/* width */
		*::-webkit-scrollbar {
			width: 3px;
		}

		/* Track */
		*::-webkit-scrollbar-track {
			background: #f1f1f1;
		}

		/* Handle */
		*::-webkit-scrollbar-thumb {
			background: #aaa;
		}

		/* Handle on hover */
		*::-webkit-scrollbar-thumb:hover {
			background: #3289c8;
		}

		textarea {
			resize: none;
		}

		/*message_status*/
		/* Custom CSS styles */
		.chat-box {
			max-width: 750px;
			max-height: 300px;
			/* Limit the height of the chat box */
			overflow-y: auto;
			/* Enable vertical scrolling */
		}

		.chat-box p {
			margin: 5px 0;
			/* Add spacing between chat messages */
		}

		.chat-input-group {
			position: relative;
			/* Set position to relative for proper alignment */
		}

		#message {
			border-radius: 20px;
			/* Adjust border radius for message input */
			resize: none;
			/* Disable resizing of textarea */
		}

		#sendBtn {
			position: absolute;
			/* Position the send button */
			right: 10px;
			bottom: 10px;
		}

		.ltext {
			background-color: blueviolet;
			color: black;
			max-width: 50%;
			font-size: large;
		}

		.rtext {
			background-color: blue;
			color: aliceblue;
			max-width: 50%;
			font-size: large;


		}


		.emoji-picker {
			position: absolute;
			bottom: 60px;
			/* Adjust based on your layout */
			border: 1px solid #ddd;
			padding: 5px;
			background-color: white;
			width: 400px;
			/* Adjust as necessary */
			display: grid;
			grid-template-columns: repeat(8, 1fr);
			/* Adjust column count based on preference */
			gap: 5px;
			overflow-y: auto;
			max-height: 400px;
		}
	</style>


</head>

<body class="  ">
	<!-- loader Start -->
	<?php
	// include("./Public/Pages/Common/loader.php");

	?>
	<!-- loader END -->

	<!-- sidebar  -->
	<?php
	include("./Public/Pages/Common/sidebar.php");

	?>

	<main class="main-content">
		<?php
		include("./Public/Pages/Common/main_content.php");
		?>


		<div class="content-inner container-fluid pb-0" id="page_layout">
			<div class="p-2 w-400
                rounded shadow">
				<?php if ($_SESSION['role'] == 'User') { ?>
					<div>
						<h3>Online Agents Available for Chat</h3>
						<ul>
							<?php foreach ($agents as $agent) { ?>
								<a href="./Chat_Screen?user=<?= $agent['username'] ?>" class="d-flex
	    				          justify-content-between
	    				          align-items-center p-2">
									<div class="d-flex
	    					            align-items-center">
										<img src="../uploads/profile/<?= !empty($chatWith['p_p']) ? $chatWith['p_p'] : '07.png' ?>" class="w-15 rounded-circle">
										<h3 class="fs-xs m-2">
											<?= $agent['name'] ?><br>
											<small>

												<?php
												// echo lastChat($_SESSION['user_id'], $conversation['id'], $conn);
												?>
											</small>
										</h3>
									</div>

								<?php } ?>
						</ul>
					</div>
					<div>
						<h3>Chat History</h3>

						<ul id="chatList" class="list-group mvh-50 overflow-auto" id="chat-box">
							<?php if (!empty($conversations)) { ?>
								<?php

								foreach ($conversations as $conversation) { ?>
									<li class="list-group-item">
										<a href="./Chat_Screen?user=<?= $conversation['username'] ?>" class="d-flex
	    				          justify-content-between
	    				          align-items-center p-2">
											<div class="d-flex
	    					            align-items-center">
										<img src="../uploads/profile/<?= !empty($chatWith['p_p']) ? $chatWith['p_p'] : '07.png' ?>" class="w-15 rounded-circle">
												<h3 class="fs-xs m-2">
													<?= $conversation['name'] ?><br>
													<small>
														<?php
														echo lastChat($_SESSION['user_id'], $conversation['id'], $conn);
														?>
													</small>
												</h3>
											</div>
											<?php if (last_seen($conversation['last_seen']) == "Active") { ?>
												<div title="online">
													<div class="online"></div>
												</div>
											<?php } ?>
										</a>
									</li>
								<?php } ?>
							<?php } else { ?>
								<div class="alert alert-info 
    				            text-center">
									<i class="fa fa-comments d-block fs-big"></i>
									No messages yet, Start the conversation
								</div>
							<?php }
							?>
						</ul>

					</div>


				<?php } else { ?>

					<div>
						<div class="d-flex
    		            mb-3 p-3 bg-light
			            justify-content-between
			            align-items-center">
							<div class="d-flex
    			            align-items-center">
							<img src="../uploads/profile/<?= !empty($chatWith['p_p']) ? $chatWith['p_p'] : '07.png' ?>" class="w-15 rounded-circle">
								<h3 class="fs-xs m-2"><?= $user['name'] ?></h3>
							</div>
						</div>

						<div class="input-group mb-3">
							<input type="text" placeholder="Search..." id="searchText" class="form-control">
							<button class="btn btn-primary" id="serachBtn">
								<i class="fa fa-search">Search</i>
							</button>
						</div>
						<ul id="chatList" class="list-group mvh-50 overflow-auto" id="chat-box">
							<?php if (!empty($conversations)) { ?>
								<?php

								foreach ($conversations as $conversation) { ?>
									<li class="list-group-item">
										<a href="./Chat_Screen?user=<?= $conversation['username'] ?>" class="d-flex
	    				          justify-content-between
	    				          align-items-center p-2">
											<div class="d-flex
	    					            align-items-center">
												<img src="../assets/images/avatars/<?= !empty($chatWith['p_p']) ? $chatWith['p_p'] : '07.png' ?>" class="w-15 rounded-circle">
												<h3 class="fs-xs m-2">
													<?= $conversation['name'] ?><br>
													<small>
														<?php
														echo lastChat($_SESSION['user_id'], $conversation['id'], $conn);
														?>
													</small>
												</h3>
											</div>
											<?php if (last_seen($conversation['last_seen']) == "Active") { ?>
												<div title="online">
													<div class="online"></div>
												</div>
											<?php } ?>
										</a>
									</li>
								<?php } ?>
							<?php } else { ?>
								<div class="alert alert-info 
    				            text-center">
									<i class="fa fa-comments d-block fs-big"></i>
									No messages yet, Start the conversation
								</div>
						<?php }
						} ?>
						</ul>
					</div>
			</div>


			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

			<script>
				$(document).ready(function() {

					// Search
					$("#searchText").on("input", function() {
						var searchText = $(this).val();
						if (searchText == "") return;
						$.post('../Public/Pages/Chat/app/ajax/search.php', {
								key: searchText
							},
							function(data, status) {
								$("#chatList").html(data);
							});
					});

					// Search using the button
					$("#serachBtn").on("click", function() {
						var searchText = $("#searchText").val();
						if (searchText == "") return;
						$.post('../Public/Pages/Chat/app/ajax/search.php', {
								key: searchText
							},
							function(data, status) {
								$("#chatList").html(data);
							});
					});


					/** 
					auto update last seen 
					for logged in user
					**/
					let lastSeenUpdate = function() {
						$.get("../Public/Pages/Chat/app/ajax/update_last_seen.php");
					}
					lastSeenUpdate();
					/** 
					auto update last seen 
					every 10 sec
					**/
					setInterval(lastSeenUpdate, 10000);

				});
			</script>




		</div>






		<?
		include("./Public/Pages/Common/footer.php");
		
		?>

	</main>
	<!-- Wrapper End-->
	<!-- Live Customizer start -->
	<!-- Setting offcanvas start here -->
	<?php
	include("./Public/Pages/Common/theme_custom.php");

	?>

	<!-- Settings sidebar end here -->

	<?php
	include("./Public/Pages/Common/settings_link.php");

	?>
	<?php
	include("./Public/Pages/Common/scripts.php");

	?>

</body>

</html>