<?php global $mysqli, $rows;
$var = "trash";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "delete from students_info where lrn = '$id'";
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

if (isset($_POST['studentEnrollmentId'])) {
    $id = $_POST['studentEnrollmentId'];
    $sql = "delete from students_enrollment_info where id = '$id'";
    $result = mysqli_query($conn, $sql);
}

if (isset($_POST['add-enrollment'])) {
    $lrn = $_POST['add-enrollment-lrn'];
    $gradeLevel = $_POST['add-enrollment-grade'];
    $schoolYear = $_POST['add-enrollment-school-year'];
    $schoolYear = date("Y-m-d", strtotime($schoolYear));
    $dateEnrolled = $_POST['add-enrollment-date-enrolled'];
    $dateEnrolled = date("Y-m-d", strtotime($dateEnrolled));
    $status = $_POST['add-enrollment-status'];
    $sql = "insert into students_enrollment_info (students_info_lrn,grade,school_year,date_enrolled,status) VALUES ('$lrn','$gradeLevel','$schoolYear','$dateEnrolled','$status')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
              alert("saved successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&lrn=' . $lrn . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['add-new-student'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $middleName = $_POST['middleName'];
    $gender = $_POST['gender'];
    $birthDate = $_POST['birthDate'];
    $birthDate = date("Y-m-d", strtotime($birthDate));
    $birthPlace = $_POST['birthPlace'];
    $civilStatus = $_POST['civilStatus'];
    $age = $_POST['age'];
    $nationality = $_POST['nationality'];
    $religion = $_POST['religion'];
    $contactNumber = $_POST['contactNumber'];
    $emailAddress = $_POST['emailAddress'];
    $homeAddress = $_POST['homeAddress'];
    $guardianName = $_POST['guardianName'];
    $lrn = $_POST['lrn'];
    $id = $_GET['id'];

    $sql = "insert into students_info (f_name,l_name,m_name,gender,b_place,c_status,age,b_date,nationality,religion,contact_number,email_address,home_address,lrn,guardian_name, addedBy) VALUES ('$firstName', '$lastName', '$middleName', '$gender', '$birthPlace', '$civilStatus', '$age', '$birthDate' , '$nationality', '$religion', '$contactNumber', '$emailAddress', '$homeAddress', '$lrn', '$guardianName', '$id')";
    $result = mysqli_query($conn, $sql);

    $sqlUserInfo = "insert into users_info (last_name,first_name,username,password,user_type,user_lrn) VALUES ('$lastName','$firstName','$lrn','$lastName','student','$lrn')";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

    if ($resultUserInfo) {
        echo '<script>';
        echo '   
              alert("saved successfully");
              window.location.href = "?id=' . $rows['id'] . '&datasavedsuccessfully";
            ';
        echo '</script>';
    } else {
        echo '<script>';
        echo 'console.log("failed")';
        echo '</script>';
    }

}

if (isset($_POST['update-student-info'])) {
    $firstName = $_POST['up-firstName'];
    $lastName = $_POST['up-lastName'];
    $middleName = $_POST['up-middleName'];
    $gender = $_POST['up-gender'];
    $birthDate = $_POST['up-birthDate'];
    $birthDate = date("Y-m-d", strtotime($birthDate));
    $birthPlace = $_POST['up-birthPlace'];
    $civilStatus = $_POST['up-civilStatus'];
    $age = $_POST['up-age'];
    $nationality = $_POST['up-nationality'];
    $religion = $_POST['up-religion'];
    $contactNumber = $_POST['up-contactNumber'];
    $emailAddress = $_POST['up-emailAddress'];
    $homeAddress = $_POST['up-homeAddress'];
    $guardianName = $_POST['up-guardianName'];
    $lrn = $_POST['up-lrn'];
    $sql = "update students_info set f_name = '$firstName', l_name = '$lastName', m_name = '$middleName', gender = '$gender', b_date='$birthDate', b_place = '$birthPlace', c_status = '$civilStatus'
                     , age = '$age', nationality = '$nationality', religion = '$religion', contact_number = '$contactNumber', email_address = '$emailAddress', home_address = '$homeAddress', guardian_name='$guardianName' where lrn = '$lrn'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
              alert("updated successfully");
              window.location.href = "?id=' . $rows['id'] . '&datasavedsuccessfully";
            ';
        echo '</script>';
    }
}

