<?php global $var, $var1; ?>

<?php
global $conn;
include "../../db_conn.php";

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /1-php-grading-system/admins_page/404");
} else {
        $id = $_GET['id'];
        $sql = "SELECT * FROM users_info WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_assoc($result);

        if (isset($_POST['logout'])) {
            unset($_SESSION['username']); // remove it now we have used it
            header("Location: /1-php-grading-system/students_page/signin/");
        }
}


?>

<!DOCTYPE html>
<html>
<title>MABES GRADE INQUIRY</title>
<link rel="shortcut icon" href="../../assets/img/mabes.png"/>
<head>
    <link rel="stylesheet" href="../../assets/css/style_custom.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="../../assets/js/js_header.js" defer></script>
</head>
<body class="bg-blue" onload="loadTopArrow()">
<div class="p-fixed w-20p h-100p bg-gray-light b-top-right-radius-10 z-i-9999" id="side">
    <div class="d-flex-center t-color-white b-bottom-white-3px f-weight-bold h-4em">
        <a href="/1-php-grading-system/"> <img
                    src="../../assets/img/mabes.png" alt=""
                    class="w-38px m-3-6px"/></a>
        <p class="m-0 b-right-2px-white pad-left-6px pad-right-6px t-color-green"> MABES </p>
        <p class=" m-0 pad-left-6px">GRADE INQUIRY</p>
        <div class="c-hand p-absolute r-0 d-flex-center w-2-5em h-60px f-weight-100 bg-hover-blue b-top-right-radius-10"
             onclick="tops()">x
        </div>
    </div>

    <div id="sideTab" class="pad-1em t-color-white f-weight-bold">
        <div class="m-t-3em"></div>
        <div class="tab-dashboard d-none h-4em d-flex-center ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('dashboard')" <?php if ($var === "dashboard") { ?> style="background: #bababa;"  <?php } ?> >
                Dashboard <?= $var1 ?>
            </div>
            <div class="d-flex-end w-4em "></div>
        </div>
        <div class="tab-addUser d-none h-4em d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('add_user')" <?php if ($var === "add_user" || $var === "add_student" || $var === "add_new_user"  || $var === "add_teacher") { ?> style="background: #bababa;"  <?php } ?>>
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
                     onclick="selectTab('add_teacher')" <?php if ($var === "add_teacher") { ?> style="background: #bababa;"  <?php } ?>>
                    Teacher
                </div>
            </div>
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('add_student')" <?php if ($var === "add_student") { ?> style="background: #bababa;"  <?php } ?>>
                    Student
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
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('add_records')" <?php if ($var === "add_records" || $var === "promote_student" || $var === "student_list" || $var === "subject_list") { ?> style="background: #bababa;"  <?php } ?>>
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
            <div class="records_student_list h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"
                     onclick="selectTab('student_list')" <?php if ($var === "student_list") { ?> style="background: #bababa;"  <?php } ?>>
                    Student List
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
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('maintenance')" <?php if ($var === "maintenance" || $var === "school_year" || $var === "grade_list") { ?> style="background: #bababa;"  <?php } ?>>
                Maintenance
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
                    Grade List
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
        <div class="tab-trash d-none h-5em  d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('trash')" <?php if ($var === "trash") { ?> style="background: #bababa;"  <?php } ?>>
                Trash
            </div>
            <div class="d-flex-end w-4em"></div>
        </div>
    </div>
</div>

<div id="settings-details" class="p-absolute j-content-center z-i-999910" style=" position:fixed;  height: 19em;
    width: 14em;
    top: 64px;
    right: 17px;
    display: none;
    background: #006ebf;">
    <form action="index.php" method="post">
        <button type="submit"
                name="logout">LOGOUT
        </button>
    </form>
</div>

<div id="top" class="bg-blue p-fixed  w-80p d-flex r-0 h-4em z-i-9999">
    <div class="w-30p "><label id="top-icon"
                               class="h-100p w-3em t-align-center d-flex-center c-hand d-none f-size-26px w-2em bg-hover-white"
                               for="" onclick="tops()">
            ☰</label></div>
    <div class="d-flex-end w-70p">
        <!--        <input type="text" placeholder="Search...">-->
        <div class="d-flex-center m-l-13px m-r-13px">
            Hello, <label for="" class="m-b-0 m-l-3px">   <?= $rows['user_type'] ?> <?= $rows['last_name'] ?> </label>
        </div>
        <?php if ($rows['img_path'] == '') { ?>
            <img src="../../assets/users_img/noImage.png"
                 style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;"
                    alt="" class="w-32px">
        <?php } else { ?>
            <img src="<?= $rows['img_path'] ?>"
                 style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;"
                    alt="" class="w-32px">
        <?php } ?>


        <img id="settings" src="https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-arrow-down-b-512.png"
             class="w-18px m-r-13px c-hand transition-0-5s" alt="" onclick="settings()"/>

    </div>

</div>

<div id="writeMessage" class="p-fixed d-flex-center c-hand" style="
    width: 3.5em;
    height: 3.5em;
    border-radius: 50%;

    bottom: 10px;
    right: 10px;
    z-index: 99999;">
    <img src="../../assets/img/writeMessage.png" alt="sds"  style="height: 2em; width: 2em">
</div>


</body>
</html>

<script>
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
                tabName === 'maintenanceTab' || tabName === 'masterlistTab' || tabName === 'studentRecordTab' ? 'h-8-5em'
                    : tabName === 'userTab' || tabName === 'recordsTab' ? 'h-13em'
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
            $('#userTab').addClass('h-13em')
        } else {
            $('#arrowLeftButton').addClass('bg-img-1')
            $('#userTab').removeClass('h-13em')
        }

        let get_rec = localStorage.getItem('studArrowLeft_records');

        if (get_rec === '1') {
            $('#arrowLeftButton_records').addClass('bg-img-2')
            $('#recordsTab').addClass('h-13em')
        } else {
            $('#arrowLeftButton_records').addClass('bg-img-1')
            $('#recordsTab').removeClass('h-13em')
        }

        let get_main = localStorage.getItem('studArrowLeft_maintenance');

        if (get_main === '1') {
            $('#arrowLeftButton_maintenance').addClass('bg-img-2')
            $('#maintenanceTab').addClass('h-8-5em')
        } else {
            $('#arrowLeftButton_maintenance').addClass('bg-img-1')
            $('#maintenanceTab').removeClass('h-8-5em')
        }

        let get_masterlist = localStorage.getItem('studArrowLeft_masterlist');
        if (get_masterlist === '1') {
            $('#arrowLeftButton_masterlist').addClass('bg-img-2')
            $('#masterlistTab').addClass('h-8-5em')
        } else {
            $('#arrowLeftButton_masterlist').addClass('bg-img-1')
            $('#masterlistTab').removeClass('h-8-5em')
        }

        let get_studentRecord = localStorage.getItem('studArrowLeft_studentRecord');
        if (get_studentRecord === '1') {
            $('#arrowLeftButton_studentRecord').addClass('bg-img-2')
            $('#studentRecordTab').addClass('h-8-5em')
        } else {
            $('#arrowLeftButton_studentRecord').addClass('bg-img-1')
            $('#studentRecordTab').removeClass('h-8-5em')
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

    viewUserTabs();
    loadStudArrowLeft();

</script>