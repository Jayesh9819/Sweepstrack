<?php
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
}
