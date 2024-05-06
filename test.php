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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            cursor: pointer;
        }
    </style>


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
            <br>
            <div class="box-body">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">See All the data</h3>
                        <h6 class="box-subtitle">All The Records</h6>
                    </div>



                    <?php
                    include "./App/db/db_connect.php";
                    $role = $_SESSION['role'];
                    $page = '';
                    $page = $_SESSION['page1'];
                    $branch = $_SESSION['branch1'];


                    if ($role == 'Admin') {
                        $sql = "SELECT * FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0)";
                    } elseif ($role == "Agent") {
                        $pagesArray = explode(", ", $page);
                        $quotedPages = [];
                        foreach ($pagesArray as $pageName) {
                            $quotedPages[] = "'" . mysqli_real_escape_string($conn, $pageName) . "'";
                        }
                        $whereClause = "page IN (" . implode(", ", $quotedPages) . ")";
                        // $sql = "SELECT * FROM user WHERE Role = 'User' AND $whereClause";

                        $sql = "SELECT * FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0) AND $whereClause AND approval_status=0 ";
                    } elseif ($role == "Manager" || $role == "Supervisor") {
                        $sql = "SELECT * FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0) AND branch='$branch' AND approval_status=1";
                    } else {
                        $sql = "SELECT * FROM transaction WHERE Redeem != 0 AND Redeem IS NOT NULL AND (redeem_status = 0 OR cashout_status = 0) AND page='$page'";
                    }
                    // echo $sql;
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
                                        <th>Timestamp</th>
                                        <th>Username</th>
                                        <th>Amount</th>
                                        <th>Platform Name</th>
                                        <th>Page Name</th>
                                        <th>Cash Tag</th>

                                        <?php
                                        if ($role == 'Admin') {
                                            echo '
                      <th>Approval</th>
                      <th>Approved By</th>
                      <th>Platform Redeem</th>
                      <th>Redeem By</th> 
                      <th>Cash Out</th>
                      <th>Cashout By</th>';
                                        } elseif ($role == 'Manager' || $role == 'Supervisor') {
                                            echo '<th>Approved By</th><th>Platform Redeem</th>
                      <th>Redeem By</th> 
                      <th>Cash Out</th>
                      <th>Cashout By</th><th>Message</th>';
                                        } elseif ($role == 'Agent') {
                                            echo '<th>Approve</th>
                      <th>Reject</th>';
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $row) :
                                        $createdAt = new DateTime($row['created_at'], new DateTimeZone('UTC'));
                                        $createdAtFormatted = $createdAt->format('Y-m-d H:i:s');
                                        $id = $row['tid'];
                                    ?>
                                        <tr>
                                            <td><?= $createdAtFormatted ?></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td><?= htmlspecialchars($row['redeem']) ?></td>
                                            <td><?= htmlspecialchars($row['platform']) ?></td>
                                            <td><?= htmlspecialchars($row['page']) ?></td>
                                            <td><?= htmlspecialchars($row['cashtag']) ?></td>

                                            <?php if ($role == 'Admin') : ?>
                                                <td>
                                                    <?php if ($row['approval_status'] == 0) : ?>
                                                        <button class="btn btn-warning" onclick="status(<?= $id; ?>, 'transaction', 'approval_status', 'tid','approved_by')">Pending</button>
                                                    <?php elseif ($row['approval_status'] == 1) : ?>
                                                        <button class="btn btn-success">Approved</button>
                                                    <?php elseif ($row['approval_status'] == 2) : ?>
                                                        <button class="btn btn-danger">Rejected</button>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>

                                            <?php if ($role == 'Admin' || $role == 'Manager' || $role == 'Supervisor') : ?>
                                                <td><?= htmlspecialchars($row['approved_by']) ?></td>

                                                <td><?= htmlspecialchars($row['redeem_by']) ?></td>
                                                <td>
                                                    <button class="btn btn-<?= $row['redeem_status'] == 0 ? 'warning' : 'success' ?>" onclick="status(<?= $id; ?>, 'transaction', 'redeem_status', 'tid','redeem_by')">
                                                        <?= $row['redeem_status'] == 0 ? 'Pending' : 'Done' ?>
                                                    </button>
                                                </td>
                                                <td><?= htmlspecialchars($row['cashout_by']) ?></td>
                                                <td>
                                                    <button class="btn btn-<?= $row['cashout_status'] == 0 ? 'warning' : 'success' ?>" onclick="openModal(<?= $id; ?>)">
                                                        <?= $row['cashout_status'] == 0 ? 'Pending' : 'Done' ?>
                                                    </button>
                                                </td>
                                                <td><?= !empty($row['Reject_msg']) ? htmlspecialchars($row['Reject_msg']) : ' ' ?></td>

                                            <?php elseif ($role == 'Agent') : ?>
                                                <td>
                                                    <button class="btn btn-primary" onclick="status(<?= $id; ?>, 'transaction', 'approval_status', 'tid','approved_by')">Approve</button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger" onclick="Reject(<?= $id; ?>, 'transaction', 'approval_status', 'tid','approved_by')">Reject</button>
                                                </td>
                                            <?php endif; ?>
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
        <?php
        include("./Public/Pages/Common/theme_custom.php");
        ?>
        <?php
        include("./Public/Pages/Common/settings_link.php");

        ?>
        <?php
        include("./Public/Pages/Common/scripts.php");
        ?>
    </main>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Select from the Dropdown</h2>
            <form action="javascript:void(0);" onsubmit="submitCashapp()">
                <?php
                include './App/db/db_connect.php';

                $sql = "SELECT * FROM cashapp";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo '<select id="cashappName">';
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['cid'] . '">' . htmlspecialchars($row['name']) . '</option>';
                    }
                    echo '</select>';
                } else {
                    echo "0 results";
                }

                $conn->close();
                ?>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById("myModal");
        var selectedId;

        function openModal(id) {
            selectedId = id; // Store ID when modal is opened
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        function submitCashapp() {
            var cashapps = document.getElementById("cashappName").value;
            console.log(cashapps);
            closeModal(); // Close the modal
            cashapp(selectedId, cashapps, 'transaction', 'cashout_status', 'tid', 'cashout_by'); // Call the function with parameters
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        function status(product_id, table, field, id, where) {
            if (confirm("Are you sure you want to Chnage the Status?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../App/Logic/commonf.php?action=Approval", true);

                // Set the Content-Type header
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Include additional parameters in the data sent to the server
                const data = "id=" + product_id + "&table=" + table + "&field=" + field + "&cid=" + id + "&where=" + where;

                // Log the data being sent
                console.log("Data sent to server:", data);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        console.log("XHR status:", xhr.status);

                        if (xhr.status === 200) {
                            console.log("Response received:", xhr.responseText);

                            try {
                                const response = JSON.parse(xhr.responseText);

                                if (response) {
                                    console.log("Parsed JSON response:", response);

                                    if (response.success) {
                                        alert("Done successfully!");
                                        location.reload();
                                    } else {
                                        alert("Error : " + response.message);
                                    }
                                } else {
                                    console.error("Invalid JSON response:", xhr.responseText);
                                    alert("Invalid JSON response from the server.");
                                }
                            } catch (error) {
                                console.error("Error parsing JSON:", error);
                                alert("Error parsing JSON response from the server.");
                            }
                        } else {
                            console.error("HTTP request failed:", xhr.statusText);
                            alert("Error: " + xhr.statusText);
                        }
                    }
                };

                // Log any network errors
                xhr.onerror = function() {
                    console.error("Network error occurred.");
                    alert("Network error occurred. Please try again.");
                };

                // Send the request
                xhr.send(data);
            }
        }

        function cashapp(product_id, cashapps, table, field, id, cashout_by) {

            if (confirm("Are you sure you want to Chnage the Status?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../App/Logic/commonf.php?action=cashapp", true);

                // Set the Content-Type header
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Include additional parameters in the data sent to the server
                const data = "id=" + product_id + "&table=" + table + "&field=" + field + "&cid=" + id + "&cashapp=" + cashapps;

                // Log the data being sent
                console.log("Data sent to server:", data);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        console.log("XHR status:", xhr.status);

                        if (xhr.status === 200) {
                            console.log("Response received:", xhr.responseText);

                            try {
                                const response = JSON.parse(xhr.responseText);

                                if (response) {
                                    console.log("Parsed JSON response:", response);

                                    if (response.success) {
                                        alert("Done successfully!");
                                        location.reload();
                                    } else {
                                        alert("Error : " + response.message);
                                    }
                                } else {
                                    console.error("Invalid JSON response:", xhr.responseText);
                                    alert("Invalid JSON response from the server.");
                                }
                            } catch (error) {
                                console.error("Error parsing JSON:", error);
                                alert("Error parsing JSON response from the server.");
                            }
                        } else {
                            console.error("HTTP request failed:", xhr.statusText);
                            alert("Error: " + xhr.statusText);
                        }
                    }
                };

                // Log any network errors
                xhr.onerror = function() {
                    console.error("Network error occurred.");
                    alert("Network error occurred. Please try again.");
                };

                // Send the request
                xhr.send(data);
            }
        }

        function Reject(tid, table, field, id) {
            const msg = prompt("Enter the Reason to Reject");

            if (confirm("Are you sure you want to Reject?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../App/Logic/commonf.php?action=Reject", true);

                // Set the Content-Type header
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Include additional parameters in the data sent to the server
                const data = "id=" + tid + "&table=" + table + "&field=" + field + "&cid=" + id + "&msg=" + msg;

                // Log the data being sent
                console.log("Data sent to server:", data);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        console.log("XHR status:", xhr.status);

                        if (xhr.status === 200) {
                            console.log("Response received:", xhr.responseText);

                            try {
                                const response = JSON.parse(xhr.responseText);

                                if (response) {
                                    console.log("Parsed JSON response:", response);

                                    if (response.success) {
                                        alert("Done successfully!");
                                        location.reload();
                                    } else {
                                        alert("Error : " + response.message);
                                    }
                                } else {
                                    console.error("Invalid JSON response:", xhr.responseText);
                                    alert("Invalid JSON response from the server.");
                                }
                            } catch (error) {
                                console.error("Error parsing JSON:", error);
                                alert("Error parsing JSON response from the server.");
                            }
                        } else {
                            console.error("HTTP request failed:", xhr.statusText);
                            alert("Error: " + xhr.statusText);
                        }
                    }
                };

                // Log any network errors
                xhr.onerror = function() {
                    console.error("Network error occurred.");
                    alert("Network error occurred. Please try again.");
                };

                // Send the request
                xhr.send(data);
            }
        }
    </script>
</body>

</html>