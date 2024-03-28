<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "./Public/Pages/Common/header.php";
    ?>
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
    ?>
    <title>REGISTER PAGE</title>
</head>

<body style="height: 100%; background-color: white;" class=" ">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body ">
                <img src="../assets/images/CustCountFinal.png" style="height: 25%;" alt="loader" class="image-loader img-fluid ">
            </div>
        </div>
    </div>
    <br>
    <br>


    <!-- loader END -->
    <div class="wrapper">
        <section class="login-content overflow-hidden">
            <div class="row no-gutters align-items-center bg-white">
                <div class="col-md-12 col-lg-6 align-self-center">
                    <div class="row justify-content-center">
                        <div style="position: relative ; left: 100px;" class="col-md-12 col-lg-6 align-self-center">
                            <a href="#" class="navbar-brand d-flex align-items-center mb-3 justify-content-center text-primary">
                                <div class="logo-normal">
                                    <img src="../assets/images/CustCountFinal.png" style="height: 100px; " alt="">
                                </div>
                                <h1 style="font-family: 'Times New Roman', Times, serif; color: #39DFE5; font-size: 3em; font-weight: bold; " class="logo-title ms-3 mb-0">CustCount</h1>

                                <h5 style=" text-decoration:double; position: relative; right: 180px; top: 40px; color: limegreen; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;" class="logo-title ms-3 mb-0">COUNT, IMPACT, PROSPER</h5>

                            </a>
                        </div>
                        <div class="col-md-9">
                            <div class="card auth-card  d-flex justify-content-center mb-0">
                                <div class="card-body">
                                    <h2 class="mb-2 text-center">Sign Up</h2>
                                    <p class="text-center">.</p>
                                    <form action="../App/Logic/register.php?action=register" method="POST">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="fullname" class="form-label">Full Name</label>
                                                    <input class="form-control" type="text" id="fullname" name="fullname" placeholder="Enter your name" required="" value="<?php echo isset($_SESSION['form_values']['fullname']) ? htmlspecialchars($_SESSION['form_values']['fullname']) : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="username" class="form-label">User Name</label>
                                                    <input class="form-control" type="text" id="username" name="username" placeholder="Enter your user-name" required="" value="<?php echo isset($_SESSION['form_values']['username']) ? htmlspecialchars($_SESSION['form_values']['username']) : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input class="form-control" type="password" id="password" name="password" placeholder="Enter your password" required="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="fb_link" class="form-label">Facebook Link</label>
                                                    <input class="form-control" type="text" id="fb_link" name="fb_link" placeholder="Enter your Facebook link" required="" value="<?php echo isset($_SESSION['form_values']['fb_link']) ? htmlspecialchars($_SESSION['form_values']['fb_link']) : ''; ?>">
                                                </div>
                                            </div>
                                            <input type="hidden" id="page_id" name="page" value="<?php echo isset($_GET['p']) ? htmlspecialchars($_GET['p']) : ''; ?>">
                                            <input class="form-control" type="hiddden" id="role" name="role" placeholder="Enter your Page ID" required hidden value="User">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="fb_link" class="form-label">Refer Code</label>
                                                    <input class="form-control" type="text" id="rfc" name="rfc" placeholder="Enter your Refer Code" required value="<?php echo isset($_GET['r']) ? htmlspecialchars($_GET['r']) : (isset($_SESSION['form_values']['rfc']) ? htmlspecialchars($_SESSION['form_values']['rfc']) : ''); ?>">
                                                </div>
                                            </div>

                                            <div class="col-lg-12 d-flex justify-content-center">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="checkbox-signup" name="terms" <?php echo (isset($_SESSION['form_values']['terms']) && $_SESSION['form_values']['terms'] == 'on') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="checkbox-signup">I accept <a href="javascript: void(0);" class="text-muted">Terms and Conditions</a></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Sign Up</button>
                                        </div>
                                        <p class="text-center my-3">or sign in with other accounts?</p>
                                        <div class="d-flex justify-content-center">
                                            <ul class="list-group list-group-horizontal list-group-flush">

                                                <li class="list-group-item border-0 pb-0">
                                                    <a href="#"><img src="https://templates.iqonic.design/product/qompac-ui/html/dist/assets/images/brands/fb.svg" alt="fb" loading="lazy"></a>
                                                </li>

                                            </ul>
                                        </div>
                                        <p class="mt-3 text-center">
                                            Already have an Account <a href="./Login_to_CustCount" class="text-underline">Sign In</a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-lg-block d-none p-0 overflow-hidden" style="position: relative; right: 80px; background-color: #39DFE5; height: 100%;">
                    <video src="../assets/images/CustCount2.mp4" class="img-fluid gradient-main" alt="images" loop autoplay muted></video>
                </div>
            </div>
        </section>
    </div>

    <?php include "./Public/Pages/Common/scripts.php" ?>

</body>

</html>