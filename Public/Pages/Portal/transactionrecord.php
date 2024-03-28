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
    $_SESSION['timezone']='America/New_York';

    if (session_status() !== PHP_SESSION_ACTIVE) session_start();

    // Display error message if available
    if (isset($_SESSION['login_error'])) {
        echo '<p class="error">' . $_SESSION['login_error'] . '</p>';
        unset($_SESSION['login_error']); // Clear the error message
    }

 
    ?>
    <!-- css -->
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .cashin {
            color: green;
        }

        .cashout {
            color: red;
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
            <!-- /.box-header -->
            <div class="box-body">
                <!-- <div class="table-responsive"> -->
                    <!-- <table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">

                    </table> -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">See All the data</h3>
                            <h6 class="box-subtitle">All The Records</h6>
                        </div>



                        <?php
                        include "./App/db/db_connect.php";

                        if (isset($_GET['u'])) {
                            $u = $_GET['u'];
                            $sql = "SELECT * FROM transaction WHERE username = ?";
                        
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('s', $u);
                            $stmt->execute();

                            $result = $stmt->get_result();
                            $results = $result->fetch_all(MYSQLI_ASSOC);

                            $stmt->close();
                            $conn->close();

                            if (empty($results)) {
                                echo "No records found";
                            } else {
                                usort($results, function ($a, $b) {
                                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                                });
                        ?>

                                <div class="table-responsive">

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
                                            <?php foreach ($results as $row) : ?>
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
                            }
                        } else {
                            echo "User parameter ('u') not provided.";
                        }
                            ?>

                            <!-- echo -->

                            <?
                            include("./Public/Pages/Common/footer.php");
                       
                            ?>
                                </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script>
            $(document).ready(function() {
                $('#example').DataTable({
                    "order": [
                        [9, "desc"]
                    ],
                    dom: 'Bfrtip', // Add the Bfrtip option to enable buttons

                    buttons: [
            'copy', 'excel', 'pdf'
        ]
                });
            });



    </script>
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
