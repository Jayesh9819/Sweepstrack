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
            $sql = "SELECT * FROM page WHERE name = '$username'";

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
                                            <th scope="col">Page Name</th>
                                            <th scope="col">Added By</th>
                                            <th scope="col">Status</th>

                                            <th scope="col">Created At</th>
                                </tr>';
                                        while ($row = $result->fetch_assoc()) {
                                            // Output column names as table headers


                                            echo "<tr>";
                                            echo "<td>" . $row['pid'] . "</td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['by_u'] . "</td>";
                                            echo "<td>" . ($row['status'] == 1 ? 'Activated' : 'Not Active') . "</td>";


                                            echo "<td>" . $row['created_at'] . "</td>";
                                            $id = $row['pid'];
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
                                <a href="./See_All_Reports<?php unset($_SESSION['start_date'], $_SESSION['end_date'], $_SESSION['u'], $_SESSION['r'], $_SESSION['page'], $_SESSION['branch']);
                                                            $_SESSION['page'] = $username; ?>" class="btn btn-outline-info rounded-pill mt-2">
                                    <i class="fas fa-xmark"><?php echo "Transcation Record"  ?></i>
                                </a>

                                <a href="javascript:void(0);" class="btn btn-outline-info rounded-pill mt-2" onclick="status(<?php echo $id; ?>, 'page', 'status','pid')">
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