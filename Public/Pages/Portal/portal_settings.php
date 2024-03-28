<!doctype html>
<html lang="en" dir="ltr">

<head>
    <?php
    include("./Public/Pages/Common/header.php");
    include "./Public/Pages/Common/auth_user.php";

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
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time_zone'])) {
        $userId = $_SESSION['user_id'];
        $newTimeZone = $_POST['time_zone'];

        $sql = "UPDATE user SET timezone = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$newTimeZone, $userId]);
        unset($_SESSION['timezone']); 
        $_SESSION['timezone']=$newTimeZone;

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Time zone updated successfully'];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Handle password change
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'], $_POST['confirm_password'])) {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            $userId = $_SESSION['user_id'];

            $sql = "UPDATE user SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$newPassword, $userId]);

            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Password Chnaged.'];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Passwords do not match'];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    // Handle profile picture upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
        $userId = $_SESSION['user_id'];
        // $profilePicture = $_FILES['profile_picture'];
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/profile/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $profilePicture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileName = time() . '-' . basename($_FILES['profile_picture']['name']);
            $targetFilePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                $profilePicture = $fileName;
            } else {
                echo "Error uploading file.";
                exit;
            }
        }


        $sql = "UPDATE user SET p_p = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$profilePicture, $userId]);
        unset($_SESSION['p_p']); 
        $_SESSION['p_p']=$profilePicture;

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Profile picture updated successfully'];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .settings-form {
            margin-bottom: 20px;
        }
    </style>


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
                <div class="col-md-8 col-lg-6 mx-auto">
                    <h3 class="mt-4 mb-3">Your Settings</h3>

                    <!-- Time Zone Update Form -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="settings-form">
                        <div class="form-group">
                            <label for="time_zone">Select your time zone:</label>
                            <select name="time_zone" id="time_zone" class="form-control">
                                <?php
                                $timezones = DateTimeZone::listIdentifiers();
                                foreach ($timezones as $timezone) {
                                    $selected = ($_SESSION['timezone'] ?? 'UTC') === $timezone ? ' selected' : '';
                                    echo "<option value=\"$timezone\"$selected>$timezone</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Time Zone</button>
                    </form>

                    <!-- Password Change Form -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="settings-form">
                        <div class="form-group">
                            <input type="password" name="new_password" placeholder="New Password" required class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm_password" placeholder="Confirm New Password" required class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>

                    <!-- Profile Picture Update Form -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="settings-form">
                        <div class="form-group">
                            <input type="file" name="profile_picture" required class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-primary">Upload Picture</button>
                    </form>
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
    function echoToastScript($type, $message)
    {
        echo "<script type='text/javascript'>document.addEventListener('DOMContentLoaded', function() { toastr['$type']('$message'); });</script>";
    }

    ?>
    <!-- Live Customizer end -->

    <?php
    include("./Public/Pages/Portal/scripts.php");

    ?>

</body>

</html>