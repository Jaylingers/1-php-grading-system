<?php global $conn;
$var = "change_password";
include '../../students_page/header.php';


if (isset($_POST['changePass1'])) {
    $changePass = $_POST['changePass1'];
    $id = $_GET['id'];

    $sqlUser = "SELECT * FROM users_info WHERE id='$id'";
    $resultUser = mysqli_query($conn, $sqlUser);
    $rowsUser = mysqli_fetch_assoc($resultUser);
    $changePassAttempts = $rowsUser['change_pass_attempts'];
    $changePassAttempts = $changePassAttempts + 1;

    // Hash the admin password
    $hashed_admin_password = password_hash($changePass, PASSWORD_DEFAULT);

    $sql = "UPDATE users_info SET password='$hashed_admin_password', change_pass_attempts='$changePassAttempts' WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../../assets/css/student/change_password.css"/>
</head>
<body>
<div class="change-password-container">
    <div class="form">
        <h2>Create new password</h2>
        <p class="text-muted">
            Please create a new password for your account.
        </p>
        <div class="box">
            <p class="text-muted">New Password</p>
            <input type="password" id="newpass" name="newpass"/>
        </div>
        <div class="box">
            <p class="text-muted">Confirm Password</p>
            <input type="password" id="confirmpass"/>
        </div>
        <div id="error-message" class="error-message"></div>
        <div class="button">
            <input type="submit" value="Proceed" class="btn" name="changePass1" onclick="validatePassword()"/>
        </div>
    </div>
</div>
</body>

<script src="../../assets/js/student/change_password.js"></script>