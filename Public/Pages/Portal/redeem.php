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


    ?>

</head>

<body class="  ">
    <?php
    include("./Public/Pages/Common/sidebar.php");

    ?>
    <main class="main-content">
        <?php
        include("./Public/Pages/Common/main_content.php");
        ?>


        <div class="content-inner container-fluid pb-0" id="page_layout">
            <br>
            <div class="box-body">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">See All the data</h3>
                        <h6 class="box-subtitle">All The Records</h6>
                    </div>



                    <?php
                    include "./App/db/db_connect.php";
                    $role = $_SESSION['role'];
                    $page='';
                    $page = $_SESSION['page1'];

                    if ($role == 'Admin') {
                        $sql = "SELECT * FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0)";
                    } else {
                        $sql = "SELECT * FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0) AND page='$page'";
                    }
                    // echo $sql;
                    $stmt = $conn->prepare($sql);
                    // $stmt->bind_param('s', $u);
                    $stmt->execute();

                    $result = $stmt->get_result();
                    $results = $result->fetch_all(MYSQLI_ASSOC);

                    $stmt->close();
                    $conn->close();

                    if (empty($results)) {
                        echo "No records found";
                    } else {
                        usort($results, function ($a, $b) {
                            return strtotime($b['created_at']) - strtotime($a['created_at']);
                        });
                    ?>

                        <div class="table-responsive">

                            <table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                <thead>
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>Username</th>
                                        <th>Amount</th>
                                        <th>Platform Name</th>
                                        <th>Page Name</th>
                                        <th>By</th>
                                        <th>Platform Redeem</th>
                                        <th>CashOut</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $row) :
                                        $createdAt = new DateTime($row['created_at'], new DateTimeZone('UTC'));
                                        $createdAtFormatted = $createdAt->format('Y-m-d H:i:s');
                                        $platformRedeemStatus = $row['redeem_status'];
                                        $cashOutStatus = $row['cashout_status'];
                                        $id = $row['tid']


                                    ?>

                                        <tr>
                                            <td><?= $createdAtFormatted ?></td>
                                            <td><?= $row['username'] ?></td>
                                            <td><?= $row['redeem'] ?></td>
                                            <td><?= $row['platform'] ?></td>
                                            <td><?= $row['page'] ?></td>
                                            <td><?= $row['by_u'] ?></td>
                                            <td>
                                                <?php if ($platformRedeemStatus == 0) : ?>
                                                    <button class="btn btn-warning" onclick="status(<?php echo $id; ?>, 'transaction', 'redeem_status','tid')">Pending</button>
                                                <?php elseif ($platformRedeemStatus == 1) : ?>
                                                    <button class="btn btn-success">Done</button>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($cashOutStatus == 0) : ?>
                                                    <button class="btn btn-warning" onclick="cashapp(<?php echo $id; ?>, 'transaction', 'redeem_status','tid')">Pending</button>
                                                <?php elseif ($cashOutStatus == 1) : ?>
                                                    <button class="btn btn-success">Done</button>
                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        <?php
                    }

                        ?>
                        </div>

                </div>
            </div>
        </div>

        <?php
        include("./Public/Pages/Common/theme_custom.php");
        ?>
        <?php
        include("./Public/Pages/Common/settings_link.php");

        ?>
        <?php
        include("./Public/Pages/Common/scripts.php");
        ?>
    </main>
    <script>
        function status(product_id, table, field, id) {
            if (confirm("Are you sure you want to Activate or Deactivate?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../App/Logic/commonf.php?action=status", true);

                // Set the Content-Type header
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Include additional parameters in the data sent to the server
                const data = "id=" + product_id + "&table=" + table + "&field=" + field + "&cid=" + id;

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

        function cashapp(product_id, table, field, id) {
            const cashAppName = prompt("Please enter the cashapp name:");

            if (confirm("Are you sure you want to Activate or Deactivate?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../App/Logic/commonf.php?action=cashapp", true);

                // Set the Content-Type header
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Include additional parameters in the data sent to the server
                const data = "id=" + product_id + "&table=" + table + "&field=" + field + "&cid=" + id + "&cashapp=" + cashAppName;

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
</body>

</html>