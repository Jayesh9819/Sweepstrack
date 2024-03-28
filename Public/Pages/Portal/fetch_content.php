<?php
if (isset($_GET['type'])) {
    $type = $_GET['type'];

    // Determine content based on type
    switch ($type) {
        case 'deposit':
            include './Components/Deposit_Form.php';
            break;
        case 'withdrawal':
            include './Components/Withdrawl_Form.php';
            break;
        case 'refund':
            include './Components/Refund_Form.php';
            break;
        case 'getDeposit':
            include './Components/getData.php';
            break;
        default:
            echo "<div>Invalid Request</div>";
    }
}
