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
    $referName = $_GET['u'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $referName = $_POST['username'];
        $amount = $_POST['amount'];
        $type = "Referred Bonus";
        $byname = $_SESSION['username'];
        $trans = "Credit";

        $sql = "INSERT INTO referrecord (username, amount, type, byname, trans) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsss", $referName, $amount, $type, $byname, $trans);
        if ($stmt->execute()) {
            header("Location: ./Set_Refer");
            echoToastScript('success', 'Record inserted successfully');
        }else{
            header("Location: " . $_SERVER['PHP_SELF']);
            echoToastScript('error', 'Record Not inserted ');



        }

        // Display success toast
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

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3>Refer Recharge Form</h3>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST">

                                    <div class="mb-3">
                                        <label for="referralPercentage" class="form-label">UserName </label>
                                        <input type="text" class="form-control" id="referralPercentage" name="username" required value="<?php echo htmlspecialchars($referName); ?>" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="referralPercentage" class="form-label">Amount </label>
                                        <input type="text" class="form-control" id="referralPercentage" name="amount" required value="">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>






        <?

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