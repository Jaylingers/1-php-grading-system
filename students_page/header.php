<?php
global $var, $rows;

// Start the session
session_start();

global $conn;
include "../../db_conn.php";

// Check user type in the session
if (!isset($_SESSION['user_type'])) {
    header("Location: /1-php-grading-system/admins_page/404");
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT ui.id AS idUI, ui.*, si.* FROM users_info ui LEFT JOIN students_info si ON si.lrn = ui.user_lrn
                WHERE ui.id='$id'";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_assoc($result);
    }

    // Check if $rows is not set or empty
    if (isset($_POST['logout'])) {
        unset($_SESSION['user_type']); // remove it now we have used it
        unset($_SESSION['ids']); // remove it now we have used it
        header("Location: /1-php-grading-system/");
    }
    if (isset($_POST['changePass'])) {
        $changePass = $_POST['changePass'];
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

        echo "<script>window.location.href='?id=$id'</script>";
    }

    if (isset($_POST['darkMode'])) {
        $darkMode = $_POST['darkMode'];
        $id = $_POST['id'];

        $sql = "UPDATE users_info SET dark_mode='$darkMode' WHERE id='$id'";
        $result = mysqli_query($conn, $sql);

        // Update dark mode session
        $_SESSION['dark_mode'] = $darkMode;
    }

    $id = $_GET['id'];
    // Fetch dark mode setting from the database
    $sqlDarkMode = "SELECT dark_mode FROM users_info WHERE id='$id'";
    $resultDarkMode = mysqli_query($conn, $sqlDarkMode);
    $rowDarkMode = mysqli_fetch_assoc($resultDarkMode);
    $darkModeFromDB = $rowDarkMode['dark_mode'];
//    echo "<script> alert('$darkModeFromDB')</script>";
}
?>

<!DOCTYPE html>
<html>
<title>MABES GRADE | INQUIRY</title>
<link rel="shortcut icon" href="../../assets/img/mabes.png" />
<head>
    <link rel="stylesheet" href="../../assets/css/student/style.css" />
    <link rel="stylesheet" href="../../assets/css/student/common.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../assets/js/js_header.js" defer></script>
</head>
<body <?php echo (isset($rows) && !empty($rows)) ? (isset($darkModeFromDB) && $darkModeFromDB == 1 ? 'class="dark-theme"' : '') : 'style="display:none;"'; ?>>
<header>
    <div class="logo" title="MABES">
        <img src="../../assets/img/mabes.png" alt=""/>
        <h1 clas="new1"><span>MABES</span><span class="new2">|</span><span class="grade-inquiry">GRADE INQUIRY</span>
        </h1>
    </div>
    <div class="navbar">
        <a href="/1-php-grading-system/students_page/student_info/?id=<?php echo $_GET['id'] ?>"
           class="<?php if ($var === "student_info") { ?> active  <?php } ?>">
            <span class="material-icons-sharp">home</span>
            <h3>Home</h3>
        </a>
        <a href="/1-php-grading-system/students_page/student_record/?id=<?php echo $_GET['id'] ?>"
           class="<?php if ($var === "student_record") { ?> active  <?php } ?>">
            <span class="material-icons-sharp">today</span>
            <h3>Student Record</h3>
        </a>
        <a href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?>"
           class="<?php if ($var === "teachers_list") { ?> active  <?php } ?>">
            <span class="material-icons-sharp">grid_view</span>
            <h3>Teacher List</h3>
        </a>
        <a href="/1-php-grading-system/students_page/change_password/?id=<?php echo $_GET['id'] ?>"
           class="<?php if ($var === "change_password") { ?> active  <?php } ?>">
            <span class="material-icons-sharp">password</span>
            <h3>Change Password</h3>
        </a>
        <a href="/1-php-grading-system/students_page/logout/?id=<?php echo $_GET['id'] ?>"
           class="<?php if ($var === "logout") { ?> active  <?php } ?>">
            <span class="material-icons-sharp" onclick="">logout</span>
            <h3>Logout</h3>
        </a>
    </div>
    <div id="profile-btn">
        <span class="material-icons-sharp">person</span>
    </div>
    <div class="theme-toggler">
        <span class="material-icons-sharp active">light_mode</span>
        <span class="material-icons-sharp">dark_mode</span>
    </div>
</header>
<div class="container">
    <aside class="side-container">
        <div class="profile">
            <div class="top">
                <div class="profile-photo">
                    <img src="../../assets/img/student.png" alt=""/>
                </div>
                <div class="info">
                    <p>Hello, <b><?= $rows['last_name'] ?></b></p>
                    <small class="text-muted"><?= $rows['user_lrn'] ?></small>
                </div>
            </div>
            <div class="about">
                <h5>STUDENT INFORMATION</h5>
                <p>Name: <?= $rows['last_name'] ?>, <?= $rows['first_name'] ?></p>
                <h5>Birthdate</h5>
                <p><?= $rows['b_date'] ?></p>
                <h5>Gender</h5>
                <p><?= $rows['gender'] ?></p>
                <h5>Age</h5>
                <p><?= $rows['age'] ?></p>
                <h5>Contact</h5>
                <p><?= $rows['contact_number'] ?></p>
                <h5>Email</h5>
                <p><?= $rows['email_address'] ?></p>
                <h5>Address</h5>
                <p><?= $rows['home_address'] ?></p>
            </div>
        </div>
    </aside>

    <script src="../../assets/js/student/app.js"></script>

<script>

    function logout() {
        $('#modal-logout').attr('style', 'display: block !important;');
    }

    function changePassword() {
        $('#modal-change-password').attr('style', 'display: block !important;');
    }

    $(document).on('click', '#modal-cancel', function (e) {
        $('#modal-logout').attr('style', 'display: none !important;');
        $('#modal-change-password').attr('style', 'display: none !important;');
    });

    $(document).on('click', '#modal-ok', function (e) {
        Post('', { logout: 'logout' });
    });

    $(document).on('click', '#modal-change-password-ok', function (e) {
        var change_password = $('.change-pass').val();
        Post('', { changePass: change_password });
    });

    themeToggler.onclick = function () {
        document.body.classList.toggle('dark-theme');
        themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
        themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');

        // Check if body has the class 'dark-theme'
        var darkMode = document.body.classList.contains('dark-theme') ? 1 : 0;

        // Use AJAX to update the dark mode session in PHP
        $.ajax({
            type: 'POST',
            url: 'index.php', // Replace with the actual PHP script URL
            data: { darkMode: darkMode, id: <?php echo $_GET['id'] ?> },
            success: function (response) {
                console.log('Dark mode session updated successfully.');
            },
            error: function (error) {
                console.error('Error updating dark mode session:', error);
            }
        });
    };

    $(document).ready(function () {
        // Fetch dark mode setting from the database
        var darkModeFromDB = <?php echo $darkModeFromDB; ?>;

        // Set 'dark-theme' class if dark mode is enabled
        if (darkModeFromDB === 1) {
            document.body.classList.add('dark-theme');
        }
    });


</script>

</html>
