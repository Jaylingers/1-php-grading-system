
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
        echo "<script> $('.login').show();</script>";

    }
}

session_start();
if (isset($_SESSION['user_type'])) {
    $userType = $_SESSION['user_type'];
    $id = $_SESSION['ids'];
    echo "<script> alert('$userType')</script>";
    if(strtolower($userType) == 'student'){
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
    <link rel="stylesheet" href="mabes-frontpage/style.css"/>
    <link rel="stylesheet" href="assets/css/style_custom.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"/>
    <link rel="shortcut icon" href="mabes-frontpage/mabes.png"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

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

    .login {
        width: 620px;
        height: 560px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        animation: fadein 4s;
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        -webkit-box-shadow: -2px 1.5px 10.5px 10px #dddddd;
        -moz-box-shadow: -2px 1.5px 10.5px 10px #dddddd;
        box-shadow: -2px 1.5px 10.5px 10px white;
    }

    input {

        margin-bottom: 10px;
        height: 60px;
        padding: 5px;
        border: 1px solid gray;
        border-radius: 5px;
    }

    .text {
        color: green;
        font-size: 25px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: bold;
    }

    .text2 {
        color: black;
        font-size: 30px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: bold;
    }

</style>

<body style="overflow: hidden">
<div class="">
    <div class="header">
        <div data-aos="fade-right" data-aos-duration="700" class="logo">
            <img src="mabes-frontpage/mabes.png" alt=""/>
        </div>
    </div>
    <div class="banner" style="background-image: url('mabes-frontpage/mabesRooms2.jpg')">
        <div
                data-aos="fade-left"
                data-aos-delay="900"
                data-aos-duration="700"
                class="glass-wrapper"
        ></div>
    </div>
    <div class="hero-title">
        <p
                data-aos="fade-left"
                data-aos-delay="500"
                data-aos-duration="700"
                class="line"
        >
            School
        </p>
        <p
                data-aos="fade-left"
                data-aos-delay="600"
                data-aos-duration="700"
                class="school"
        >
            MABES <span class="multitext"></span>
        </p>
        <div
                data-aos="fade-left"
                data-aos-delay="800"
                data-aos-duration="700"
                class="details"
        >
            Mactan Airbase Elementary School is to have a online platform and
            friendly access to the student to see their different Grades in intire
            quarter.
        </div>
        <div
                data-aos="fade-left"
                data-aos-delay="800"
                data-aos-duration="700"
                class="Visit"
        >
            <button onclick="login()">LOGIN</button>
        </div>
    </div>
    <div class="mabes-list">
        <ul class="place-list">
            <li
                    data-aos="zoom-in"
                    data-aos-duration="700"
                    data-aos-duration="700"
                    class="active"
                    data-img="mabes-frontpage/mabes2.jpg"
            >
                <div class="thumbnail" style="background-image: url('mabes-frontpage/mabes2.jpg')">
                    <div class="details">
                        <p class="place">MABES</p>
                        <p class="place-name">GROUND FIELD</p>
                    </div>
                </div>
            </li>
            <li
                    data-aos="zoom-in"
                    data-aos-duration="750"
                    data-aos-duration="700"
                    data-img="mabes-frontpage/mabes3.jpg"
            >
                <div class="thumbnail" style="background-image: url('mabes-frontpage/mabes3.jpg')">
                    <div class="details">
                        <p class="place">MABES</p>
                        <p class="place-name">SCHOOL FRONT</p>
                    </div>
                </div>
            </li>
            <li
                    data-aos="zoom-in"
                    data-aos-duration="850"
                    data-aos-duration="700"
                    data-img="mabes-frontpage/mabesRooms2.jpg"
            >
                <div
                        class="thumbnail"
                        style="background-image: url('mabes-frontpage/mabesRooms2.jpg')"
                >
                    <div class="details">
                        <p class="place">MABES</p>
                        <p class="place-name">SHOOL ROOMS</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>


</div>
<div class="login" style="
  position: fixed;
    top: 7em;
    right: 7em;
     transition-delay: 2s;
">
    <div class="t-align-center"><img
                src="assets/img/mabes.png" alt=""
                class="h-8em w-8em"/></div>
    <div class="text">Mactan Airbase Elementary School</div>
    <div class="text2">Grade Inquiry</div>
    <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_GET['error']; ?>
        </div>
    <?php } ?>
    <form action="index.php" method="post" class="d-flex-center t-align-center w-100p b-shadow-none">
        <div class="b-radius-10px  h-80p w-77p">
            <div class="d-flex-center h-5em">
                <input type="username"
                       id="username"
                       name="username"
                       required
                       placeholder="Username/LRN"
                       class="h-3em w-40p t-align-center f-size-1em b-radius-10px"
                       value="<?php if (isset($_GET['username']))
                           echo($_GET['username']); ?>"
                >
            </div>
            <div class="d-flex-center h-5em">
                <input placeholder="Password" type="password"
                       class="h-3em w-40p t-align-center f-size-1em b-radius-10px"
                       id="password"
                       name="password"
                       required
                       value="<?php if (isset($_GET['password']))
                           echo($_GET['password']); ?>"
                >
            </div>

            <div class="d-flex-center h-5em">
                <button type="submit"
                        class="c-hand h-3em w-30p t-align-center f-size-1em b-radius-10px bg-blue"
                        name="login">Login
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();

    var typingEffect = new Typed(".multitext", {
        strings: ["ONLINE", "GRADE", "INQUIRY"],
        loop: true,
        typeSpeed: 100,
        backSpeed: 80,
        backDelay: 1500,
    });

    let places = document.querySelectorAll(".mabes-list li");
    let active = "lingayen-1.jpg";

    places.forEach((e) => {
        e.addEventListener("mouseenter", (event) => {
            places.forEach((e) => {
                e.classList.remove("active");
            });

            event.target.classList.add("active");
            active = event.target.getAttribute("data-img");
            let banner = document.querySelector(".banner");
            banner.style.backgroundImage = `url('${active}')`;
        });
    });

    function login() {
        $('.login').delay(90).show(20);
    }


    function loadPage() {
        $('.login').hide();
        localStorage.setItem('studArrowLeft', '0')
        localStorage.setItem('topArrow', '1')

        var error = '<?php echo isset($_GET['error']) ? $_GET['error'] : '' ?>';
        if (error !== '') {
            login();
        }
    }

    loadPage();

</script>
</body>
</html>
