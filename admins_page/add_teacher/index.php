<?php global $mysqli, $rows;
$var = "add_teacher";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['lrn'])) {
    $lrn = $_POST['lrn'];

    $id = $_GET['id'];
    $sqlSelectRemovedBy = "select CONCAT(first_name, ' ', last_name) as 'name' from users_info where id = '$id'";
    $resultSelectRemovedBy = mysqli_query($conn, $sqlSelectRemovedBy);
    $rowsSelectRemovedBy = mysqli_fetch_assoc($resultSelectRemovedBy);
    $removedBy = '';
    foreach ($rowsSelectRemovedBy as $key => $value) {
        $removedBy .= $value;
    }

    $sqlStudentInfo = "select * from teachers_info where lrn = '$lrn'";
    $resultStudentInfo = mysqli_query($conn, $sqlStudentInfo);
    $rowsStudentInfo = mysqli_fetch_assoc($resultStudentInfo);
    $name = $rowsStudentInfo['first_name'] . ' ' . $rowsStudentInfo['last_name'];
    $historyData = '';
    $historyData .= ' <h3> Teachers Info</h3>';
    foreach ($rowsStudentInfo as $key => $value) {
        $historyData .= $key . ': ' . $value . ' <br/>';
    }

    $sqlStudentSubjectInfo = "select * from teachers_subject_info where teachers_lrn = '$lrn'";
    $resultStudentSubjectInfo = mysqli_query($conn, $sqlStudentSubjectInfo);
    $rowsStudentSubjectInfo = mysqli_fetch_assoc($resultStudentSubjectInfo);
    $historyData .= ' <h3> Teachers Subject Info</h3>';
    foreach ($rowsStudentSubjectInfo as $key => $value) {
        $historyData .= $key . ': ' . $value . ' <br/>';
    }

    $sqlUserInfo = "select * from users_info where user_lrn = '$lrn'";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);
    $rowsUserInfo = mysqli_fetch_assoc($resultUserInfo);
    $historyData .= ' <h3> Users Info</h3>';
    foreach ($rowsUserInfo as $key => $value) {
        $historyData .= $key . ': ' . $value . ' <br/>';
    }

    $sqlInsertTrash = "insert into trash_info (user_lrn,name,history,removed_date,removed_by,position) VALUES ('$lrn', '$name','$historyData', now(),'$removedBy','teacher')";
    $resultInsertTrash = mysqli_query($conn, $sqlInsertTrash);

    $sql = "delete from teachers_info where lrn = '$lrn'";
    $result = mysqli_query($conn, $sql);

    $sqlDelete = "delete from teachers_subject_info where teachers_lrn = '$lrn'";
    $resultDelete = mysqli_query($conn, $sqlDelete);

    $sqlDeleteUser = "delete from users_info where user_lrn = '$lrn'";
    $resultDeleteUser = mysqli_query($conn, $sqlDeleteUser);

    if ($resultDelete) {
        echo '<script>';
        echo '
              history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['add-new-teacher'])) {
    $lrn = $_POST['lrn-add'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $civilStatus = $_POST['civilStatus'];
    $emailAddress = $_POST['emailAddress'];
    $grade = $_POST['grade'];
    $section = $_POST['section'];
    $contactNumber = $_POST['contactNumber'];

    $selectTeacher = "select * from teachers_info where lrn = '$lrn'";
    $resultSelectTeacher = mysqli_query($conn, $selectTeacher);
    $rowsSelectTeacher = mysqli_fetch_assoc($resultSelectTeacher);
    if ($rowsSelectTeacher) {
        echo '<script>';
        echo '
             alert("Teacher Already Exist");
              history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&lrnExist=' . $lrn . '");
                window.location.reload();
            ';
        echo '</script>';
        return;
    }


    $sql = "insert into teachers_info (lrn, first_name, last_name,address,gender,civil_status,email_address,grade,section,date_added,contact_number) values ('$lrn','$firstName','$lastName','$address','$gender','$civilStatus','$emailAddress','$grade','$section',now(),'$contactNumber')";
    $result = mysqli_query($conn, $sql);

    // Hash the admin password
    $hashed_admin_password = password_hash($lastName, PASSWORD_DEFAULT);

    $sqlUserInfo = "insert into users_info (last_name,first_name,username,password,user_type,user_lrn) VALUES ('$lastName','$firstName','$lrn','$hashed_admin_password','teacher','$lrn')";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

    if ($result) {
        echo '<script>';
        echo '
              history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&added_successfully=' . $lrn . '");
              window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['edit-teacher'])) {
    $lrn = $_POST['lrnUpdate'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $civilStatus = $_POST['civilStatus'];
    $emailAddress = $_POST['emailAddress'];
    $grade = $_POST['grade'];
    $section = $_POST['section'];
    $contactNumber = $_POST['contactNumber'];
    $sqlUpdateTeacher = "update teachers_info set first_name='$firstName', last_name='$lastName', address='$address', gender='$gender', civil_status='$civilStatus', email_address='$emailAddress', grade='$grade', section='$section',contact_number='$contactNumber' where lrn='$lrn'";
    $resultUpdateTeacher = mysqli_query($conn, $sqlUpdateTeacher);
    if ($resultUpdateTeacher) {
        echo '<script>';
        echo '
              history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&added_successfully=' . $lrn . '");
                window.location.reload();
            ';
        echo '</script>';
    }

}
function debug_to_console($data, $context = 'Debug in Console')
{

    // Buffering to solve problems frameworks, like header() in this and not a solid return.
    ob_start();

    $output = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output = sprintf('<script>%s</script>', $output);

    echo $output;
}

if (isset($_POST['add-new-subject'])) {
    $lrn = $_GET['lrn'];
    $name = $_GET['name'] . '"s';
    $subject = $_POST['subject'];
    $room = $_POST['room'];
    $grade_level = $_POST['grade-level'];
    $time_in = $_POST['time-in'];
    $time_out = $_POST['time-out'];
    $schedule_day = $_POST['schedule-day'];

    $sql = "insert into teachers_subject_info (subject,room,schedule_time_in, schedule_time_out,schedule_day,teachers_lrn) values ('$subject','$room','$time_in','$time_out','$schedule_day','$lrn')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<script>';
        echo '
              history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&lrn=' . $lrn . '&&name=' . $name . '");
                window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['teacherSubjectID'])) {

    $id = $_POST['teacherSubjectID'];
    $sqlDelete = "delete from teachers_subject_info where id = '$id'";
    $resultDelete = mysqli_query($conn, $sqlDelete);

}

if (isset($_POST['edit-subject'])) {
    $subject = $_POST['edit-subject-name'];
    $room = $_POST['edit-room'];
    $grade_level = $_POST['edit-grade-level'];
    $time_in = $_POST['edit-time-in'];
    $time_out = $_POST['edit-time-out'];
    $schedule_day = $_POST['edit-schedule-day'];
    $id = $_POST['edit-id'];
    $sqlUpdate = "update teachers_subject_info set subject='$subject', room='$room', schedule_time_in='$time_in', schedule_time_out='$time_out', schedule_day='$schedule_day' where id='$id'";
    $resultUpdate = mysqli_query($conn, $sqlUpdate);
}

if (isset($_POST['studentID'])) {
    $student_lrn = $_POST['studentID'];
    $teacher_lrn = $_GET['teachers_lrn'];
    $grade = $_GET['searchGrade'];

    $sqlSelect = "select * from teachers_subject_info where teachers_lrn='$teacher_lrn' ";
    $resultSelect = mysqli_query($conn, $sqlSelect);
    $row = mysqli_fetch_assoc($resultSelect);
    foreach ($resultSelect as $key => $value) {
        $value = $value['subject'];
        $sql = "insert into students_grade_info (teacher_lrn, Student_lrn, subject, grade_level) values ('$teacher_lrn','$student_lrn', '$value', '$grade')";
        $result = mysqli_query($conn, $sql);
    }
}

if (isset($_POST['teacherStudentID'])) {
    $teacherStudentID = $_POST['teacherStudentID'];
    $sql = "delete from students_grade_info where student_lrn='$teacherStudentID[0]' and grade_level='$teacherStudentID[1]'";
    $result = mysqli_query($conn, $sql);
}
?>

<div class="d-flex-end p-absolute w-100p bottom-0 t-60px">
    <div id="content"
         class="bg-off-white w-79-8p h-100p b-r-7px contents one_page ">

        <style>
            .table-1 tbody tr th, .table-1 tbody tr td {
                border-bottom: 0 !important;
                border-top: 0 !important;
            }

            .table-1 thead tr th, .table-1 thead tr td {
                border-top: 0 !important;
                border-bottom: 3px solid #ddd;
            }

            tr:nth-child(even) {
                background-color: #fbe4d5;
            }

            .table-1 thead {
                background-color: #ed7d31;
                color: white;
            }
            #search_name {
  width: 20%;
  padding: 10px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
  background-color: #f8f8f8;
  outline: none;
}

#search_name::placeholder {
  color: #999;
}

