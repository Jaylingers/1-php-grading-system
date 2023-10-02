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
        <div id="content" class=" w-79-8p h-100p b-r-7px">
            <div class="m-2em d-flex-center h-48em ">
                <div class="bg-white t-color-black pad-5px w-44em b-radius-10px">
                    <div class="t-align-center"><img
                                src="https://th.bing.com/th/id/OIP.Lsswy08HBmxSwV6Pt9uZKgHaHa?pid=ImgDet&rs=1" alt=""
                                class="h-4em w-4em"/></div>
                    <div class="t-align-center f-size-2em f-weight-bold">MABES</div>
                    <div class="t-align-center f-size-2em f-weight-bold">Online Grading Inquiry Student</div>
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
                                       placeholder="Username"
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
                            <div class="d-flex-center h-2em">
                                <label for=""
                                       class="t h-2em w-40p t-align-center f-size-1em b-radius-10px t-color-blue">Forgot
                                    Password</label>
                            </div>
                            <div class="d-flex-center h-5em">
                                <button type="submit"
                                        class="c-hand h-3em w-30p t-align-center f-size-1em b-radius-10px bg-blue"
                                        name="login">login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    localStorage.setItem('studArrowLeft', '0')
    localStorage.setItem('topArrow', '1')
</script>

