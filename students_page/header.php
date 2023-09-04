<?php global $var; ?>
<!DOCTYPE html>
<html>
<title>MABES GRADING INQUIRY</title>
<link rel="shortcut icon" href="https://th.bing.com/th/id/OIP.Lsswy08HBmxSwV6Pt9uZKgHaHa?pid=ImgDet&rs=1"/>
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
        <a href="/1-php-grading-system/"> <img
                    src="https://th.bing.com/th/id/OIP.Lsswy08HBmxSwV6Pt9uZKgHaHa?pid=ImgDet&rs=1" alt=""/></a>
        <p> MABES </p>
        <p>Grading Inquiry</p>
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
                <a href="/1-php-grading-system/students_page/home" <?php if ($var === "home") { ?> style="border-bottom: 3px solid #9747ff"  <?php } ?>>Home</a>
            </li>
            <li>
                <a href="/1-php-grading-system/students_page/about" <?php if ($var === "about") { ?> style="border-bottom: 3px solid #9747ff"  <?php } ?>>About
                    Us</a></li>
            <li>
                <a href="/1-php-grading-system/students_page/contact" <?php if ($var === "contact") { ?> style="border-bottom: 3px solid #9747ff"  <?php } ?>>Contact
                    Us</a></li>
        </ul>
    </div>
    <div>
        <button class="btn btn-primary" onclick="signin()">Sign In</button>
    </div>
</nav>
</body>
</html>