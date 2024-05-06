<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <?php include     "./Public/Pages/Common/header.php";



    ?>

    <!-- Include jQuery first -->

    <?php
    session_start(); // Ensure session is started

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
    <link rel="stylesheet" href="../Public/Pages/Signing/login/style.css">

    <title>LOGIN PAGE</title>

</head>


<body style="height: 100%; background-color: white;" class="">
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body ">
                <img src="<?php echo $settings['loader']; ?>" style="height: 25%;" alt="loader" class="image-loader img-fluid ">
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="wrapper">
        <section class="login-content overflow-hidden">
            <div class="row no-gutters align-items-center bg-white">
                <div class="col-md-12 col-lg-6 align-self-center">
                    <div class="row justify-content-center">
                        <div style="position: relative ; left: -50px;" class="col-md-12 col-lg-6 align-self-center">
                            <a href="#" class="navbar-brand d-flex align-items-center mb-3 justify-content-center text-primary">
                                <div class="logo-normal">
                                    <img src="<?php echo $settings['logo']; ?>" style=" height: 100px; " alt="">
                                </div>
                                <h1 style="font-family: 'Times New Roman', Times, serif; color:<?php echo $settings['color']; ?>; font-size: 3em; font-weight: bold; " class="logo-title ms-3 mb-0"><?php echo $settings['name']; ?></h1>

                                <h5 style=" text-decoration:double; position: relative; right: 180px; top: 40px; color: <?php echo $settings['color']; ?>; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" class="logo-title ms-3 mb-0"><?php echo $settings['slogan']; ?></h5>

                            </a>
                        </div>
                        <div class="row justify-content-center pt-5">
                            <div class="col-md-9">
                                <div class="card  d-flex justify-content-center mb-0 auth-card iq-auth-form">
                                    <div class="card-body">
                                        <h2 class="mb-2 text-center" style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">Sign In</h2>
                                    <p class=" text-center">Login to stay connected.</p>
                                            <form action="../App/Logic/login.php" method="post">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="username" class="form-label">User Name</label>
                                                            <input value="<?php echo isset($_SESSION['login_form_values']['username']) ? htmlspecialchars($_SESSION['login_form_values']['username']) : ''; ?>" class="form-control" type="text" id="username" name="username" placeholder="Enter your user-name" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="password" class="form-label">Password</label>
                                                            <input class="form-control" type="password" required="" id="password" name="password" placeholder="Enter your password">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 d-flex justify-content-between">
                                                        <div class="form-check mb-3">
                                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                                            <label class="form-check-label" for="customCheck1">Remember Me</label>
                                                        </div>
                                                        <a href="#">Forgot Password?</a>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-primary">Sign In</button>
                                                </div>
                                                <p class="text-center my-3">or sign in with other accounts?</p>
                                                <!-- For Android -->
                                                </p>

                                                <div class="d-flex justify-content-center">
                                                    <ul class="list-group list-group-horizontal list-group-flush">

                                                        <li class="list-group-item border-0 pb-0">
                                                            <a href="#"><img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/brands/fb.svg" alt="fb" loading="lazy"></a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </form>
                                            <p class="mt-3 text-center">
                                                <button onclick="window.location.href='<?php echo $settings['androidlink']; ?>'" class="btn btn-primary">Download for Android</button>

                                                <!-- For iOS -->
                                                <button onclick="window.location.href='<?php echo $settings['ioslink']; ?>'" class="btn btn-primary">Download for iOS</button>
                                            <p class="mt-3 text-center">
                                                For iOS Install using Scarlet or AltStore or similar tools
                                            </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-lg-block d-none p-0 overflow-hidden" style="position: relative; right: 80px; background-color: #39DFE5;">
                    <img src="<?php echo $settings['banner']; ?>" class="img-fluid gradient-main" alt="images" loop autoplay muted></img>
                </div>
            </div>
            <div id="chatButton" class="chat-button">
                Open Chat
            </div>
            <div id="userFormModal" class="modal">
                <div class="modal-content">
                    <span class="close-button">&times;</span>
                    <form id="userInfoForm">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                        <label for="refercode">Refer Code (Optional):</label>
                        <input type="text" id="refercode" name="refercode">
                        <button type="submit">Start Chat</button>
                    </form>
                </div>
            </div>

        </section>
    </div>

    <script src="../Public/Pages/Signing/login/script.js"></script>

    <?php include "./Public/Pages/Common/scripts.php" ?>

</body>

</html>