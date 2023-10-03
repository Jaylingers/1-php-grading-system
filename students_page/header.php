<?php global $var; ?>
<!DOCTYPE html>
<html>
<title>MABES GRADING INQUIRY</title>
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
        <a href="/1-php-grading-system/"> <img
                    src="../../assets/img/mabes.png" alt=""/></a>
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