#search_name:focus {
  border-color: #007bff;
  background-color: #fff;
}
        </style>

        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px pad-1em">

                <div class="pad-1em  f-weight-bold d-flex">
                    <h3>
                        Teacher List
                    </h3>
                    <div class="r-50px p-absolute t-54px d-flex-center">
                        <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50"
                             onclick="showModal('add-new-teacher', 'New Teachers','','small')">
                        &nbsp; &nbsp;
                        <svg class="c-hand" onclick="deleteTeachers('teachers-list')" height="43" id="svg2"
                             version="1.1" viewBox="0 0 99.999995 99.999995" width="50"
                             xmlns="http://www.w3.org/2000/svg"
                        >
                            <defs id="defs4">
                                <filter id="filter4510" style="color-interpolation-filters:sRGB">
                                    <feFlood flood-color="rgb(0,0,0)" flood-opacity="0.470588" id="feFlood4512"
                                             result="flood"/>
                                    <feComposite id="feComposite4514" in="flood" in2="SourceGraphic" operator="in"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur4516" in="composite1" result="blur"
                                                    stdDeviation="5"/>
                                    <feOffset dx="0" dy="4.7" id="feOffset4518" result="offset"/>
                                    <feComposite id="feComposite4520" in="SourceGraphic" in2="offset" operator="over"
                                                 result="composite2"/>
                                </filter>
                                <filter id="filter5064" style="color-interpolation-filters:sRGB">
                                    <feFlood flood-color="rgb(206,242,245)" flood-opacity="0.835294" id="feFlood5066"
                                             result="flood"/>
                                    <feComposite id="feComposite5068" in="flood" in2="SourceGraphic" operator="out"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur5070" in="composite1" result="blur"
                                                    stdDeviation="5.9"/>
                                    <feOffset dx="0" dy="-8.1" id="feOffset5072" result="offset"/>
                                    <feComposite id="feComposite5074" in="offset" in2="SourceGraphic" operator="atop"
                                                 result="composite2"/>
                                </filter>
                                <filter id="filter5364" style="color-interpolation-filters:sRGB">
                                    <feFlood flood-color="rgb(0,0,0)" flood-opacity="0.835294" id="feFlood5366"
                                             result="flood"/>
                                    <feComposite id="feComposite5368" in="flood" in2="SourceGraphic" operator="in"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur5370" in="composite1" result="blur"
                                                    stdDeviation="5"/>
                                    <feOffset dx="0" dy="4.2" id="feOffset5372" result="offset"/>
                                    <feComposite id="feComposite5374" in="SourceGraphic" in2="offset" operator="over"
                                                 result="fbSourceGraphic"/>
                                    <feColorMatrix id="feColorMatrix5592" in="fbSourceGraphic"
                                                   result="fbSourceGraphicAlpha"
                                                   values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/>
                                    <feFlood flood-color="rgb(254,255,189)" flood-opacity="1" id="feFlood5594"
                                             in="fbSourceGraphic" result="flood"/>
                                    <feComposite id="feComposite5596" in="flood" in2="fbSourceGraphic" operator="out"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur5598" in="composite1" result="blur"
                                                    stdDeviation="7.6"/>
                                    <feOffset dx="0" dy="-8.1" id="feOffset5600" result="offset"/>
                                    <feComposite id="feComposite5602" in="offset" in2="fbSourceGraphic" operator="atop"
                                                 result="composite2"/>
                                </filter>
                                <filter id="filter4400" style="color-interpolation-filters:sRGB">
                                    <feFlood flood-color="rgb(0,0,0)" flood-opacity="0.470588" id="feFlood4402"
                                             result="flood"/>
                                    <feComposite id="feComposite4404" in="flood" in2="SourceGraphic" operator="in"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur4406" in="composite1" result="blur"
                                                    stdDeviation="5"/>
                                    <feOffset dx="0" dy="5" id="feOffset4408" result="offset"/>
                                    <feComposite id="feComposite4410" in="SourceGraphic" in2="offset" operator="over"
                                                 result="fbSourceGraphic"/>
                                    <feColorMatrix id="feColorMatrix4640" in="fbSourceGraphic"
                                                   result="fbSourceGraphicAlpha"
                                                   values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/>
                                    <feFlood flood-color="rgb(255,253,180)" flood-opacity="1" id="feFlood4642"
                                             in="fbSourceGraphic" result="flood"/>
                                    <feComposite id="feComposite4644" in="flood" in2="fbSourceGraphic" operator="out"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur4646" in="composite1" result="blur"
                                                    stdDeviation="5"/>
                                    <feOffset dx="0" dy="-5" id="feOffset4648" result="offset"/>
                                    <feComposite id="feComposite4650" in="offset" in2="fbSourceGraphic" operator="atop"
                                                 result="composite2"/>
                                </filter>
                                <filter id="filter4678" style="color-interpolation-filters:sRGB">
                                    <feFlood flood-color="rgb(255,253,180)" flood-opacity="1" id="feFlood4680"
                                             result="flood"/>
                                    <feComposite id="feComposite4682" in="flood" in2="SourceGraphic" operator="out"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur4684" in="composite1" result="blur"
                                                    stdDeviation="5"/>
                                    <feOffset dx="0" dy="-7" id="feOffset4686" result="offset"/>
                                    <feComposite id="feComposite4688" in="offset" in2="SourceGraphic" operator="atop"
                                                 result="composite2"/>
                                </filter>
                                <filter id="filter5045" style="color-interpolation-filters:sRGB">
                                    <feFlood flood-color="rgb(255,250,175)" flood-opacity="1" id="feFlood5047"
                                             result="flood"/>
                                    <feComposite id="feComposite5049" in="flood" in2="SourceGraphic" operator="out"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur5051" in="composite1" result="blur"
                                                    stdDeviation="5"/>
                                    <feOffset dx="0" dy="-6" id="feOffset5053" result="offset"/>
                                    <feComposite id="feComposite5055" in="offset" in2="SourceGraphic" operator="atop"
                                                 result="composite2"/>
                                </filter>
                                <filter id="filter4607" style="color-interpolation-filters:sRGB;">
                                    <feFlood flood-color="rgb(255,247,180)" flood-opacity="1" id="feFlood4609"
                                             result="flood"/>
                                    <feComposite id="feComposite4611" in="flood" in2="SourceGraphic" operator="out"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur4613" in="composite1" result="blur"
                                                    stdDeviation="5"/>
                                    <feOffset dx="0" dy="-6" id="feOffset4615" result="offset"/>
                                    <feComposite id="feComposite4617" in="offset" in2="SourceGraphic" operator="atop"
                                                 result="composite2"/>
                                </filter>
                                <filter id="filter4507" style="color-interpolation-filters:sRGB;">
                                    <feFlood flood-color="rgb(255,249,199)" flood-opacity="1" id="feFlood4509"
                                             result="flood"/>
                                    <feComposite id="feComposite4511" in="flood" in2="SourceGraphic" operator="out"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur4513" in="composite1" result="blur"
                                                    stdDeviation="3"/>
                                    <feOffset dx="0" dy="-2.60417" id="feOffset4515" result="offset"/>
                                    <feComposite id="feComposite4517" in="offset" in2="SourceGraphic" operator="atop"
                                                 result="fbSourceGraphic"/>
                                    <feColorMatrix id="feColorMatrix4687" in="fbSourceGraphic"
                                                   result="fbSourceGraphicAlpha"
                                                   values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/>
                                    <feFlood flood-color="rgb(255,244,153)" flood-opacity="1" id="feFlood4689"
                                             in="fbSourceGraphic" result="flood"/>
                                    <feComposite id="feComposite4691" in="flood" in2="fbSourceGraphic" operator="out"
                                                 result="composite1"/>
                                    <feGaussianBlur id="feGaussianBlur4693" in="composite1" result="blur"
                                                    stdDeviation="3.4"/>
                                    <feOffset dx="0" dy="-3.9" id="feOffset4695" result="offset"/>
                                    <feComposite id="feComposite4697" in="offset" in2="fbSourceGraphic" operator="atop"
                                                 result="composite2"/>
                                </filter>
                            </defs>
                            <g id="layer3" style="display:inline" transform="translate(0,-99.999988)">
                                <g id="g4283">
                                    <path d="m 64.41211,130.39258 a 2.5002498,2.5002498 0 0 0 -2.472657,2.52539 l -0.175781,44.90039 a 2.5002498,2.5002498 0 1 0 5,0.0195 l 0.175781,-44.90039 a 2.5002498,2.5002498 0 0 0 -2.527343,-2.54492 z m -14.351573,0 a 2.5002498,2.5002498 0 0 0 -2.472656,2.52539 L 47.4121,177.81836 a 2.5002498,2.5002498 0 1 0 5,0.0195 l 0.175781,-44.90039 a 2.5002498,2.5002498 0 0 0 -2.527344,-2.54492 z m -13.876943,0 a 2.5002498,2.5002498 0 0 0 -2.472656,2.52539 l -0.175781,44.90039 a 2.5002498,2.5002498 0 1 0 5,0.0195 l 0.175781,-44.90039 a 2.5002498,2.5002498 0 0 0 -2.527344,-2.54492 z M 20,99.999988 c -11.0800091,0 -20,8.919992 -20,20.000002 l 0,60 c 0,11.08 8.9199909,20 20,20 l 60,0 c 11.080007,0 20,-8.92 20,-20 l 0,-60 C 100,108.91998 91.080007,99.999988 80,99.999988 l -60,0 z m 23.490234,14.923832 13.019532,0 c 0.873657,0 1.578125,0.70446 1.578125,1.57812 l 0,3.03125 16.99414,0 c 1.028311,0 1.855469,0.82716 1.855469,1.85547 l 0,2.91406 c 0,1.02831 -0.827158,1.85547 -1.855469,1.85547 l -50.164062,0 c -1.02831,0 -1.855469,-0.82716 -1.855469,-1.85547 l 0,-2.91406 c 0,-1.02831 0.827159,-1.85547 1.855469,-1.85547 l 16.99414,0 0,-3.03125 c 0,-0.87366 0.704468,-1.57812 1.578125,-1.57812 z m -17.001953,13.30859 47.023438,0 0,48.88867 c 0,4.40704 -3.548036,7.95508 -7.955078,7.95508 l -31.113282,0 c -4.407042,0 -7.955078,-3.54804 -7.955078,-7.95508 l 0,-48.88867 z"
                                          id="path4218"
                                          style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;direction:ltr;block-progression:tb;writing-mode:lr-tb;baseline-shift:baseline;text-anchor:start;white-space:normal;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:4.99999952;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>
                <br/>

                <?php
                $search_lrn = isset($_GET['search_lrn']) ? $_GET['search_lrn'] : '';

                $sql = "SELECT ti.grade, ti.contact_number, ti.section, ti.id as id, ti.lrn,ti.first_name, ti.last_name, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject SEPARATOR ', ') as subject
                FROM `teachers_subject_info` tsi
                right join teachers_info ti on ti.lrn = tsi.teachers_lrn 
                WHERE ti.lrn LIKE '%$search_lrn%'
                GROUP BY ti.lrn order by ti.id desc";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                $lrn = 'T' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "teachers".
                $total_pages = $mysqli->query("SELECT ti.grade, ti.contact_number, ti.section, ti.id,ti.lrn,ti.first_name, ti.last_name, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject SEPARATOR ', ') as subject
                FROM `teachers_subject_info` tsi
                right join teachers_info ti on ti.lrn = tsi.teachers_lrn
                 WHERE ti.lrn LIKE '%$search_lrn%'
                GROUP BY ti.lrn order by ti.id desc")->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare("SELECT ti.grade, ti.contact_number, ti.section, ti.id,ti.lrn,ti.first_name, ti.last_name, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject SEPARATOR ', ') as subject
                FROM `teachers_subject_info` tsi
                right join teachers_info ti on ti.lrn = tsi.teachers_lrn 
                    WHERE ti.lrn LIKE '%$search_lrn%'
                GROUP BY ti.lrn ORDER BY ti.id LIMIT ?,?")) {
                    // Calculate the page to get the results we need from our table.
                    $calc_page = ($page - 1) * $num_results_on_page;
                    $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                    $stmt->execute();
                    // Get the results...
                    $result = $stmt->get_result();
                    ?>
                    <input placeholder="search lrn" id="search_name" type="text" class="search_lrn m-b-5px"
                           onchange="search('lrn')"/>
                    <table class="table table-1  b-shadow-dark ">
                        <thead>
                        <tr>
                            <th class="t-align-center"><label for="teachers-list-cb"
                                                              class="d-flex-center"></label><input
                                        id="teachers-list-cb" type="checkbox"
                                        onclick="checkCBteachers('teachers-list', 'teachers-list-cb')"
                                        class="sc-1-3 c-hand"/></th>
                            <th>No</th>
                            <th>LRN</th>
                            <th>FullName</th>
                            <th>Address</th>
                            <th>Gender</th>
                            <th>Civil Status</th>
                            <th>Email Address</th>
                            <th>Grade</th>
                            <th>Section</th>
                            <th>Subject</th>
                            <th class="t-align-center">Edit</th>
                            <th class="t-align-center">View Student</th>
                        </tr>
                        </thead>
                        <tbody id="teachers-list">
                        <?php
                        $i = 0;
                        while ($row = $result->fetch_assoc()):
                            $i++;
                            ?>
                            <tr>
                                <td class="d-flex-center"><label>
                                        <input type="checkbox" class="sc-1-3 c-hand check" id="<?= $row['lrn'] ?>"/>
                                    </label></td>
                                <th scope="row"><?= $i ?> </th>
                                <td><?= $row['lrn'] ?></td>
                                <td><?= $row['last_name'] ?> <?= $row['first_name'] ?></td>
                                <td><?= $row['address'] ?></td>
                                <td><?= $row['gender'] ?></td>
                                <td><?= $row['civil_status'] ?></td>
                                <td><?= $row['email_address'] ?></td>
                                <td><?= $row['grade'] ?></td>
                                <td><?= $row['section'] ?></td>
                                <td><?= $row['subject'] ?></td>
                                <td>
                                    <label for="" class="t-color-red c-hand f-weight-bold d-flex-center"
                                           onclick="editTeacher('<?= $row['lrn'] ?>','<?= $row['last_name'] ?>','<?= $row['first_name'] ?>','<?= $row['address'] ?>','<?= $row['gender'] ?>','<?= $row['civil_status'] ?>','<?= $row['subject'] ?>','<?= $row['email_address'] ?>','<?= $row['grade'] ?>','<?= $row['section'] ?>','<?= $row['contact_number'] ?>')"
                                    >
                                        <svg width="40" height="40" viewBox="0 0 48 48"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <defs>
                                                <style>.cls-1 {
                                                        fill: url(#linear-gradient);
                                                    }

                                                    .cls-2 {
                                                        fill: url(#linear-gradient-2);
                                                    }

                                                    .cls-3 {
                                                        fill: url(#linear-gradient-3);
                                                    }

                                                    .cls-4 {
                                                        fill: #666;
                                                    }

                                                    .cls-5 {
                                                        fill: url(#linear-gradient-4);
                                                    }

                                                    .cls-6 {
                                                        fill: url(#linear-gradient-5);
                                                    }

                                                    .cls-7 {
                                                        fill: url(#linear-gradient-6);
                                                    }

                                                    .cls-8 {
                                                        fill: url(#linear-gradient-7);
                                                    }

                                                    .cls-9 {
                                                        fill: url(#linear-gradient-8);
                                                    }

                                                    .cls-10 {
                                                        fill: url(#linear-gradient-9);
                                                    }

                                                    .cls-11 {
                                                        fill: url(#linear-gradient-10);
                                                    }

                                                    .cls-12 {
                                                        fill: url(#linear-gradient-11);
                                                    }

                                                    .cls-13 {
                                                        fill: url(#linear-gradient-12);
                                                    }

                                                    .cls-14 {
                                                        fill: url(#linear-gradient-13);
                                                    }

                                                    .cls-15 {
                                                        fill: url(#linear-gradient-14);
                                                    }</style>
                                                <linearGradient gradientUnits="userSpaceOnUse" id="linear-gradient"
                                                                x1="22.98" x2="26.48" y1="23.9" y2="28.27">
                                                    <stop offset="0.04" stop-color="#fbb480"/>
                                                    <stop offset="1" stop-color="#c27c4a"/>
                                                </linearGradient>
                                                <linearGradient id="linear-gradient-2" x1="7.85" x2="11.63"
                                                                xlink:href="#linear-gradient" y1="35.07" y2="39.53"/>
                                                <linearGradient id="linear-gradient-3" x1="7.26" x2="12.14"
                                                                xlink:href="#linear-gradient" y1="33.38" y2="38.26"/>
                                                <linearGradient id="linear-gradient-4" x1="35.06" x2="41.75"
                                                                xlink:href="#linear-gradient" y1="9.61" y2="16.3"/>
                                                <linearGradient id="linear-gradient-5" x1="32.45" x2="41.29"
                                                                xlink:href="#linear-gradient" y1="6.23" y2="17.91"/>
                                                <linearGradient gradientTransform="translate(21.95 -5.88) rotate(44.99)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-6"
                                                                x1="17.07" x2="22.48" y1="22.56" y2="27.98">
                                                    <stop offset="0.01" stop-color="#ffdc2e"/>
                                                    <stop offset="1" stop-color="#f79139"/>
                                                </linearGradient>
                                                <linearGradient gradientTransform="translate(28.21 -8.47) rotate(45)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-7"
                                                                x1="22.57" x2="26.35" y1="28.06" y2="31.84">
                                                    <stop offset="0.01" stop-color="#f46000"/>
                                                    <stop offset="1" stop-color="#de722c"/>
                                                </linearGradient>
                                                <linearGradient gradientTransform="translate(25.08 -7.17) rotate(45)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-8"
                                                                x1="20.21" x2="24.85" y1="25.7" y2="30.35">
                                                    <stop offset="0.01" stop-color="#f99d46"/>
                                                    <stop offset="1" stop-color="#f46000"/>
                                                </linearGradient>
                                                <linearGradient
                                                        gradientTransform="translate(23.66 -19.41) rotate(44.98)"
                                                        gradientUnits="userSpaceOnUse" id="linear-gradient-9" x1="34.09"
                                                        x2="36.35" y1="17.69" y2="19.95">
                                                    <stop offset="0.01" stop-color="#a1a1a1"/>
                                                    <stop offset="1" stop-color="#828282"/>
                                                </linearGradient>
                                                <linearGradient gradientTransform="translate(17.4 -16.81) rotate(44.98)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-10"
                                                                x1="27.79" x2="30.61" y1="11.39" y2="14.22">
                                                    <stop offset="0.01" stop-color="#fafafa"/>
                                                    <stop offset="1" stop-color="#dedede"/>
                                                </linearGradient>
                                                <linearGradient gradientTransform="translate(20.55 -18.12) rotate(45)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-11"
                                                                x1="30.43" x2="34.61" y1="14.03" y2="18.21">
                                                    <stop offset="0.01" stop-color="#d4d4d4"/>
                                                    <stop offset="1" stop-color="#a6a6a6"/>
                                                </linearGradient>
                                                <linearGradient
                                                        gradientTransform="translate(23.67 -19.41) rotate(44.99)"
                                                        gradientUnits="userSpaceOnUse" id="linear-gradient-12" x1="33.9"
                                                        x2="36.13" y1="17.5" y2="19.73">
                                                    <stop offset="0.01" stop-color="#b2b2b2"/>
                                                    <stop offset="1" stop-color="#939393"/>
                                                </linearGradient>
                                                <linearGradient
                                                        gradientTransform="translate(17.41 -16.82) rotate(44.99)"
                                                        gradientUnits="userSpaceOnUse" id="linear-gradient-13"
                                                        x1="28.07" x2="30.21" y1="11.67" y2="13.81">
                                                    <stop offset="0.01" stop-color="#fafafa"/>
                                                    <stop offset="1" stop-color="#efefef"/>
                                                </linearGradient>
                                                <linearGradient gradientTransform="translate(20.55 -18.12) rotate(45)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-14"
                                                                x1="30.39" x2="34.73" y1="14" y2="18.34">
                                                    <stop offset="0.01" stop-color="#e5e5e5"/>
                                                    <stop offset="1" stop-color="#b7b7b7"/>
                                                </linearGradient>
                                            </defs>
                                            <title/>
                                            <g id="icons">
                                                <g data-name="Layer 3" id="Layer_3">
                                                    <path class="cls-1"
                                                          d="M41.43,11.27,36.61,6.46a2.8,2.8,0,0,0-4,0L8,31.06,6.27,38.73l3.06,3.06,7.49-1.94,24.61-24.6A2.83,2.83,0,0,0,41.43,11.27Z"/>
                                                    <polygon class="cls-2"
                                                             points="7.24 39.7 10.56 33.59 14.29 37.32 8.19 40.65 7.24 39.7"/>
                                                    <polygon class="cls-3"
                                                             points="9.33 41.78 16.82 39.85 18.45 38.23 14.29 37.32 8.19 40.65 9.33 41.78"/>
                                                    <path class="cls-4"
                                                          d="M7.33,42.3l2-.51L6.27,38.73s-.21.91-.46,2S6.23,42.58,7.33,42.3Z"/>
                                                    <path class="cls-5"
                                                          d="M41.43,11.27,36.61,6.46a2.8,2.8,0,0,0-4,0L29.92,9.17l2.53,2.53,3.73,3.73L38.71,18l2.72-2.71A2.83,2.83,0,0,0,41.43,11.27Z"/>
                                                    <path class="cls-6"
                                                          d="M41.46,11.87,37.62,8a2.25,2.25,0,0,0-3.17,0l-3.07,3.08,2,2,3,3,2,2L41.46,15A2.24,2.24,0,0,0,41.46,11.87Z"/>
                                                    <rect class="cls-7" height="3.58"
                                                          transform="translate(-11.37 19.67) rotate(-44.99)"
                                                          width="24.8" x="5.67" y="21.77"/>
                                                    <rect class="cls-8" height="3.58"
                                                          transform="translate(-13.96 25.93) rotate(-45)" width="24.8"
                                                          x="11.92" y="28.03"/>
                                                    <rect class="cls-9" height="5.27"
                                                          transform="translate(-12.66 22.8) rotate(-45)" width="24.8"
                                                          x="8.79" y="24.05"/>
                                                    <rect class="cls-10" height="3.58"
                                                          transform="translate(-3.02 30.45) rotate(-44.98)" width="7.63"
                                                          x="31.46" y="17.08"/>
                                                    <rect class="cls-11" height="3.58"
                                                          transform="translate(-0.43 24.2) rotate(-44.98)" width="7.62"
                                                          x="25.2" y="10.83"/>
                                                    <rect class="cls-12" height="5.27"
                                                          transform="translate(-1.72 27.34) rotate(-45)" width="7.62"
                                                          x="28.33" y="13.11"/>
                                                    <rect class="cls-13" height="3.58"
                                                          transform="translate(-3.02 30.46) rotate(-44.99)" width="6.15"
                                                          x="32.19" y="17.08"/>
                                                    <rect class="cls-14" height="3.58"
                                                          transform="translate(-0.43 24.2) rotate(-44.99)" width="6.15"
                                                          x="25.94" y="10.83"/>
                                                    <rect class="cls-15" height="5.27"
                                                          transform="translate(-1.72 27.34) rotate(-45)" width="6.15"
                                                          x="29.06" y="13.11"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </label>
                                </td>
                                <td class="t-align-center">
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewStudentList('<?= $row['grade'] ?>', '<?= $row['section'] ?>','<?= $row['lrn'] ?>')"
                                    >
                                        <svg width="40" height="40" id="Icons" viewBox="0 0 48 48"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <style>.cls-1, .cls-4 {
                                                        fill: #656769;
                                                    }

                                                    .cls-1, .cls-10, .cls-11, .cls-2, .cls-3, .cls-9 {
                                                        stroke: #45413c;
                                                        stroke-linecap: round;
                                                        stroke-linejoin: round;
                                                    }

                                                    .cls-2 {
                                                        fill: none;
                                                    }

                                                    .cls-3 {
                                                        fill: #ffe500;
                                                    }

                                                    .cls-5 {
                                                        fill: #525252;
                                                    }

                                                    .cls-10, .cls-6 {
                                                        fill: #ffcebf;
                                                    }

                                                    .cls-7 {
                                                        fill: #ffb59e;
                                                    }

                                                    .cls-11, .cls-8 {
                                                        fill: #45413c;
                                                    }

                                                    .cls-8 {
                                                        opacity: 0.15;
                                                    }

                                                    .cls-9 {
                                                        fill: #a86c4d;
                                                    }

                                                    .cls-12 {
                                                        fill: #ff6242;
                                                    }

                                                    .cls-13 {
                                                        fill: #ffa694;
                                                    }</style>
                                            </defs>
                                            <title/>
                                            <path class="cls-1"
                                                  d="M23.55,17.33l-15-7.64a.5.5,0,0,1,0-.89l15-7.69a1,1,0,0,1,.92,0l15,7.69a.5.5,0,0,1,0,.89l-15,7.64A1,1,0,0,1,23.55,17.33Z"
                                                  data-name="&lt;Path&gt;" id="_Path_"/>
                                            <line class="cls-2" data-name="&lt;Path&gt;" id="_Path_2" x1="8.31"
                                                  x2="8.31" y1="9.24" y2="17.19"/>
                                            <path class="cls-3"
                                                  d="M9.27,17.18a1,1,0,0,0-1-1.07,1,1,0,0,0-1,1.07l-.19,3.69H9.46Z"
                                                  data-name="&lt;Path&gt;" id="_Path_3"/>
                                            <path class="cls-4"
                                                  d="M31.54,34.09,24,31.22l-7.54,2.87a6.76,6.76,0,0,0-4.35,6.31V45H35.89V40.4A6.76,6.76,0,0,0,31.54,34.09Z"/>
                                            <path class="cls-5"
                                                  d="M31.54,34.09,24,31.22l-7.54,2.87a6.76,6.76,0,0,0-4.35,6.31v3a6.76,6.76,0,0,1,4.35-6.31L24,34.21l7.54,2.87a6.76,6.76,0,0,1,4.35,6.31v-3A6.76,6.76,0,0,0,31.54,34.09Z"/>
                                            <path class="cls-2"
                                                  d="M31.54,34.09,24,31.22l-7.54,2.87a6.76,6.76,0,0,0-4.35,6.31V45H35.89V40.4A6.76,6.76,0,0,0,31.54,34.09Z"/>
                                            <path class="cls-6"
                                                  d="M24,35.11h0S21.46,34,21.46,32.57V29.72A2.54,2.54,0,0,1,24,27.17h0a2.54,2.54,0,0,1,2.54,2.55v2.85C26.54,34,24,35.11,24,35.11Z"
                                                  data-name="&lt;Path&gt;" id="_Path_4"/>
                                            <path class="cls-7"
                                                  d="M24,27.17a2.54,2.54,0,0,0-2.54,2.54v.6a2.54,2.54,0,0,0,5.08,0v-.59A2.54,2.54,0,0,0,24,27.17Z"
                                                  data-name="&lt;Path&gt;" id="_Path_5"/>
                                            <path class="cls-2"
                                                  d="M24,35.11h0S21.46,34,21.46,32.57V29.72A2.54,2.54,0,0,1,24,27.17h0a2.54,2.54,0,0,1,2.54,2.55v2.85C26.54,34,24,35.11,24,35.11Z"
                                                  data-name="&lt;Path&gt;" id="_Path_6"/>
                                            <line class="cls-2" data-name="&lt;Path&gt;" id="_Path_7" x1="13.45"
                                                  x2="17.06" y1="36.35" y2="45"/>
                                            <line class="cls-2" data-name="&lt;Path&gt;" id="_Path_8" x1="34.55"
                                                  x2="30.94" y1="36.35" y2="45"/>
                                            <line class="cls-2" data-name="&lt;Path&gt;" id="_Path_9" x1="20.54"
                                                  x2="21.27" y1="40.48" y2="45"/>
                                            <line class="cls-2" data-name="&lt;Path&gt;" id="_Path_10" x1="27.46"
                                                  x2="26.73" y1="40.48" y2="45"/>
                                            <ellipse class="cls-8" cx="24" cy="45.5" data-name="&lt;Ellipse&gt;"
                                                     id="_Ellipse_" rx="15" ry="1.5"/>
                                            <path class="cls-9"
                                                  d="M24,6.91h0a9.82,9.82,0,0,1,9.82,9.82v5.14a0,0,0,0,1,0,0H14.18a0,0,0,0,1,0,0V16.73A9.82,9.82,0,0,1,24,6.91Z"
                                                  data-name="&lt;Rectangle&gt;" id="_Rectangle_"/>
                                            <path class="cls-10"
                                                  d="M34.87,21.12a1.83,1.83,0,0,0-1.39-1.54l-.59-.16a2.06,2.06,0,0,1-1.5-2V15.38A1.88,1.88,0,0,0,30,13.57a15.34,15.34,0,0,1-6,1.09,15.34,15.34,0,0,1-6-1.09,1.88,1.88,0,0,0-1.4,1.81v2.06a2.06,2.06,0,0,1-1.5,2l-.59.16a1.83,1.83,0,0,0-1.39,1.54,1.81,1.81,0,0,0,1.81,2h.11a9,9,0,0,0,17.9,0h.11A1.81,1.81,0,0,0,34.87,21.12Z"/>
                                            <path class="cls-11"
                                                  d="M18.57,20.91a.77.77,0,1,0,.77-.77A.76.76,0,0,0,18.57,20.91Z"
                                                  data-name="&lt;Path&gt;" id="_Path_11"/>
                                            <path class="cls-11"
                                                  d="M29.43,20.91a.77.77,0,1,1-.77-.77A.76.76,0,0,1,29.43,20.91Z"
                                                  data-name="&lt;Path&gt;" id="_Path_12"/>
                                            <g data-name="&lt;Group&gt;" id="_Group_">
                                                <g data-name="&lt;Group&gt;" id="_Group_2">
                                                    <g data-name="&lt;Group&gt;" id="_Group_3">
                                                        <g data-name="&lt;Group&gt;" id="_Group_4">
                                                            <g data-name="&lt;Group&gt;" id="_Group_5">
                                                                <g data-name="&lt;Group&gt;" id="_Group_6">
                                                                    <g data-name="&lt;Group&gt;" id="_Group_7">
                                                                        <path class="cls-12"
                                                                              d="M21.16,25.6a.44.44,0,0,0-.33.16.42.42,0,0,0-.1.35,3.32,3.32,0,0,0,6.54,0,.42.42,0,0,0-.1-.35.44.44,0,0,0-.33-.16Z"
                                                                              data-name="&lt;Path&gt;" id="_Path_13"/>
                                                                        <path class="cls-13"
                                                                              d="M24,27a4,4,0,0,0-2.52.77,3.36,3.36,0,0,0,5,0A4,4,0,0,0,24,27Z"
                                                                              data-name="&lt;Path&gt;" id="_Path_14"/>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                            <g data-name="&lt;Group&gt;" id="_Group_8">
                                                <path class="cls-2"
                                                      d="M21.16,25.6a.44.44,0,0,0-.33.16.42.42,0,0,0-.1.35,3.32,3.32,0,0,0,6.54,0,.42.42,0,0,0-.1-.35.44.44,0,0,0-.33-.16Z"
                                                      data-name="&lt;Path&gt;" id="_Path_15"/>
                                            </g>
                                            <ellipse class="cls-7" cx="18.06" cy="24.19" data-name="&lt;Ellipse&gt;"
                                                     id="_Ellipse_2" rx="1" ry="0.6"/>
                                            <ellipse class="cls-7" cx="29.94" cy="24.19" data-name="&lt;Ellipse&gt;"
                                                     id="_Ellipse_3" rx="1" ry="0.6"/>
                                            <path class="cls-4"
                                                  d="M14.68,9c0-1.22,3.9-2.21,9.32-2.21s9.32,1,9.32,2.21l1,8.59c0-1.28-4.61-2.32-10.3-2.32s-10.3,1-10.3,2.32Z"
                                                  data-name="&lt;Path&gt;" id="_Path_16"/>
                                            <path class="cls-5"
                                                  d="M24,10.07c4.62,0,8.22.6,9.61,1.43L33.32,9c0-1.22-3.9-2.21-9.32-2.21s-9.32,1-9.32,2.21l-.29,2.53C15.78,10.67,19.38,10.07,24,10.07Z"
                                                  data-name="&lt;Path&gt;" id="_Path_17"/>
                                            <path class="cls-2"
                                                  d="M14.68,9c0-1.22,3.9-2.21,9.32-2.21s9.32,1,9.32,2.21l1,8.59c0-1.28-4.61-2.32-10.3-2.32s-10.3,1-10.3,2.32Z"
                                                  data-name="&lt;Path&gt;" id="_Path_18"/>
                                        </svg>
                                    </label>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>

                    <?php
                    $stmt->close();
                }
                ?>
                Total Results: <?= $total_pages ?>
                <div class="m-2em d-flex-end m-t-n1em">
                    <div class="d-flex-center">
                        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="prev"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


<div id="myModal">
    <script src="../../assets/js/js_header.js"></script>

    <div class="modal-content">
        <div id="top-icon"
             class="top-icon h-100p d-flex-center p-absolute w-3em c-hand f-size-26px w-2em bg-hover-white t-color-white"
             onclick="tops()" style="left: -97px;top: -97px;height: 61px;">☰
        </div>
        <div class="modal-header a-center">
        </div>
        <div class="modal-body">
            <div id="add-new-teacher" class="modal-child d-none">
                <form method="post">
                    <div class="custom-grid-container" tabindex="1">
                        <div class="custom-grid-item ">
                            <input placeholder="<?php echo $lrn ?>" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="lrn-add"
                                   name="lrn-add"
                                   value="<?php echo $lrn ?>">
                            <div class="w-70p m-l-1em">Grade</div>
                            <select name="grade" id="grade"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                    onchange="selectGrade('add')">
                                <option value="0" selected></option>
                                <?php
                                $sql = "select * from grade_info group by grade";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?php echo $row['grade'] ?>">
                                        Grade <?php echo $row['grade'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <div class="w-70p m-l-1em">First Name</div>
                            <input placeholder="First Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="firstName"
                                   name="firstName"
                                   required>
                            <div class="w-70p m-l-1em">Last Name</div>
                            <input placeholder="Last Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="lastName"
                                   name="lastName"
                                   required>
                            <div class="w-70p m-l-1em">Address</div>
                            <input placeholder="Address" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="address"
                                   name="address"
                                   required>
                            <div class="w-70p m-l-1em">Gender</div>
                            <select name="gender" id="gender"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="" disabled selected>Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <div class="w-70p m-l-1em">Civil Status</div>
                            <select name="civilStatus" id="civilStatus"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="" disabled selected>Civil Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                            </select>

                            <div class="w-70p m-l-1em">Email</div>
                            <input placeholder="Email Address" type="email"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="emailAddress"
                                   name="emailAddress"
                                   required>

                            <div class="w-70p m-l-1em">Section</div>
                            <select name="section" id="section"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="0" selected></option>
                                <?php
                                $grade = isset($_GET['searchGradeAdd']) ? $_GET['searchGradeAdd'] : '';
                                $sql = "select * from grade_info where grade = '$grade'";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?php echo $row['section'] ?>">
                                        <?php echo $row['section'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <div class="w-70p m-l-1em">Contact Number</div>
                            <input placeholder="Contact Number" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="contactNumber"
                                   name="contactNumber"
                                   required>

                        </div>

                    </div>
                    <div class="d-flex-end">
                        <button type="submit"
                                class="c-hand btn-success btn"
                                name="add-new-teacher"
                                style="background-color: #ffffff !important; border-color: #ffffff;">
                            <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
                        </button>

                    </div>
                </form>
            </div>
            <div id="edit-teacher" class="modal-child d-none">
                <form method="post">
                    <div class="custom-grid-container" tabindex="1">
                        <div class="custom-grid-item ">
                            <input type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="lrnUpdate"
                                   name="lrnUpdate"
                                   readonly="true"
                            >
                            <div class="w-70p m-l-1em">Grade</div>
                            <select name="grade" id="grade"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                    onchange="selectGrade('edit')">
                                <option value="0" selected></option>
                                <?php
                                $sql = "select * from grade_info group by grade";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?php echo $row['grade'] ?>">
                                        <?php echo $row['grade'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <div class="w-70p m-l-1em">First Name</div>
                            <input placeholder="First Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="firstName"
                                   name="firstName"
                                   required>
                            <div class="w-70p m-l-1em">Last Name</div>
                            <input placeholder="Last Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="lastName"
                                   name="lastName"
                                   required>
                            <div class="w-70p m-l-1em">Address</div>
                            <input placeholder="Address" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="address"
                                   name="address"
                                   required>
                            <div class="w-70p m-l-1em">Gender</div>
                            <select name="gender" id="gender"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="" disabled selected>Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <div class="w-70p m-l-1em">Civil Status</div>
                            <select name="civilStatus" id="civilStatus"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="" disabled selected>Civil Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                            </select>

                            <div class="w-70p m-l-1em">Email</div>
                            <input placeholder="Email Address" type="email"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="emailAddress"
                                   name="emailAddress"
                                   required>
                            <div class="w-70p m-l-1em">Section</div>
                            <select name="section" id="section"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="0" selected></option>
                                <?php
                                $grade = isset($_GET['searchGradeEdit']) ? $_GET['searchGradeEdit'] : '';
                                $sql = "select * from grade_info where grade = '$grade'";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?php echo $row['section'] ?>">
                                        <?php echo $row['section'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <div class="w-70p m-l-1em">Contact Number</div>
                            <input placeholder="Contact Number" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="contactNumber"
                                   name="contactNumber"
                                   required>


                        </div>

                    </div>
                    <div class="d-flex-end">
                        <button type="submit"
                                class="c-hand btn-success btn"
                                name="edit-teacher"
                                style="background-color: #ffffff !important; border-color: #ffffff;">
                            <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
                        </button>
                    </div>
                </form>
            </div>
            <div id="view-subject-loads" class="modal-child pad-bottom-2em d-none">
                <div class="d-flex-end gap-1em">
                    <button
                            class="btn bg-hover-gray-dark-v1" onclick="showModal('add-new-subject', 'Add New Subject')">
                        Add New
                    </button>
                    <button
                            class="btn bg-hover-gray-dark-v1"
                            onclick="deleteTeachers('teacher-subject')">Delete Selected
                    </button>
                </div>
                <?php

                if (isset($_GET['lrn'])) {
                    $lrns = $_GET['lrn'];
                    $name = $_GET['name'] . '"s';
                    echo "<script>showModal('view-subject-loads', '$name Subjects')</script>";
                    $sql = " select id, subject, schedule_day, room, CONCAT(`schedule_time_in`, ' - ', `schedule_time_out`) as 'schedule_time', schedule_time_in, schedule_time_out from teachers_subject_info where teachers_lrn='$lrns' ";
                    $teachers_enrollment_info_result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($teachers_enrollment_info_result);
                    $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                    $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                    // Get the total number of records from our table "teachers".
                    $total_pages = $mysqli->query("select id, subject, schedule_day, room, CONCAT(`schedule_time_in`, ' - ', `schedule_time_out`) as 'schedule_time', schedule_time_in, schedule_time_out from teachers_subject_info where teachers_lrn='$lrns' ")->num_rows;
                    //  Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                    $page = isset($_GET['page_enrollment']) && is_numeric($_GET['page_enrollment']) ? $_GET['page_enrollment'] : 1;

                    // Number of results to show on each page.
                    $num_results_on_page = 5;

                    if ($stmt = $mysqli->prepare("select id, subject, schedule_day, room, CONCAT(`schedule_time_in`, ' - ', `schedule_time_out`) as 'schedule_time', schedule_time_in, schedule_time_out from teachers_subject_info where teachers_lrn='$lrns' ORDER BY id LIMIT ?,?")) {
                        //    Calculate the page to get the results we need from our table.
                        $calc_page = ($page - 1) * $num_results_on_page;
                        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                        $stmt->execute();
                        // Get the results...
                        $teachers_enrollment_info_result = $stmt->get_result();
                        ?>

                        <table class="table table-1 m-t-1em">
                            <thead>
                            <tr>
                                <th class="t-align-center"><label for="teacher-subject-cb"
                                                                  class="d-flex-center"></label><input
                                            id="teacher-subject-cb" type="checkbox"
                                            onclick="checkCBteachers('teacher-subject','teacher-subject-cb')"
                                            class="sc-1-3 c-hand"/></th>
                                <th>No</th>
                                <th>Subject</th>
                                <th>Grade</th>
                                <th>Day</th>
                                <th>Room</th>
                                <th>Schedule Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="teacher-subject">
                            <?php
                            $i = 0;
                            while ($row = $teachers_enrollment_info_result->fetch_assoc()):
                                $i++;
                                ?>
                                <tr>
                                    <td class="d-flex-center"><label>
                                            <input type="checkbox" class="sc-1-3 c-hand check" id="<?= $row['id'] ?>"/>
                                        </label></td>
                                    <th scope="row"><?= $i ?> </th>
                                    <th scope="row"><?= $row['subject'] ?> </th>
                                    <th scope="row"><?= $row['grade_level'] ?> </th>
                                    <td><?= $row['schedule_day'] ?></td>
                                    <td><?= $row['room'] ?></td>
                                    <td><?= $row['schedule_time'] ?></td>

                                    <td>
                                        <label for="" class="t-color-red c-hand f-weight-bold"
                                               onclick="editSubject('<?= $row['id'] ?>','<?= $row['subject'] ?>','<?= $row['grade_level'] ?>','<?= $row['schedule_day'] ?>','<?= $row['room'] ?>','<?= $row['schedule_time_in'] ?>','<?= $row['schedule_time_out'] ?>')">Edit</label>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>

                        <?php
                        $stmt->close();
                    }
                }
                ?>
                <div class="m-2em d-flex-end m-t-n1em">
                    <div class="d-flex-center">
                        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="prev"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <div id="add-new-subject" class="modal-child d-none">
                <form method="post">
                    <div class="custom-grid-container" tabindex="2">
                        <div class="custom-grid-item ">
                            <div class="w-70p m-l-1em">Subject</div>
                            <input placeholder="Subject" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="subject"
                                   name="subject"
                                   required>
                            <div class="w-70p m-l-1em">Room</div>
                            <input placeholder="Room" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="room"
                                   name="room"
                                   required>
                            <div class="w-70p m-l-1em">Grade</div>
                            <input placeholder="Grade" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="grade-level"
                                   name="grade-level"
                                   required>
                            <div class="w-70p m-l-1em">Time In</div>
                            <input placeholder="Time In" type="time"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="time-in"
                                   name="time-in"
                                   required>
                            <div class="w-70p m-l-1em">Time Out</div>
                            <input placeholder="Time Out" type="time"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="time-out"
                                   name="time-out"
                                   required>
                            <div class="w-70p m-l-1em">Schedule Day</div>
                            <input placeholder="Schedule Day" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="schedule-day"
                                   name="schedule-day"
                                   required>

                        </div>
                    </div>
                    <div class="d-flex-end">
                        <label class="btn bg-hover-gray-dark-v1 m-b-0"
                               onclick="showModal('view-subject-loads',' <?= $_GET['name'] . 's' ?>  Subjects')">
                            Back
                        </label>
                        &nbsp; &nbsp;
                        <button type="submit"
                                class="c-hand btn-success btn"
                                name="add-new-subject">Save
                        </button>
                    </div>
                </form>
            </div>
            <div id="edit-subject" class="modal-child d-none">
                <form method="post">
                    <div class="custom-grid-container" tabindex="2">
                        <div class="custom-grid-item ">
                            <input placeholder="id" type="hidden"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="edit-id"
                                   name="edit-id"
                                   readonly="true">
                            <div class="w-70p m-l-1em">Subject</div>
                            <input placeholder="Subject" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="edit-subject-name"
                                   name="edit-subject-name"
                                   required>
                            <div class="w-70p m-l-1em">Room</div>
                            <input placeholder="Room" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="edit-room"
                                   name="edit-room"
                                   required>
                            <div class="w-70p m-l-1em">Grade</div>
                            <input placeholder="Grade" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="edit-grade-level"
                                   name="edit-grade-level"
                                   required>
                            <div class="w-70p m-l-1em">Time In</div>
                            <input placeholder="Time In" type="time"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="edit-time-in"
                                   name="edit-time-in"
                                   required>
                            <div class="w-70p m-l-1em">Time Out</div>
                            <input placeholder="Time Out" type="time"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="edit-time-out"
                                   name="edit-time-out"
                                   required>
                            <div class="w-70p m-l-1em">Schedule Day</div>
                            <input placeholder="Schedule Day" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="edit-schedule-day"
                                   name="edit-schedule-day"
                                   required>

                        </div>
                    </div>
                    <div class="d-flex-end">
                        <label class="btn bg-hover-gray-dark-v1 m-b-0"
                               onclick="showModal('view-subject-loads',' <?= $_GET['name'] . 's' ?>  Subjects')">
                            Back
                        </label>
                        &nbsp; &nbsp;
                        <button type="submit"
                                class="c-hand btn-success btn"
                                name="edit-subject">Save
                        </button>
                    </div>
                </form>
            </div>
            <div id="view-students" class="modal-child pad-bottom-2em d-none">
                <div class="d-flex-end gap-1em">
                    <button
                            class="btn bg-hover-gray-dark-v1" onclick="showModal('add-students', 'Add Students')">
                        Add Students
                    </button>
                    <button
                            class="btn bg-hover-gray-dark-v1"
                            onclick="deleteTeachers('teacher-students')">Delete Selected
                    </button>
                </div>
                <?php

                if (isset($_GET['teachers_lrn'])) {
                    $lrns = $_GET['teachers_lrn'];
                    $name = $_GET['name'] . '"s';
                    echo "<script>showModal('view-students', '$name Students')</script>";
                    $sql = "select ssi.id,si.lrn, CONCAT(si.f_name, ' ', si.l_name) as 'fullname', si.gender, ssi.grade_level from students_grade_info ssi left join students_info si on si.lrn = ssi.student_lrn where ssi.teacher_lrn='$lrns' GROUP BY ssi.grade_level desc ";
                    $teachers_enrollment_info_result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($teachers_enrollment_info_result);
                    $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                    $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                    // Get the total number of records from our table "teachers".
                    $total_pages = $mysqli->query("select ssi.id,si.lrn, CONCAT(si.f_name, ' ', si.l_name) as 'fullname', si.gender, ssi.grade_level from students_grade_info ssi left join students_info si on si.lrn = ssi.student_lrn where ssi.teacher_lrn='$lrns' GROUP BY ssi.grade_level desc  ")->num_rows;
                    //  Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                    $page = isset($_GET['page_enrollment']) && is_numeric($_GET['page_enrollment']) ? $_GET['page_enrollment'] : 1;
//jay
                    // Number of results to show on each page.
                    $num_results_on_page = 5;

                    if ($stmt = $mysqli->prepare("select ssi.id,si.lrn, CONCAT(si.f_name, ' ', si.l_name) as 'fullname', si.gender, ssi.grade_level from students_grade_info ssi left join students_info si on si.lrn = ssi.student_lrn where ssi.teacher_lrn='$lrns' GROUP BY ssi.grade_level ORDER BY id LIMIT ?,?")) {
                        //    Calculate the page to get the results we need from our table.
                        $calc_page = ($page - 1) * $num_results_on_page;
                        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                        $stmt->execute();
                        // Get the results...
                        $teachers_enrollment_info_result = $stmt->get_result();
                        ?>

                        <table class="table table-1 m-t-1em">
                            <thead>
                            <tr>
                                <th class="t-align-center"><label for="teacher-subject-cb"
                                                                  class="d-flex-center"></label><input
                                            id="teacher-subject-cb" type="checkbox"
                                            onclick="checkCBteachers('teacher-students','teacher-subject-cb')"
                                            class="sc-1-3 c-hand"/></th>
                                <th>No</th>
                                <th>LRN</th>
                                <th>Fullname</th>
                                <th>Sex</th>
                                <th>Grade</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="teacher-students">
                            <?php
                            $i = 0;
                            while ($row = $teachers_enrollment_info_result->fetch_assoc()):
                                $i++;
                                ?>
                                <tr>
                                    <td class="d-flex-center"><label>
                                            <input type="checkbox" class="sc-1-3 c-hand check"
                                                   id="<?= $row['lrn'] ?>,<?= $row['grade_level'] ?>"/>
                                        </label></td>
                                    <th scope="row"><?= $i ?> </th>
                                    <th scope="row"><?= $row['lrn'] ?> </th>
                                    <th scope="row"><?= $row['fullname'] ?> </th>
                                    <th scope="row"><?= $row['gender'] ?></th>
                                    <th scope="row"><?= $row['grade_level'] ?></th>
                                    <td>
                                        <label for="" class="t-color-red c-hand f-weight-bold"
                                               onclick="editSubject('<?= $row['id'] ?>','<?= $row['subject'] ?>','<?= $row['grade_level'] ?>','<?= $row['schedule_day'] ?>','<?= $row['room'] ?>','<?= $row['schedule_time_in'] ?>','<?= $row['schedule_time_out'] ?>')">View
                                            Grade</label>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>

                        <?php
                        $stmt->close();
                    }
                }
                ?>
                <div class="m-2em d-flex-end m-t-n1em">
                    <div class="d-flex-center">
                        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="prev"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/add_teacher/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <div id="add-students" class="modal-child d-none">
                Search Grade:
                <?php
                if (isset($_GET['teachers_lrn'])) {
                    $teacher_lrn = $_GET['teachers_lrn'];
                    $sql = "select * from teachers_subject_info where teachers_lrn='$teacher_lrn' GROUP BY teachers_lrn order by id ASC";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result)) { ?>
                        <select name="student_grade" id="student_grade" onchange="changeStudentGrade()">
                            <option value=""></option>
                            <?php
                            $i = 0;
                            while ($rows = mysqli_fetch_assoc($result)) {
                                $i++;
                                ?>
                                <?php if ($rows['grade_level'] == $_GET['searchGrade']) { ?>
                                    <option value="<?= $rows['grade_level'] ?>"
                                            selected> <?= $rows['grade_level'] ?> </option>
                                <?php } else { ?>
                                    <option value="<?= $rows['grade_level'] ?>"><?= $rows['grade_level'] ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    <?php }
                } ?>

                <div class="m-2em d-flex-align-start">
                    <div class="bg-white w-100p b-radius-10px">
                        <?php
                        if (isset($_GET['searchGrade'])) {
                            $searchGrade = $_GET['searchGrade'];
                            echo "<script>showModal('add-students', 'Add Students')</script>";

                            $sql = "SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        where sei.grade = '$searchGrade'
                        GROUP BY si.id order by sei.students_info_lrn DESC";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                            $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                            // Get the total number of records from our table "students".
                            $total_pages = $mysqli->query("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                          where sei.grade = '$searchGrade'
                        GROUP BY si.id order by sei.students_info_lrn DESC")->num_rows;
                            // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                            // Number of results to show on each page.
                            $num_results_on_page = 10;

                            if ($stmt = $mysqli->prepare("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                          where sei.grade = '$searchGrade'
                        GROUP BY si.id order by sei.students_info_lrn DESC LIMIT ?,?")) {
                                // Calculate the page to get the results we need from our table.
                                $calc_page = ($page - 1) * $num_results_on_page;
                                $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                                $stmt->execute();
                                // Get the results...
                                $result = $stmt->get_result();
                                ?>

                                <table class="table table-1 ">
                                    <thead>
                                    <tr>
                                        <th class="t-align-center"><label for="student-list-cb"
                                                                          class="d-flex-center"></label><input
                                                    id="student-list-cb" type="checkbox"
                                                    onclick="checkCBStudents('student-list', 'student-list-cb')"
                                                    class="sc-1-3 c-hand"/></th>
                                        <th>No</th>
                                        <th>LRN</th>
                                        <th>Firstname</th>
                                        <th>Lastname</th>
                                        <th>Birthdate</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th>Grade</th>
                                    </tr>
                                    </thead>
                                    <tbody id="student-list">
                                    <?php
                                    $i = 0;
                                    while ($row = $result->fetch_assoc()):
                                        $i++;
                                        ?>
                                        <tr>
                                            <td class="d-flex-center"><label>
                                                    <input type="checkbox" class="sc-1-3 c-hand check"
                                                           id="<?= $row['lrn'] ?>"/>
                                                </label></td>
                                            <th scope="row"><?= $i ?> </th>
                                            <td><?= $row['lrn'] ?></td>
                                            <td><?= $row['f_name'] ?></td>
                                            <td><?= $row['l_name'] ?></td>
                                            <td><?= $row['b_date'] ?></td>
                                            <td><?= $row['age'] ?></td>
                                            <td><?= $row['gender'] ?></td>
                                            <td><?= $_GET['searchGrade'] ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                                <div class="m-2em d-flex-end m-t-n1em">
                                    <div class="d-flex-center">
                                        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                                            <ul class="pagination">
                                                <?php if ($page > 1): ?>
                                                    <li class="prev"><a
                                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ($page > 3): ?>
                                                    <li class="start"><a
                                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page + 1 ?>">1</a>
                                                    </li>
                                                    <li class="dots">...</li>
                                                <?php endif; ?>

                                                <?php if ($page - 2 > 0): ?>
                                                    <li class="page"><a
                                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                                    </li><?php endif; ?>
                                                <?php if ($page - 1 > 0): ?>
                                                    <li class="page"><a
                                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                                    </li><?php endif; ?>

                                                <li class="currentpage"><a
                                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                                </li>

                                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                                    <li class="page"><a
                                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                                    </li><?php endif; ?>
                                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                                    <li class="page"><a
                                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                                    </li><?php endif; ?>

                                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                                    <li class="dots">...</li>
                                                    <li class="end"><a
                                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                                    <li class="next"><a
                                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page + 1 ?>">Next</a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php
                                $stmt->close();
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class=" p-absolute btm-1em w-98p">
                    <div class="r-50px d-flex-end gap-1em m-t-1em">
                        <label class="btn bg-hover-gray-dark-v1 m-b-0"
                               onclick="showModal('view-students',' <?= $_GET['name'] . 's' ?>  Students')">
                            Back
                        </label>
                        <button type="submit"
                                class="c-hand btn-success btn"
                                onclick="addStudent('student-list')">Submit
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>

    function checkCBteachers(id, cb) {
        if ($('#' + cb).is(':checked')) {
            $('#' + id + ' input[type="checkbox"]').prop('checked', true);

        } else {
            $('#' + id + ' input[type="checkbox"]').prop('checked', false);
        }
    }

    $(document).on('click', '#modal-delete-cancel', function (e) {
        $('#modal-delete').attr('style', 'display: none !important;')
        $('#modal-checkbox').attr('style', 'display: none !important;')

    });

    $(document).on('click', '#modal-success', function (e) {
        $('#modal-addedSuccessfully').attr('style', 'display: none !important;')
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>');
        window.location.reload();
    });

    $(document).on('click', '#modal-delete-ok', function (e) {
        deleteAction($('#modal-delete').val());
        $('#modal-delete').attr('style', 'display: none !important;')
    });

    function deleteTeachers(id) {
        var count = 0;
        $('#' + id + ' input[type="checkbox"]:checked').each(function () {
            count++;
        });
        if (count > 0) {
            $('#modal-delete').attr('style', 'display: block;')
            $('#modal-delete').val(id);
        } else {
            $('#modal-checkbox').attr('style', 'display: block;')
        }
    }

    function deleteAction(id) {
        var idArray = [];
        var count = 0;
        $('#' + id + ' input[type="checkbox"]:checked').each(function () {
            idArray.push($(this).attr('id'));
            count++;
        });
        if (count > 0) {
            idArray.forEach(function (teachSubjID) {
                    $.post('', {lrn: teachSubjID})
            });
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>');
            window.location.reload();
        }
    }

    function viewTeacherEnrollment(lrn, fullname) {
        var name = fullname.split(',')[0];
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>&&lrn=' + lrn + '&&name=' + name);
        window.location.reload();
    }

    function viewTeacherStudents(teachers_lrn, fullname) {
        var name = fullname.split(',')[0];
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>&&teachers_lrn=' + teachers_lrn + '&&name=' + name);
        window.location.reload();
    }

    function showGrade(fullName, gradeLevel, schoolYear) {
        $('#view-teacher-grade #view-teacher-grade-name').text(fullName);
        $('#view-teacher-grade #view-teacher-grade-grade').text(gradeLevel);
        $('#view-teacher-grade #view-teacher-grade-school-year').text(schoolYear);
        showModal('view-teacher-grade', 'teacher Grade')
    }

    function print(id) {
        var orientation;
        xdialog.confirm('Choose Print Orientation?', function () {
            orientation = 'landscape';
            $('#' + id).load('print_page', function () {
                var printContent = document.getElementById(id).innerHTML;
                var WinPrint = window.open('', '', '');
                var content = '<style>@page { size: A4 ' + orientation + ';  margin: 1.5em 1em 0 1em !important;} table{border-collapse: collapse !important;} .action-button{display:none}</style> <link rel="stylesheet" href="https://code.jquery.com/jquery-3.5.1.min.js"> <link rel="stylesheet" href="../../assets/css/style_custom.css">' + printContent;
                WinPrint.document.write(content);
                WinPrint.document.close();
                WinPrint.focus();
                setTimeout(function () {
                    WinPrint.print();
                    WinPrint.close();
                }, 100);
            });
        }, {
            // style: 'width:420px;font-size:0.8rem;',
            buttons: {
                ok: 'Landscape',
                cancel: 'Portrait'
            },
            oncancel: function () {
                orientation = 'portrait';
                $('#' + id).load('print_page', function () {
                    var printContent = document.getElementById(id).innerHTML;
                    var WinPrint = window.open('', '', '');
                    var content = '<style>@page { size: A4 ' + orientation + ';  margin: 1.5em 1em 0 1em !important;} table{border-collapse: collapse !important;} .action-button{display:none}</style> <link rel="stylesheet" href="https://code.jquery.com/jquery-3.5.1.min.js"> <link rel="stylesheet" href="../../assets/css/style_custom.css">' + printContent;
                    WinPrint.document.write(content);
                    WinPrint.document.close();
                    WinPrint.focus();
                    setTimeout(function () {
                        WinPrint.print();
                        WinPrint.close();
                    }, 1000);
                });
            }
        });
    }

    var arr = [];

    function addSubject() {
        var count = arr.length;
        arr[count] = ["''", "''", "''", "''", "''"];
        $('#add-subject').val(arr);

        $('#add-subject-parent').append('<div id="' + count + '" class="add-subject-child b-1px-black b-radius-10px m-b-1em" style="background:#ed7d31"> <div onclick="arrOnclick(' + count + ')" class="w-2em t-align-center f-r c-hand t-close"> X </div>' +
        '<div class="w-70p m-l-1em m-t-1em">Subject</div><input onchange="arrOnChange(' + count + ')" placeholder="Subject" type="text" class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="subject" name="subject" required> ' +
        '<div class="w-70p m-l-1em">Room</div><input onchange="arrOnChange(' + count + ')" placeholder="room" type="text" class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="room" name="room" required> <div class="w-70p m-l-1em">Grade</div><input onchange="arrOnChange(' + count + ')" placeholder="Grade" type="text" class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="grade_level" name="grade_level" required><div class="w-70p m-l-1em">Time IN</div><input onchange="arrOnChange(' + count + ')" placeholder="Schedule Time" type="time" class="h-3em  f-size-1em b-radius-10px m-1em m-t-5px" id="schedule_time_in" name="schedule_time_in" required><div class="w-70p m-l-1em">Time Out</div><input onchange="arrOnChange(' + count + ')" placeholder="Schedule Time" type="time" class="h-3em  f-size-1em b-radius-10px m-1em m-t-5px" id="schedule_time_out" name="schedule_time_out" required>  ' +
        '<div class="w-70p m-l-1em">Schedule Day</div><input onchange="arrOnChange(' + count + ')" placeholder="Schedule Day" type="text" class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="schedule_day" name="schedule_day" required></div> </div>')
        count++;
    }

    function arrOnChange(id) {
        var arr2 = '';
        arr[id][0] = $('#add-subject-parent .add-subject-child:nth-child(' + (id + 1) + ') #subject').val();
        arr[id][1] = $('#add-subject-parent .add-subject-child:nth-child(' + (id + 1) + ') #room').val();
        arr[id][2] = $('#add-subject-parent .add-subject-child:nth-child(' + (id + 1) + ') #grade_level').val();
        arr[id][3] = $('#add-subject-parent .add-subject-child:nth-child(' + (id + 1) + ') #schedule_time_in').val();
        arr[id][4] = $('#add-subject-parent .add-subject-child:nth-child(' + (id + 1) + ') #schedule_time_out').val();
        arr[id][5] = $('#add-subject-parent .add-subject-child:nth-child(' + (id + 1) + ') #schedule_day').val();

        arr.map((item, index) => {
            arr2 += "['" + item[0] + "','" + item[1] + "','" + item[2] + "','" + item[3] + "','" + item[4] + "','" + item[5] + "'],";
        })
        var newArr = arr2.slice(0, -1).replace("],[", "] , [")
        $('#add-subject').val(newArr);
    }

    function arrOnclick(id) {
        var arr2 = '';
        arr.splice(id, 1);
        $('#add-subject-parent #' + id).remove();

        $('#add-subject-parent .add-subject-child').each(function (index) {
            $(this).attr('id', index);
            $(this).find('.t-close').attr('onclick', 'arrOnclick(' + index + ')');
            $(this).find('#subject').attr('onChange', 'arrOnChange(' + index + ')');
            $(this).find('#room').attr('onChange', 'arrOnChange(' + index + ')');
            $(this).find('#grade_level').attr('onChange', 'arrOnChange(' + index + ')');
            $(this).find('#schedule_time_in').attr('onChange', 'arrOnChange(' + index + ')');
            $(this).find('#schedule_time_out').attr('onChange', 'arrOnChange(' + index + ')');
            $(this).find('#schedule_day').attr('onChange', 'arrOnChange(' + index + ')');
        })

        arr.map((item, index) => {
            arr2 += "['" + item[0] + "','" + item[1] + "','" + item[2] + "','" + item[3] + "','" + item[4] + "','" + item[5] + "'],";
        })
        var newArr = arr2.slice(0, -1).replace("],[", "] , [")
        $('#add-subject').val(newArr);
    }

    function editSubject(id, subject, gradeLevel, scheduleDay, room, scheduleTimeIn, scheduleTimeOut) {
        $('#edit-subject #edit-id').val(id);
        $('#edit-subject #edit-subject-name').val(subject);
        $('#edit-subject #edit-room').val(room);
        $('#edit-subject #edit-grade-level').val(gradeLevel);
        $('#edit-subject #edit-time-in').val(scheduleTimeIn);
        $('#edit-subject #edit-time-out').val(scheduleTimeOut);
        $('#edit-subject #edit-schedule-day').val(scheduleDay);
        showModal('edit-subject', 'Edit Subject');
    }

    function changeStudentGrade() {
        var grade = $('#student_grade').val();
        $.post('', {$searchGrade: grade})
        var lrn = '<?php if (isset($_GET['teachers_lrn'])) echo $_GET['teachers_lrn']?>';
        var name = '<?php if (isset($_GET['name'])) echo $_GET['name']?>';
        var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
        history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&searchGrade=' + grade + '&&teachers_lrn=' + lrn + '&&name=' + name);
        window.location.reload();
    }

    function addStudent(id) {
        var studentID = [];
        var studentCount = 0;
        $('#' + id + ' input[type="checkbox"]:checked').each(function () {
            studentID.push($(this).attr('id'));
            studentCount++;
        });


        if (studentCount > 0) {
            var r = confirm("Are you sure you want to add this student ?");
            if (r === true) {
                studentID.forEach(function (studentID) {
                    $.post('', {studentID: studentID})
                });

                var lrn = '<?php if (isset($_GET['teachers_lrn'])) echo $_GET['teachers_lrn']?>';
                var name = '<?php if (isset($_GET['name'])) echo $_GET['name']?>';
                var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
                var grade = '<?php if (isset($_GET['searchGrade'])) echo $_GET['searchGrade']?>';
                history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&teachers_lrn=' + lrn + '&&name=' + name);
                window.location.reload();

            }
        } else {
            alert('Please select a student!');
        }
    }

    function search(status) {
        if(status === "name") {
            var search = $('.search_name').val();
            if (search !== '') {
                window.location.href = '?id=<?php echo $_GET['id'] ?>&&searchName=' + search;
            } else {
                window.location.href = '?id=<?php echo $_GET['id'] ?>';
            }
        } else {
            var search_lrn = $('.search_lrn').val();
            if (search_lrn !== '') {
                window.location.href = '?id=<?php echo $_GET['id'] ?>&&search_lrn=' + search_lrn;
            } else {
                window.location.href = '?id=<?php echo $_GET['id'] ?>';
            }
        }

    }


    function editTeacher(lrn, lastName, firstName, address, gender, civilStatus, subject, email, grade, section,contactNumber) {
        $('#edit-teacher #lrnUpdate').val(lrn);
        $('#edit-teacher #lastName').val(lastName);
        $('#edit-teacher #firstName').val(firstName);
        $('#edit-teacher #address').val(address);
        $('#edit-teacher #gender').val(gender)
        $('#edit-teacher #civilStatus').val(civilStatus);
        $('#edit-teacher #subject').val(subject);
        $('#edit-teacher #emailAddress').val(email);
        $('#edit-teacher #grade').val(grade);
        $('#edit-teacher #section').val(section);
        $('#edit-teacher #contactNumber').val(contactNumber);
        showModal('edit-teacher', 'Edit Teacher', '', 'small');
    }

    function viewStudentList(grade, section, teacher_lrn) {
        history.pushState({page: 'another page'}, 'another page', '/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=' + grade + '&&Tsection=' + section + '&&Tlrn=' + teacher_lrn);
        window.location.reload();
    }

    function selectGrade(status) {
        if (status === 'add') {
            var fname = $('#add-new-teacher #firstName').val();
            var lname = $('#add-new-teacher #lastName').val();
            var address = $('#add-new-teacher #address').val();
            var gender = $('#add-new-teacher #gender').val();
            var civilStatus = $('#add-new-teacher #civilStatus').val();
            var email = $('#add-new-teacher #emailAddress').val();
            var grade = $('#add-new-teacher #grade').val();
            var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
            history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&searchGradeAdd=' + grade + '&&fname=' + fname + '&&lname=' + lname + '&&address=' + address + '&&gender=' + gender + '&&civilStatus=' + civilStatus + '&&email=' + email);
            window.location.reload();
        } else {
            var lrnUpdate = $('#edit-teacher #lrnUpdate').val();
            var fname = $('#edit-teacher #firstName').val();
            var lname = $('#edit-teacher #lastName').val();
            var address = $('#edit-teacher #address').val();
            var gender = $('#edit-teacher #gender').val();
            var civilStatus = $('#edit-teacher #civilStatus').val();
            var email = $('#edit-teacher #emailAddress').val();
            var grade = $('#edit-teacher #grade').val();
            var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
            history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&searchGradeEdit=' + grade + '&&lrnUpdate=' + lrnUpdate + '&&fname=' + fname + '&&lname=' + lname + '&&address=' + address + '&&gender=' + gender + '&&civilStatus=' + civilStatus + '&&email=' + email);
            window.location.reload();
        }

    }

    function loadPage() {
        var added_successfully = '<?php echo isset($_GET['added_successfully']) ? $_GET['added_successfully'] : '' ?>';
        if (added_successfully !== '') {
            $('#modal-addedSuccessfully').attr('style', 'display: block;')
        }

        var search_lrn = '<?php echo isset($_GET['search_lrn']) ? $_GET['search_lrn'] : '' ?>';
        if (search_lrn !== '') {
            $('.search_lrn').val(search_lrn);
        }

        var lrnExists = '<?php echo isset($_GET['lrnExist']) ? $_GET['lrnExist'] : '' ?>';
        if (lrnExists !== '') {
            $('#lrn-add').val(lrnExists);
            showModal('add-new-teacher', 'New Teacher', '', 'small');
        }

        var searchGradeAdd = '<?php echo isset($_GET['searchGradeAdd']) ? $_GET['searchGradeAdd'] : '' ?>';
        var fname = '<?php echo isset($_GET['fname']) ? $_GET['fname'] : '' ?>';
        var lname = '<?php echo isset($_GET['lname']) ? $_GET['lname'] : '' ?>';
        var address = '<?php echo isset($_GET['address']) ? $_GET['address'] : '' ?>';
        var gender = '<?php echo isset($_GET['gender']) ? $_GET['gender'] : '' ?>';
        var civilStatus = '<?php echo isset($_GET['civilStatus']) ? $_GET['civilStatus'] : '' ?>';
        var email = '<?php echo isset($_GET['email']) ? $_GET['email'] : '' ?>';
        if (searchGradeAdd !== '') {
            $('#add-new-teacher #grade').val(searchGradeAdd);
            $('#add-new-teacher #firstName').val(fname);
            $('#add-new-teacher #lastName').val(lname);
            $('#add-new-teacher #address').val(address);
            $('#add-new-teacher #gender').val(gender);
            $('#add-new-teacher #civilStatus').val(civilStatus);
            $('#add-new-teacher #emailAddress').val(email);
            showModal('add-new-teacher', 'New Teacher', '', 'small');
        }

        var searchGradeEdit = '<?php echo isset($_GET['searchGradeEdit']) ? $_GET['searchGradeEdit'] : '' ?>';
        var lrnUpdate = '<?php echo isset($_GET['lrnUpdate']) ? $_GET['lrnUpdate'] : '' ?>';
        if (searchGradeEdit !== '') {
            $('#edit-teacher #lrnUpdate').val(lrnUpdate);
            $('#edit-teacher #grade').val(searchGradeEdit);
            $('#edit-teacher #firstName').val(fname);
            $('#edit-teacher #lastName').val(lname);
            $('#edit-teacher #address').val(address);
            $('#edit-teacher #gender').val(gender);
            $('#edit-teacher #civilStatus').val(civilStatus);
            $('#edit-teacher #emailAddress').val(email);
            showModal('edit-teacher', 'Edit Teacher', '', 'small');
        }
    }

    loadPage();
</script>
<link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>