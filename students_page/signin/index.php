<?php $var = "sign in";
include '../../students_page/header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

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
        $_SESSION['username'] = $username;
        if($row['user_type'] == 'student') {
            header("Location: /1-php-grading-system/students_page/teacher_info?id=" . $row['id']);
        } else if($row['user_type'] == 'teacher') {
            header("Location: /1-php-grading-system/admins_page/teacher_info?id=" . $row['id']);
        } else {
            header("Location: /1-php-grading-system/admins_page/dashboard?id=" . $row['id']);
        }
    } else {
        header("Location: ../signin?error=username and password is incorrect, pls try again. &$user_data");
    }
}
?>

<div style="background: #14ae5c;">
    <div class="d-flex-center w-100p">
        <div id="content" class=" w-82-8p h-100p b-r-10px">
            <div class="m-2em d-flex-center h-48em ">
                <div class="login">
                    <div class="t-align-center"><img
                                src="../../assets/img/mabes.png" alt=""
                                class="h-8em w-8em"/></div>
                    <div class="text">Mactan Airbase Elementary School</div>
                    <div class="text2">Grade Inquiry</div>
                    <?php if (isset($_GET['error'])) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $_GET['error']; ?>
                        </div>
                    <?php } ?>
                    <form action="index.php" method="post" class="d-flex-center t-align-center w-100p b-shadow-none">
                        <div class="b-radius-10px bg-gray h-80p w-77p">
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
                                        name="login">Log
                                </button>
                            </div>
                        </div>
                    </form>
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
    .login{
        width: 620px;
        height: 520px;
  margin: 0 auto;
  margin-top: 100px;
  background: rgba(255,255,255,0.5);
  padding: 20px;
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
<footer>
<div class="footer fade-in">
    &#169; 2023 MABES|Grade Inquiry | All rights reserved
  </div>
</footer>

<script>
    localStorage.setItem('studArrowLeft', '0')
    localStorage.setItem('topArrow', '1')
</script>

