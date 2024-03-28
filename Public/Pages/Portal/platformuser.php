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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $platformuserid = $conn->real_escape_string($_POST['username']);
        $platfromname = $conn->real_escape_string($_POST['platform']);
        // Assuming $username comes from a session or another source
        $username = $_SESSION['username'] ?? 'defaultUsername'; // Fallback if not set
        $by_add = $_SESSION['username'];

        // Use prepared statements to insert safely into the database
        $stmt = $conn->prepare("INSERT INTO Platformuser (username, platformuserid, platfromname, by_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $platformuserid, $platfromname, $by_add);
        if ($stmt->execute()) {
            echo "<div>Submission Successful!</div>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Fetch platforms from the database
    $sql = "SELECT * FROM platform where status=1";
    $result = $conn->query($sql);

    $platforms = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $platforms[] = $row;
        }
    } else {
        echo "0 results";
    }
    $conn->close();

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
                <h2>Register</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="platformSelect">Platform</label>
                        <select class="form-control" id="platformSelect" name="platform">
                            <?php foreach ($platforms as $platform) : ?>
                                <option value="<?= htmlspecialchars($platform['name']) ?>"><?= htmlspecialchars($platform['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <?php
            // Assuming $conn is your database connection from include './App/db/db_connect.php';
            include './App/db/db_connect.php';

            $sql = "SELECT * FROM PlatformUser where username='$username'"; // Replace 'other_user_info' with other columns you might want to display
            $result = $conn->query($sql);
            ?>

            <div class="container mt-5">
                <div class="row">
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($user = $result->fetch_assoc()) : ?>
                            <div class="col-md-4">
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($user['username']) ?></h5>
                                        <p class="card-text">Some info about the user here.</p>
                                        <!-- Use additional user info if needed -->
                                        <a href="#" class="btn btn-primary">Go somewhere</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <p>No users found.</p>
                    <?php endif; ?>
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