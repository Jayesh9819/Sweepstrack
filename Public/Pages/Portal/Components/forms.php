<?php
include "./Public/Pages/Portal/Components/formcomp.php";
include "./App/db/db_connect.php";

$title = "Add User";
$segments = explode('/', rtrim($uri, '/'));
$lastSegment = end($segments);

$action = strtoupper($lastSegment);

if (isset($action)) {

    global $title;
    $heading = "Fill the details";
    $role = $_SESSION['role'];
    // echo $role;
    // Assuming you have defined or included your functions like fhead, field, select, etc.
    // ...

    if ($action == 'ADD_USER' || $action == 'EDIT_USER') {
        if (isset($_POST['role'])) {
            $r = $_POST['role'];
        };
        $title = $action == 'ADD_USER' ? "Add Details" : "Edit Details";
        $postUrl = $action == 'ADD_USER' ? "../App/Logic/register.php?action=register" : '../App/Logic/register.php?action=editregister';

        echo fhead($title, $heading, $postUrl);
        echo '<br>';

        $branchopt = "<option value=''>Select Branch Name</option>";
        $resultBranch = $conn->query("SELECT * FROM branch where status=1");
        if ($resultBranch->num_rows > 0) {
            while ($row = $resultBranch->fetch_assoc()) {
                $branchopt .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }

        $pageopt = "<option value=''>Select Page Name</option>";
        $resultPage = $conn->query("SELECT * FROM page where status=1");
        if ($resultPage->num_rows > 0) {
            while ($row = $resultPage->fetch_assoc()) {
                $pageopt .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }
        if ($action == 'EDIT_USER') {
            $username = $_GET['u'];
            $sql = "Select * from user where username='$username'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $r = isset($row['role']);

            $branchOptions = []; // Initialize an empty string for options
            $branchQuery = "SELECT name FROM page where status=1";
            $branchResult = $conn->query($branchQuery);
            while ($branchRow = $branchResult->fetch_assoc()) {
                $branchOptions[$branchRow['name']] = $branchRow['name'];
                // $branchOptions .= "<option value='{$branchRow['name']}'>{$branchRow['name']}</option>";
            }
            // echo select("Sub Section", "condtion", "condtion", $branchOptions, isset($_POST['condtion']) ? $_POST['condtion'] : '');

            echo $name = field("Name", "text", "name", "Enter Your Name", isset($row['name']) ? $row['name'] : '');
            echo $username = field("Username", "text", "username", "Enter Your Username", isset($row['username']) ? $row['username'] : '');
            echo $password = field("Password", "password", "password", "Enter Your Password", isset($row['password']) ? $row['password'] : '');
            echo '<input type="hidden" name="role" value="' . (isset($row['role']) ? $row['role'] : '') . '" >';

            // Additional fields for 'EDIT_USER'

            echo $fbLink = field("Facebook Link", "text", "fb_link", "Enter Your Facebook Link", isset($row['Fb-link']) ? $row['Fb-link'] : '');

            if (isset($row['role'])) {
                if ($row['role'] == 'Supervisor' || $row['role'] == 'Agent') {
                    echo select("Page name", "page", "page", $branchOptions, isset($row['pagename']) ? $row['pagename'] : '');
                } elseif ($row['role'] == 'Manager' || $row['role'] == 'User') {
                    echo select("Page name", "page", "page", $branchOptions, isset($row['pagename']) ? $row['pagename'] : '');
                } else {
                    echo "Invalid attempt";
                }
            }
        } else {


            echo $name = field("Name", "text", "name", "Enter Your Name", isset($_POST['name']) ? $_POST['name'] : '');
            echo $username = field("Username", "text", "username", "Enter Your Username", isset($_POST['username']) ? $_POST['username'] : '');
            echo $password = field("Password", "password", "password", "Enter Your Password", isset($_POST['password']) ? $_POST['password'] : '');
            echo '<input type="hidden" name="role" value="' . (isset($_POST['role']) ? $_POST['role'] : '') . '" >';

            // Additional fields for 'EDIT_USER'
            echo $fbLink = field("Facebook Link", "text", "fb_link", "Enter Your Facebook Link", isset($_POST['fb_link']) ? $_POST['fb_link'] : '');


            if (isset($_POST['role'])) {
                if ($_POST['role'] == 'Supervisor' || $_POST['role'] == 'Agent') {
                    echo '<label for="pagename">Page Name</label>';
                    echo '<select class="form-select" id="pagename" name="page" onchange="showOtherField(this, \'cashAppname-other\')">' . $pageopt . '</select>';
                } elseif ($_POST['role'] == 'Manager' || $_POST['role'] == 'User') {
                    echo '<label for="pagename">Page Name</label>';
                    echo '<select class="form-select" id="pagename" name="page" onchange="showOtherField(this, \'cashAppname-other\')">' . $pageopt . '</select>';
                } else {
                    echo "Invalid attempt";
                }
            }


            echo '<div id="useradd" style="display:none;">';
            echo '</div>';
        }
        echo '<br>';

        echo $Submit;
        echo $Cancel;
        echo $formend;
    } else if ($action == "CASH_UP_ADD" || $action == "CASH_UP_EDIT" && $role = ("Admin" || "Manager")) {
        if ($action == "CASH_UP_ADD") {
            $title = "CashApp Add Details ";
            $heading = "Enter the Details Correctly";
            $action = "../App/Logic/creation.php?action=cashAppAdd";
            echo fhead($title, $heading, $action);
            echo field("Name", "text", "name", "Enter The name");
            echo field("Cash Tag", "text", "cashtag", "Enter the Cash Tag");
            echo field("Opening Balance", "number", "openingbalance", "Enter The Opening Balance");
            echo field("Page ID", "number", "pageid", "Enter Your Page ID");
            echo field("Branch ID", "number", "branchid", "Enter Your Branch Id");
            echo field("Withdrawl ", "number", "withdrawl", "Enter the Withdrawl");
            echo $Submit;
            echo $Cancel;
            echo $formend;
        } else if ($action == "CASH_UP_EDIT") {
            $username = $_GET['u'];
            $sql = "Select * from cashapp where name='$username'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $title = "CashApp Edit Details ";
            $heading = "Enter the Details Correctly";
            $action = "../App/Logic/creation.php?action=cashAppEdit";

            echo $name = field("Name", "text", "name", "Enter Your Name", isset($row['name']) ? $row['name'] : '');
            echo $cashtag = field("Cash Tag", "text", "cashtag", "Enter the Cash Tag", isset($row['cashtag']) ? $row['cashtag'] : '');
            echo $current_balance = field("Opening Balance", "number", "openingbalance", "Enter The Opening Balance", isset($row['current_balance']) ? $row['current_balance'] : '');
            echo $pageid = field("Page ID", "number", "pageid", "Enter Your Page ID", isset($row['remark']) ? $row['pageid'] : '');
            echo $branchid = field("Branch ID", "number", "branchid", "Enter Your Branch Id", isset($row['branchid']) ? $row['branchid'] : '');
            echo $withdrawl = field("Withdrawl ", "number", "withdrawl", "Enter the Withdrawl", isset($row['withdrawl']) ? $row['withdrawl'] : '');
        }
    } else if ($action == "CASH_OUT" && ($role == "Agent" || $role == "Supervisor" || $role == "Admin")) {
        $title = "Reedem  Details";
        $heading = "Enter the Details Correctly";
        $action = "../App/Logic/creation.php?action=CashOut";

        echo fhead($title, $heading, $action);
        if (isset($_GET['u'])) {
            $depositID = $_GET['u'];
            echo field("Enter the User Name", "text", "username", "Enter the Username", $depositID, "readonly");
        } else {
            echo field("Enter the User Name", "text", "username", "Enter the Username");
        }

        echo field("Reedem Amount", "number", "reedemamount", "Enter the Reedem Amount");
        $pageop = "<option value=''>Select Page</option>";
        $result = $conn->query("SELECT * FROM page where status =1");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pageop .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }

        echo field("Excess Amount", "number", "excessamount", "Enter the Excess Amount");
        $platformOptions = "<option value=''>Select Platform</option>";
        $result = $conn->query("SELECT name FROM platform where status =1");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $platformOptions .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }
        $platformOptions .= "<option value='other'>Other</option>";
        echo '<label for="platformname">Platform Name</label>';
        echo '<select class="form-select" id="platformname" name="platformname" onchange="showOtherField(this, \'platformname-other\')">' . $platformOptions . '</select>';
        echo '<input type="text" id="platformname-other" name="platformname_other" style="display:none;" placeholder="Enter Platform Name">';

        // echo field("cashApp Name", "text", "cashAppname", "Enter the cashApp Name");
        $cashAppOptions = "<option value=''>Select cashApp</option>";
        $result = $conn->query("SELECT * FROM cashapp where status =1");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cashAppOptions .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }
        $cashAppOptions .= "<option value='other'>Other</option>";
        echo '<label for="cashAppname">cashApp Name</label>';
        echo '<select class="form-select" id="cashAppname" name="cashAppname" onchange="showOtherField(this, \'cashAppname-other\')">' . $cashAppOptions . '</select>';
        echo '<input type="text" id="cashAppname-other" name="cashAppname_other" style="display:none;" placeholder="Enter cashApp Name">';
        echo field("Tip", "number", "tip", "Enter the Tip Amount");
        echo field("Remark", "text", "remark", "Enter the Remark ", "", "");

        echo $Submit;
        echo $Cancel;
        echo $formend;
    } else if ($action == "DEPOSIT" && ($role != "User")) {
        echo "Current URL: " . $_SERVER['REQUEST_URI'] . "<br>";

        $title = "Reedem Details";
        $heading = "Fill in the Redeem Details";
        $actionUrl = "../App/Logic/creation.php?action=Deposit";
        echo fhead($title, $heading, $actionUrl);
        if (isset($_GET['u'])) {
            // Fetch the corresponding value from the database based on the ID
            $depositID = $_GET['u'];
            echo field("Enter the User Name", "text", "username", "Enter the Username", $depositID, "readonly");

            // Replace with your database fetching logic
        } else {
            echo field("Enter the User Name", "text", "username", "Enter the Username");
        }
        echo field("Deposit Amount", "number", "depositamount", "Enter the Deposit Amount");
        $pageop = "<option value=''>Select Page</option>";
        $result = $conn->query("SELECT name FROM page where status=1");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pageop .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }
        // echo '<input type="text" id="platformname-other" name="platformname_other" style="display:none;" placeholder="Enter Platform Name">';

        // echo field("page ID", "text", "fbid", "Enter the Facebook ID");
        $platformOptions = "<option value=''>Select Platform</option>";
        $result = $conn->query("SELECT name FROM platform where status=1");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $platformOptions .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }
        $platformOptions .= "<option value='other'>Other</option>";
        echo '<label for="platformname">Platform Name</label>';
        echo '<select class="form-select" id="platformname" name="platformname" onchange="showOtherField(this, \'platformname-other\')">' . $platformOptions . '</select>';
        echo '<input type="text" id="platformname-other" name="platformname_other" style="display:none;" placeholder="Enter Platform Name">';

        // echo field("cashApp Name", "text", "cashAppname", "Enter the cashApp Name");
        $cashAppOptions = "<option value=''>Select cashApp</option>";
        $result = $conn->query("SELECT * FROM cashapp where status=1");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cashAppOptions .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }
        $cashAppOptions .= "<option value='other'>Other</option>";
        echo '<label for="cashAppname">cashApp Name</label>';
        echo '<select class="form-select" id="cashAppname" name="cashAppname" onchange="showOtherField(this, \'cashAppname-other\')">' . $cashAppOptions . '</select>';
        echo '<input type="text" id="cashAppname-other" name="cashAppname_other" style="display:none;" placeholder="Enter cashApp Name">';

        echo field("Bonus Amount", "number", "bonusamount", "Enter the Bonus Amount");
        echo field("Remark", "text", "remark", "Enter the Remark ", "", "");

        echo $Submit;
        echo $Cancel;
        echo $formend;
    }
    if ($action == "PLATFORM" && ($role == "Admin" || $role == "Manager")) {
        $title = "Add Platform Name";
        $heading = "Enter the Platform Information Below";
        $actionUrl = "../App/Logic/creation.php?action=platform"; // Adjust the action as needed

        echo fhead($title, $heading, $actionUrl);

        // Fields for Platform Details
        echo field("Platform Name", "text", "platformname", "Enter the Platform Name");
        // echo field("Status", "checkbox", "status", "Active");

        // Using a checkbox as a workaround for the active/inactive button
        echo '<div class="form-group">
                <label for="status">Status</label>
                <input type="checkbox" id="status" name="status" value="1">
              </div>';

        echo field("Current Balance", "number", "currentbalance", "Enter the Current Balance");
        // echo field("Added By", "text", "addedby", "Enter the Name of the Person Adding");

        echo $Submit;
        echo $Cancel;
        echo $formend;
    } else if ($action == "ADD_CASHAPP" || $action == "EDIT_CASHAPP" && $role == "Admin") {
        if ($action ==  "ADD_CASHAPP") {
            $title = "CashApp Details";
            $heading = "Enter CashApp Information";
            $actionUrl = "../App/Logic/creation.php?action=CashApp"; // Adjust the action as needed

            echo fhead($title, $heading, $actionUrl);
            // Fields for CashApp Details
            echo field("CashApp Name", "text", "cashAppname", "Enter the CashApp Name");
            echo field("CashApp Tag", "text", "cashApptag", "Enter the CashApp Tag");
            echo field("CashApp Email", "email", "email", "Enter the CashApp Email");

            // Using a checkbox as a workaround for the active/inactive button
            echo '<div class="form-group">
                <label for="active">Active</label>
                <input type="checkbox" id="active" name="active" value="1">
              </div>';

            echo field("Current Balance", "number", "currentbalance", "Enter the Current Balance");
            echo field("CashApp Remark", "textarea", "remark", "Enter the Remark ");

            echo $Submit;
            echo $Cancel;
            echo $formend;
        } else if ($action ==  "EDIT_CASHAPP") {
            $username = $_GET['u'];
            $sql = "Select * from cashapp where name='$username'";
            $result = $conn->query($sql);

            $title = "CashApp Details";
            $heading = "Enter CashApp Information";
            $actionUrl = "../App/Logic/creation.php?action=EditCashApp";

            echo fhead($title, $heading, $actionUrl);

            echo $name = field("CashApp Name", "text", "name", "Enter the CashApp Name", isset($row['name']) ? $row['name'] : '');
            echo $cashtag = field("CashApp Tag", "text", "cashtag", "Enter the CashApp Tag", isset($row['cashtag']) ? $row['cashtag'] : '');
            echo $email = field("CashApp Email", "email", "email", "Enter the CashApp Email", isset($row['email']) ? $row['email'] : '');
            // Check if status is 1, then auto-check the checkbox
            $statusChecked = isset($row['status']) && $row['status'] == 1 ? 'checked' : '';

            echo '<div class="form-group"> <label for="active">Active</label> <input type="checkbox" id="active" name="status" value="1" ' . $statusChecked . '> </div>';
            echo $current_balance = field("Current Balance", "number", "current_balance", "Enter the Current Balance", isset($row['current_balance']) ? $row['current_balance'] : '');
            echo $remark = field("CashApp Remark", "textarea", "remark", "Enter the Remark ", isset($row['remark']) ? $row['remark'] : '');
            echo $Submit;
            echo $Cancel;
            echo $formend;
        }
    } else if ($action == "WITHDRAWL" && ($role == "Admin")) {
        // Fetch platform names from the database
        $title = "Withdrawl Action Details";
        $heading = "Fill in the Details";
        $actionUrl = "../App/Logic/creation.php?action=Withdrawl"; // Adjust the action URL as needed

        echo fhead($title, $heading, $actionUrl);

        // Assume $conn is your database connection
        $platformOptions = "<option value=''>Select Platform</option>";
        $result = $conn->query("SELECT name FROM platform");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $platformOptions .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }
        $platformOptions .= "<option value='other'>Other</option>";

        // Fetch cashApp names from the database
        $cashAppOptions = "<option value=''>Select cashApp Name</option>";
        $result = $conn->query("SELECT * FROM cashapp");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cashAppOptions .= "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        }
        $cashAppOptions .= "<option value='other'>Other</option>";
        if (isset($_GET['u'])) {
            $depositID = $_GET['u'];
            echo field("Enter the User Name", "text", "username", "Enter the Username", $depositID, "readonly");
        } else {
            echo field("Enter the User Name", "text", "username", "Enter the Username");
        }

        // Platform Name dropdown with "Other" option
        echo '<label for="platformname">Platform Name</label>';
        echo '<select class="form-select" id="platformname" name="platformname" onchange="showOtherField(this, \'platformname-other\')">' . $platformOptions . '</select>';
        echo '<input type="text" id="platformname-other" name="platformname_other" style="display:none;" placeholder="Enter Platform Name">';

        // cashApp Name dropdown with "Other" option
        echo '<label for="cashAppname">cashApp Name</label>';
        echo '<select class="form-select" id="cashAppname" name="cashAppname" onchange="showOtherField(this, \'cashAppname-other\')">' . $cashAppOptions . '</select>';
        echo '<input type="text" id="cashAppname-other" name="cashAppname_other" style="display:none;" placeholder="Enter cashApp Name">';

        // Remaining fields
        echo field("Cashtag", "text", "cashtag", "Enter the Cashtag");
        echo field("Amount", "number", "amount", "Enter the Amount");
        echo field("Remark", "text", "remark", "Enter any remarks", "", "");

        echo $Submit;
        echo $Cancel;
        echo $formend;
    } else if ($action == 'ADD_BRANCH' || $action == 'EDIT_BRANCH') {
        if ($action == 'ADD_BRANCH') {
            $title = $action == 'ADD_BRANCH' ? "Add Branch" : "Edit Branch";
            $postUrl = $action == 'ADD_BRANCH' ? "../App/Logic/creation.php?action=AddBranch" : './edit_branch';

            echo fhead($title, $heading, $postUrl);
            echo '<br>';
            echo $name = field("Name", "text", "name", "Enter Branch Name", isset($_POST['name']) ? $_POST['name'] : '');
            echo '<div class="form-group">
                <label for="active">Status</label>
                <input type="checkbox" id="status" name="status" value="1">
              </div>';
            echo $Submit;
            echo $Cancel;
            echo $formend;
        } else if ($action == 'EDIT_BRANCH') {

            $name = $_GET['u'];
            $sql = "Select * from branch where name='$name'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $title = "Branch Details";
            $heading = "Enter Branch Information";
            $actionUrl = "../App/Logic/creation.php?action=EditBranch";




            echo fhead($title, $heading, $actionUrl);

            echo $name = field("Branch Name", "text", "name", "Enter the Branch Name", isset($row['name']) ? $row['name'] : '');

            $statusChecked = isset($row['status']) && $row['status'] == 1 ? 'checked' : '';
            echo '<div class="form-group"> <label for="active">Active</label> <input type="checkbox" id="active" name="status" value="1" ' . $statusChecked . '> </div>';

            $bid = $row['bid'];

            echo $Submit;
            echo $Cancel;
            echo $formend;
        }
    } else if ($action == 'ADD_PAGE' || $action == 'EDIT_PAGE') {
        $title = $action == 'ADD_PAGE' ? "Add Page" : "Edit Page";
        $postUrl = $action == 'ADD_PAGE' ? "../App/Logic/creation.php?action=AddPage" : './edit_page';

        echo fhead($title, $heading, $postUrl);
        echo '<br>';
        $branchOptions = ""; // Initialize an empty string for options
        // Replace the query with your actual query to fetch branch IDs
        $branchQuery = "SELECT * FROM branch where status=1";
        $branchResult = $conn->query($branchQuery);
        while ($branchRow = $branchResult->fetch_assoc()) {
            $branchOptions .= "<option value='{$branchRow['bid']}'>{$branchRow['name']}</option>";
        }
        echo '<label for="branchname">Branch Name</label>';
        echo '<select class="form-select" id="platformname" name="bid" onchange="showOtherField(this, \'branchname-other\')">' . $branchOptions . '</select>';
        echo $name = field("Name", "text", "name", "Enter Page Name", isset($_POST['name']) ? $_POST['name'] : '');
        echo '<div class="form-group">
                <label for="status">Status</label>
                <input type="checkbox" id="status" name="status" value="1">
              </div>';

        echo $Submit;
        echo $Cancel;
        echo $formend;
    } elseif ($action == 'SEE_REPORTS') {
        unset($_SESSION['fields'], $_SESSION['condition']);
        $title = "See All Reports";
        $heading = "Select the details carefully";

        // Store POST data directly into variables for better performance
        $condition = $_POST['condition'] ?? '';
        $field = $_POST['field'] ?? '';

        // Set session variables
        $_SESSION['field'] = $field;
        $_SESSION['condition'] = $condition;

        // Generate HTML output
        echo fhead($title, $heading, isset($_SESSION['field'], $_SESSION['condition']) && $_SESSION['field'] !== '' && $_SESSION['condition'] !== '' ? "./Reports" : "#");

        // Generate select dropdown for 'field'
        echo select("Field", "field", "field", ["branch", "page", "platform", "cashapp"], $_SESSION['field'] ?? '');

        // Check if 'field' is set and fetch condition options accordingly
        if (!empty($field)) {
            // Initialize an empty array for condition options
            $conditionOptions = [];

            // Prepare and execute a single query to fetch condition options
            $conditionQuery = "SELECT name FROM $field WHERE status = 1";
            $conditionResult = $conn->query($conditionQuery);
            if ($conditionResult) {
                while ($conditionRow = $conditionResult->fetch_assoc()) {
                    $conditionOptions[] = $conditionRow['name'];
                }
                $conditionResult->free(); // Free the result set
            }

            // Generate select dropdown for 'condition'
            echo select("Sub Section", "condition", "condition", array_combine($conditionOptions, $conditionOptions), $condition);
        }
        echo $Submit;
        echo $Cancel . $formend;
    } elseif ($action == "RECHARGE_PLATFORM" || $action == "RECHARGE_CASHAPP" || $action == "REDEEM_CASHAPP" || $action == "REDEEM_PLATFORM") {
        // Set dynamic title based on the action
        $title = ($action == "RECHARGE_PLATFORM" || $action == "REDEEM_PLATFORM") ? " Platform" : " CashApp";
        $heading = "Select the details carefully";
        $postUrl = ($action == "RECHARGE_PLATFORM" || $action == "REDEEM_PLATFORM") ? "../App/Logic/creation.php?action=Recharge_platform" : "../App/Logic/creation.php?action=Recharge_Cashup";
        echo fhead($title, $heading, $postUrl);
        echo '<br>';

        // Adding Cashtag field

        // Additional fields for RECHARGE_PLATFORM
        if ($action == "RECHARGE_PLATFORM" || $action == "REDEEM_PLATFORM") {
            echo field("Platform Name", "text", "platform", "Enter Platform Name", isset($_GET['name']) ? $_GET['name'] : '', "required", "readonly");
            echo field("Amount", "number", "amount", "Enter Amount ");
            echo field("Remark", "text", "remark", "Enter Remark ", "", "");
            if ($action == "RECHARGE_PLATFORM") {
                echo '<input name="type" value="Recharge" hidden>';
            } elseif ($action == "REDEEM_PLATFORM") {
                echo '<input name="type" value="Redeem" hidden>';
            }
        }

        // Additional fields for RECHARGE_CASHAPP
        if ($action == "RECHARGE_CASHAPP" || $action == "REDEEM_CASHAPP") {
            echo field("CashApp Name", "text", "cashapp", "Enter CashApp Name", isset($_GET['name']) ? $_GET['name'] : '', "required", "readonly");
            echo field("Amount", "number", "amount", "Enter Amount ");
            echo field("Remark", "text", "remark", "Enter Remark ", "", "");
            if ($action == "RECHARGE_CASHAPP") {
                echo '<input name="type" value="Recharge" hidden>';
            } elseif ($action == "REDEEM_CASHAPP") {
                echo '<input name="type" value="Redeem" hidden>';
            }
        }

        echo $Submit;
        echo $Cancel;
        echo $formend;
    } elseif ($action == "FREE_PLAY") {
        $title = "Free Play";
        $heading = "Fill in the details for Free Play";
        $postUrl = "../App/Logic/creation.php?action=Free_Play";
        $conditionQuery = "SELECT name FROM platform WHERE status = 1";
        $conditionResult = $conn->query($conditionQuery);
        if ($conditionResult) {
            while ($conditionRow = $conditionResult->fetch_assoc()) {
                $conditionOptions[] = $conditionRow['name'];
            }
            $conditionResult->free(); // Free the result set
        }

        echo fhead($title, $heading, $postUrl);
        $name = $_GET['u'];
        echo field("Username", "text", "username", "", $name,"required","readonly");
        echo field("Amount", "number", "amount", "Enter Amount for the free play", '');
        echo select("Platform", "platform", "platform", array_combine($conditionOptions, $conditionOptions));
        echo field("Remark", "text", "remark", "Enter Remark", "","");
        echo $Submit;
        echo $Cancel;
        echo $formend;

    }
}


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    function showOtherField(select, otherFieldId) {
        var otherField = document.getElementById(otherFieldId);
        if (select.value === 'other') {
            otherField.style.display = 'block';
        } else {
            otherField.style.display = 'none';
        }
    }
</script>

<script>
    // $(document).ready(function() {
    //     $('#role').change(function() {
    //         var isManager = $(this).val() === 'Manager';
    //         $('#mageradd').toggle(isManager);


    //     });
    //     $('#role').change(function() {
    //         var isManager = $(this).val() === 'Agent';
    //         $('#agentadd').toggle(isManager);


    //     });
    //     $('#role').change(function() {
    //         var isManager = $(this).val() === 'User';
    //         $('#useradd').toggle(isManager);


    //     });

    // });
</script>