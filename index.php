<?php
global $conn;
include "db_conn.php";

if (isset($_POST['login'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    $sql = "select * from users_info where username='$username' and password='$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $user_data = 'username=' . $username . '&password=' . $password;

    if ($row['username'] === $username && $row['password'] === $password) {

        session_start();
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['ids'] = $row['id'];
        $user_type = $row['user_type'];
        $id = $row['id'];

        $sqlInsertPageVisited = "insert into page_visited_info (user_id, date_visited) values ('$id', now())";
        mysqli_query($conn, $sqlInsertPageVisited);

        if (strtolower($user_type) == 'student') {
            header("Location: /1-php-grading-system/students_page/student_info?id=" . $row['id']);
        } else if (strtolower($user_type) == 'teacher') {
            header("Location: /1-php-grading-system/teachers_page/teacher_info?id=" . $row['id']);
        } else {
            header("Location: /1-php-grading-system/admins_page/dashboard?id=" . $row['id']);
        }
    } else {
        header("Location: /1-php-grading-system/?error=username and password is incorrect, pls try again. &$user_data");
    }
}

session_start();
if (isset($_SESSION['user_type'])) {
    $userType = $_SESSION['user_type'];
    $id = $_SESSION['ids'];
    echo "<script> alert('$userType')</script>";
    if (strtolower($userType) == 'student') {
        header("Location: /1-php-grading-system/students_page/student_info?id=" . $id);
    } else if (strtolower($userType) == 'teacher') {
        header("Location: /1-php-grading-system/teachers_page/teacher_info?id=" . $id);
    } else {
        header("Location: /1-php-grading-system/admins_page/dashboard?id=" . $id);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MABES GRADE INQUIRY</title>
    <link rel="stylesheet" href="assets/css/main.css"/>
    <link rel="stylesheet" href="assets/css/media.css"/>
    <link rel="shortcut icon" href="mabes-frontpage/mabes.png"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
    />
</head>
<div class="mobile-login" style=" display: none;
    position: fixed;
    width: 100%;
    height: 100%;
    overflow: hidden;
    text-align: center;
    justify-content: center;
    z-index: 9;

    align-items: center;">
    <div style="width: 100%;">
        <div class="logo">
            <h1><span>MABES</span> GRADE INQUIRY</h1>
        </div>
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert" style="color: red">
                <?php echo $_GET['error']; ?>
            </div>
        <?php } ?>
        <form action="index.php" method="post" class="d-flex-center t-align-center w-100p b-shadow-none">
            <div class="b-radius-10px  h-80p w-77p">
                <div class="d-flex-center h-5em">
                    <input type="username" style="width: 36em; border-radius: 2em;
    height: 5em;"
                           id="username"
                           name="username"
                           required
                           placeholder="Username/LRN"
                           class="h-3em w-40p t-align-center f-size-1em b-radius-10px"
                           value="<?php if (isset($_GET['username']))
                               echo($_GET['username']); ?>"
                    >
                </div>
                <br>
                <div class="d-flex-center h-5em">
                    <input placeholder="Password" type="password" style="width: 36em; border-radius: 2em;
    height: 5em;"
                           class="h-3em w-40p t-align-center f-size-1em b-radius-10px"
                           id="password"
                           name="password"
                           required
                           value="<?php if (isset($_GET['password']))
                               echo($_GET['password']); ?>"
                    >
                </div>
                <br>
                <div class="d-flex-center h-5em" style="display: flex; justify-content: center">
                    <div style=" width: 30em; text-align: right">
                        <button
                                class="c-hand h-3em w-30p t-align-center f-size-1em b-radius-10px bg-blue btn_io1 "
                                onclick="back()"
                        >
                          Back

                        </button>
                        <button type="submit"
                                class="c-hand h-3em w-30p t-align-center f-size-1em b-radius-10px bg-blue btn_io "
                                name="login">Login
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>

</div>
<body id="body">
<!-- Top header -->
<div class="top-header">
    <div class="container_thr">
        <div class="df_thr">
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
<nav class="navbar">
    <div class="container">
        <div class="content_nv">
            <ul class="nav-links">
                <li><a href="#intro" class="active_link_nv">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
<!-- Intro section -->
<section id="intro" class="intro">
    <div class="container">
        <div class="content_io">
            <h1>MACTAN AIR BASE ELEMENTARY SCHOOL</h1>
            <div class="btn_io">
                LOGIN
            </div>
        </div>
    </div>
</section>
<!-- End Intro section -->
<!-- About section  -->
<section id="about" class="about">
    <div class="container">
        <div class="df_at">
            <img src="assets/img/Learning.jpg" class="img_at" alt="About Picture"/>
            <div class="text_at">
                <p class="first_at">ABOUT US</p>
                <h1>Innovative Way To Learn</h1>
                <p>
                    There a lot of facilities in our school. You don't only study, but
                    also open your opportunities in there. You will be glad to study
                    in there. Our teachers are professional. So you accept the modern,
                    creative lessons. Our school good at english and sports.
                </p>
            </div>
        </div>
    </div>
</section>
<!-- End About section  -->
<!-- Contact section -->
<section id="contact" class="contact">
    <div class="container">
        <div class="df_ct">
            <div class="text_ct">
                <p class="first_ct">NEED GOOD SCHOOL</p>
                <h1>ONLINE GRADE INQUIRE</h1>
                <p>
                    This website help us to manage . We have a lot interesting
                    methods. You can see our advantages:
                </p>
                <p><i class="fa-solid fa-check"></i> No Hasle Go to school</p>
                <p><i class="fa-solid fa-check"></i> Free Access through Online</p>
            </div>
        </div>
    </div>
</section>
<!-- End Contact section -->
<!-- Footer -->
<footer class="footer">
    <div class="df_foo">
        <div class="col_foo">
            <p class="heading_foo">GET IN TOUCH</p>
            <p>
                <i class="fa-solid fa-location-dot"></i> Sangi Road, Lapu-lapu City,
                Cebu
            </p>
            <p><i class="fa-solid fa-envelope"></i> mabes@gmail.com</p>
            <p><i class="fa-solid fa-phone"></i> (032) 340 8046</p>
            <div class="icons_foo">
                <a href="https://t.me/your_telegram_link">
                    <i class="fa-brands fa-telegram"></i>
                </a>
                <a href="https://web.facebook.com/profile.php?id=100057676800277">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/your_twitter_handle">
                    <i class="fa-brands fa-twitter"></i>
                </a>
            </div>
        </div>
        <div class="col_foo">
            <p class="heading_foo">Contact Us</p>
            <p><b>Call Us</b></p>
            <p class="smallp_thr">(032) 340 8046</p>
            <p><b>Our email</b></p>
            <p class="smallp_thr">mabes@gmail.com</p>
        </div>
        <div class="col_foo">
            <p class="heading_foo">NEWSLETTER</p>
            <p>
                If you want to request. You must connet to us. Give us your email.
                If you want to sign up, you must type form in connect section.
            </p>
            <div class="email-div">
                <input type="text" class="form-control" placeholder="Name"/>
                <div class="btn_foo"><a href="#">Send Now</a></div>
            </div>
        </div>
    </div>
    <div class="bottom-footer">
        <div class="df_foo">
            <p>@ All right reserved. Mactan Airbase Elementary School</p>
        </div>
    </div>
</footer>
<!-- End Footer -->
<!-- Js link -->
</body>

<script src="assets/js/script.js"></script>
<script>

    $(document).ready(function () {
        $('.btn_io').click(function () {
            $('body').css('overflow', 'hidden');
            $('.mobile-login').css('display', 'flex');
            $('.mobile-login').show();
        });
    });

    function loadPage() {
        var error = '<?php echo isset($_GET['error']) ? $_GET['error'] : '' ?>';
        if (error != '') {
            $('body').css('overflow', 'hidden');
            $('.mobile-login').css('display', 'flex');
            $('.mobile-login').show();
        }

    }
    function back() {
        window.location.href = "/1-php-grading-system/";
    }

    loadPage();
</script>
</body>
</html>
