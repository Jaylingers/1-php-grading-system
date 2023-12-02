<?php global $var; ?>

<?php
global $conn;
include "../../db_conn.php";

session_start();
if (!isset($_SESSION['user_type'])) {
    header("Location: /1-php-grading-system/admins_page/404");
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users_info WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_assoc($result);

    if (isset($_POST['logout'])) {
        unset($_SESSION['user_type']); // remove it now we have used it
        unset($_SESSION['ids']); // remove it now we have used it
        header("Location: /1-php-grading-system/");
    }

    if(isset($_POST['changePass'])) {
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


    <link rel="stylesheet" href="../../assets/css/style_custom.css">
    <link rel="stylesheet" href="../../assets/css/student_style.css">
    <!--    <link rel="stylesheet" href="../../assets/css/style.css">-->
    <script src="../../assets/js/js_header.js" defer></script>

    <link rel="stylesheet" href="../../assets/css/main.css"/>
    <link rel="stylesheet" href="../../assets/css/media.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>

<!-- Top header -->
<div class="top-header" style="background: white">
    <div class="container_thr">
        <div class="df_thr">
            <img src="../../assets/img/mabes.png" alt="" class="logo1"/>
            <img src="../../assets/img/mabes.png" alt="" class="logo2"/>
            <div class="logo">
                <h1><span>MABES</span> GRADE INQUIRY</h1>
            </div>
            <div class="col_thr">
                <div class="df2_thr">
                    <i class="fa-solid fa-location-dot"></i>
                    <div class="text_thr">
                        <p><b>Our location</b></p>
                        <p class="smallp_thr">Sangi Rd,Lapu-lapu City, Cebu</p>
                    </div>
                </div>
            </div>
            <div class="col_thr">
                <div class="df2_thr">
                    <i class="fa-solid fa-envelope"></i>
                    <div class="text_thr">
                        <p><b>Our email</b></p>
                        <p class="smallp_thr">mabes@gmail.com</p>
                    </div>
                </div>
            </div>
            <div class="col_thr">
                <div class="df2_thr">
                    <i class="fa-solid fa-phone"></i>
                    <div class="text_thr">
                        <p><b>Call Us</b></p>
                        <p class="smallp_thr">(032) 340 8046</p>
                    </div>
                </div>
            </div>
            <div class="bars">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </div>
</div>
<!-- End Top header -->
<!-- Navbar -->
<nav class="navbar" style="height: 4em;
    display: flex;
    justify-content: center;
    align-items: center;">
    <div class="container">
        <div class="content_nv">
            <ul class="nav-links">
                <li>
                    <a href="/1-php-grading-system/students_page/student_info/?id=<?php echo $_GET['id'] ?>" <?php if ($var === "student_info") { ?> style="border-bottom: 3px solid #9747ff"  <?php } ?>>Student
                        Info</a>
                </li>
                <li>
                    <a href="/1-php-grading-system/students_page/student_record/?id=<?php echo $_GET['id'] ?>" <?php if ($var === "student_record") { ?> style="border-bottom: 3px solid #9747ff"  <?php } ?>>Student
                        Record
                    </a></li>
                <li>
                    <a href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?>" <?php if ($var === "teachers_list") { ?> style="border-bottom: 3px solid #9747ff"  <?php } ?>>Teachers
                        List
                    </a></li>
                <li class="logout">
                    <a href="#" onclick="changePassword()">Change Password</a>
                </li>
                <li class="logout">
                    <a href="#" onclick="logout()">Logout</a>
                </li>
            </ul>
        </div>


    </div>

</nav>
<!-- End Navbar -->

<div class="name-1"  style="height: 3em;    position: absolute;
  top: 11em;
    right: 6em;
 ">
   Hello, <?php echo $rows['last_name'] ?>
</div>
<div>

    <div id="modal-logout" class="modal2">
        <div class="square">
            <div class="modal-content">
                <div class="modal-content1">
                    <div class="modal-logo  d-flex-center">
                        <img src="../../assets/img/logout.png" width="60" height="60" alt="">
                    </div>
                    <div class="modal-short-msg d-flex-center">
                        <h1> Are you sure? </h1>
                    </div>
                    <div class="modal-long-msg  d-flex-center">
                        <h7>
                            Do you really want to logout? This process cannot be undone.
                        </h7>
                    </div>
                    <div class="modal-msg-choice d-flex-center">
                        <div class="modal-msg-choice-yes btn">
                            <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-cancel">Cancel</button>
                        </div>
                        <div class="modal-msg-choice-no">
                            <button class="btn-primary btn" id="modal-ok">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-change-password" class="modal2">
        <div class="square">
            <div class="modal-content">
                <div class="modal-content1">
                    <div class="modal-logo  d-flex-center">
                        <img src="../../assets/img/pw.png" width="60" height="60" alt="">
                    </div>

                        <?php
                        $sqlUser = "SELECT * FROM users_info WHERE id='$id'";
                        $resultUser = mysqli_query($conn, $sqlUser);
                        $rowsUser = mysqli_fetch_assoc($resultUser);
                        $changePassAttempts = $rowsUser['change_pass_attempts'];

                        if ($changePassAttempts < 3) { ?>
                            <div class="w-100p d-flex-center">
                                <input type="text" id="search_name" placeholder="change password" class="change-pass">
                            </div>
                                <div class="modal-long-msg  d-flex-center">
                                    <h7>
                                        You can only have 1 time to change your password. Do you want to continue?
                                    </h7>
                                </div>
                                <div class="modal-msg-choice d-flex-center">
                                    <div class="modal-msg-choice-yes btn">
                                        <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-cancel">Cancel</button>
                                    </div>
                                    <div class="modal-msg-choice-no">
                                        <button class="btn-primary btn" id="modal-change-password-ok">Ok</button>
                                    </div>
                                </div>
                        <?php } else { ?>
                        <div class="w-100p d-flex-center">
                            <input type="text" id="search_name" placeholder="change password" class="change-pass" disabled="true">
                        </div>

                            <div class="modal-long-msg  d-flex-center t-align-center t-color-red">
                                <h7>
                                    You riched the maximum attempt to change your password. <br> If you want to change your password, please contact the administrator.
                                </h7>
                            </div>
                            <div class="modal-msg-choice d-flex-center">
                                <div class="modal-msg-choice-yes btn">
                                    <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-cancel">Cancel</button>
                                </div>
                                <div class="modal-msg-choice-no">
                                    <button class="btn-primary btn" id="modal-change-password-ok" disabled>Ok</button>
                                </div>
                            </div>
                        <?php } ?>


                </div>
            </div>
        </div>
    </div>

    <?php if ($rows['img_path'] == '') { ?>
        <img id="settings" src="../../assets/users_img/noImage.png"
             style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;   position: absolute;
  top: 10em;
    right: 2em;
    background: #e6e6e6;"
             alt="" class="w-32px c-hand">
    <?php } else { ?>
        <img id="settings" src="<?= $rows['img_path'] ?>"
             style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;  position: absolute;
  top: 10em;
    right: 2em;
    background: #e6e6e6;"
             alt="" class="w-32px c-hand">
    <?php } ?>
    <div id="settings-details" class="p-absolute j-content-center z-i-999910" style="     position: absolute;
    height: 9em;
    width: 14em;
    top: 13.55em;
    right: 3em;
    display: none;
    background: #e6e6e6;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
">

        <style>
            .admin-settings {
                border-radius: 13px;
            }

            .admin-settings:hover {
                background: #808080a8;
            }
        </style>
        <div class="custom-grid-container w-100p pad-1em  settings-1 t-color-black" tabindex="1">
            <div class="custom-grid-item d-flex-start c-hand admin-settings settings-1" onclick="changePassword()">
                <div class=" b-bottom-gray-1px w-100p h-100p d-flex-start settings-1">
                    <img class="settings-1" src="../../assets/img/logout2.png" alt=""
                         style="width: 2em; height: 2em">
                    <label for=""
                           class="settings-1 c-hand m-t-9px f-weight-bold">&nbsp;&nbsp;Change Password</label>
                </div>
            </div>
            <div class="custom-grid-item d-flex-start c-hand admin-settings settings-1" onclick="logout()">
                <div class=" b-bottom-gray-1px w-100p h-100p d-flex-start settings-1">
                    <img class="settings-1" src="../../assets/img/logout2.png" alt=""
                         style="width: 2em; height: 2em">
                    <label for=""
                           class="settings-1 c-hand m-t-9px f-weight-bold">&nbsp;&nbsp;Logout</label>
                </div>
            </div>
        </div>
    </div>
</div>
<style>

    body {
        margin: 0;
        padding: 0;
    }

    /* Footer styles */
    .footer {
        background: #333;
        color: #fff;
        padding: 20px;
        text-align: center;
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        transition: opacity 0.3s;
    }

    /* Animation styles */
    .fade-in {
        opacity: 0;
        animation: fadeInAnimation 2s ease-in forwards;
    }

    @keyframes fadeInAnimation {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

</style>
<footer>
    <div class="footer fade-in">
        &#169; 2023 MABES|Grade Inquiry | All rights reserved
    </div>
</footer>
<script src="../../assets/js/script.js"></script>
</body>

<script>

    function logout() {
        $('#modal-logout').attr('style', 'display: block !important;')
    }

    function changePassword() {
        $('#modal-change-password').attr('style', 'display: block !important;')
    }

    $(document).on('click', '#modal-cancel', function(e){
        $('#modal-logout').attr('style', 'display: none !important;')
        $('#modal-change-password').attr('style', 'display: none !important;')
    });

    $(document).on('click', '#modal-ok', function(e){
        Post('', {logout: 'logout'});
    });

    $(document).on('click', '#modal-change-password-ok', function(e){
        var change_password = $('.change-pass').val();
        Post('', {changePass: change_password});
    });


    document.body.onclick = function (e) {
        if (e.target.id === 'settings') {
            if ($('#settings-details').hasClass("d-flex")) {
                $('#settings-details').removeClass("d-flex")
                x = 0;
            } else {
                x = 1;
                $('#settings-details').addClass("d-flex")
            }
        } else if (e.target.className.includes('settings-1')) {
            $('#settings-details').addClass("d-flex")
        } else {
            $('#settings-details').removeClass("d-flex")
        }
    }

</script>
</html>