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
           
            $username = $conn->real_escape_string($_POST['state']);

            // Prepare the SQL statement
            $sql = "SELECT * FROM user WHERE username = '$username'";

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
                                <button type="button" class="btn btn-info rounded-pill mt-2 flex-wrap d-flex justify-content-between align-items-center">Create User</button>
                            </div>

                            <div class="card-body">
                                <div class="custom-table-effect table-responsive  border rounded">
                                    <?php

                                    if ($result) {
                                        // Fetch the results
                                        echo '<table class="table mb-0">';
                                        echo "<tr>";
                                        echo '<tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Password</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Last Login</th>
                            </tr>';
                                        while ($row = $result->fetch_assoc()) {
                                            // Output column names as table headers
                                            echo "<tr>
                                            <td>{$row['id']}</td>

                                        <td>{$row['username']}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['password']}</td>
                                            <td>{$row['role']}</td>
                                            <td>{$row['created_at']}</td> <!-- Consider if you really want to display passwords -->
                                            <td>{$row['last_login']}</td>
                                    echo </tr>";
                                            $id = $row['id'];
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "Error: " . $conn->error;
                                    }
                                    ?>
                                </div>
                                <br>
                                <br>

                                <a href="./deposit?u=<?php echo $username; ?>" style="text-decoration: none;">
                                    <button type="button" class="btn btn-danger rounded-pill mt-2">Recharge</button>
                                </a>
                                <a href="./cash_out?u=<?php echo $username; ?>" style="text-decoration: none;">
                                    <button type="button" class="btn btn-success rounded-pill mt-2">Redeem</button>
                                </a>
                                <a href="javascript:void(0);" class="" onclick="passreset(<?php echo $id; ?>, 'user', 'password','id')">
                                    <button type="button" class="btn btn-warning rounded-pill mt-2">Password Reset</button>
                                </a>
                                <a href="./record?u=<?php echo $username; ?>" style="text-decoration: none;">
                                    <button type="button" class="btn btn-light rounded-pill mt-2">Transaction Record</button>
                                </a>
                                <a href="./Edit_User?u=<?php echo $username; ?>" style="text-decoration: none;">
                                    <button type="button" class="btn btn-danger rounded-pill mt-2">Edit Button</button>
                                </a>
                                <a href="./Free_Play?u=<?php echo $username; ?>" style="text-decoration: none;">
                                    <button type="button" class="btn btn-danger rounded-pill mt-2">Free Play</button>
                                </a>
                                <a href="./PlatformUser?u=<?php echo $username; ?>" style="text-decoration: none;">
                                    <button type="button" class="btn btn-danger rounded-pill mt-2">Map User and Platform</button>
                                </a>


                                <button type="button" class="btn btn-success rounded-pill mt-2">Activate</button>
                                <a href="./Chat_Screen?user=<?php echo $username; ?>" style="text-decoration: none;">
                                <button type="button" class="btn btn-secondary rounded-pill mt-2">Chat History</button>
                                </a>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
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

    <script>
        function passreset(product_id, table, field, id) {
            if (confirm("Are you sure you want to Reset the Password?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../App/Logic/commonf.php?action=passreset", true);

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
                                        alert("Reset Successfully!");
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