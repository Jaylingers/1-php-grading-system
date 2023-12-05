<?php global $conn;
$var = "logout";
include '../../students_page/header.php';

if (isset($_POST['logout1'])) {
    unset($_SESSION['user_type']); // remove it now we have used it
    unset($_SESSION['ids']); // remove it now we have used it
    echo '<script>';
    echo '   
                history.pushState({page: "another page"}, "another page", "/1-php-grading-system");
                window.location.reload();
            ';
    echo '</script>';

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../../assets/css/student/logout.css"/>
</head>
<body>
<div class="change-password-container">
    <form action="index.php" method="post">
        <h2>Are you sure?</h2>
        <p class="text-muted">
            Do you really want to logout? This process cannot be undone.
        </p>

        <div class="button">
            <input type="submit" name="logout1" value="Continue" class="btn"/>
        </div>
    </form>
</div>
</body>

