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


    <?php
    $role = $_SESSION['role'];
    if (in_array($role, ['Agent', 'Supervisor', 'Manager', 'Admin'])) {
        // The user is a manager, let them stay on the page
        // You can continue to load the rest of the page here
    } else {
        // The user is not a manager, redirect them to the login page
        header('Location: ./Login_to_CustCount'); // Replace 'login.php' with the path to your login page
        exit(); // Prevent further execution of the script
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Deposits List</h4>
                        </div>
                        <?php
                        include './App/db/db_connect.php';
                        $branch = $_SESSION['branch1'];
                        $page = $_SESSION['page1'];
                        $role = $_SESSION['role'];
                        if ($role == 'Manager' || $role == 'Supervisor') {
                            $sql = "SELECT * FROM transaction where type='Credit' AND branch='$branch'";
                        } elseif ($role == 'Agent') {
                            $page = $_SESSION['page1'];

                            $pagesArray = explode(", ", $page);
                            $quotedPages = [];
                            foreach ($pagesArray as $pageName) {
                                $quotedPages[] = "'" . mysqli_real_escape_string($conn, $pageName) . "'";
                            }
                            $whereClause = "page IN (" . implode(", ", $quotedPages) . ")";
                            // $sql = "SELECT * FROM user WHERE Role = 'User' AND $whereClause";

                            $sql = "SELECT * FROM transaction where type='Credit' AND $whereClause";
                        } elseif ($role == 'Admin') {
                            $sql = "SELECT * FROM transaction where type='Credit'";
                        }

                        // $sql = "SELECT * FROM transaction where type='Debit'";

                        $result = $conn->query($sql);

                        // Check if there are results

                        if ($result->num_rows > 0) {
                        ?>
                            <div class="card-body">
                                <div class="custom-table-effect table-responsive  border rounded">
                                    <table class="table mb-0" id="example">
                                        <thead>
                                            <tr class="bg-white">
                                            <?php
                                            echo '<tr>
                                            
                                            <th scope="col">ID</th>
                                            <th scope="col">Username</th>
                                            <th scope="col"> Amount</th>
                                            <th scope="col">Excess Amount</th>
                                            <th scope="col">Tip Amount</th>
                                            <th scope="col">Platform</th>
                                            <th scope="col">Cash App Name</th>
                                            <th scope="col">By Username</th>
                                            <th scope="col">By Role</th>
                                            <th scope="col">Added Time</th>
                                            <th scope="col">Action</th>

                                            </tr></thead><tbody>';

                                            while ($row = $result->fetch_assoc()) {
                                                echo "
                                                <tr>
                                                        <td>{$row['tid']}</td>
                                                        <td>{$row['username']}</td>
                                                        <td>{$row['redeem']}</td>
                                                        <td>{$row['excess']}</td>
                                                        <td>{$row['tip']}</td>
                                                        <td>{$row['platform']}</td>
                                                        <td>{$row['cashapp']}</td>
                                                        <td>{$row['by_u']}</td>
                                                        <td>{$row['by_role']}</td>
                                                        <td>{$row['created_at']}</td>
                                                        <td>
                                                        <a href='javascript:void(0);' onclick='delete1({$row['tid']}, \"transaction\", \"tid\");'>
                                                        &#10060; 
                                                    </a>
                                                                                            </td>
                                                      </tr>";
                                            }
                                            echo '</tbody>';

                                            // End table
                                            echo '</table>';
                                        } else {
                                            echo "0 results";
                                        }

                                        // Close connection
                                        $conn->close();
                                            ?>
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
                                        window.location.reload();
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
    </Script>
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