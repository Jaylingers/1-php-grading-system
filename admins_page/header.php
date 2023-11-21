<?php global $var, $var1; ?>

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
    $row = mysqli_fetch_assoc($result);
    if ($row['user_type'] === 'student') {
        $sqlStudent = "SELECT * FROM students_info si
             left join users_info ui on ui.user_lrn = si.lrn
             WHERE ui.id='$id'";
        $resultStudent = mysqli_query($conn, $sqlStudent);
        $rows = mysqli_fetch_assoc($resultStudent);
    } else if ($row['user_type'] === 'teacher') {
        $sqlTeacher = "SELECT * FROM teachers_info ti
             left join users_info ui on ui.user_lrn = ti.lrn
             WHERE ui.id='$id'";
        $resultTeacher = mysqli_query($conn, $sqlTeacher);
        $rows = mysqli_fetch_assoc($resultTeacher);
    } else {
        $rows = $row;
    }

    if (isset($_POST['logout'])) {
        unset($_SESSION['user_type']); // remove it now we have used it
        unset($_SESSION['ids']); // remove it now we have used it
        header("Location: /1-php-grading-system/");
    }
}

if (isset($_POST['editProfile'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $id = $_GET['id'];

    $sql = "UPDATE users_info SET first_name='$firstname', last_name='$lastname', username='$username', password='$password', email='$email' WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
              alert("saved successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&updateProfile=success");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['darkMode'])) {
    $id = $_GET['id'];
    $darkMode = $_POST['darkMode'];
    $sql = "UPDATE users_info SET dark_mode='$darkMode' WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                window.location.reload();
            ';
        echo '</script>';
    }
}

?>


