<?php
ob_start();
session_start();

class Commonf
{
    public function status()
    {
        include "./db_connect.php";
        $response = array('success' => false, 'message' => '');

        if (isset($_POST['id'], $_POST['table'], $_POST['field'])) {
            $cid = $_POST['cid'];
            $id = $_POST['id'];
            $table = $_POST['table'];
            $field = $_POST['field'];

            $sql = "SELECT $field FROM $table WHERE $cid=$id";
            //Select status from platform where bud=1
            // Change 'id' to your actual primary key column name
            $result = $conn->query($sql);

            if ($result) {
                $row = $result->fetch_assoc();
                $currentStatus = $row[$field];

                if ($currentStatus == 1) {
                    $updatesql = "UPDATE $table SET $field = 0 WHERE $cid = $id"; // Change 'id' to your actual primary key column name
                    if ($conn->query($updatesql) === TRUE) {
                        $response['success'] = true;
                        $response['message'] = "Item removed from the cart successfully!";
                    } else {
                        $response['message'] = "Error updating status: " . $conn->error;
                    }
                } elseif ($currentStatus == 0 || $currentStatus == null) {
                    $updatesql = "UPDATE $table SET $field = 1 WHERE $cid = $id"; // Change 'id' to your actual primary key column name
                    if ($conn->query($updatesql) === TRUE) {
                        $response['success'] = true;
                        $response['message'] = "Item updated successfully!";
                    } else {
                        $response['message'] = "Error updating status: " . $conn->error;
                    }
                }
            } else {
                $response['message'] = "Error in SQL query: " . $conn->error;
            }
        } else {
            $response['message'] = "Missing required parameters (id, table, field)";
        }

        // Clear any unwanted output before sending JSON response
        ob_clean();

        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function modifydate()
    {
        include "./db_connect.php";
        $response = array('success' => false, 'message' => '');

        if (isset($_POST['id'], $_POST['table'])) {
            $id = $_POST['id'];
            $table = $_POST['table'];
            $field = $_POST['field'];
            $cid = $_POST['cid'];

            // Ensure proper escaping of variables to prevent SQL injection
            $id = mysqli_real_escape_string($conn, $id);
            $table = mysqli_real_escape_string($conn, $table);
            $field = mysqli_real_escape_string($conn, $field);

            // Use prepared statement to prevent SQL injection
            $sql = "UPDATE $table SET $field = CURDATE() WHERE $cid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Date modified successfully!";
            } else {
                $response['message'] = "Error modifying date: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['message'] = "Missing required parameters (id, table)";
        }

        // Clear any unwanted output before sending JSON response
        ob_clean();

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function Approval()
    {
        include "./db_connect.php";
        $response = array('success' => false, 'message' => '');

        if (isset($_POST['id'], $_POST['table'], $_POST['field'])) {
            $cid = $_POST['cid'];
            $id = $_POST['id'];
            $table = $_POST['table'];
            $field = $_POST['field'];
            $where = $_POST['where'];
            $username = $_SESSION['username'];

            $sql = "SELECT $field FROM $table WHERE $cid=$id  ";
            //Select status from platform where bud=1
            // Change 'id' to your actual primary key column name
            $result = $conn->query($sql);

            if ($result) {
                $row = $result->fetch_assoc();
                $currentStatus = $row[$field];

                if ($currentStatus == 1) {
                    $updatesql = "UPDATE $table SET $field = 0,$where='$username' WHERE $cid = $id"; // Change 'id' to your actual primary key column name
                    if ($conn->query($updatesql) === TRUE) {
                        $response['success'] = true;
                        $response['message'] = "Item removed from the cart successfully!";
                    } else {
                        $response['message'] = "Error updating status: " . $conn->error;
                    }
                } elseif ($currentStatus == 0 || $currentStatus == null) {
                    $updatesql = "UPDATE $table SET $field = 1,$where='$username' WHERE $cid = $id"; // Change 'id' to your actual primary key column name
                    if ($conn->query($updatesql) === TRUE) {
                        $response['success'] = true;
                        $response['message'] = "Item updated successfully!";
                    } else {
                        $response['message'] = "Error updating status: " . $conn->error;
                    }
                }
            } else {
                $response['message'] = "Error in SQL query: " . $conn->error;
            }
        } else {
            $response['message'] = "Missing required parameters (id, table, field)";
        }

        // Clear any unwanted output before sending JSON response
        ob_clean();

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function passreset()
    {
        include "./db_connect.php";
        $response = array('success' => false, 'message' => '');

        if (isset($_POST['id'], $_POST['table'])) {
            $id = $_POST['id'];
            $table = $_POST['table'];
            $field = $_POST['field'];
            $cid = $_POST['cid'];

            // Ensure proper escaping of variables to prevent SQL injection
            $id = mysqli_real_escape_string($conn, $id);
            $table = mysqli_real_escape_string($conn, $table);
            $field = mysqli_real_escape_string($conn, $field);

            // Use prepared statement to prevent SQL injection
            $sql = "UPDATE $table SET $field = '123456' WHERE $cid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Password Reset Successfully!";
            } else {
                $response['message'] = "Error in Password Reset: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['message'] = "Missing required parameters (id, table)";
        }

        // Clear any unwanted output before sending JSON response
        ob_clean();

        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function delete()
    {
        include "./db_connect.php";
        $response = array('success' => false, 'message' => '');

        if (isset($_POST['id'], $_POST['table'])) {
            $id = $_POST['id'];
            $table = $_POST['table'];
            $field = $_POST['field'];

            $deletesql = "DELETE FROM $table WHERE $field = $id"; // Change 'bid' to your actual primary key column name

            if ($conn->query($deletesql) === TRUE) {
                $response['success'] = true;
                $response['message'] = "Item deleted successfully!";
            } else {
                $response['message'] = "Error deleting item: " . $conn->error;
            }
        } else {
            $response['message'] = "Missing required parameters (id, table)";
        }

        // Clear any unwanted output before sending JSON response
        ob_clean();

        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function cashapp()
    {
        include "./db_connect.php";
        $response = array('success' => false, 'message' => '');

        if (isset($_POST['id'], $_POST['cashapp'])) {
            // Sanitize user inputs
            $id = $conn->real_escape_string($_POST['id']);
            $cashapp = $conn->real_escape_string($_POST['cashapp']);
            $username = $_SESSION['username'];

            // Prepare the SQL statement using a prepared statement
            $sql = "UPDATE transaction SET cashapp = ?, cashout_status = 1, cashout_by = ? WHERE tid = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $response['message'] = "Error in preparing SQL statement";
            } else {
                // Bind parameters and execute the statement
                $stmt->bind_param("ssi", $cashapp, $username, $id);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Transaction updated successfully!";
                } else {
                    $response['message'] = "Error updating transaction: " . $stmt->error;
                }
                $stmt->close(); // Close the statement
            }
        } else {
            $response['message'] = "Missing required parameters (id, cashapp)";
        }

        // Clear any unwanted output before sending JSON response
        ob_clean();

        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function Reject()
    {
        include "./db_connect.php";
        $response = array('success' => false, 'message' => '');

        if (isset($_POST['id'], $_POST['msg'])) {
            // Sanitize user inputs
            $id = $conn->real_escape_string($_POST['id']);
            if (!empty($_POST['msg'])) {
                $msg = $conn->real_escape_string($_POST['msg']);
            } else {
                $msg = null;
            }
                        $username = $_SESSION['username'];

            // Prepare the SQL statement using a prepared statement
            $sql = "UPDATE transaction SET Reject_msg = ?, approval_status = 2, approved_by = ? WHERE tid = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $response['message'] = "Error in preparing SQL statement";
            } else {
                // Bind parameters and execute the statement
                $stmt->bind_param("ssi", $msg, $username, $id);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Sucessfully Rejected";
                } else {
                    $response['message'] = "Error updating transaction: " . $stmt->error;
                }
                $stmt->close(); // Close the statement
            }
        } else {
            $response['message'] = "Missing required parameters (id, cashapp)";
        }

        // Clear any unwanted output before sending JSON response
        ob_clean();

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

$com = new Commonf;
if (isset($_GET['action']) && $_GET['action'] == "status") {
    $com->status();
} else if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $com->delete();
} else if (isset($_GET['action']) && $_GET['action'] == "modify") {
    $com->modifydate();
} else if (isset($_GET['action']) && $_GET['action'] == "passreset") {
    $com->passreset();
} else if (isset($_GET['action']) && $_GET['action'] == "cashapp") {
    $com->cashapp();
} else if (isset($_GET['action']) && $_GET['action'] == "Approval") {
    $com->Approval();
} else if (isset($_GET['action']) && $_GET['action'] == "Reject") {
    $com->Reject();
}
