<?php global $conn, $rows;
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
    if ($result) {
        echo '<script>';
        echo '   
                history.pushState({page: "another page"}, "another page", "?id=' . $_GET['id'] . '&&page=change_password");
                    window.location.reload();
            ';
        echo '</script>';
    }


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
        <div id="save-message" ></div>
        <div class="button">
            <input type="submit" value="Continue" class="btn" name="changePass1" onclick="validatePassword()"/>
        </div>
    </div>
</div>
</body>
<script>
    function validatePassword() {
        var newPassword = document.getElementById("newpass").value;
        var confirmPassword = document.getElementById("confirmpass").value;
        var errorMessageElement = document.getElementById("error-message");

        // Check if change pass attempts is equal to 3
        var changePassAttempts = <?php
            $sqlUser = "SELECT * FROM users_info WHERE id='$id'";
            $resultUser = mysqli_query($conn, $sqlUser);
            $rowsUser = mysqli_fetch_assoc($resultUser);
            $changePassAttempts = $rowsUser['change_pass_attempts'];

            echo $changePassAttempts; ?>;
        if (changePassAttempts > 2) {
            errorMessageElement.textContent = "Warning: You have reached the maximum number of change password attempts. Contact support for assistance.";
            document.getElementById("save-message").textContent = "";
            return false;
        } else {
            if (newPassword !== confirmPassword) {
                errorMessageElement.textContent = "Password and Confirm Password do not match!";
                return false;
            } else {
                Post('', {changePass1: newPassword});
            }
        }

        errorMessageElement.textContent = "";
        return true;
    }
    function loadPage() {
        var page = window.location.href.split("page=")[1];
        if (page === "change_password") {
            var errorMessageElement = document.getElementById("save-message");
            errorMessageElement.textContent = "Saved successfully!";
        }
    }
    loadPage();
</script>