if (isset($_POST['studId'])) {
    $id = $_POST['studId'];
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
//        foreach ($studentInfo as $key => $value) {
//            if ($value !== " ") {
//                $val = str_replace(" ", "", $value);
//                echo '<script> console.log("' . $val . '"); </script>';
//            }
//        }
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
//            foreach ($studentEnrollmentInfo as $key => $value) {
//                if ($value !== " ") {
//                    $val = str_replace(" ", "", $value);
//                    echo '<script> console.log("' . $val . '"); </script>';
//                }
//            }
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

        $studentGradeInfo = str_replace("Student Grade Info</h3>", "", $history[3]);
        if ($studentGradeInfo !== " ") {
            echo '<script> console.log("Student Grade Info"); </script>';

            $studentGradeInfo = explode("id:", $studentGradeInfo);
            foreach ($studentGradeInfo as $key => $value) {
                if ($value !== " ") {
                    $value = explode("<br/>", $value);
//                    foreach ($value as $key => $val) {
//                        if ($val !== " ") {
//                            $val = str_replace(" ", "", $val);
//                            echo '<script> console.log("' . $val . '"); </script>';
//                        }
//
//                    }

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
//                    foreach ($value as $key => $val) {
//                        if ($val !== " ") {
//                            $val = str_replace(" ", "", $val);
//                            echo '<script> console.log("' . $val . '"); </script>';
//                        }
//                    }

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

        $sqlUserInfo = "insert into users_info (last_name,first_name,username,password,user_type,user_lrn) VALUES ('$sLname','$sFname','$sLrn','$sLname','student','$sLrn')";
        $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

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

        $sqlUserInfo = "insert into users_info (last_name,first_name,username,password,user_type,user_lrn) VALUES ('$tLname','$tFname','$tLrn','$tLname','teacher','$tLrn')";
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

if(isset($_POST['deleteTrash'])) {
    $id = $_POST['deleteTrash'];
    $sql = "delete from trash_info where id = '$id'";
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

<div class="d-flex-end p-absolute w-100p bottom-0 t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px contents one_page <?= $rows['dark_mode'] === '1' ? 'bg-dark' : ''  ?>">

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
        </style>

        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px pad-1em">

                <div class="m-t-19px m-l-13px f-weight-bold d-flex">
                    <h3>
                        Trash Lists
                    </h3>
                    <div class="r-50px p-absolute t-54px">
                        <svg class="c-hand" onclick="deleteStudents('student-list')" height="43" id="svg2"
                             version="1.1" viewBox="0 0 99.999995 99.999995" width="50"
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
                                          style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;direction:ltr;block-progression:tb;writing-mode:lr-tb;baseline-shift:baseline;text-anchor:start;white-space:normal;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:4.99999952;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>
                <br/>

                <?php
                $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
                $sql = "select * from trash_info where name like '%$searchName%' order by id desc Limit 1";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "students".
                $total_pages = $mysqli->query("select * from trash_info where name like '%$searchName%' order by id desc")->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare("select * from trash_info where name like '%$searchName%' order by id desc LIMIT ?,?")) {
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

                            <th></th>
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
                                <td>
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewStudentInformation('<?= $row['history'] ?>')"
                                    >View History</label> &nbsp;&nbsp;
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="recoverStudentInformation('<?= $row['id'] ?>')"
                                    >Recover</label>
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
                                                href="/1-php-grading-system/admins_page/add_student/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/add_student/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_student/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_student/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/add_student/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_student/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/add_student/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/add_student/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/add_student/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
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

    function deleteStudents(id) {
        var studentID = [];
        var studentCount = 0;
        $('#' + id + ' input[type="checkbox"]:checked').each(function () {
            studentID.push($(this).attr('id'));
            studentCount++;
        });
        if (studentCount > 0) {
            var r = confirm("Are you sure you want to delete ?");
            if (r === true) {
                var isStudentEnrollment = false;
                studentID.forEach(function (studId) {

                    if (id === 'student-enrollment') {
                        $.post('', {studentEnrollmentId: studId})
                        isStudentEnrollment = true;
                    } else {
                        $.post('', {deleteTrash: studId})
                    }
                });
                if (isStudentEnrollment) {
                    <?php
                    if (isset($_GET['lrn'])) {
                    ?>
                    history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id']?>' + '&&lrn=<?php echo $_GET['lrn']?>');
                    <?php } ?>
                } else {
                    history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>');
                }
                alert('Successfully deleted!')
                window.location.reload();

            }
        } else {
            if (id === 'student-enrollment') {
                alert('Please select a enrollment!');
            } else {
                alert('Please select a student!');
            }
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

    function recoverStudentInformation(studId) {
        var r = confirm("Are you sure you want to recover ?");
        if (r === true) {
            Post('', {studId: studId})
            alert('Successfully recovered!')
        }
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