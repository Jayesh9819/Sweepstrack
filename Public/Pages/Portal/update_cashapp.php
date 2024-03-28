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
    unset($_SESSION['start_date'],$_SESSION['end_date'],$_SESSION['u'],$_SESSION['r'],$_SESSION['page'],$_SESSION['branch']);

    
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


            <?php
            include './App/db/db_connect.php';

          
            $user = $_POST['state'];
            echo $user;
            $username = $conn->real_escape_string($_POST['state']);

            // Prepare the SQL statement
            $sql = "SELECT * FROM cashapp WHERE name = '$username'";

            // Execute the query
            $result = $conn->query($sql);

            // Check if query was successful

            ?>

            <div class="content-inner container-fluid pb-0" id="page_layout">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0"><?php echo $user; ?> Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="custom-table-effect table-responsive  border rounded">
                                    <?php

                                    if ($result) {
                                        // Fetch the results
                                        echo '<table class="table mb-0">';

                                        echo '<tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">Opening Balance</th>
                                        <th scope="col">CashApp Name</th>
                                        <th scope="col">Cash Tag</th>
                                        <th scope="col">Email Address</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Remarks</th>
                                                                </tr>';
                                        while ($row = $result->fetch_assoc()) {
                                            // Output column names as table headers


                                            echo "<tr>
                                            <td>{$row['cid']}</td>
                                            <td>{$row['start']}</td>
                                            <td>{$row['end']}</td>
                                            <td>{$row['current_balance']}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['cashtag']}</td>
                                            <td>{$row['email']}</td>
                                            <td>" . ($row['status'] == 1 ? 'Activated' : 'Not Active') . "</td>
                                            <td>{$row['remark']}</td>";
                                            $id = $row['cid'];
                                            $status = $row['status'];
                                            echo "</tr>";
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "Error: " . $conn->error;
                                    }
                                    ?>
                                </div>
                                <br>
                                <br>
                                <a href="./Edit_Cashapp?u=<?php echo $username; ?>" style="text-decoration: none;">
                                    <button type="button" class="btn btn-danger rounded-pill mt-2">Edit Details</button>
                                </a>
                                <a href="./Recharge_cashapp?name=<?php echo $username; ?>" style="text-decoration: none;">
                                    <button type="button" class="btn btn-danger rounded-pill mt-2">Recharge CashApp</button>
                                </a>
                                <a href="./Redeem_cashapp?name=<?php echo $username; ?>" style="text-decoration: none;">
                                    <button type="button" class="btn btn-danger rounded-pill mt-2">Redeem CashApp</button>
                                </a>

                                <a href="./PlatformRec<?php $_SESSION['u']=$username ?>">
                                    <button type="submit" class="btn btn-warning rounded-pill mt-2">Transaction Record</button>
                                </a>

                                <a href="javascript:void(0);" class="" onclick="modify(<?php echo $id; ?>, 'cashapp', 'start','cid')">
                                    <button type="button" class="btn btn-warning rounded-pill mt-2">Start Date</button>
                                </a>

                                <a href="javascript:void(0);" class="" onclick="modify(<?php echo $id; ?>, 'cashapp', 'end','cid')">
                                    <button type="button" class="btn btn-warning rounded-pill mt-2">End Date</button>
                                </a>


                                <a href="javascript:void(0);" class="btn btn-outline-info rounded-pill mt-2" onclick="status(<?php echo $id; ?>, 'cashapp', 'status','cid')">
                                    <i class="fas fa-xmark"><?php echo $status == 1 ? 'DeActivate' : 'Activate'  ?></i>
                                </a>
                                <!-- <button type="button" class="btn btn-success rounded-pill mt-2">Page is Active</button> -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <script>
            function modify(product_id, table, field, id) {
                if (confirm("Are you sure you want to Activate or Deactivate?")) {
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "../App/Logic/commonf.php?action=modify", true);

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
        </script>




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
    <!-- Live Customizer end -->

    <!-- Library Bundle Script -->
    <?php
    include("./Public/Pages/Common/scripts.php");

    ?>

</body>

</html>