<?php global $mysqli, $rows;
$var = "trash";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['recoverId'])) {
    $id = $_POST['recoverId'];
    $sql = "select * from trash_info where id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $lrn = $row['user_lrn'];
    $history = $row['history'];
    $position = $row['position'];
    $history = explode("<h3>", $history);
    if ($position === 'student') {
        $studentInfo = str_replace("Student Info</h3>", "", $history[1]);
        echo '<script> console.log("Student Info"); </script>';

        $studentInfo = explode("<br/>", $studentInfo);
        $sLrn = trim(str_replace("lrn: ", "", $studentInfo[1]));
        $sFname = trim(str_replace("f_name: ", "", $studentInfo[2]));
        $sLname = trim(str_replace("l_name: ", "", $studentInfo[3]));
        $sMname = trim(str_replace("m_name: ", "", $studentInfo[4]));
        $sGender = trim(str_replace("gender: ", "", $studentInfo[5]));
        $sBdate = trim(str_replace("birth_date: ", "", $studentInfo[6]));
        $sBdate = date("Y-m-d", strtotime($sBdate));
        $sBplace = trim(str_replace("b_place: ", "", $studentInfo[7]));
        $sCstatus = trim(str_replace("c_status: ", "", $studentInfo[8]));
        $sAge = trim(str_replace("age: ", "", $studentInfo[9]));
        $sNationality = trim(str_replace("nationality: ", "", $studentInfo[10]));
        $sReligion = trim(str_replace("religion: ", "", $studentInfo[11]));
        $sContact = trim(str_replace("contact_number: ", "", $studentInfo[12]));
        $sEmail = trim(str_replace("email_address: ", "", $studentInfo[13]));
        $sHome = trim(str_replace("home_address: ", "", $studentInfo[14]));
        $sGuardian = trim(str_replace("guardian_name: ", "", $studentInfo[15]));
        $sAddedBy = trim(str_replace("addedBy: ", "", $studentInfo[16]));
        $sTeacherLrn = trim(str_replace("teacher_lrn: ", "", $studentInfo[17]));
        $insertStudent = "insert into students_info (f_name,l_name,m_name,gender,b_place,c_status,age,b_date,nationality,religion,contact_number,email_address,home_address,lrn,guardian_name, addedBy,teacher_lrn) VALUES ('$sFname', '$sLname', '$sMname', '$sGender', '$sBplace', '$sCstatus', '$sAge', '$sBdate' , '$sNationality', '$sReligion', '$sContact', '$sEmail', '$sHome', '$sLrn', '$sGuardian', '$sAddedBy', '$sTeacherLrn')";
        $result = mysqli_query($conn, $insertStudent);

        $studentEnrollmentInfo = str_replace("Student Enrollment Info</h3>", "", $history[2]);
        if ($studentEnrollmentInfo !== " ") {
            echo '<script> console.log("Student Enrollment Info"); </script>';
            $studentEnrollmentInfo = explode("<br/>", $studentEnrollmentInfo);
            $studentEnrollmentLrn = trim(str_replace("students_info_lrn: ", "", $studentEnrollmentInfo[1]));
            $studentEnrollmentGrade = trim(str_replace("grade: ", "", $studentEnrollmentInfo[2]));
            $studentEnrollmentSection = trim(str_replace("section: ", "", $studentEnrollmentInfo[3]));
            $studentEnrollmentSchoolYear = trim(str_replace("school_year: ", "", $studentEnrollmentInfo[4]));
            $studentEnrollmentSchoolYear = date("Y-m-d", strtotime($studentEnrollmentSchoolYear));
            $studentEnrollmentDateEnrolled = trim(str_replace("date_enrolled: ", "", $studentEnrollmentInfo[5]));
            $studentEnrollmentDateEnrolled = date("Y-m-d", strtotime($studentEnrollmentDateEnrolled));
            $studentEnrollmentStatus = trim(str_replace("status: ", "", $studentEnrollmentInfo[6]));

            $insertStudentEnrollmentInfo = "insert into students_enrollment_info (students_info_lrn,grade,section,school_year,date_enrolled,status) VALUES ('$studentEnrollmentLrn','$studentEnrollmentGrade','$studentEnrollmentSection','$studentEnrollmentSchoolYear','$studentEnrollmentDateEnrolled','$studentEnrollmentStatus')";
            $result = mysqli_query($conn, $insertStudentEnrollmentInfo);

        }

        // Hash the admin password
        $hashed_admin_password = password_hash($sLname, PASSWORD_DEFAULT);

        $sqlUserInfo = "insert into users_info (last_name,first_name,username,password,user_type,user_lrn) VALUES ('$sLname','$sFname','$sLrn','$hashed_admin_password','student','$sLrn')";
        $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

        $studentGradeInfo = str_replace("Student Grade Info</h3>", "", $history[3]);
        if ($studentGradeInfo !== " ") {
            echo '<script> console.log("Student Grade Info"); </script>';

            $studentGradeInfo = explode("id:", $studentGradeInfo);
            foreach ($studentGradeInfo as $key => $value) {
                if ($value !== " ") {
                    $value = explode("<br/>", $value);
                    $sLrn = trim(str_replace("student_lrn: ", "", $value[1]));
                    $sTeacherLrn = trim(str_replace("teacher_lrn: ", "", $value[2]));
                    $sSubject = trim(str_replace("subject: ", "", $value[3]));
                    $sGrade = trim(str_replace("grade: ", "", $value[4]));
                    $sFirstGrade = trim(str_replace("first_grade: ", "", $value[5]));
                    $sSecondGrade = trim(str_replace("second_grade: ", "", $value[6]));
                    $sThirdGrade = trim(str_replace("third_grade: ", "", $value[7]));
                    $sFourthGrade = trim(str_replace("fourth_grade: ", "", $value[8]));
                    $sFinal = trim(str_replace("final: ", "", $value[9]));
                    $sUnits = trim(str_replace("units: ", "", $value[10]));
                    $sStatus = trim(str_replace("status: ", "", $value[11]));
                    $insertStudentGradeInfo = "insert into students_grade_info (student_lrn,teacher_lrn,subject,grade,first_grade,second_grade,third_grade,fourth_grade,final,units,status) VALUES ('$sLrn','$sTeacherLrn','$sSubject','$sGrade','$sFirstGrade','$sSecondGrade','$sThirdGrade','$sFourthGrade','$sFinal','$sUnits','$sStatus')";
                    $result = mysqli_query($conn, $insertStudentGradeInfo);
                }
            }

        }

        $studentGradeAttendanceInfo = str_replace("Student Grade Attendance Info</h3>", "", $history[4]);
        if ($studentGradeAttendanceInfo !== " ") {
            echo '<script> console.log("Student Grade Attendance Info"); </script>';

            $studentGradeAttendanceInfo = explode("id:", $studentGradeAttendanceInfo);
            foreach ($studentGradeAttendanceInfo as $key => $value) {
                if ($value !== " ") {
                    $value = explode("<br/>", $value);
                    $sGrade = trim(str_replace("grade: ", "", $value[1]));
                    $sLrn = trim(str_replace("student_lrn: ", "", $value[2]));
                    $sTeacherLrn = trim(str_replace("teacher_lrn: ", "", $value[3]));
                    $sJuneDaysClasses = trim(str_replace("june_days_classes: ", "", $value[4]));
                    $sJuneDaysPresents = trim(str_replace("june_days_presents: ", "", $value[5]));
                    $sJulyDaysClasses = trim(str_replace("july_days_classes: ", "", $value[6]));
                    $sJulyDaysPresents = trim(str_replace("july_days_presents: ", "", $value[7]));
                    $sAugDaysClasses = trim(str_replace("aug_days_classes: ", "", $value[8]));
                    $sAugDaysPresents = trim(str_replace("aug_days_presents: ", "", $value[9]));
                    $sSepDaysClasses = trim(str_replace("sep_days_classes: ", "", $value[10]));
                    $sSepDaysPresents = trim(str_replace("sep_days_presents: ", "", $value[11]));
                    $sOctDaysClasses = trim(str_replace("oct_days_classes: ", "", $value[12]));
                    $sOctDaysPresents = trim(str_replace("oct_days_presents: ", "", $value[13]));
                    $sNovDaysClasses = trim(str_replace("nov_days_classes: ", "", $value[14]));
                    $sNovDaysPresents = trim(str_replace("nov_days_presents: ", "", $value[15]));
                    $sDecDaysClasses = trim(str_replace("dec_days_classes: ", "", $value[16]));
                    $sDecDaysPresents = trim(str_replace("dec_days_presents: ", "", $value[17]));
                    $sJanDaysClasses = trim(str_replace("jan_days_classes: ", "", $value[18]));
                    $sJanDaysPresents = trim(str_replace("jan_days_presents: ", "", $value[19]));
                    $sFebDaysClasses = trim(str_replace("feb_days_classes: ", "", $value[20]));
                    $sFebDaysPresents = trim(str_replace("feb_days_presents: ", "", $value[21]));
                    $sMarDaysClasses = trim(str_replace("mar_days_classes: ", "", $value[22]));
                    $sMarDaysPresents = trim(str_replace("mar_days_presents: ", "", $value[23]));
                    $sAprDaysClasses = trim(str_replace("apr_days_classes: ", "", $value[24]));
                    $sAprDaysPresents = trim(str_replace("apr_days_presents: ", "", $value[25]));
                    $sMayDaysClasses = trim(str_replace("may_days_classes: ", "", $value[26]));
                    $sMayDaysPresents = trim(str_replace("may_days_presents: ", "", $value[27]));

                    $insertStudentGradeAttendanceInfo = "insert into students_grade_attendance_info (student_lrn,teacher_lrn,grade,june_days_classes,june_days_presents,july_days_classes,july_days_presents,aug_days_classes,aug_days_presents,sep_days_classes,sep_days_presents,oct_days_classes,oct_days_presents,nov_days_classes,nov_days_presents,dec_days_classes,dec_days_presents,jan_days_classes,jan_days_presents,feb_days_classes,feb_days_presents,mar_days_classes,mar_days_presents,apr_days_classes,apr_days_presents,may_days_classes,may_days_presents) VALUES ('$sLrn','$sTeacherLrn','$sGrade','$sJuneDaysClasses','$sJuneDaysPresents','$sJulyDaysClasses','$sJulyDaysPresents','$sAugDaysClasses','$sAugDaysPresents','$sSepDaysClasses','$sSepDaysPresents','$sOctDaysClasses','$sOctDaysPresents','$sNovDaysClasses','$sNovDaysPresents','$sDecDaysClasses','$sDecDaysPresents','$sJanDaysClasses','$sJanDaysPresents','$sFebDaysClasses','$sFebDaysPresents','$sMarDaysClasses','$sMarDaysPresents','$sAprDaysClasses','$sAprDaysPresents','$sMayDaysClasses','$sMayDaysPresents')";
                    $result = mysqli_query($conn, $insertStudentGradeAttendanceInfo);
                }
            }

        }

    } else if ($position === 'teacher') {
        $teacherInfo = str_replace("Teachers Info</h3>", "", $history[1]);
        $teacherInfo = explode("<br/>", $teacherInfo);
        echo '<script> console.log("' . implode($teacherInfo) . '"); </script>';
        foreach ($teacherInfo as $key => $value) {
            if ($value !== " ") {
                $val = str_replace(" ", "", $value);
                echo '<script> console.log("' . $val . '"); </script>';
            }
        }

        $tLrn = trim(str_replace("lrn: ", "", $teacherInfo[1]));
        $tFname = trim(str_replace("first_name: ", "", $teacherInfo[2]));
        $tLname = trim(str_replace("last_name: ", "", $teacherInfo[3]));
        $tAddress = trim(str_replace("address:", "", $teacherInfo[4]));
        $tGender = trim(str_replace("gender:", "", $teacherInfo[5]));
        $tCivil = trim(str_replace("civil_status:", "", $teacherInfo[6]));
        $tEmail = trim(str_replace("email_address:", "", $teacherInfo[7]));
        $tSection = trim(str_replace("section:", "", $teacherInfo[8]));
        $tGrade = trim(str_replace("grade:", "", $teacherInfo[9]));
        $insertTeacher = "insert into teachers_info (lrn, first_name, last_name,address,gender,civil_status,email_address,grade,section) values ('$tLrn','$tFname','$tLname','$tAddress','$tGender','$tCivil','$tEmail','$tGrade','$tSection')";
        $result = mysqli_query($conn, $insertTeacher);

        // Hash the admin password
        $hashed_admin_password = password_hash($tLname, PASSWORD_DEFAULT);

        $sqlUserInfo = "insert into users_info (last_name,first_name,username,password,user_type,user_lrn) VALUES ('$tLname','$tFname','$tLrn','$hashed_admin_password','teacher','$tLrn')";
        $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

    }

    $sqlDeleteTrash = "delete from trash_info where id = '$id'";
    $result = mysqli_query($conn, $sqlDeleteTrash);

    echo '<script>';
    echo '
              history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                window.location.reload();
            ';
    echo '</script>';
}

