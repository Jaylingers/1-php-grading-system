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

    <?php if ($var === "index") { ?>
    <link rel="stylesheet" href="assets/css/style_custom.css">
    <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/js/js_header.js" defer></script>
    <?php } else { ?>
    <link rel="stylesheet" href="../../assets/css/style_custom.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
        <script src="../../assets/js/js_header.js" defer></script>
    <?php } ?>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div style="height: 3em;background: #9747ff;"></div>

<nav class="navbar">
    <div class="brand-title">

            <?php if ($var === "index") { ?>
            <img
                    src="assets/img/mabes.png" alt=""/>
        <?php } else { ?>
            <img
                    src="../../assets/img/mabes.png" alt=""/></a>
        <?php } ?>
        <p> Mactan Airbase Elementary School </p>
        <p>Grade Inquiry</p>
    </div>
    <a href="#" class="toggle-button">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </a>
    <div class="navbar-links">
        <ul>
            <li>
                <a href="/1-php-grading-system/students_page/home/?id=<?php echo $_GET['id'] ?>" <?php if ($var === "home") { ?> style="border-bottom: 3px solid #9747ff"  <?php } ?>>Student Info</a>
            </li>
            <li>
                <a href="/1-php-grading-system/students_page/about/?id=<?php echo $_GET['id'] ?>" <?php if ($var === "about") { ?> style="border-bottom: 3px solid #9747ff"  <?php } ?>>Teachers Info
                    Us</a></li>
            <li>
                <a href="/1-php-grading-system/students_page/contact/?id=<?php echo $_GET['id'] ?>" <?php if ($var === "contact") { ?> style="border-bottom: 3px solid #9747ff"  <?php } ?>>Teachers List
                    </a></li>
        </ul>
    </div>
    <div>
        <form action="index.php" method="post">
            <button type="submit" class="c-hand"
                    name="logout">LOGOUT
            </button>
        </form>
    </div>
</nav>
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
</body>
</html>