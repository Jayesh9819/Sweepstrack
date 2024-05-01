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
    include './App/db/db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $platformuserid = $conn->real_escape_string($_POST['username']);
        $platfromname = $conn->real_escape_string($_POST['platform']);
        // Assuming $username comes from a session or another source
        $username = $_POST['user'] ?? 'defaultUsername'; // Fallback if not set
        $by_add = $_SESSION['username'];

        // Use prepared statements to insert safely into the database
        $stmt = $conn->prepare("INSERT INTO Platformuser (username, platformuserid, platfromname, by_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $platformuserid, $platfromname, $by_add);
        if ($stmt->execute()) {
            echo "<div>Submission Successful!</div>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Fetch platforms from the database
    $sql = "SELECT * FROM platform where status=1";
    $result = $conn->query($sql);

    $platforms = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $platforms[] = $row;
        }
    } else {
        echo "0 results";
    }
    $conn->close();

    ?>


    <style>
        .container {
            max-width: 1200px;
            /* Adjust the maximum width of the container */
            margin: 2rem auto;
            /* Center the container */
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Soft shadow for depth */
            border-radius: 10px;
            /* Rounded corners for a modern look */
            transition: transform 0.3s ease-in-out;
            /* Smooth transform on hover */
        }

        .card:hover {
            transform: translateY(-5px);
            /* Slight lift effect on hover */
        }

        .card-body {
            padding: 1.5rem;
            /* Spacious padding inside the card */
        }

        .btn-primary {
            background-color: #007bff;
            /* Bootstrap primary blue */
            border: none;
            border-radius: 5px;
            /* Slight rounding on buttons */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            /* Subtle shadow for buttons */
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
        }

        .row {
            margin-right: 0;
            margin-left: 0;
        }

        .col-md-4 {
            padding: 15px;
            /* Spacing between columns */
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
            <div class="container mt-5">
                <h2>Map The User And its Platform</h2>
                <form method="post">
                    <?php if (isset($_GET['u'])) {
                        echo    '<input type="hidden" name="user" value="' . $_GET['u'] . '">';
                    } ?>
                    <input type="hidden" value="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="platformSelect">Platform</label>
                        <select class="form-control" id="platformSelect" name="platform">
                            <?php foreach ($platforms as $platform) : ?>
                                <option value="<?= htmlspecialchars($platform['name']) ?>"><?= htmlspecialchars($platform['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <?php
            // Assuming $conn is your database connection from include './App/db/db_connect.php';
            include './App/db/db_connect.php';
            $username = $_GET['u'];
           $sql= "SELECT Platformuser.*, user.id AS uid
            FROM Platformuser
            JOIN user ON Platformuser.username = user.username
            WHERE Platformuser.username = '$username'";
                        $result = $conn->query($sql);
            ?>

            <div class="container mt-5">
                <div class="row">
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($user = $result->fetch_assoc()) : ?>
                            <div class="col-md-4">
                                <div class="card" style="width: 100%;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($user['username']) ?></h5>
                                        <h6 class="card-text">Platform User ID: <?= htmlspecialchars($user['platformuserid']) ?></h6>
                                        <h6 class="card-text">Platform Name: <?= htmlspecialchars($user['platfromname']) ?></h6>
                                        <a href="./Show_Profile?u=<?php echo $user['uid']; ?>" class="btn btn-primary">View Profile</a>
                                        <!-- Delete button -->
                                        <a href="" class="btn btn-danger" onclick="delete1(<?php echo $user['id']; ?>, 'Platformuser','id')">Delete</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div class="col-12">
                            <p class="text-center">No users found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>





        </div>






        <?

        ?>

    </main>
    <script>
                function delete1(product_id, table, field) {
            if (confirm("Are you sure you want to Delete")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../App/Logic/commonf.php?action=delete", true);

                // Set the Content-Type header
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Include additional parameters in the data sent to the server
                const data = "id=" + product_id + "&table=" + table + "&field=" + field;

                // Log the data being sent
                console.log("Data sent to server:", data);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        console.log("XHR status:", xhr.status);

                        if (xhr.status === 200) {
                            console.log("Response received:", xhr.responseText);

                            try {
                                const response = JSON.parse(xhr.responseText);

                                if (response) {
                                    console.log("Parsed JSON response:", response);

                                    if (response.success) {
                                        alert("Done successfully!");
                                        location.reload();
                                    } else {
                                        alert("Error : " + response.message);
                                    }
                                } else {
                                    console.error("Invalid JSON response:", xhr.responseText);
                                    alert("Invalid JSON response from the server.");
                                }
                            } catch (error) {
                                console.error("Error parsing JSON:", error);
                                alert("Error parsing JSON response from the server.");
                            }
                        } else {
                            console.error("HTTP request failed:", xhr.statusText);
                            alert("Error: " + xhr.statusText);
                        }
                    }
                };

                // Log any network errors
                xhr.onerror = function() {
                    console.error("Network error occurred.");
                    alert("Network error occurred. Please try again.");
                };

                // Send the request
                xhr.send(data);
            }
        }

    </script>
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
    <!-- Live Customizer end -->

    <!-- Library Bundle Script -->
    <?php
    include("./Public/Pages/Common/scripts.php");

    ?>

</body>

</html>