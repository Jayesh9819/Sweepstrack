<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
function downloadCSV($conn, $startTime, $endTime)
{
    $user = $_SESSION['username'];
    // Assuming the real_escape_string has already been applied
    $sql = "SELECT recharge,redeem,excess,bonus,page,cashapp,by_u,username,platform,type,freepik,tip,remark,created_at FROM transaction WHERE by_u='$user' AND TIME(created_at) BETWEEN '$startTime' AND '$endTime'";
    $result = $conn->query($sql);

    if ($result === false) {
        // SQL Error
        echo "SQL Error: " . $conn->error;
    } else if ($result->num_rows > 0) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="report.csv"');

        $output = fopen('php://output', 'w');
        $columns = ['Recharge', 'Redeem', 'Excess', 'Bonus', 'Page Name', 'CashAppName', 'Done By', 'UserName', 'Platform', 'Type', 'FreePlay', 'TIP', 'Remark', 'Created At']; // Replace with actual column names
        fputcsv($output, $columns);

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }

        fclose($output);
    } else {
        echo "No records found.";
    }

    // Close the database connection
    $conn->close();

    // Stop script execution
    exit;
}
if (isset($_GET['start_time']) && isset($_GET['end_time'])) {
    // Start output buffering
    ob_start();

    // Database connection details
    include './App/db/db_connect.php';

    $startTime = $conn->real_escape_string($_GET['start_time']);
    $endTime = $conn->real_escape_string($_GET['end_time']);

    // Call the CSV download function
    downloadCSV($conn, $startTime, $endTime);
}


?>
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

    $rechargeQuery = "SELECT SUM(recharge) AS total_recharge FROM transaction WHERE type='Debit' AND date(created_at) = CURDATE()";
    $redeemQuery = "SELECT SUM(redeem) AS total_redeem FROM transaction WHERE type='Credit' AND  date(created_at) = CURDATE()";
    $activeUsersQuery = "SELECT COUNT(*) AS active_users FROM user WHERE role='User' AND status = 1";
    // ... Add more queries as needed

    // Execute the queries and fetch the results
    $rechargeResult = $conn->query($rechargeQuery);
    $redeemResult = $conn->query($redeemQuery);
    $activeUsersResult = $conn->query($activeUsersQuery);

    // Extract the data
    $totalRecharge = $rechargeResult->fetch_assoc()['total_recharge'];
    $totalRedeem = $redeemResult->fetch_assoc()['total_redeem'];
    $activeUsers = $activeUsersResult->fetch_assoc()['active_users'];

    // Close the database connection
    $conn->close();


    // If here, the script is not in download mode; it should continue to render the page normally.
    ?>
    <style>
        /* CSS for modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            cursor: pointer;
        }
    </style>

</head>

<body class="  ">
    <!-- loader Start -->
    <?php
    //  include("./Public/Pages/Common/loader.php");
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
            <br>
            <br>
            <button class="btn btn-outline-success rounded-pill mt-2" id="myBtn">End Shift</button>
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <form id="timeForm">
                        <label for="start_time">Start Time:</label>
                        <input type="time" id="start_time" name="start_time" required>

                        <label for="end_time">End Time:</label>
                        <input type="time" id="end_time" name="end_time" required>

                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <h4 class="mb-5">Analytics Overview</h4>
                        <div class="col-lg-3 col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="mb-3"><?php echo $totalRecharge; ?></h2>
                                    <h5>Today Recharge Total</h5>
                                    <!-- You can add logic to calculate percentage changes -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="mb-3"><?php echo $totalRedeem; ?></h2>
                                    <h5>Today Redeem Amount</h5>
                                    <!-- You can add logic to calculate percentage changes -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="mb-3"><?php echo $activeUsers; ?></h2>
                                    <h5>Active Users</h5>
                                    <!-- You can add logic to calculate percentage changes -->
                                </div>
                            </div>
                        </div>
                        <!-- ... Other cards -->
                    </div>
                </div>
            </div>
            <?
            include("./Public/Pages/Common/footer.php");

            ?>
        </div>

    </main>
    <!-- Wrapper End-->
    <!-- Live Customizer start -->
    <!-- Setting offcanvas start here -->
    <?php
    include("./Public/Pages/Common/theme_custom.php");
    ?>
    <script>
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        document.getElementById("timeForm").onsubmit = function(event) {
            event.preventDefault();
            var startTime = document.getElementById("start_time").value;
            var endTime = document.getElementById("end_time").value;
            window.location.href = `?start_time=${encodeURIComponent(startTime)}&end_time=${encodeURIComponent(endTime)}`;
        };
    </script>


    <!-- Settings sidebar end here -->

    <?php
    include("./Public/Pages/Common/settings_link.php");
    ?>
    <!-- Live Customizer end -->

    <?php
    include("./Public/Pages/Common/scripts.php");
    ?>

</body>

</html>