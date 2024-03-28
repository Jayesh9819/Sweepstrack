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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        foreach ($_POST as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    // Store GET parameters in session
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        foreach ($_GET as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
    $_SESSION['timezone'] = 'America/New_York';

    if (isset($_SESSION['timezone'])) {
        $selectedTimezone = $_SESSION['timezone'];
        // Set the default timezone to the selected timezone
        date_default_timezone_set($selectedTimezone);
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
            <form method="GET" action="#">
                <input type="hidden" name="u" value="<?php echo isset($_SESSION['u']) ? htmlspecialchars($_SESSION['u']) : ''; ?>">
                <input type="hidden" name="r" value="<?php echo isset($_SESSION['r']) ? htmlspecialchars($_SESSION['r']) : ''; ?>">

                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="start_date" class="col-form-label">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($_SESSION['start_date']) ? htmlspecialchars($_SESSION['start_date']) : ''; ?>">
                    </div>
                    <div class="col-auto">
                        <label for="end_date" class="col-form-label">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo isset($_SESSION['end_date']) ? htmlspecialchars($_SESSION['end_date']) : ''; ?>">
                    </div>
                    <label for="timezone">Select Timezone:</label>
                    <select name="timezone" id="timezone">
                        <option value="America/New_York" <?php echo ($_SESSION['timezone'] ?? '') === 'America/New_York' ? 'selected' : ''; ?>>EST (America/New York)</option>
                        <option value="America/Chicago" <?php echo ($_SESSION['timezone'] ?? '') === 'America/Chicago' ? 'selected' : ''; ?>>CST (America/Chicago)</option>
                        <option value="Asia/Kolkata" <?php echo ($_SESSION['timezone'] ?? '') === 'Asia/Kolkata' ? 'selected' : ''; ?>>IST (Asia/Kolkata)</option>
                    </select>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>

                </div>
            </form>

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
                    $username = $_SESSION['username'];

                    $sql = "SELECT * FROM transaction WHERE username='$username'"; // Always true condition to start the WHERE clause
                    if (isset($_SESSION['start_date']) && isset($_SESSION['end_date']) && $_SESSION['start_date'] !== '' && $_SESSION['end_date'] !== '') {
                        // Both start and end dates are provided
                        $start_date = $_SESSION['start_date'];
                        $end_date = $_SESSION['end_date'];
                        $sql .= " AND created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
                    } elseif (isset($_SESSION['start_date']) && !isset($_SESSION['end_date']) && $_SESSION['start_date'] !== '') {
                        // Only start date is provided
                        $start_date = $_SESSION['start_date'];
                        $sql .= " AND created_at >= '$start_date 00:00:00'";
                    } elseif (!isset($_SESSION['start_date']) && isset($_SESSION['end_date']) && $_SESSION['end_date'] !== '') {
                        // Only end date is provided
                        $end_date = $_SESSION['end_date'];
                        $sql .= " AND created_at <= '$end_date 23:59:59'";
                    } elseif (isset($_SESSION['start_date']) && isset($_SESSION['end_date']) && $_SESSION['start_date'] !== '' && $_SESSION['end_date'] === '') {
                        // Only start date is provided and end date is empty
                        $start_date = $_SESSION['start_date'];
                        $sql .= " AND created_at >= '$start_date 00:00:00'";
                    }


                    $stmt = $conn->prepare($sql);
                    // $stmt->bind_param('s', $u);
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
                                        <th>Bonus Amount</th>
                                        <th>Free Play</th>
                                        <th>Platform Name</th>
                                        <th>CashApp Name</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $row) :
                                        $createdAt = new DateTime($row['created_at'], new DateTimeZone('UTC'));
                                        $createdAt->setTimezone(new DateTimeZone($selectedTimezone));
                                        $createdAtFormatted = $createdAt->format('Y-m-d H:i:s');

                                    ?>

                                        <tr>
                                            <td class="<?= ($row['type'] === 'Debit') ? 'Debit' : 'Credit' ?>">
                                                <?= $row['type'] ?>
                                            </td>
                                            <td><?= $row['recharge'] ?></td>
                                            <td><?= $row['redeem'] ?></td>
                                            <td><?= $row['bonus'] ?></td>
                                            <td><?= $row['freepik'] ?></td>
                                            <td><?= $row['platform'] ?></td>
                                            <td><?= $row['cashapp'] ?></td>

                                            <td><?= $createdAtFormatted ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        <?php
                    }

                        ?>
                        </div>

                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#example').DataTable({
                    "order": [
                        [7, "desc"]
                    ],
                    dom: 'Bfrtip', // Add the Bfrtip option to enable buttons

                    buttons: [
                        'copy', 'excel', 'pdf'
                    ]
                });
            });
        </script>

        <!-- echo -->

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