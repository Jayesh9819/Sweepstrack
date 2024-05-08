<?php 
function getUsernameByReferralCode($conn, $referralCode)
{
    $referralCode = mysqli_real_escape_string($conn, $referralCode);
    $query = "SELECT username FROM user WHERE refer_code = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $referralCode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['username']; // Return the username for the given referral code
    } else {
        return null; // Referral code does not exist
    }
}

function processReferralCode($conn, $name, $referralCode)
{
    // Fetch the username of the person who is registering
    $userName = mysqli_real_escape_string($conn, $name);

    // Get the username who referred the registering user
    $referredByUserName = getUsernameByReferralCode($conn, $referralCode);

    if ($referredByUserName) {
        // Now, let's check if the referrer was also referred by someone (to find the affiliate)
        $affiliateQuery = "SELECT refered_by FROM refferal WHERE name = '$referredByUserName'";
        $affiliateResult = mysqli_query($conn, $affiliateQuery);
        $affiliateUserName = null; // Default to null if no affiliate exists

        if ($affiliateRow = mysqli_fetch_assoc($affiliateResult)) {
            $affiliateUserName = $affiliateRow['refered_by'];
        }

        // Insert new entry into referral table including the affiliate (if exists)
        $insertQuery = "INSERT INTO refferal (name, refered_by, afilated_by) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sss", $userName, $referredByUserName, $affiliateUserName);
        if (mysqli_stmt_execute($stmt)) {
            // Set success toast message
            print_r('success', 'Referral code added successfully!');
        } else {
            // Set error toast message
            print_r('error', 'Error adding referral code: ' . mysqli_error($conn));
        }
    } else {
        // Set error toast message if referral code does not exist
        setToast('error', 'Referral code does not exist!');
    }
}
