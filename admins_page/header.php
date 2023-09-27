<?php global $var, $var1; ?>

<?php
global $conn;
include "../../db_conn.php";

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /1-php-grading-system/admins_page/404");
} else {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    $id = validate(isset($_GET['id']));
    $sql = "SELECT * FROM users_info WHERE id=$id";
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
<title>MABES GRADING INQUIRY</title>
<link rel="shortcut icon" href="https://th.bing.com/th/id/OIP.Lsswy08HBmxSwV6Pt9uZKgHaHa?pid=ImgDet&rs=1"/>
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
                    src="https://th.bing.com/th/id/OIP.Lsswy08HBmxSwV6Pt9uZKgHaHa?pid=ImgDet&rs=1" alt=""
                    class="w-38px m-3-6px"/></a>
        <p class="m-0 b-right-2px-white pad-left-6px pad-right-6px t-color-green"> MABES </p>
        <p class=" m-0 pad-left-6px">Grading Inquiry</p>
        <div class="c-hand p-absolute r-0 d-flex-center w-2-5em h-60px f-weight-100 bg-hover-blue b-top-right-radius-10"
             onclick="tops()">x
        </div>
    </div>

    <div id="sideTab" class="pad-1em t-color-white f-weight-bold">
        <div class=" h-4em m-t-3em  d-flex-center ">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('dashboard')" <?php if ($var === "dashboard") { ?> style="background: #bababa;"  <?php } ?> >
                Dashboard <?= $var1 ?>
            </div>
            <div class="d-flex-end w-4em "></div>
        </div>
        <div class=" h-4em d-flex-center m-t-5px">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('add_user')" <?php if ($var === "add_user" || $var === "add_student" || $var === "add_new_user") { ?> style="background: #bababa;"  <?php } ?>>
                Add User
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft','userTab')"></div>
            </div>
        </div>

        <div id="userTab" class="ov-hidden transition-0-5s " style="height: 0">
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
                     onclick="selectTab('add_new_user')" <?php if ($var === "add_new_user") { ?> style="background: #bababa;"  <?php } ?>>
                    Add New User
                </div>
            </div>
        </div>




        <div class=" h-4em d-flex-center m-t-5px">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('add_records')" <?php if ($var === "add_records" || $var === "promote_student" || $var === "student_list" || $var === "subject_list") { ?> style="background: #bababa;"  <?php } ?>>
                Records
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_records" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_records','recordsTab')"></div>
            </div>
        </div>

        <div id="recordsTab" class="ov-hidden transition-0-5s " style="height: 0">
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




        <div class=" h-4em d-flex-center m-t-5px">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('maintenance')" <?php if ($var === "maintenance" || $var === "school_year" || $var === "grade_list") { ?> style="background: #bababa;"  <?php } ?>>
                Maintenance
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_maintenance" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_maintenance','maintenanceTab')"></div>
            </div>
        </div>

        <div id="maintenanceTab" class="ov-hidden transition-0-5s " style="height: 0">
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



<!--        <div class=" h-4em d-flex-center m-t-5px">-->
<!--            <div class="d-flex-center h-100p w-80p  b-radius-2em bg-hover-gray-dark c-hand"-->
<!--                 onclick="selectTab('teacher_list')" --><?php //if ($var === "teacher_list") { ?><!-- style="background: #bababa;"  --><?php //} ?>
<!--                Teacher List-->
<!--            </div>-->
<!--            <div class="d-flex-end w-4em"></div>-->
<!--        </div>-->

        <div class=" h-4em  d-flex-center m-t-5px">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('create_announcement')" <?php if ($var === "create_announcement") { ?> style="background: #bababa;"  <?php } ?>>
                Create Announcement
            </div>
            <div class="d-flex-end w-4em m-t-5px"></div>
        </div>
        <div class=" h-5em  d-flex-center m-t-5px">
            <div class="d-flex-center h-100p w-80p b-radius-2em bg-hover-gray-dark c-hand"
                 onclick="selectTab('trash')" <?php if ($var === "trash") { ?> style="background: #bababa;"  <?php } ?>>
                Trash
            </div>
            <div class="d-flex-end w-4em"></div>
        </div>


    </div>
</div>

<div id="settings-details" class="p-absolute j-content-center z-i-999910" style="  height: 19em;
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
            Hello, <label for="" class="m-b-0 m-l-3px">   <?= $rows['username'] ?></label>
        </div>
        <img src="https://cdn4.iconfinder.com/data/icons/man-user-human-person-avatar-business-profile/100/18A-1User-512.png"
             alt="" class="w-32px">
        <img id="settings" src="https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-arrow-down-b-512.png"
             class="w-18px m-r-13px c-hand transition-0-5s" alt="" onclick="settings()"/>

    </div>

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
        }else if (tab === 'maintenance') {
            saveKeyOnLocalStorage('#arrowLeftButton_maintenance', 'studArrowLeft_maintenance', 'maintenanceTab');
        } else {
            window.location.href = "/1-php-grading-system/admins_page/" + tab + "/?id=" + <?= $rows['id'] ?>;
        }
    }

    function saveKeyOnLocalStorage(e, keyName, tabName) {

        if (tabName === 'userTab' || tabName === 'recordsTab' || tabName === 'maintenanceTab') {
            $('#' + tabName).toggleClass(
                tabName === 'userTab' ? 'h-8-5em'
                    : tabName === 'recordsTab' ? 'h-12-5em'
                        : tabName === 'maintenanceTab' ? 'h-8-5em'
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
            $('#userTab').addClass('h-8-5em')
        } else {
            $('#arrowLeftButton').addClass('bg-img-1')
            $('#userTab').removeClass('h-8-5em')
        }

        let get_rec = localStorage.getItem('studArrowLeft_records');

        if (get_rec === '1') {
            $('#arrowLeftButton_records').addClass('bg-img-2')
            $('#recordsTab').addClass('h-12-5em')
        } else {
            $('#arrowLeftButton_records').addClass('bg-img-1')
            $('#recordsTab').removeClass('h-12-5em')
        }

        let get_main = localStorage.getItem('studArrowLeft_maintenance');

        if (get_main === '1') {
            $('#arrowLeftButton_maintenance').addClass('bg-img-2')
            $('#maintenanceTab').addClass('h-8-5em')
        } else {
            $('#arrowLeftButton_maintenance').addClass('bg-img-1')
            $('#maintenanceTab').removeClass('h-8-5em')
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

        } else {

        }
    }

    loadStudArrowLeft();

</script>