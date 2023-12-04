<?php global $var, $rows; ?>

<?php
global $conn;
include "../../db_conn.php";

session_start();
if (!isset($_SESSION['user_type'])) {
    header("Location: /1-php-grading-system/admins_page/404");
} else {

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM users_info WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_assoc($result);
    }

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
}

?>


<!DOCTYPE html>
<html>
<title>MABES GRADE | INQUIRY</title>
<link rel="shortcut icon" href="../../assets/img/mabes.png"/>
<head>
    <link rel="stylesheet" href="../../assets/css/student/style.css"/>
    <link rel="stylesheet" href="../../assets/css/student/common.css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../assets/js/js_header.js" defer></script>


</head>
<body>

<div>
    <!--    <div id="modal-logout" class="modal2">-->
    <!--        <div class="square">-->
    <!--            <div class="modal-content">-->
    <!--                <div class="modal-content1">-->
    <!--                    <div class="modal-logo  d-flex-center">-->
    <!--                        <img src="../../assets/img/logout.png" width="60" height="60" alt="">-->
    <!--                    </div>-->
    <!--                    <div class="modal-short-msg d-flex-center">-->
    <!--                        <h1> Are you sure? </h1>-->
    <!--                    </div>-->
    <!--                    <div class="modal-long-msg  d-flex-center">-->
    <!--                        <h7>-->
    <!--                            Do you really want to logout? This process cannot be undone.-->
    <!--                        </h7>-->
    <!--                    </div>-->
    <!--                    <div class="modal-msg-choice d-flex-center">-->
    <!--                        <div class="modal-msg-choice-yes btn">-->
    <!--                            <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-cancel">Cancel</button>-->
    <!--                        </div>-->
    <!--                        <div class="modal-msg-choice-no">-->
    <!--                            <button class="btn-primary btn" id="modal-ok">Ok</button>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
</div>


<header>
    <div class="logo" title="University Management System">
        <img src="../../assets/img/mabes.png" alt=""/>
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
    <aside>
        <div class="profile">
            <div class="top">
                <div class="profile-photo">
                    <img src="<?= $rows['img_path'] ?>" alt=""/>
                </div>
                <div class="info">
                    <p>Hello, <b>Limpangog</b></p>
                    <small class="text-muted">12102030</small>
                </div>
            </div>
            <div class="about">
                <h5>Student Info</h5>
                <p>Name: Limpangog Daisy</p>
                <h5>Contact</h5>
                <p>1234567890</p>
                <h5>Email</h5>
                <p>unknown@gmail.com</p>
                <h5>Address</h5>
                <p>Sudtonggan</p>
            </div>
        </div>
    </aside>

    <!--    <main>-->
    <!--        <h1>SECTION A</h1>-->
    <!--        LIST OF STUDENT HERE-->
    <!--    </main>-->

    <script src="../../assets/js/student/app.js"></script>
</body>

<script>

    function logout() {
        $('#modal-logout').attr('style', 'display: block !important;')
    }

    function changePassword() {
        $('#modal-change-password').attr('style', 'display: block !important;')
    }

    $(document).on('click', '#modal-cancel', function (e) {
        $('#modal-logout').attr('style', 'display: none !important;')
        $('#modal-change-password').attr('style', 'display: none !important;')
    });

    $(document).on('click', '#modal-ok', function (e) {
        Post('', {logout: 'logout'});
    });

    $(document).on('click', '#modal-change-password-ok', function (e) {
        var change_password = $('.change-pass').val();
        Post('', {changePass: change_password});
    });
</script>
</html>