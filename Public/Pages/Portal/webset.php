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
    include "./App/db/db_connect.php"; // Ensure this path is correct for your DB connection script

    // Function to save uploaded files
    function saveUploadedFile($fileInfo, $allowedExtensions = ['jpg', 'png', 'gif', 'ipa', 'apk']) {
        if ($fileInfo['error'] == UPLOAD_ERR_NO_FILE) {
            return null;
        }
    
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        $fileExt = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($fileExt), $allowedExtensions)) {
            throw new Exception("Invalid file type.");
        }
    
        $safeName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', basename($fileInfo['name']));
        $filePath = $uploadDir . $safeName;
    
        if (move_uploaded_file($fileInfo['tmp_name'], $filePath)) {
            // Check if the file is an app file and return a full URL
            if (in_array(strtolower($fileExt), ['ipa', 'apk'])) {
                $webPath = 'https://' . $_SERVER['HTTP_HOST'] . '/uploads/'; // Adjust 'http' to 'https' if necessary
                return $webPath . $safeName;
            } else {
                // Return the path relative to the document root for other files
                return '/uploads/' . $safeName;
            }
        } else {
            throw new Exception("Failed to move the uploaded file.");
        }
    }
    
    


    // Handling form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {



        try {
            $settings = [
                'name' => $_POST['name'] ?? '',
                'slogan' => $_POST['slogan'] ?? '',
                'color' => $_POST['themeColor'] ?? '#ffffff',
                'logo' => isset($_FILES['logo']) ? saveUploadedFile($_FILES['logo'], ['jpg', 'png', 'gif']) : null,
                'banner' => isset($_FILES['banner']) ? saveUploadedFile($_FILES['banner'], ['jpg', 'png', 'gif','webp']) : null,
                'icon' => isset($_FILES['icon']) ? saveUploadedFile($_FILES['icon'], ['jpg', 'png', 'gif']) : null,
                'loader' => isset($_FILES['loader']) ? saveUploadedFile($_FILES['loader'], ['gif','jpg', 'png']) : null,
                'ioslink' => isset($_FILES['iosApp']) ? saveUploadedFile($_FILES['iosApp'], ['ipa']) : null,
                'androidlink' => isset($_FILES['androidApp']) ? saveUploadedFile($_FILES['androidApp'], ['apk']) : null
            ];

            foreach ($settings as $key => $value) {
                if ($value !== null) { // Check if a new value has been provided
                    $sql = "UPDATE websetting SET value = ? WHERE name = ?";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $conn->error);
                    }
                    $stmt->bind_param("ss", $value, $key);
                    if (!$stmt->execute()) {
                        throw new Exception("Execute failed: " . $stmt->error);
                    }
                }
            }
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Settings updated successfully'];

            $stmt->close();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];

        }

        // $conn->close();
    }


    // Fetch current settings
    $currentSettings = [];
    $sql = "SELECT * FROM websetting";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $currentSettings[$row['name']] = $row['value'];
        }
    }


    ?>

</head>

<body class="  ">
    <?php
    include("./Public/Pages/Common/sidebar.php");

    ?>

    <main class="main-content">
        <?php
        include("./Public/Pages/Common/main_content.php");
        ?>
        <div class="content-inner container-fluid pb-0" id="page_layout">
            <div class="container mt-5">
                <h2>Update Website Settings</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $currentSettings['name'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo:</label>
                        <input type="file" class="form-control" id="logo" name="logo">
                        <?php if (isset($currentSettings['logo'])) : ?>
                            <img src="<?php echo $currentSettings['logo']; ?>" alt="Current Logo" style="width: 100px; height: auto;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="slogan">Slogan:</label>
                        <input type="text" class="form-control" id="slogan" name="slogan" value="<?php echo $currentSettings['slogan'] ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="icon">Icon:</label>
                        <input type="file" class="form-control" id="icon" name="icon">
                        <?php if (isset($currentSettings['icon'])) : ?>
                            <img src="<?php echo $currentSettings['icon']; ?>" alt="Current Icon" style="width: 50px; height: auto;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="loader">Loader GIF:</label>
                        <input type="file" class="form-control" id="loader" name="loader">
                        <?php if (isset($currentSettings['loader'])) : ?>
                            <img src="<?php echo $currentSettings['loader']; ?>" alt="Current Loader" style="width: 100px; height: auto;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="icon">Banner:</label>
                        <input type="file" class="form-control" id="banner" name="banner">
                        <?php if (isset($currentSettings['banner'])) : ?>
                            <img src="<?php echo $currentSettings['banner']; ?>" alt="Current Icon" style="width: 100px; height: auto;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="themeColor">Theme Color:</label>
                        <input type="color" class="form-control" id="themeColor" name="themeColor" value="<?php echo $currentSettings['color'] ?? '#ffffff'; ?>">
                    </div>
                    <div class="form-group">
                        <label for="iosApp">iOS App (.iab):</label>
                        <input type="file" class="form-control" id="iosApp" name="iosApp" accept=".ipa">
                    </div>
                    <div class="form-group">
                        <label for="androidApp">Android App (.apk):</label>
                        <input type="file" class="form-control" id="androidApp" name="androidApp" accept=".apk">
                    </div>

                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </form>
            </div>

            <!-- Bootstrap JavaScript -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


        </div>
    </main>
    <?php
    include("./Public/Pages/Common/theme_custom.php");
    ?>
    <?php
    include("./Public/Pages/Common/settings_link.php");

    ?>
    <?php
    include("./Public/Pages/Common/scripts.php");
    ?>

</body>

</html>