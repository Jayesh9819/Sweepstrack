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
                        <div class="box-header with-border">
                        <h3 class="box-title">See All the data</h3>
                        <h6 class="box-subtitle">All The Records</h6>
                    </div>
                        </div>
                        <?php
                        include './App/db/db_connect.php';

                        if (isset($_POST)) {
                         
                            $condition = $_POST['field'];
                            $query = $_POST['condition'];
                            $sql = "select * from transaction where $condition='$query'";
                            $result = $conn->query($sql);
                        }

                        if ($result->num_rows > 0) {
                        ?>
                            <div class="card-body">
                                <div class="custom-table-effect table-responsive  border rounded">

                                        <table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                                <tr>
                                                    <th>Transaction Type</th>
                                                    <th>Recharge</th>
                                                    <th>Redeem</th>
                                                    <th>Excess Amount</th>
                                                    <th>Bonus Amount</th>
                                                    <th>Free Play</th>
                                                    <th>Platform Name</th>
                                                    <th>Page Name</th>
                                                    <th>CashApp Name</th>
                                                    <th>Timestamp</th>
                                                    <th>Username</th>
                                                    <th>By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($result as $row) : ?>
                                                    <tr>
                                                        <td class="<?= ($row['type'] === 'Debit') ? 'Debit' : 'Credit' ?>">
                                                            <?= $row['type'] ?>
                                                        </td>
                                                        <td><?= $row['recharge'] ?></td>
                                                        <td><?= $row['redeem'] ?></td>
                                                        <td><?= $row['excess'] ?></td>
                                                        <td><?= $row['bonus'] ?></td>
                                                        <td><?= $row['freepik'] ?></td>

                                                        <td><?= $row['platform'] ?></td>
                                                        <td><?= $row['page'] ?></td>
                                                        <td><?= $row['cashapp'] ?></td>

                                                        <td><?= $row['created_at'] ?></td>
                                                        <td><?= $row['username'] ?></td>
                                                        <td><?= $row['by_u'] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php
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

    <!-- Library Bundle Script -->
    <?php
    include("./Public/Pages/Common/scripts.php");

    ?>

</body>

</html>