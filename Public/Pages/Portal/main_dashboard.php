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

            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <h4 class="mb-5">Analytics Overview</h4>
                        <div class="col-lg-3 col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="mb-3">862</h2>
                                    <h5>Users</h5>
                                    <small>10% Decrease last week</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="mb-3">4.1M</h2>
                                    <h5>Sessions</h5>
                                    <small>24% Decrease last week</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="mb-3">143</h2>
                                    <h5>Visit Duration</h5>
                                    <small>10% change</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="mb-3">32%</h2>
                                    <h5>Bounce Rate</h5>
                                    <small>10% change</small>
                                </div>
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

    <?php
    include("./Public/Pages/Common/scripts.php");
    ?>

</body>

</html>