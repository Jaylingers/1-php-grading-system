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

}
?>


<!DOCTYPE html>
<html>
<title>MABES GRADING INQUIRY123</title>
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
    height: 5em;
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
        <!--    <form action="index.php" method="post">-->
        <div class="custom-grid-container w-100p pad-1em  settings-1 t-color-black" tabindex="1">
            <div class="custom-grid-item d-flex-start c-hand admin-settings settings-1" onclick="logout()">
                <div class=" b-bottom-gray-1px w-100p h-100p d-flex-start settings-1">
                    <img class="settings-1" src="../../assets/img/logout2.png" alt=""
                         style="width: 2em; height: 2em">
                    <label for=""
                           class="settings-1 c-hand m-t-9px f-weight-bold">Logout</label>
                </div>
            </div>
        </div>
        <!--    </form>-->
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
        var r = confirm("Are you sure you want to logout?");
        if (r === true) {
            Post('', {logout: 'logout'});
        }
    }

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