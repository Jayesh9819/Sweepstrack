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
    ;
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
    ?>
    <style>

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
                <div class="col-sm-12">
                    <h3 class="mt-4 mb-3">Your Notes</h3>
                    <!-- <a href="#" type="button"  class="btn btn-info rounded-pill mt-2 ">
                    
                <svg fill="none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.33 2H16.66C20.06 2 22 3.92 22 7.33V16.67C22 20.06 20.07 22 16.67 22H7.33C3.92 22 2 20.06 2 16.67V7.33C2 3.92 3.92 2 7.33 2ZM12.82 12.83H15.66C16.12 12.82 16.49 12.45 16.49 11.99C16.49 11.53 16.12 11.16 15.66 11.16H12.82V8.34C12.82 7.88 12.45 7.51 11.99 7.51C11.53 7.51 11.16 7.88 11.16 8.34V11.16H8.33C8.11 11.16 7.9 11.25 7.74 11.4C7.59 11.56 7.5 11.769 7.5 11.99C7.5 12.45 7.87 12.82 8.33 12.83H11.16V15.66C11.16 16.12 11.53 16.49 11.99 16.49C12.45 16.49 12.82 16.12 12.82 15.66V12.83Z" fill="currentColor" />
  </svg>

                Add Notes</a> -->
                    <br>
                    <br>
                </div>
                <div class="col-lg-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <form action="../App/Logic/add_notes.php" method="post">
                                <input type="text" required class="bg-primary form-control" style="font-weight: bold; font-size: 16px; color: white; background-color: #007bff; " id="titleInput" name="title" placeholder="Add Title">
                                <br>
                                <textarea required style="font-weight: bold; font-size: 16px; color: white; background-color: #007bff;  " class="form-control bg-primary" id="contentInput" name="content" rows="3" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante."></textarea>
                                <!-- <footer class="blockquote-footer text-white font-size-12">Someone famous in <cite title="Source Title" class="text-white">Source Title</cite></footer> -->
                                <br>
                                <button type="submit" class="btn btn-light">Add Note</button>
                        </div>
                        </form>
                    </div>
                </div>
                <?php
                $username = $_SESSION['username'];
                $role = $_SESSION['role'];
                $sql = "SELECT * FROM notes WHERE by_username ='$username' ORDER BY created_at DESC ";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-lg-4">';
                        echo '    <div class="card text-white bg-primary mb-3" style="position: relative;">'; // Added relative positioning for absolute delete icon positioning
                        echo '        <div class="card-body">';
                        echo '            <a href="../App/Logic/delete_note.php?id=' . htmlspecialchars($row["nid"]) . '" style="font-size: 34px; position: absolute; top: 0px; right: 10px; color: white; text-decoration: none;" onclick="return confirm(\'Are you sure you want to delete this note?\');">&times;</a>'; // Delete icon/button
                        echo '            <h4 class="card-title text-white">' . htmlspecialchars($row["tittle"]) . '</h4>';
                        echo '            <blockquote class="blockquote mb-0">';
                        echo '                <p class="font-size-14">' . htmlspecialchars($row["content"]) . '</p>';
                        echo '                <footer class="blockquote-footer text-white font-size-12">' . htmlspecialchars($row["created_at"]) . '</cite></footer>';
                        echo '            </blockquote>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No NOTES YET </p>';
                }
                $conn->close();
                ?>
                <!-- <div class="col-lg-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h4 class="card-title text-white">Title</h4>
                        <blockquote class="blockquote mb-0">
                            <p class="font-size-14">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer text-white font-size-12">Someone famous in <cite title="Source Title" class="text-white">Source Title</cite></footer>
                        </blockquote>

                        
                    </div>
                </div>
            </div> -->

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