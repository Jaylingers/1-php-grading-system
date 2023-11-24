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
    <title>MABES GRADE | INQUIRY</title>
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
<body id="body">
<!-- Top header -->
<div class="top-header">
    <div class="container_thr">
        <div class="df_thr">
            <img src="assets/img/mabes.png" alt="" class="logo1" />
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
<!-- Login form container -->
<div id="login-container" class="login-container">
    <div class="login-form">
        <span class="close-btn" onclick="closeLoginForm()">&times;</span>
        <img src="assets/img/mabes.png" alt="IMG" class="logo2"/>
        <form action="index.php" method="post" class="login-form">
            <span class="login-form-title">Student Login</span>
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert" style="color: red">
                    <?php echo $_GET['error']; ?>
                </div>
            <?php } ?>
            <div class="wrap-input">
                <input
                        type="text"
                        class="input"
                        name="username"
                        placeholder="Username/Lrn"
                        required
                />
                <span class="focus-input"></span>
                <span class="symbol-input">
              <i class="fa fa-envelope" aria-hidden="true"></i>
            </span>
            </div>
            <div class="wrap-input">
                <input
                        type="password"
                        class="input"
                        name="password"
                        placeholder="Password"
                        required
                />
                <span class="focus-input"></span>
                <span class="symbol-input">
              <i class="fa fa-lock" aria-hidden="true"></i>
            </span>
            </div>

            <div class="login-form-btn-container">
                <!--                <button class="login-form-btn">Login</button>-->
                <button type="submit"
                        class="login-form-btn"
                        name="login">Login
                </button>
            </div>
        </form>
    </div>
</div>
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
                    
Our school boasts a multitude of facilities that go beyond traditional academics, offering a diverse range of opportunities for personal and intellectual growth. It's not just a place to study; it's a hub where you can unlock numerous possibilities. The environment is designed to inspire and encourage, providing modern and creative lessons that our professional teachers deliver with expertise. When you choose to study in our school, you not only gain proficiency in English but also excel in sports, showcasing the well-rounded education we provide. You'll find that our commitment to excellence extends far beyond the classroom, making your educational journey a truly enriching and fulfilling experience.
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
                This website helps us manage various tasks through a range of interesting methods. Explore our advantages:
                </p>
                <p><i class="fa-solid fa-check"></i> Hassle-Free School Access</p>
                <p><i class="fa-solid fa-check"></i> Free Online Accessibility</p>
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
            document.getElementById("login-container").style.display = "flex";
        });
    });

    function loadPage() {
        var error = '<?php echo isset($_GET['error']) ? $_GET['error'] : '' ?>';
        if (error != '') {
            document.getElementById("login-container").style.display = "flex";
        }

    }

    function closeLoginForm() {
        document.getElementById("login-container").style.display = "none";
    }

    // Close the form if the user clicks outside the form
    window.onclick = function (event) {
        var loginContainer = document.getElementById("login-container");
        if (event.target === loginContainer) {
            loginContainer.style.display = "none";
        }
    };
    loadPage();
</script>
</html>