<?php if ($rows['dark_mode'] === '1') { ?>
<!DOCTYPE html>
<html>
<title>MABES GRADE INQUIRY</title>
<link rel="shortcut icon" href="../../assets/img/mabes.png"/>
<head>
    <link rel="stylesheet" href="../../assets/css/style_custom.css">
    <link rel="stylesheet" href="../../assets/css/admin_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="../../assets/js/js_header.js" defer></script>
</head>
<body class="bg-dark" onload="loadTopArrow()">
<div class="b-shadow-dark p-fixed w-20p h-100p bg-gray-light b-top-right-radius-10 z-i-99999 bg-dark" id="side">
    <div class="d-flex-center t-color-white b-bottom-white-3px f-weight-bold h-4em" id="side-a">
        <a href="/1-php-grading-system/"> <img
                    src="../../assets/img/mabes.png" alt=""
                    class="w-38px m-3-6px"/></a>
        <p class="m-0 b-right-2px-white pad-left-6px pad-right-6px t-color-green"> MABES </p>
        <p class=" m-0 pad-left-6px">GRADE INQUIRY</p>
        <div id="x-hide-show-side-bar" class="c-hand p-absolute r-0 d-flex-center w-2-5em h-60px f-weight-100 bg-hover-blue b-top-right-radius-10"
             onclick="tops()">x
        </div>
    </div>

    <div id="sideTab" class="pad-1em t-color-white f-weight-bold">
        <div class="m-t-3em"></div>
        <div class="tab-dashboard d-none h-4em d-flex-center ">
            <div class="p-absolute" style="margin-right: 11em;">
                <?xml version="1.0" ?><svg class="svg-1" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z"/></svg>
            </div>
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('dashboard')" <?php if ($var === "dashboard") { ?> style="background: #bababa;"  <?php } ?> >
                Dashboard <?= $var1 ?>
            </div>
            <div class="d-flex-end w-4em "></div>
        </div>
        <div class="tab-addUser d-none h-4em d-flex-center m-t-5px ">
            <div class="p-absolute" style="margin-right: 11em;">
                <?xml version="1.0" ?><!DOCTYPE svg  PUBLIC '-//W3C//DTD SVG 1.1//EN'  'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'><svg class="svg-1" height="24px" id="Capa_1" style="enable-background:new 0 0 100 90;" version="1.1" viewBox="0 0 100 90" width="100px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M61.885,67.24c-12.457-4.491-16.438-8.282-16.438-16.395c0-4.871,3.803-3.28,5.473-12.2c0.693-3.7,4.053-0.06,4.695-8.507   c0-3.366-1.834-4.203-1.834-4.203s0.932-4.982,1.297-8.816C55.531,12.342,52.289,0,35,0S14.469,12.342,14.922,17.119   c0.365,3.834,1.297,8.816,1.297,8.816s-1.834,0.837-1.834,4.203c0.643,8.447,4.002,4.807,4.693,8.507   c1.672,8.919,5.475,7.329,5.475,12.2c0,8.113-3.98,11.904-16.438,16.395C6.615,67.78,3.039,68.621,0,69.933V90h80   c0,0,0-7.396,0-10.526C80,76.341,74.381,71.746,61.885,67.24z M85,40V25H75v15H60v10h15v15h10V50h15V40H85z"/></g><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/></svg>
                 </div>
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('add_user')" <?php if ($var === "add_user" || $var === "add_student" || $var === "add_new_user" || $var === "add_teacher" || $var === "student_list") { ?> style="background: #bababa;"  <?php } ?>>
                Add User
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft','userTab')"></div>
            </div>
        </div>
        <div class="tab-addUser d-none ov-hidden transition-0-5s " id="userTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('add_teacher')" <?php if ($var === "add_teacher" || $var === "student_list") { ?> style="background: #bababa;"  <?php } ?>>
                    Teacher
                </div>
            </div>
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('add_new_user')" <?php if ($var === "add_new_user") { ?> style="background: #bababa;"  <?php } ?>>
                    Admin
                </div>
            </div>
        </div>
        <div class="tab-teacherInfo d-none h-5em  d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('teacher_info')" <?php if ($var === "teacher_info") { ?> style="background: #bababa;"  <?php } ?>>
                Teacher Information
            </div>
            <div class="d-flex-end w-4em"></div>
        </div>
        <div class="tab-masterlist d-none h-4em d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('masterlist')" <?php if ($var === "masterlist" || $var === "add_student" || $var === "student_list_masterlist") { ?> style="background: #bababa;"  <?php } ?>>
                Masterlist
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_masterlist" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_masterlist','masterlistTab')"></div>
            </div>
        </div>
        <div class="tab-masterlist d-none ov-hidden transition-0-5s " id="masterlistTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('add_student')" <?php if ($var === "add_student") { ?> style="background: #bababa;"  <?php } ?>>
                    Add Student
                </div>
            </div>
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('student_list_masterlist')" <?php if ($var === "student_list_masterlist") { ?> style="background: #bababa;"  <?php } ?>>
                    Student List
                </div>
            </div>
        </div>
        <div class="tab-records d-none h-4em d-flex-center m-t-5px ">
            <div class="p-absolute" style="margin-right: 11em;">
                <?xml version="1.0" ?><svg class="svg-1" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M 16 2 C 14.74 2 13.850156 2.89 13.410156 4 L 5 4 L 5 29 L 27 29 L 27 4 L 18.589844 4 C 18.149844 2.89 17.26 2 16 2 z M 16 4 C 16.55 4 17 4.45 17 5 L 17 6 L 20 6 L 20 8 L 12 8 L 12 6 L 15 6 L 15 5 C 15 4.45 15.45 4 16 4 z M 7 6 L 10 6 L 10 10 L 22 10 L 22 6 L 25 6 L 25 27 L 7 27 L 7 6 z M 9 13 L 9 15 L 11 15 L 11 13 L 9 13 z M 13 13 L 13 15 L 23 15 L 23 13 L 13 13 z M 9 17 L 9 19 L 11 19 L 11 17 L 9 17 z M 13 17 L 13 19 L 23 19 L 23 17 L 13 17 z M 9 21 L 9 23 L 11 23 L 11 21 L 9 21 z M 13 21 L 13 23 L 23 23 L 23 21 L 13 21 z"/></svg>
            </div>
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('add_records')" <?php if ($var === "add_records" || $var === "promote_student" || $var === "subject_list") { ?> style="background: #bababa;"  <?php } ?>>
                Records
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_records" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_records','recordsTab')"></div>
            </div>
        </div>
        <div class="tab-records d-none ov-hidden transition-0-5s " id="recordsTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('promote_student')" <?php if ($var === "promote_student") { ?> style="background: #bababa;"  <?php } ?>>
                    Promote Student
                </div>
            </div>

            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('subject_list')" <?php if ($var === "subject_list") { ?> style="background: #bababa;"  <?php } ?>>
                    Subject List
                </div>
            </div>
        </div>
        <div class="tab-maintenance d-none h-4em d-flex-center m-t-5px ">
            <div class="p-absolute" style="margin-right: 11em;">
                <?xml version="1.0" ?><svg class="svg-1" width="100" height="24" id="Layer_1" style="enable-background:new 0 0 30 30;" version="1.1" viewBox="0 0 30 30" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M16.758,21.401c0,0,4.496,4.496,4.819,4.819c1.04,1.04,2.725,1.04,3.764,0c1.04-1.04,1.04-2.725,0-3.765  c-0.323-0.323-4.819-4.819-4.819-4.819L16.758,21.401z"/><path d="M23.998,11.003l-3.201-0.8l-0.8-3.201l3.706-3.706c-2.129-0.677-4.551-0.176-6.24,1.512c-2.41,2.41-1.639,5.547,0.772,7.957  c2.41,2.41,5.547,3.182,7.957,0.771c1.689-1.689,2.19-4.111,1.512-6.239L23.998,11.003z"/><polygon points="12.5,11.5 9,8 8,5 4,3 2,5 4,9 7,10 10.5,13.5 "/><path d="M17.879,8.879c-3.364,3.364-12.636,12.636-13,13c-1.172,1.172-1.172,3.071,0,4.243c1.172,1.172,3.071,1.172,4.243,0  c0.364-0.364,9.636-9.636,13-13L17.879,8.879z M7,25c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1s1,0.448,1,1C8,24.552,7.552,25,7,25z  "/></svg>  </div>
        </div>
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('maintenance')" <?php if ($var === "maintenance" || $var === "school_year" || $var === "grade_list") { ?> style="background: #bababa;"  <?php } ?>>
                &nbsp;&nbsp;&nbsp;&nbsp; Maintenance
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_maintenance" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_maintenance','maintenanceTab')"></div>
            </div>
        </div>
        <div class="tab-maintenance d-none ov-hidden transition-0-5s " id="maintenanceTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('school_year')" <?php if ($var === "school_year") { ?> style="background: #bababa;"  <?php } ?>>
                    School Year
                </div>
            </div>
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('grade_list')" <?php if ($var === "grade_list") { ?> style="background: #bababa;"  <?php } ?>>
                    Grade and Section List
                </div>
            </div>
        </div>
        <div class="tab-studentInfo d-none h-4em  d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('student_info')" <?php if ($var === "student_info") { ?> style="background: #bababa;"  <?php } ?>>
                Student Info
            </div>
            <div class="d-flex-end w-4em m-t-5px"></div>
        </div>
        <div class="tab-studentRecord d-none h-4em d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('studentRecord')" <?php if ($var === "studentRecord" || $var === "grade" || $var === "report") { ?> style="background: #bababa;"  <?php } ?>>
                Student Record
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_studentRecord" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_studentRecord','studentRecordTab')"></div>
            </div>
        </div>
        <div class="tab-studentRecord d-none ov-hidden transition-0-5s " id="studentRecordTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('grade')" <?php if ($var === "grade") { ?> style="background: #bababa;"  <?php } ?>>
                    Grade
                </div>
            </div>
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('report')" <?php if ($var === "report") { ?> style="background: #bababa;"  <?php } ?>>
                    Report
                </div>
            </div>
        </div>
        <div class="tab-teacherList d-none h-5em  d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('teacher_list')" <?php if ($var === "teacherList") { ?> style="background: #bababa;"  <?php } ?>>
                Teacher List
            </div>
            <div class="d-flex-end w-4em"></div>
        </div>
        <div class="tab-notification d-none h-4em  d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('notification')" <?php if ($var === "notification") { ?> style="background: #bababa;"  <?php } ?>>
                Notification
            </div>
            <div class="d-flex-end w-4em m-t-5px"></div>
        </div>
        <div class="tab-trash d-none h-4em  d-flex-center m-t-5px ">
            <div class="p-absolute" style="margin-right: 11em;">
                <?xml version="1.0" ?><svg class="svg-1" width="100" height="24" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title/><g data-name="1" id="_1"><path d="M356.65,450H171.47a41,41,0,0,1-40.9-40.9V120.66a15,15,0,0,1,15-15h237a15,15,0,0,1,15,15V409.1A41,41,0,0,1,356.65,450ZM160.57,135.66V409.1a10.91,10.91,0,0,0,10.9,10.9H356.65a10.91,10.91,0,0,0,10.91-10.9V135.66Z"/><path d="M327.06,135.66h-126a15,15,0,0,1-15-15V93.4A44.79,44.79,0,0,1,230.8,48.67h66.52A44.79,44.79,0,0,1,342.06,93.4v27.26A15,15,0,0,1,327.06,135.66Zm-111-30h96V93.4a14.75,14.75,0,0,0-14.74-14.73H230.8A14.75,14.75,0,0,0,216.07,93.4Z"/><path d="M264.06,392.58a15,15,0,0,1-15-15V178.09a15,15,0,1,1,30,0V377.58A15,15,0,0,1,264.06,392.58Z"/><path d="M209.9,392.58a15,15,0,0,1-15-15V178.09a15,15,0,0,1,30,0V377.58A15,15,0,0,1,209.9,392.58Z"/><path d="M318.23,392.58a15,15,0,0,1-15-15V178.09a15,15,0,0,1,30,0V377.58A15,15,0,0,1,318.23,392.58Z"/><path d="M405.81,135.66H122.32a15,15,0,0,1,0-30H405.81a15,15,0,0,1,0,30Z"/></g></svg>
            </div>
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('trash')" <?php if ($var === "trash") { ?> style="background: #bababa;"  <?php } ?>>
                Trash
            </div>
            <div class="d-flex-end w-4em"></div>
        </div>
    </div>
</div>

<div id="settings-details" class="p-absolute j-content-center z-i-999910" style="     position: fixed;
    height: 14em;
    width: 14em;
    top: 64px;
    right: 17px;
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
    <div class="custom-grid-container w-100p pad-1em  settings-1 bg-dark t-color-white" tabindex="1">
        <div class="custom-grid-item d-flex-start c-hand admin-settings"
             onclick="showModalInfo('<?= $rows['user_type'] ?>','<?= $rows['last_name'] ?>','profile')">
            <div class=" b-bottom-gray-1px w-100p h-100p d-flex-start settings-1">

                <!--                <img src="../../assets/img/profile1.png" alt="" style="width: 2em; height: 2em"> -->
                <svg class=" settings-1" style="width: 2em; height: 2em" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><title/>
                    <g id="about">
                        <path class="settings-1" d="M16,16A7,7,0,1,0,9,9,7,7,0,0,0,16,16ZM16,4a5,5,0,1,1-5,5A5,5,0,0,1,16,4Z"/>
                        <path class="settings-1" d="M17,18H15A11,11,0,0,0,4,29a1,1,0,0,0,1,1H27a1,1,0,0,0,1-1A11,11,0,0,0,17,18ZM6.06,28A9,9,0,0,1,15,20h2a9,9,0,0,1,8.94,8Z"/>
                    </g>
                </svg>
                <label for=""
                       class="c-hand m-t-9px f-weight-bold  settings-1">Profile</label>
            </div>
        </div>
        <div class="custom-grid-item d-flex-start c-hand admin-settings settings-1"
             onclick="darkMode()">
            <div class="settings-1 b-bottom-gray-1px w-100p h-100p d-flex-start">
                <img class="settings-1" src="../../assets/img/darkMode.png" alt="" style="width: 2em; height: 2em">
                <label for=""
                       class="settings-1 c-hand m-t-9px f-weight-bold">Dark
                    Mode</label>
                <div class="settings-1 transition-0-5s" id="circle-parent" style="
    height: 2em;
    width: 4em;
    border-radius: 17px;
    display: flex;
    align-items: center;
    margin-left: 12px;
 ">
                    <div class="settings-1" id="circle-child" style="
    height: 18px;
    width: 18px;
    border-radius: 50%;
    background: white;
   "></div>
                </div>
            </div>

        </div>
        <div class="custom-grid-item d-flex-start c-hand admin-settings settings-1" onclick="logout()">
            <div class=" b-bottom-gray-1px w-100p h-100p d-flex-start settings-1">
                <img class="settings-1" src="../../assets/img/logout.png" alt="" style="width: 2em; height: 2em"> <label for=""
                                                                                                                         class="settings-1 c-hand m-t-9px f-weight-bold">Logout</label>
            </div>
        </div>
    </div>
    <!--    </form>-->
</div>

<div id="top" class="p-fixed  w-82p d-flex r-0 h-4em z-i-9999 bg-blue b-shadow-dark bg-dark">
    <div class="w-30p "><label id="top-icon"
                               class="h-100p w-3em t-align-center d-flex-center c-hand d-none f-size-26px w-2em bg-hover-white"
                               for="" onclick="tops()">
            ☰</label></div>
    <div class="d-flex-end w-70p m-r-13px">
        <!--        <input type="text" placeholder="Search...">-->
        <div class="d-flex-center m-l-13px m-r-13px">
            Hello, <label for="" class="m-b-0 m-l-3px">   <?= $rows['user_type'] ?> <?= $rows['last_name'] ?>  </label>
        </div>
        <?php if ($rows['img_path'] == '') { ?>
            <img id="settings" src="../../assets/users_img/noImage.png"
                 style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;"
                 alt="" class="w-32px c-hand" >
        <?php } else { ?>
            <img id="settings" src="<?= $rows['img_path'] ?>"
                 style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;"
                 alt="" class="w-32px c-hand" >
        <?php } ?>


        <!--        <img id="settings" src="https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-arrow-down-b-512.png"-->
        <!--             class="w-18px m-r-13px c-hand transition-0-5s" alt="" />-->

    </div>

</div>

<div id="writeMessage" class="p-fixed d-flex-center c-hand" style="
    width: 3.5em;
    height: 3.5em;
    border-radius: 50%;

    bottom: 10px;
    right: 10px;
    z-index: 99999;">
    <img src="../../assets/img/writeMessage.png" alt="sds" style="height: 2em; width: 2em">
</div>

<div id="myModalAdminSettings" style="z-index: 9999999 !important; width: 100% !important;">
    <div class="modal-content" style="width: 65%; zoom: 0.8;">
        <div id="top-icon"
             class="top-icon h-100p d-flex-center p-absolute w-3em c-hand f-size-26px w-2em bg-hover-white t-color-white"
             onclick="tops()" style="left: -97px;top: -97px;height: 61px;">☰
        </div>
        <div class="modal-header a-center">
        </div>
        <div class="modal-body" style="overflow: hidden; background: #adadad;">
            <div id="show-profile-info" class="modal-child d-none h-100p">
                <div class="custom-grid-container h-100p" tabindex="2">
                    <div class="custom-grid-item h-100p d-flex-center">
                        <img class="pad-1em b-shadow-dark"
                             src="<?= $rows['img_path'] === ' ' ? $rows['img_path'] : '../../assets/users_img/noImage.png' ?>"
                             alt="" style="width: 86%;
    height: 35em; border-radius: 50%;">
                    </div>
                    <div id="editProfile" class="custom-grid-item b-shadow-dark pad-1em"
                         style="background: #d6d6d6; height: 41em;">


                        <div id="display">
                            LRN: <?= isset($rows['lrn']) ? $rows['lrn'] : $rows['id'] ?><br>
                            First Name: <label for=""> <?= $rows['first_name'] ?> </label>
                            <br>
                            Last Name: <label for=""> <?= $rows['last_name'] ?> </label>
                            <br>
                            UserName: <label for=""> <?= $rows['username'] ?> </label>
                            <br>
                            Password: <label for=""> <?= $rows['password'] ?> </label>
                            <br>
                            Email: <label for=""> <?= isset($rows['email']) ? $rows['email'] : 'none' ?> </label> <br>
                            User Type: <label for=""> <?= $rows['user_type'] ?> </label>
                            <br>
                            <div>
                                <button id="edit"
                                        class="btn btn-success bg-hover-gray-dark-v1"
                                        style="position: absolute; right: 24px; bottom: 29px;"
                                        onclick="edit()">
                                    Edit
                                </button>
                            </div>

                        </div>
                        <div id="editForm" class="d-none">
                            <form method="post">
                                LRN: <?= isset($rows['lrn']) ? $rows['lrn'] : $rows['id'] ?><br>
                                First Name: <input
                                        value="<?= $rows['first_name'] ?>" id="firstname" type="text" name="firstname"
                                        class=" m-b-5px"/>
                                <br>
                                Last Name: <input
                                        value="<?= $rows['last_name'] ?>" id="lastname" type="text" name="lastname"
                                        class=" m-b-5px"/>
                                <br>
                                UserName: <input
                                        value="<?= $rows['username'] ?>" id="username" type="text"
                                        class=" m-b-5px" name="username"/>
                                <br>
                                Password: <input
                                        value="<?= $rows['password'] ?>" id="password" type="text"
                                        class=" m-b-5px" name="password"/>
                                <br>
                                Email:
                                <input
                                        value="<?= $rows['email'] ?>"
                                        id="email" type="email" name="email"
                                        class=" m-b-5px"/> <br>
                                User Type: <input
                                        value="<?= $rows['user_type'] ?>" id="user_type" type="text"
                                        class=" m-b-5px"
                                        readonly="true"/>
                                <br>

                                <div>
                                    <button id="saveButton" type="submit" class="c-hand btn-success btn "
                                            name="editProfile" style="position: absolute; right: 24px; bottom: 29px;">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>
            <div id="show-themes" class="modal-child d-none h-100p">
                <h2>Side Bar</h2>
               BG Color:  <input type="color" id="sideBarColor"> <br>
                 Font Color: <input type="color" id="sideBarFontColor"> <br>
                 Font Style: <input type="text" id="sideBarFontStyle"> <br>
                 Font Size: <input type="number" id="sideBarFontSize"> <br>
                Font Weight: <input type="number" id="sideBarFontWeight"> <br>

                <h2>Header</h2>
                BG Color:  <input type="color" id="topBarColor"> <br>
                 Font Color: <input type="color" id="topBarFontColor"> <br>
                 Font Style: <input type="text" id="topBarFontStyle"> <br>
                 Font Size: <input type="number" id="topBarFontSize"> <br>
                font Weight: <input type="number" id="topBarFontWeight"> <br>
            </div>

        </div>
    </div>
</div>
</body>
</html>
<?php } else { ?>
    <!DOCTYPE html>
    <html>
    <title>MABES GRADE INQUIRY</title>
    <link rel="shortcut icon" href="../../assets/img/mabes.png"/>
    <head>
        <link rel="stylesheet" href="../../assets/css/style_custom.css">
        <link rel="stylesheet" href="../../assets/css/admin_style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
              integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="../../assets/js/js_header.js" defer></script>
    </head>
    <body class="bg-dark" onload="loadTopArrow()">
    <div class="b-shadow-dark p-fixed w-20p h-100p bg-gray-light b-top-right-radius-10 z-i-99999" id="side">
        <div class="d-flex-center t-color-white b-bottom-white-3px f-weight-bold h-4em" id="side-a">
            <a href="/1-php-grading-system/"> <img
                        src="../../assets/img/mabes.png" alt=""
                        class="w-38px m-3-6px"/></a>
            <p class="m-0 b-right-2px-white pad-left-6px pad-right-6px t-color-green"> MABES </p>
            <p class=" m-0 pad-left-6px">GRADE INQUIRY</p>
            <div id="x-hide-show-side-bar" class="c-hand p-absolute r-0 d-flex-center w-2-5em h-60px f-weight-100 bg-hover-blue b-top-right-radius-10"
                 onclick="tops()">x
            </div>
        </div>

        <div id="sideTab" class="pad-1em t-color-white f-weight-bold">
            <div class="m-t-3em"></div>
            <div class="tab-dashboard d-none h-4em d-flex-center ">
                <div class="p-absolute" style="margin-right: 11em;">
                    <?xml version="1.0" ?><svg class="svg-1" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z"/></svg>
                </div>
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('dashboard')" <?php if ($var === "dashboard") { ?> style="background: #bababa;"  <?php } ?> >
                    Dashboard <?= $var1 ?>
                </div>
                <div class="d-flex-end w-4em "></div>
            </div>
            <div class="tab-addUser d-none h-4em d-flex-center m-t-5px ">
                <div class="p-absolute" style="margin-right: 11em;">
                    <?xml version="1.0" ?><!DOCTYPE svg  PUBLIC '-//W3C//DTD SVG 1.1//EN'  'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'><svg class="svg-1" height="24px" id="Capa_1" style="enable-background:new 0 0 100 90;" version="1.1" viewBox="0 0 100 90" width="100px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M61.885,67.24c-12.457-4.491-16.438-8.282-16.438-16.395c0-4.871,3.803-3.28,5.473-12.2c0.693-3.7,4.053-0.06,4.695-8.507   c0-3.366-1.834-4.203-1.834-4.203s0.932-4.982,1.297-8.816C55.531,12.342,52.289,0,35,0S14.469,12.342,14.922,17.119   c0.365,3.834,1.297,8.816,1.297,8.816s-1.834,0.837-1.834,4.203c0.643,8.447,4.002,4.807,4.693,8.507   c1.672,8.919,5.475,7.329,5.475,12.2c0,8.113-3.98,11.904-16.438,16.395C6.615,67.78,3.039,68.621,0,69.933V90h80   c0,0,0-7.396,0-10.526C80,76.341,74.381,71.746,61.885,67.24z M85,40V25H75v15H60v10h15v15h10V50h15V40H85z"/></g><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/></svg>
                </div>
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('add_user')" <?php if ($var === "add_user" || $var === "add_student" || $var === "add_new_user" || $var === "add_teacher" || $var === "student_list") { ?> style="background: #bababa;"  <?php } ?>>
                    Add User
                </div>
                <div class="d-flex-end w-4em">
                    <div id="arrowLeftButton" class="w-1-5em h-1-5em c-hand "
                         onclick="saveKeyOnLocalStorage(this,'studArrowLeft','userTab')"></div>
                </div>
            </div>
            <div class="tab-addUser d-none ov-hidden transition-0-5s " id="userTab" style="height: 0">
                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('add_teacher')" <?php if ($var === "add_teacher" || $var === "student_list") { ?> style="background: #bababa;"  <?php } ?>>
                        Teacher
                    </div>
                </div>
                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('add_new_user')" <?php if ($var === "add_new_user") { ?> style="background: #bababa;"  <?php } ?>>
                        Admin
                    </div>
                </div>
            </div>
            <div class="tab-teacherInfo d-none h-5em  d-flex-center m-t-5px ">
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('teacher_info')" <?php if ($var === "teacher_info") { ?> style="background: #bababa;"  <?php } ?>>
                    Teacher Information
                </div>
                <div class="d-flex-end w-4em"></div>
            </div>
            <div class="tab-masterlist d-none h-4em d-flex-center m-t-5px ">
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('masterlist')" <?php if ($var === "masterlist" || $var === "add_student" || $var === "student_list_masterlist") { ?> style="background: #bababa;"  <?php } ?>>
                    Masterlist
                </div>
                <div class="d-flex-end w-4em">
                    <div id="arrowLeftButton_masterlist" class="w-1-5em h-1-5em c-hand "
                         onclick="saveKeyOnLocalStorage(this,'studArrowLeft_masterlist','masterlistTab')"></div>
                </div>
            </div>
            <div class="tab-masterlist d-none ov-hidden transition-0-5s " id="masterlistTab" style="height: 0">
                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('add_student')" <?php if ($var === "add_student") { ?> style="background: #bababa;"  <?php } ?>>
                        Add Student
                    </div>
                </div>
                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('student_list_masterlist')" <?php if ($var === "student_list_masterlist") { ?> style="background: #bababa;"  <?php } ?>>
                        Student List
                    </div>
                </div>
            </div>
            <div class="tab-records d-none h-4em d-flex-center m-t-5px ">
                <div class="p-absolute" style="margin-right: 11em;">
                    <?xml version="1.0" ?><svg class="svg-1" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M 16 2 C 14.74 2 13.850156 2.89 13.410156 4 L 5 4 L 5 29 L 27 29 L 27 4 L 18.589844 4 C 18.149844 2.89 17.26 2 16 2 z M 16 4 C 16.55 4 17 4.45 17 5 L 17 6 L 20 6 L 20 8 L 12 8 L 12 6 L 15 6 L 15 5 C 15 4.45 15.45 4 16 4 z M 7 6 L 10 6 L 10 10 L 22 10 L 22 6 L 25 6 L 25 27 L 7 27 L 7 6 z M 9 13 L 9 15 L 11 15 L 11 13 L 9 13 z M 13 13 L 13 15 L 23 15 L 23 13 L 13 13 z M 9 17 L 9 19 L 11 19 L 11 17 L 9 17 z M 13 17 L 13 19 L 23 19 L 23 17 L 13 17 z M 9 21 L 9 23 L 11 23 L 11 21 L 9 21 z M 13 21 L 13 23 L 23 23 L 23 21 L 13 21 z"/></svg>
                       </div>
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('add_records')" <?php if ($var === "add_records" || $var === "promote_student" || $var === "subject_list") { ?> style="background: #bababa;"  <?php } ?>>
                    Records
                </div>
                <div class="d-flex-end w-4em">
                    <div id="arrowLeftButton_records" class="w-1-5em h-1-5em c-hand "
                         onclick="saveKeyOnLocalStorage(this,'studArrowLeft_records','recordsTab')"></div>
                </div>
            </div>
            <div class="tab-records d-none ov-hidden transition-0-5s " id="recordsTab" style="height: 0">
                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('promote_student')" <?php if ($var === "promote_student") { ?> style="background: #bababa;"  <?php } ?>>
                        Promote Student
                    </div>
                </div>

                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('subject_list')" <?php if ($var === "subject_list") { ?> style="background: #bababa;"  <?php } ?>>
                        Subject List
                    </div>
                </div>
            </div>
            <div class="tab-maintenance d-none h-4em d-flex-center m-t-5px ">
                <div class="p-absolute" style="margin-right: 11em;">
                    <?xml version="1.0" ?><svg class="svg-1" width="100" height="24" id="Layer_1" style="enable-background:new 0 0 30 30;" version="1.1" viewBox="0 0 30 30" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M16.758,21.401c0,0,4.496,4.496,4.819,4.819c1.04,1.04,2.725,1.04,3.764,0c1.04-1.04,1.04-2.725,0-3.765  c-0.323-0.323-4.819-4.819-4.819-4.819L16.758,21.401z"/><path d="M23.998,11.003l-3.201-0.8l-0.8-3.201l3.706-3.706c-2.129-0.677-4.551-0.176-6.24,1.512c-2.41,2.41-1.639,5.547,0.772,7.957  c2.41,2.41,5.547,3.182,7.957,0.771c1.689-1.689,2.19-4.111,1.512-6.239L23.998,11.003z"/><polygon points="12.5,11.5 9,8 8,5 4,3 2,5 4,9 7,10 10.5,13.5 "/><path d="M17.879,8.879c-3.364,3.364-12.636,12.636-13,13c-1.172,1.172-1.172,3.071,0,4.243c1.172,1.172,3.071,1.172,4.243,0  c0.364-0.364,9.636-9.636,13-13L17.879,8.879z M7,25c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1s1,0.448,1,1C8,24.552,7.552,25,7,25z  "/></svg>  </div>
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('maintenance')" <?php if ($var === "maintenance" || $var === "school_year" || $var === "grade_list") { ?> style="background: #bababa;"  <?php } ?>>
                  &nbsp;&nbsp;&nbsp;&nbsp; Maintenance
                </div>
                <div class="d-flex-end w-4em">
                    <div id="arrowLeftButton_maintenance" class="w-1-5em h-1-5em c-hand "
                         onclick="saveKeyOnLocalStorage(this,'studArrowLeft_maintenance','maintenanceTab')"></div>
                </div>
            </div>
            <div class="tab-maintenance d-none ov-hidden transition-0-5s " id="maintenanceTab" style="height: 0">
                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('school_year')" <?php if ($var === "school_year") { ?> style="background: #bababa;"  <?php } ?>>
                        School Year
                    </div>
                </div>
                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('grade_list')" <?php if ($var === "grade_list") { ?> style="background: #bababa;"  <?php } ?>>
                        Grade and Section List
                    </div>
                </div>
            </div>
            <div class="tab-studentInfo d-none h-4em  d-flex-center m-t-5px ">
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('student_info')" <?php if ($var === "student_info") { ?> style="background: #bababa;"  <?php } ?>>
                    Student Info
                </div>
                <div class="d-flex-end w-4em m-t-5px"></div>
            </div>
            <div class="tab-studentRecord d-none h-4em d-flex-center m-t-5px ">
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('studentRecord')" <?php if ($var === "studentRecord" || $var === "grade" || $var === "report") { ?> style="background: #bababa;"  <?php } ?>>
                    Student Record
                </div>
                <div class="d-flex-end w-4em">
                    <div id="arrowLeftButton_studentRecord" class="w-1-5em h-1-5em c-hand "
                         onclick="saveKeyOnLocalStorage(this,'studArrowLeft_studentRecord','studentRecordTab')"></div>
                </div>
            </div>
            <div class="tab-studentRecord d-none ov-hidden transition-0-5s " id="studentRecordTab" style="height: 0">
                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('grade')" <?php if ($var === "grade") { ?> style="background: #bababa;"  <?php } ?>>
                        Grade
                    </div>
                </div>
                <div class=" h-4em d-flex-end m-t-5px">
                    <div class="d-flex-center w-4em"><img
                                src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                                alt="" class="w-18px c-hand rotate"></div>
                    <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                         onclick="selectTab('report')" <?php if ($var === "report") { ?> style="background: #bababa;"  <?php } ?>>
                        Report
                    </div>
                </div>
            </div>
            <div class="tab-teacherList d-none h-5em  d-flex-center m-t-5px ">
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('teacher_list')" <?php if ($var === "teacherList") { ?> style="background: #bababa;"  <?php } ?>>
                    Teacher List
                </div>
                <div class="d-flex-end w-4em"></div>
            </div>
            <div class="tab-notification d-none h-4em  d-flex-center m-t-5px ">
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('notification')" <?php if ($var === "notification") { ?> style="background: #bababa;"  <?php } ?>>
                    Notification
                </div>
                <div class="d-flex-end w-4em m-t-5px"></div>
            </div>
            <div class="tab-trash d-none h-4em  d-flex-center m-t-5px ">
                <div class="p-absolute" style="margin-right: 11em;">
                    <?xml version="1.0" ?><svg class="svg-1" width="100" height="24" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title/><g data-name="1" id="_1"><path d="M356.65,450H171.47a41,41,0,0,1-40.9-40.9V120.66a15,15,0,0,1,15-15h237a15,15,0,0,1,15,15V409.1A41,41,0,0,1,356.65,450ZM160.57,135.66V409.1a10.91,10.91,0,0,0,10.9,10.9H356.65a10.91,10.91,0,0,0,10.91-10.9V135.66Z"/><path d="M327.06,135.66h-126a15,15,0,0,1-15-15V93.4A44.79,44.79,0,0,1,230.8,48.67h66.52A44.79,44.79,0,0,1,342.06,93.4v27.26A15,15,0,0,1,327.06,135.66Zm-111-30h96V93.4a14.75,14.75,0,0,0-14.74-14.73H230.8A14.75,14.75,0,0,0,216.07,93.4Z"/><path d="M264.06,392.58a15,15,0,0,1-15-15V178.09a15,15,0,1,1,30,0V377.58A15,15,0,0,1,264.06,392.58Z"/><path d="M209.9,392.58a15,15,0,0,1-15-15V178.09a15,15,0,0,1,30,0V377.58A15,15,0,0,1,209.9,392.58Z"/><path d="M318.23,392.58a15,15,0,0,1-15-15V178.09a15,15,0,0,1,30,0V377.58A15,15,0,0,1,318.23,392.58Z"/><path d="M405.81,135.66H122.32a15,15,0,0,1,0-30H405.81a15,15,0,0,1,0,30Z"/></g></svg>
                </div>
                <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('trash')" <?php if ($var === "trash") { ?> style="background: #bababa;"  <?php } ?>>
                    Trash
                </div>
                <div class="d-flex-end w-4em"></div>
            </div>
        </div>
    </div>

    <div id="settings-details" class="p-absolute j-content-center z-i-999910" style="     position: fixed;
    height: 14em;
    width: 14em;
    top: 64px;
    right: 17px;
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
        <div class="custom-grid-container w-100p pad-1em  settings-1" tabindex="1">
            <div class="custom-grid-item d-flex-start c-hand admin-settings"
                 onclick="showModalInfo('<?= $rows['user_type'] ?>','<?= $rows['last_name'] ?>','profile')">
                <div class=" b-bottom-gray-1px w-100p h-100p d-flex-start settings-1">

                    <!--                <img src="../../assets/img/profile1.png" alt="" style="width: 2em; height: 2em"> -->
                    <svg class=" settings-1" style="width: 2em; height: 2em" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><title/>
                        <g id="about">
                            <path class="settings-1" d="M16,16A7,7,0,1,0,9,9,7,7,0,0,0,16,16ZM16,4a5,5,0,1,1-5,5A5,5,0,0,1,16,4Z"/>
                            <path class="settings-1" d="M17,18H15A11,11,0,0,0,4,29a1,1,0,0,0,1,1H27a1,1,0,0,0,1-1A11,11,0,0,0,17,18ZM6.06,28A9,9,0,0,1,15,20h2a9,9,0,0,1,8.94,8Z"/>
                        </g>
                    </svg>
                    <label for=""
                           class="c-hand m-t-9px f-weight-bold  settings-1">Profile</label>
                </div>
            </div>
            <div class="custom-grid-item d-flex-start c-hand admin-settings settings-1"
                 onclick="darkMode()">
                <div class="settings-1 b-bottom-gray-1px w-100p h-100p d-flex-start">
                    <img class="settings-1" src="../../assets/img/darkMode.png" alt="" style="width: 2em; height: 2em">
                    <label for=""
                           class="settings-1 c-hand m-t-9px f-weight-bold">Dark
                        Mode</label>
                    <div class="settings-1  transition-0-5s bg-gray" id="circle-parent" style="
    height: 2em;
    width: 4em;
    border-radius: 17px;
    display: flex;
    align-items: center;
    margin-left: 12px;
 ">
                        <div class="settings-1" id="circle-child" style="
    height: 18px;
    width: 18px;
    border-radius: 50%;
    background: white;
   "></div>
                    </div>
                </div>

            </div>
            <div class="custom-grid-item d-flex-start c-hand admin-settings settings-1" onclick="logout()">
                <div class=" b-bottom-gray-1px w-100p h-100p d-flex-start settings-1">
                    <img class="settings-1" src="../../assets/img/logout.png" alt="" style="width: 2em; height: 2em"> <label for=""
                                                                                                                             class="settings-1 c-hand m-t-9px f-weight-bold">Logout</label>
                </div>
            </div>
        </div>
        <!--    </form>-->
    </div>

    <div id="top" class="p-fixed  w-82p d-flex r-0 h-4em z-i-9999 bg-blue b-shadow-dark">
        <div class="w-30p "><label id="top-icon"
                                   class="h-100p w-3em t-align-center d-flex-center c-hand d-none f-size-26px w-2em bg-hover-white"
                                   for="" onclick="tops()">
                ☰</label></div>
        <div class="d-flex-end w-70p m-r-13px">
            <!--        <input type="text" placeholder="Search...">-->
            <div class="d-flex-center m-l-13px m-r-13px">
                Hello, <label for="" class="m-b-0 m-l-3px">   <?= $rows['user_type'] ?> <?= $rows['last_name'] ?>  </label>
            </div>
            <?php if ($rows['img_path'] == '') { ?>
                <img id="settings" src="../../assets/users_img/noImage.png"
                     style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;"
                     alt="" class="w-32px c-hand" >
            <?php } else { ?>
                <img id="settings" src="<?= $rows['img_path'] ?>"
                     style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;"
                     alt="" class="w-32px c-hand" >
            <?php } ?>


            <!--        <img id="settings" src="https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-arrow-down-b-512.png"-->
            <!--             class="w-18px m-r-13px c-hand transition-0-5s" alt="" />-->

        </div>

    </div>

    <div id="writeMessage" class="p-fixed d-flex-center c-hand" style="
    width: 3.5em;
    height: 3.5em;
    border-radius: 50%;

    bottom: 10px;
    right: 10px;
    z-index: 99999;">
        <img src="../../assets/img/writeMessage.png" alt="sds" style="height: 2em; width: 2em">
    </div>

    <div id="myModalAdminSettings" style="z-index: 9999999 !important; width: 100% !important;">
        <div class="modal-content" style="width: 65%; zoom: 0.8;">
            <div id="top-icon"
                 class="top-icon h-100p d-flex-center p-absolute w-3em c-hand f-size-26px w-2em bg-hover-white t-color-white"
                 onclick="tops()" style="left: -97px;top: -97px;height: 61px;">☰
            </div>
            <div class="modal-header a-center">
            </div>
            <div class="modal-body" style="overflow: hidden; background: #adadad;">
                <div id="show-profile-info" class="modal-child d-none h-100p">
                    <div class="custom-grid-container h-100p" tabindex="2">
                        <div class="custom-grid-item h-100p d-flex-center">
                            <img class="pad-1em b-shadow-dark"
                                 src="<?= $rows['img_path'] === ' ' ? $rows['img_path'] : '../../assets/users_img/noImage.png' ?>"
                                 alt="" style="width: 86%;
    height: 35em; border-radius: 50%;">
                        </div>
                        <div id="editProfile" class="custom-grid-item b-shadow-dark pad-1em"
                             style="background: #d6d6d6; height: 41em;">


                            <div id="display">
                                LRN: <?= isset($rows['lrn']) ? $rows['lrn'] : $rows['id'] ?><br>
                                First Name: <label for=""> <?= $rows['first_name'] ?> </label>
                                <br>
                                Last Name: <label for=""> <?= $rows['last_name'] ?> </label>
                                <br>
                                UserName: <label for=""> <?= $rows['username'] ?> </label>
                                <br>
                                Password: <label for=""> <?= $rows['password'] ?> </label>
                                <br>
                                Email: <label for=""> <?= isset($rows['email']) ? $rows['email'] : 'none' ?> </label> <br>
                                User Type: <label for=""> <?= $rows['user_type'] ?> </label>
                                <br>
                                <div>
                                    <button id="edit"
                                            class="btn btn-success bg-hover-gray-dark-v1"
                                            style="position: absolute; right: 24px; bottom: 29px;"
                                            onclick="edit()">
                                        Edit
                                    </button>
                                </div>

                            </div>
                            <div id="editForm" class="d-none">
                                <form method="post">
                                    LRN: <?= isset($rows['lrn']) ? $rows['lrn'] : $rows['id'] ?><br>
                                    First Name: <input
                                            value="<?= $rows['first_name'] ?>" id="firstname" type="text" name="firstname"
                                            class=" m-b-5px"/>
                                    <br>
                                    Last Name: <input
                                            value="<?= $rows['last_name'] ?>" id="lastname" type="text" name="lastname"
                                            class=" m-b-5px"/>
                                    <br>
                                    UserName: <input
                                            value="<?= $rows['username'] ?>" id="username" type="text"
                                            class=" m-b-5px" name="username"/>
                                    <br>
                                    Password: <input
                                            value="<?= $rows['password'] ?>" id="password" type="text"
                                            class=" m-b-5px" name="password"/>
                                    <br>
                                    Email:
                                    <input
                                            value="<?= $rows['email'] ?>"
                                            id="email" type="email" name="email"
                                            class=" m-b-5px"/> <br>
                                    User Type: <input
                                            value="<?= $rows['user_type'] ?>" id="user_type" type="text"
                                            class=" m-b-5px"
                                            readonly="true"/>
                                    <br>

                                    <div>
                                        <button id="saveButton" type="submit" class="c-hand btn-success btn "
                                                name="editProfile" style="position: absolute; right: 24px; bottom: 29px;">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>
                <div id="show-themes" class="modal-child d-none h-100p">
                    <h2>Side Bar</h2>
                    BG Color:  <input type="color" id="sideBarColor"> <br>
                    Font Color: <input type="color" id="sideBarFontColor"> <br>
                    Font Style: <input type="text" id="sideBarFontStyle"> <br>
                    Font Size: <input type="number" id="sideBarFontSize"> <br>
                    Font Weight: <input type="number" id="sideBarFontWeight"> <br>

                    <h2>Header</h2>
                    BG Color:  <input type="color" id="topBarColor"> <br>
                    Font Color: <input type="color" id="topBarFontColor"> <br>
                    Font Style: <input type="text" id="topBarFontStyle"> <br>
                    Font Size: <input type="number" id="topBarFontSize"> <br>
                    font Weight: <input type="number" id="topBarFontWeight"> <br>
                </div>

            </div>
        </div>
    </div>
    </body>
    </html>
<?php } ?>
<script>

    document.body.onclick = function (e) {
        if(e.target.id === 'settings') {
            if($('#settings-details').hasClass("d-flex")) {
                $('#settings-details').removeClass("d-flex")
                x = 0;
            } else {
                x = 1;
                $('#settings-details').addClass("d-flex")
            }
        } else if(e.target.className.includes('settings-1')) {
            $('#settings-details').addClass("d-flex")
        } else {
            $('#settings-details').removeClass("d-flex")
        }
    }

    function darkMode() {

        $('#settings-details').toggleClass('bg-dark')
        $('#top').toggleClass('bg-dark')
        $('#top').toggleClass('b-bottom-white-3px')
        $('#side').toggleClass('bg-dark')
        $('#x-hide-show-side-bar').toggleClass('bg-dark')

        let darkMode = localStorage.getItem("darkMode");
        if (darkMode !== '1') {
            localStorage.setItem("darkMode", "1");
            $('.settings-1 label').css('color', 'white')
            $('#top div').css('color', 'white')
            $('#circle-parent').addClass('pad-left-44px')
            $('#circle-parent').removeClass('bg-gray')
            $('#circle-parent').addClass('bg-light-green')
            $('#content').addClass('bg-dark')
            $('body').addClass('bg-dark')
        } else {
            localStorage.setItem("darkMode", "0");
            $('.settings-1 label').css('color', 'black')
            $('#top div').css('color', 'black')
            $('#circle-parent').removeClass('pad-left-44px')
            $('#circle-parent').addClass('bg-gray')
            $('#top').addClass('bg-blue')
            $('#x-hide-show-side-bar').removeClass('bg-dark')
            $('#settings-details').removeClass('bg-dark')
            $('#settings-details > div').removeClass('bg-dark')
            $('body').removeClass('bg-dark')
            $('#content').removeClass('bg-dark')
        }
    }

    function settings() {
        $('#settings').toggleClass("rotate")
        $('#settings-details').toggleClass("d-flex")
    }

    function tops() {
        $('#top').toggleClass("pressed")
        $('#side').toggleClass("collapsed")
        $('#content').toggleClass("pressed")
        $('#top-icon').toggleClass("d-flex")
        $('#myModal').toggleClass("full-width")
        saveKeyOnLocalStorage('#top-icon', 'topArrow', 'top')
        localStorage.getItem('topArrow') === '1' ? $('.top-icon').css('display', 'none') : $('.top-icon').css('display', '');
    }

    function selectTab(tab) {
        if (tab === 'add_user') {
            saveKeyOnLocalStorage('#arrowLeftButton', 'studArrowLeft', 'userTab');
        } else if (tab === 'add_records') {
            saveKeyOnLocalStorage('#arrowLeftButton_records', 'studArrowLeft_records', 'recordsTab');
        } else if (tab === 'maintenance') {
            saveKeyOnLocalStorage('#arrowLeftButton_maintenance', 'studArrowLeft_maintenance', 'maintenanceTab');
        } else if (tab === 'masterlist') {
            saveKeyOnLocalStorage('#arrowLeftButton_masterlist', 'studArrowLeft_masterlist', 'masterlistTab');
        } else if (tab === 'studentRecord') {
            saveKeyOnLocalStorage('#arrowLeftButton_studentRecord', 'studArrowLeft_studentRecord', 'studentRecordTab');
        } else {
            window.location.href = "/1-php-grading-system/admins_page/" + tab + "/?id=" + <?= $rows['id'] ?>;
        }
    }

    function saveKeyOnLocalStorage(e, keyName, tabName) {

        if (tabName === 'userTab' || tabName === 'recordsTab' || tabName === 'maintenanceTab' || tabName === 'masterlistTab' || tabName === 'studentRecordTab') {
            $('#' + tabName).toggleClass(
                tabName === 'userTab' || tabName === 'recordsTab' || tabName === 'maintenanceTab' || tabName === 'masterlistTab' || tabName === 'studentRecordTab' ? 'h-8-8em'
                    : 'none')
            if (localStorage.getItem(keyName) === '1') {
                $(e).removeClass('bg-img-2')
                $(e).addClass('bg-img-1')
            } else {
                $(e).removeClass('bg-img-1')
                $(e).addClass('bg-img-2')
            }
        } else {

        }

        localStorage.getItem(keyName) === '1' ? localStorage.setItem(keyName, '0') : localStorage.setItem(keyName, '1');
    }

    function loadStudArrowLeft() {
        let get = localStorage.getItem('studArrowLeft');

        if (get === '1') {
            $('#arrowLeftButton').addClass('bg-img-2')
            $('#userTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton').addClass('bg-img-1')
            $('#userTab').removeClass('h-8-8em')
        }

        let get_rec = localStorage.getItem('studArrowLeft_records');

        if (get_rec === '1') {
            $('#arrowLeftButton_records').addClass('bg-img-2')
            $('#recordsTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton_records').addClass('bg-img-1')
            $('#recordsTab').removeClass('h-8-8em')
        }

        let get_main = localStorage.getItem('studArrowLeft_maintenance');

        if (get_main === '1') {
            $('#arrowLeftButton_maintenance').addClass('bg-img-2')
            $('#maintenanceTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton_maintenance').addClass('bg-img-1')
            $('#maintenanceTab').removeClass('h-8-8em')
        }

        let get_masterlist = localStorage.getItem('studArrowLeft_masterlist');
        if (get_masterlist === '1') {
            $('#arrowLeftButton_masterlist').addClass('bg-img-2')
            $('#masterlistTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton_masterlist').addClass('bg-img-1')
            $('#masterlistTab').removeClass('h-8-8em')
        }

        let get_studentRecord = localStorage.getItem('studArrowLeft_studentRecord');
        if (get_studentRecord === '1') {
            $('#arrowLeftButton_studentRecord').addClass('bg-img-2')
            $('#studentRecordTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton_studentRecord').addClass('bg-img-1')
            $('#studentRecordTab').removeClass('h-8-8em')
        }
    }

    function loadTopArrow() {
        let get = localStorage.getItem('topArrow');

        if (get === '0') {
            $('#side').addClass('collapsed')
            $('#top').toggleClass("pressed")
            $('#top-icon').toggleClass("d-flex")
            $('#content').addClass('pressed')
            $('#myModal').addClass('full-width')
        }
    }

    function viewUserTabs() {
        var user_type = "<?= $rows['user_type'] ?>";
        if (user_type) {
            if (user_type.toLowerCase() === 'admin') {
                $('.tab-dashboard').removeClass('d-none')
                $('.tab-addUser').removeClass('d-none')
                $('.tab-records').removeClass('d-none')
                $('.tab-maintenance').removeClass('d-none')
                $('.tab-trash').removeClass('d-none')
            } else if (user_type.toLowerCase() === 'teacher') {
                $('.tab-teacherInfo').removeClass('d-none')
                $('.tab-masterlist').removeClass('d-none')
                $('.tab-records').removeClass('d-none')
                $('.tab-trash').removeClass('d-none')
                $('.records_student_list').addClass('d-none')
                $('.tab-notification').removeClass('d-none')
            } else if (user_type === 'student') {
                $('.tab-studentInfo').removeClass('d-none')
                $('.tab-studentRecord').removeClass('d-none')
                $('.tab-teacherList').removeClass('d-none')
                $('.tab-notification').removeClass('d-none')
            }
        }
    }

    function showModalInfo(userType, lastname,status) {
        if(status === 'profile'){
            showModalAdminSettings('show-profile-info', 'WELCOME ' + userType.toUpperCase() + ' ' + lastname.toUpperCase() + '!', '', '')
        } else {
            showModalAdminSettings('show-themes', 'THEMES', '', '')
        }

    }

    function edit() {
        $('#editProfile #display').addClass('d-none')
        $('#editProfile #editForm').removeClass('d-none')
    }

    function logout() {
        var r = confirm("Are you sure you want to logout?");
        if (r === true) {
            Post('', {logout: 'logout'});
        }
    }

    $(document).ready(function () {
        loadStudArrowLeft();
        viewUserTabs();
        var updateProfile = '<?php echo isset($_GET['updateProfile']) ? $_GET['updateProfile'] : '' ?>';
        if (updateProfile) {
            showModalInfo('<?= $rows['user_type'] ?>', '<?= $rows['last_name'] ?>','profile');
        }
    });

    function loadPage() {
        let load = localStorage.getItem("load");
        let darkMode = localStorage.getItem("darkMode");
        if (darkMode === '1') {
            $.post('', {darkMode: 1});

            if (load !== '1') {
                localStorage.setItem("load", "1");
                window.location.reload();
            }
            $('.settings-1 label').css('color', 'white')
            $('#top div').css('color', 'white')
            $('#circle-parent').addClass('pad-left-44px')
            $('#circle-parent').removeClass('bg-gray')
            $('#circle-parent').addClass('bg-light-green')
            $('#top').addClass('b-bottom-white-3px')

        } else {
            $.post('', {darkMode: 0});
            if (load === '1') {
                localStorage.setItem("load", "0");
                window.location.reload();
            }
            $('body').removeClass('bg-dark')
        }
    }

    loadPage();

    var colorWell;
    var defaultColor = "#0000ff";
    window.addEventListener("load", startup, false);
    function startup()
    {
        colorWell = document.querySelector("#sideBarColor");
        colorWell.addEventListener("input", updateFirst, false);
        colorWell.select();

        colorWell = document.querySelector("#topBarColor");
        colorWell.addEventListener("input", updateSecond, false);
        colorWell.select();

    }
    function updateFirst(event)
    {
        document.querySelector("#side").setAttribute('style','background-color:'+event.target.value+' !important;');
    }
    function updateSecond(event)
    {
        document.querySelector("#top").setAttribute('style','background-color:'+event.target.value+' !important;');

    }


</script>