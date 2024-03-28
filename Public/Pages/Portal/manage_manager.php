
<!doctype html>
<html lang="en" dir="ltr">


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
        <?php
        include './App/db/db_connect.php';
        // include './App/db/db_users.php';
        $sql = "SELECT * FROM user WHERE Role = 'Manager'";


        $result = $conn->query($sql);

        // Check if there are results

        if ($result->num_rows > 0) {

        ?>

            <div class="content-inner container-fluid pb-0" id="page_layout">


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">Search Manager</h4>
                                    <p>Manager by name</p>
                                    <form action="./add_user" method="POST">
                                        <input type="text" name="role" value="Manager" hidden>
                                        <button class="btn btn-outline-success rounded-pill mt-2" type="submit">Add Manager </button>
                                    </form>

                                </div>
                            </div>

                            <!-- Select Dropdown -->


                            <div class="card-body">
                                <form action="./update_manager" method="POST">
                                    <select class="select2-basic-single js-states form-select form-control" name="state" id="userSelect" style="width: 100%;" required>
                                        <option value="" disabled hidden>Select Manager</option>
                                        <?php
                                        while ($row = $result->fetch_assoc()) {

                                        ?>

                                            <option name="userdata" value="<?php echo $row['username'] ?>"> <?php echo $row['username'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>

                                    </select>
                                    <br>
                                    <br>
                                    <button class="btn btn-outline-success rounded-pill mt-2" type="submit">Update </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Manager List</h4>
                                <form action="./add_user" method="POST">
                                        <input type="text" name="role" value="Manager" hidden>
                                        <button class="btn btn-outline-success rounded-pill mt-2" type="submit">Add Manager </button>
                                    </form>

                            </div>
                            <?php
                            // include './App/db/db_connect.php';
                            // $sql = "SELECT * FROM users";

                            $result = $conn->query($sql);

                            // Check if there are results

                            if ($result->num_rows > 0) {
                            ?>

                                <div class="card-body">
                                    <div class="custom-table-effect table-responsive  border rounded">
                                    <table class="table mb-0" id="example" >
                                            <thead>
                                                <tr class="bg-white">
                                                    <?php
                                                    echo '<tr>
                                            
                                            <th scope="col">ID</th>
                                            <th scope="col">Update</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Password</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Page ID</th>

                                            <th scope="col">Created At</th>
                                            <th scope="col">Last Login</th>
                                            </tr>';
                                                    ?>
                                                    <thead>
                                                    <tbody>
                                                        <?php
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>
                                                    
                                                    <td>{$row['id']}</td>

                                                    <td>
                    <form action=\"./update_manager\" method=\"post\">
                        <input type=\"hidden\" name=\"state\" value=\"{$row['username']}\">
                        <button type=\"submit\" class=\"btn btn-outline-success rounded-pill mt-2\">Update</button>
                    </form>
                </td>
                                                    <td>{$row['username']}</td>
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['password']}</td>
                                                    <td>{$row['role']}</td>
                                                    <td>{$row['pageid']}</td>

                                                    <td>{$row['created_at']}</td> <!-- Consider if you really want to display passwords -->
                                                    <td>{$row['last_login']}</td>
                                                   
                                                  </tr>";
                                                        }
                                                        ?>

                                                    </tbody>
                                                <?php

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
    <script>


    </script>

    <!-- Library Bundle Script -->
    <script src="../assets/js/core/libs.min.js"></script>
    <!-- Plugin Scripts -->


    <!-- Select2 Script -->
    <script src="../assets/js/plugins/select2.js" defer></script>


    <!-- Slider-tab Script -->
    <script src="../assets/js/plugins/slider-tabs.js"></script>


    <!-- Lodash Utility -->
    <script src="../assets/vendor/lodash/lodash.min.js"></script>
    <!-- Utilities Functions -->
    <script src="../assets/js/iqonic-script/utility.min.js"></script>
    <!-- Settings Script -->
    <script src="../assets/js/iqonic-script/setting.min.js"></script>
    <!-- Settings Init Script -->
    <script src="../assets/js/setting-init.js"></script>
    <!-- External Library Bundle Script -->
    <script src="../assets/js/core/external.min.js"></script>
    <!-- Widgetchart Script -->
    <script src="../assets/js/charts/widgetchartsf700.js?v=1.0.1" defer></script>
    <!-- Dashboard Script -->
    <script src="../assets/js/charts/dashboardf700.js?v=1.0.1" defer></script>
    <!-- qompacui Script -->
    <script src="../assets/js/qompac-uif700.js?v=1.0.1" defer></script>
    <script src="../assets/js/sidebarf700.js?v=1.0.1" defer></script>
    <?php

    // include("./Public/Pages/Common/scripts.php");
    ?>

</body>

</html>