if(isset($_POST['deleteId'])) {
    $id = $_POST['deleteId'];
    $sql = "delete from trash_info where id = '$id'";
    $result = mysqli_query($conn, $sql);
}
?>

<div class="d-flex-end p-absolute w-100p bottom-0 t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px contents one_page ">

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

                <div class="m-t-19px m-l-13px f-weight-bold d-flex">
                    <h3>
                        Trash Lists
                    </h3>
                    <div class="r-50px p-absolute t-54px">
                        <svg class="c-hand" onclick="deleteId('student-list')"  width="50" height="43" id="svg2"
                             version="1.1" viewBox="0 0 99.999995 99.999995"
                             xmlns="http://www.w3.org/2000/svg"
                             xmlns:svg="http://www.w3.org/2000/svg">
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
                                          style="fill:red !important; color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;direction:ltr;block-progression:tb;writing-mode:lr-tb;baseline-shift:baseline;text-anchor:start;white-space:normal;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:4.99999952;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>
                <br/>

                <?php
                $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
                $id = $_GET['id'];
                $sqlSelectUser = "select * from users_info where id = '$id'";
                $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                $user_lrn = $rowSelectUser['user_lrn'];

                $sql = "select * from trash_info where name like '%$searchName%' and teacher_lrn='$user_lrn' order by id desc Limit 1";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "students".
                $total_pages = $mysqli->query("select * from trash_info where name like '%$searchName%' and teacher_lrn='$user_lrn' order by id desc")->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare("select * from trash_info where name like '%$searchName%' and teacher_lrn='$user_lrn' order by id desc LIMIT ?,?")) {
                    // Calculate the page to get the results we need from our table.
                    $calc_page = ($page - 1) * $num_results_on_page;
                    $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                    $stmt->execute();
                    // Get the results...
                    $result = $stmt->get_result();
                    ?>
                    <input placeholder="search name" id="search_name" type="text" class=" m-b-5px"
                           onchange="searchName()"/>
                    <table class="table table-1 b-shadow-dark ">
                        <thead>
                        <tr>
                            <th class="t-align-center"><label for="student-list-cb" class="d-flex-center"></label><input
                                        id="student-list-cb" type="checkbox"
                                        onclick="checkCBStudents('student-list', 'student-list-cb')"
                                        class="sc-1-3 c-hand"/></th>
                            <th>No</th>
                            <th>LRN</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Removed Date</th>
                            <th>Removed By</th>
                            <th class="t-align-center">View History</th>
                            <th class="t-align-center">Recover</th>
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
                                        <input type="checkbox" class="sc-1-3 c-hand check" id="<?= $row['id'] ?>"/>
                                    </label></td>
                                <th scope="row"><?= $i ?> </th>
                                <td><?= $row['user_lrn'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['position'] ?></td>
                                <td><?= $row['removed_date'] ?></td>
                                <td><?= $row['removed_by'] ?></td>
                                <td class="t-align-center">
                                    <svg class="c-hand" onclick="viewStudentInformation('<?= $row['history'] ?>')" width="40" height="40" id="svg8" version="1.1" viewBox="0 0 5.0281178 5.2932191" xmlns="http://www.w3.org/2000/svg"  xmlns:svg="http://www.w3.org/2000/svg"><defs id="defs2"/><g id="layer15" transform="translate(-52.386467,-34.3948)"><g id="FLAT-14"><path d="m 53.152311,34.3948 c -0.419132,0 -0.765844,0.346704 -0.765844,0.765844 v 3.761529 c 0,0.419131 0.346712,0.765844 0.765844,0.765844 h 2.673739 a 0.26460996,0.26460996 0 0 0 0.265618,-0.265615 V 36.7776 35.982818 c 0,-0.408889 -0.162007,-0.800727 -0.451136,-1.089856 l -0.04702,-0.04496 C 55.304379,34.558873 54.913058,34.3948 54.504168,34.3948 Z" id="path1372" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#82bbfb;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.529167;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:markers fill stroke;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000"/><path d="m 53.710934,35.9823 a 0.2645835,0.2645835 0 0 0 -0.265615,0.265618 0.2645835,0.2645835 0 0 0 0.265615,0.263548 h 1.058852 A 0.2645835,0.2645835 0 0 0 55.033335,36.247918 0.2645835,0.2645835 0 0 0 54.769786,35.9823 Z m 0,1.058852 a 0.2645835,0.2645835 0 0 0 -0.265615,0.265615 0.2645835,0.2645835 0 0 0 0.265615,0.263551 h 1.058852 a 0.2645835,0.2645835 0 0 0 0.263549,-0.263551 0.2645835,0.2645835 0 0 0 -0.263549,-0.265615 z" id="path1398" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#cfcfcf;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.529167;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000"/><path d="m 212,144.96289 a 17.202391,32.202391 69.881832 0 1 -13.9082,2.8418 c 0.31761,1.25231 1.4556,2.19726 2.79882,2.19726 h 10.10547 A 1.0001006,1.0001006 0 0 0 212,148.99805 Z" id="path1549" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#65a3fc;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:markers fill stroke;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000" transform="scale(0.26458334)"/><path d="m 55.827085,36.512501 c -0.873623,0 -1.5875,0.713896 -1.5875,1.5875 0,0.873636 0.713888,1.5875 1.5875,1.5875 0.873612,0 1.5875,-0.713864 1.5875,-1.5875 0,-0.873604 -0.713878,-1.5875 -1.5875,-1.5875 z" id="path1387" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#007ff9;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.529167;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000"/><path d="m 216.91797,143.05664 a 17.202391,32.202391 69.881832 0 1 -3.50391,1.4082 17.202391,32.202391 69.881832 0 1 -7.75195,2.25782 C 206.65846,148.66329 208.67778,150 211,150 c 3.30184,0 6,-2.69807 6,-6 0,-0.32173 -0.0327,-0.63503 -0.082,-0.94336 z" id="path1554" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#006ef9;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000" transform="scale(0.26458334)"/><path d="M 55.826172,37.306641 A 0.2645835,0.2645835 0 0 0 55.5625,37.570312 v 0.529297 a 0.26460996,0.26460996 0 0 0 0.263672,0.265625 h 0.529297 a 0.2645835,0.2645835 0 0 0 0.265625,-0.265625 0.2645835,0.2645835 0 0 0 -0.265625,-0.263671 h -0.263672 v -0.265626 a 0.2645835,0.2645835 0 0 0 -0.265625,-0.263671 z" id="path1404" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#cfcfcf;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.529167;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000"/><path d="m 213.9668,144.25781 a 17.202391,32.202391 69.881832 0 1 -0.55274,0.20703 17.202391,32.202391 69.881832 0 1 -1.53125,0.53711 h 1.11328 a 1.0000006,1.0000006 0 0 0 0.97071,-0.74414 z" id="path1558" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill:#a8abae;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000" transform="scale(0.26458334)"/></g></g></svg>
                                </td>
                                <td class="t-align-center">
                                    <svg class="c-hand" width="40" height="40"  onclick="recoverStudentInformation('<?= $row['id'] ?>')" style="enable-background:new 0 0 500 500.002;" version="1.1" viewBox="0 0 500 500.002" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="memory-sd-card"><g><path d="M250.001,0C111.93,0,0,111.94,0,250.006c0,138.076,111.93,249.996,250.001,249.996    C388.07,500.002,500,388.082,500,250.006C500,111.94,388.07,0,250.001,0z" style="fill:#7DBEBD;"/><g id="_x32_2"><path d="M153.948,366.656c-8.673-0.019-15.695-7.039-15.712-15.712l0,0v-128.5     c0.152-7.81,3.837-16.712,9.246-22.339l0,0l57.512-57.517c5.632-5.394,14.533-9.086,22.346-9.241l0,0v4.492v4.487     c-4.55-0.165-12.891,3.308-15.988,6.626l0,0l-57.517,57.498c-3.318,3.109-6.779,11.448-6.619,15.993l0,0v128.5     c0.009,3.662,3.066,6.723,6.733,6.723l0,0h192.106c3.667,0,6.723-3.061,6.728-6.723l0,0V149.059     c-0.005-3.662-3.061-6.728-6.728-6.728l0,0H227.34v-0.005v-4.487v-4.502l118.714,0.01c8.668,0.019,15.697,7.034,15.712,15.712     l0,0v201.885c-0.015,8.673-7.044,15.693-15.712,15.712l0,0H153.948L153.948,366.656z" style="fill:#656D78;"/><g><path d="M227.726,139.735c-6.071,0-14.55,3.517-18.839,7.8l-56.53,56.542      c-4.298,4.293-7.807,12.772-7.807,18.831v126.323c0,6.059,4.972,11.026,11.035,11.026h188.838      c6.062,0,11.033-4.967,11.033-11.026v-198.46c0-6.059-4.972-11.036-11.033-11.036H227.726z" style="fill:#656D78;"/></g><g><path d="M181.385,318.341h-17.728c-1.344,0-2.445,1.096-2.445,2.444v39.38c7.381,0,15.021,0,22.598,0      v-39.38C183.81,319.437,182.709,318.341,181.385,318.341z" style="fill:#7C838C;"/><path d="M220.231,318.341h-17.72c-1.349,0-2.44,1.096-2.44,2.444v39.38c7.374,0,15.009,0,22.595,0v-39.38      C222.666,319.437,221.565,318.341,220.231,318.341z" style="fill:#7C838C;"/><path d="M259.084,318.341h-17.733c-1.336,0-2.425,1.096-2.425,2.444v39.38c7.369,0,15.006,0,22.588,0      v-39.38C261.514,319.437,260.414,318.341,259.084,318.341z" style="fill:#7C838C;"/><path d="M297.933,318.341h-17.725c-1.341,0-2.435,1.096-2.435,2.444v39.38c7.376,0,15.013,0,22.598,0      v-39.38C300.37,319.437,299.274,318.341,297.933,318.341z" style="fill:#7C838C;"/><path d="M336.788,318.341h-17.727c-1.334,0-2.433,1.096-2.433,2.444v39.38c7.378,0,15.009,0,22.593,0      v-39.38C339.221,319.437,338.125,318.341,336.788,318.341z" style="fill:#7C838C;"/></g><path d="M304.867,288.711c0,2.935-2.406,5.351-5.356,5.351h-95.085c-2.952,0-5.363-2.416-5.363-5.351     v-91.323c0-2.939,2.411-5.355,5.363-5.355h95.085c2.95,0,5.356,2.416,5.356,5.355V288.711z" style="fill:#FFFFFF;"/><g><path d="M227.949,230.709c-0.104-2.057-0.245-4.516-0.218-6.37h-0.071      c-0.505,1.722-1.111,3.566-1.863,5.613l-2.597,7.155h-1.441l-2.391-7.024c-0.696-2.086-1.293-3.992-1.705-5.744h-0.044      c-0.053,1.853-0.157,4.313-0.296,6.525l-0.385,6.325h-1.817l1.031-14.756h2.431l2.515,7.141c0.609,1.82,1.116,3.434,1.492,4.967      h0.061c0.374-1.499,0.898-3.119,1.565-4.967l2.622-7.141h2.43l0.914,14.756h-1.855L227.949,230.709z" style="fill:#E9E9EA;"/><path d="M234.262,232.252c0.044,2.595,1.712,3.672,3.641,3.672c1.375,0,2.209-0.238,2.93-0.548      l0.327,1.378c-0.682,0.301-1.843,0.655-3.517,0.655c-3.272,0-5.225-2.149-5.225-5.351c0-3.187,1.887-5.704,4.977-5.704      c3.449,0,4.385,3.046,4.385,4.997c0,0.383-0.054,0.693-0.078,0.902H234.262z M239.906,230.864      c0.024-1.217-0.5-3.134-2.668-3.134c-1.938,0-2.801,1.795-2.959,3.134H239.906z" style="fill:#E9E9EA;"/><path d="M244.167,229.463c0-1.091-0.022-1.994-0.082-2.862h1.676l0.087,1.698h0.078      c0.582-1.004,1.574-1.945,3.318-1.945c1.443,0,2.544,0.868,2.995,2.125h0.044c0.337-0.592,0.745-1.058,1.189-1.382      c0.628-0.475,1.336-0.742,2.338-0.742c1.407,0,3.486,0.912,3.486,4.594v6.243h-1.885v-5.996c0-2.042-0.735-3.27-2.307-3.27      c-1.086,0-1.938,0.82-2.268,1.756c-0.095,0.272-0.151,0.611-0.151,0.956v6.553h-1.885v-6.35c0-1.688-0.752-2.915-2.214-2.915      c-1.201,0-2.079,0.965-2.379,1.94c-0.124,0.272-0.158,0.602-0.158,0.936v6.388h-1.882V229.463z" style="fill:#E9E9EA;"/><path d="M272.036,231.815c0,3.91-2.722,5.613-5.292,5.613c-2.865,0-5.072-2.096-5.072-5.448      c0-3.556,2.321-5.627,5.256-5.627C269.965,226.353,272.036,228.56,272.036,231.815z M263.622,231.912      c0,2.319,1.332,4.075,3.216,4.075c1.843,0,3.221-1.742,3.221-4.118c0-1.785-0.905-4.065-3.175-4.065      C264.609,227.803,263.622,229.899,263.622,231.912z" style="fill:#E9E9EA;"/><path d="M274.467,229.899c0-1.247-0.027-2.324-0.09-3.299h1.683l0.061,2.076h0.087      c0.49-1.422,1.654-2.324,2.94-2.324c0.224,0,0.371,0.019,0.543,0.073v1.804c-0.194-0.044-0.38-0.063-0.652-0.063      c-1.364,0-2.321,1.024-2.579,2.469c-0.051,0.277-0.085,0.577-0.085,0.912v5.641h-1.908V229.899z" style="fill:#E9E9EA;"/><path d="M282.875,226.6l2.326,6.253c0.237,0.703,0.504,1.533,0.67,2.168h0.048      c0.197-0.635,0.41-1.441,0.682-2.212l2.103-6.209h2.035l-2.888,7.538c-1.382,3.643-2.333,5.506-3.636,6.646      c-0.946,0.834-1.885,1.154-2.36,1.247l-0.485-1.625c0.485-0.146,1.113-0.451,1.676-0.936c0.533-0.417,1.189-1.159,1.625-2.149      c0.087-0.194,0.152-0.349,0.152-0.456c0-0.111-0.039-0.267-0.129-0.514l-3.917-9.75H282.875z" style="fill:#E9E9EA;"/><path d="M227.379,246.659h-0.044l-2.469,1.334l-0.383-1.47l3.114-1.659h1.635v14.237h-1.853V246.659z" style="fill:#E9E9EA;"/><path d="M234.417,259.102v-1.184l1.521-1.47c3.631-3.454,5.275-5.302,5.3-7.437      c0-1.45-0.693-2.789-2.828-2.789c-1.286,0-2.362,0.655-3.008,1.213l-0.623-1.368c0.989-0.82,2.387-1.441,4.033-1.441      c3.058,0,4.351,2.101,4.351,4.138c0,2.624-1.906,4.749-4.902,7.631l-1.138,1.057v0.044h6.386v1.606H234.417z" style="fill:#E9E9EA;"/><path d="M245.497,255.483c0-1.795,1.06-3.061,2.818-3.808l-0.017-0.063      c-1.586-0.752-2.266-1.97-2.266-3.202c0-2.251,1.914-3.784,4.41-3.784c2.758,0,4.138,1.722,4.138,3.512      c0,1.194-0.594,2.489-2.341,3.309v0.073c1.766,0.698,2.865,1.96,2.865,3.682c0,2.469-2.124,4.138-4.841,4.138      C247.289,259.339,245.497,257.569,245.497,255.483z M253.156,255.4c0-1.732-1.206-2.571-3.126-3.11      c-1.673,0.475-2.566,1.577-2.566,2.93c-0.07,1.445,1.031,2.712,2.848,2.712C252.041,257.932,253.156,256.86,253.156,255.4z       M247.857,248.284c0,1.422,1.074,2.188,2.717,2.625c1.222-0.422,2.168-1.295,2.168-2.59c0-1.131-0.686-2.314-2.413-2.314      C248.74,246.004,247.857,247.052,247.857,248.284z" style="fill:#E9E9EA;"/><path d="M268.813,258.446c-0.846,0.291-2.542,0.81-4.531,0.81c-2.232,0-4.07-0.567-5.511-1.96      c-1.274-1.222-2.069-3.192-2.069-5.476c0.027-4.405,3.056-7.64,7.989-7.64c1.712,0,3.046,0.374,3.689,0.684l-0.458,1.552      c-0.793-0.349-1.771-0.631-3.272-0.631c-3.582,0-5.933,2.231-5.933,5.923c0,3.745,2.258,5.962,5.695,5.962      c1.249,0,2.106-0.175,2.54-0.393v-4.4h-2.998v-1.523h4.859V258.446z" style="fill:#E9E9EA;"/><path d="M271.58,259.102c0.049-0.728,0.09-1.805,0.09-2.736v-12.811h1.901v6.66h0.044      c0.681-1.184,1.906-1.96,3.616-1.96c2.637,0,4.489,2.198,4.465,5.413c0,3.784-2.386,5.671-4.751,5.671      c-1.53,0-2.763-0.602-3.544-1.998h-0.066l-0.097,1.761H271.58z M273.571,254.852c0,0.238,0.044,0.485,0.087,0.703      c0.366,1.339,1.494,2.251,2.886,2.251c2.02,0,3.226-1.649,3.226-4.075c0-2.12-1.099-3.944-3.153-3.944      c-1.317,0-2.54,0.902-2.938,2.367c-0.039,0.219-0.109,0.485-0.109,0.796V254.852z" style="fill:#E9E9EA;"/></g></g></g></g><g id="Layer_1"/></svg>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>

                    <?php
                    $stmt->close();
                }
                ?>
                Total Records: <?= $total_pages ?>
                <div class="m-2em d-flex-end m-t-n1em">
                    <div class="d-flex-center">
                        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="prev"><a
                                                href="/1-php-grading-system/teachers_page/trash/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/teachers_page/trash/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/trash/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/trash/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/teachers_page/trash/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/trash/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/trash/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/teachers_page/trash/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/teachers_page/trash/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
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
            <div id="view-student-info" class="modal-child d-none">
                <div id="history"></div>
            </div>
        </div>
    </div>
</div>


<script>

    function checkCBStudents(id, cb) {
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

    function deleteId(id) {
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
            idArray.forEach(function (id) {
                $.post('', {deleteId: id})
            });
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>');
            window.location.reload();
        }
    }


    function viewStudentInformation(data) {
        $("#view-student-info div").html(data);
        showModal('view-student-info', 'Student Information', '', 'small');
    }

    function updateStudentInformation() {
        $('#up-lrn').val($('#view-student-info h4:nth-child(1) label').text());
        $('#up-lastName').val($('#view-student-info h4:nth-child(3) label').text());
        $('#up-gender').val($('#view-student-info h4:nth-child(16) label').text());
        $('#up-civilStatus').val($('#view-student-info h4:nth-child(9) label').text());
        $('#up-religion').val($('#view-student-info h4:nth-child(10) label').text())
        $('#up-homeAddress').val($('#view-student-info h4:nth-child(6) label').text());

        var birthDate = new Date($('#view-student-info h4:nth-child(4) label').text());
        var month = String(birthDate.getMonth() + 1).length === 1 ? '0' + (birthDate.getMonth() + 1) : (birthDate.getMonth() + 1);
        var day = String(birthDate.getDate()).length === 1 ? '0' + birthDate.getDate() : birthDate.getDate();
        $('#up-birthDate').val(birthDate.getFullYear() + '-' + month + '-' + day)

        $('#up-firstName').val($('#view-student-info h4:nth-child(2) label').text());
        $('#up-age').val($('#view-student-info h4:nth-child(5) label').text());
        $('#up-contactNumber').val($('#view-student-info h4:nth-child(11) label').text());

        $('#up-middleName').val($('#view-student-info h4:nth-child(12) label').text());
        $('#up-birthPlace').val($('#view-student-info h4:nth-child(13) label').text());
        $('#up-nationality').val($('#view-student-info h4:nth-child(14) label').text());
        $('#up-emailAddress').val($('#view-student-info h4:nth-child(15) label').text());

        $('#up-guardianName').val($('#view-student-info h4:nth-child(7) label').text());
        $('#up-gradeLevel').val($('#view-student-info h4:nth-child(8) label').text());

        showModal('update-student-info', 'Update Student')
    }

    function viewStudentEnrollment(lrn) {
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>&&lrn=' + lrn);
        window.location.reload();
    }

    function showGrade(fullName, gradeLevel, schoolYear) {
        $('#view-student-grade #view-student-grade-name').text(fullName);
        $('#view-student-grade #view-student-grade-grade').text(gradeLevel);
        $('#view-student-grade #view-student-grade-school-year').text(schoolYear);
        showModal('view-student-grade', 'Student Grade')
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

    function searchName() {
        var search = $('#search_name').val();
        if (search !== '') {
            window.location.href = '?id=<?php echo $_GET['id'] ?>&&searchName=' + search;
        } else {
            window.location.href = '?id=<?php echo $_GET['id'] ?>';
        }
    }

    $(document).on('click', '#modal-recover-cancel', function (e) {
        $('#modal-recover').attr('style', 'display: none !important;')

    });

    $(document).on('click', '#modal-recover-ok', function (e) {
        recoverAction($('#modal-recover').val());
        $('#modal-recover').attr('style', 'display: none !important;')
    });

    function recoverStudentInformation(id) {
        $('#modal-recover').attr('style', 'display: block;')
        $('#modal-recover').val(id);
    }

    function recoverAction(id) {
        Post('', {recoverId: id})
    }
    function loadPage() {
        var searchName = '<?php echo isset($_GET['searchName']) ? $_GET['searchName'] : '' ?>';
        if (searchName !== '') {
            $('#search_name').val(searchName);
        }
    }

    loadPage();

</script>
<link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>