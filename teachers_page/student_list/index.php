<?php global $mysqli, $rows, $schoolName;
$var = "student_list";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['deleteStId'])) {
    $lrn = $_POST['deleteStId'];

    $id = $_GET['id'];
    $sqlSelectRemovedBy = "select CONCAT(first_name, ' ', last_name) as 'name',user_lrn from users_info where id = '$id'";
    $resultSelectRemovedBy = mysqli_query($conn, $sqlSelectRemovedBy);
    $rowsSelectRemovedBy = mysqli_fetch_assoc($resultSelectRemovedBy);

    $user_lrn = $rowsSelectRemovedBy['user_lrn'];

    $removedBy = '';
    foreach ($rowsSelectRemovedBy as $key => $value) {
        $removedBy .= $value;
    }

    $sqlStudentInfo = "select * from students_info where lrn = '$lrn'";
    $resultStudentInfo = mysqli_query($conn, $sqlStudentInfo);
    $rowsStudentInfo = mysqli_fetch_assoc($resultStudentInfo);
    $name = $rowsStudentInfo['f_name'] . ' ' . $rowsStudentInfo['l_name'];
    $historyData = '';
    $historyData .= ' <h3> Student Info</h3>';
    foreach ($rowsStudentInfo as $key => $value) {
        $historyData .= $key . ': ' . $value . ' <br/>';
    }

    $sqlStudentEnrollmentInfo = "select * from students_enrollment_info where students_info_lrn = '$lrn'";
    $resultStudentEnrollmentInfo = mysqli_query($conn, $sqlStudentEnrollmentInfo);
    $rowsStudentEnrollmentInfo = mysqli_fetch_assoc($resultStudentEnrollmentInfo);
    $historyData .= ' <h3> Student Enrollment Info</h3>';
    foreach ($resultStudentEnrollmentInfo as $key => $value) {
        foreach ($value as $key1 => $value1) {
            $historyData .= $key1 . ': ' . $value1 . ' <br/>';
        }
    }

    $sqlStudentGradeInfo = "select * from students_grade_info where student_lrn = '$lrn'";
    $resultStudentGradeInfo = mysqli_query($conn, $sqlStudentGradeInfo);
    $rowsStudentGradeInfo = mysqli_fetch_assoc($resultStudentGradeInfo);
    $historyData .= ' <h3> Student Grade Info</h3>';
    foreach ($resultStudentGradeInfo as $key => $value) {
        foreach ($value as $key1 => $value1) {
            $historyData .= $key1 . ': ' . $value1 . ' <br/>';
        }
    }

    $sqlStudentGradeAttendanceInfo = "select * from students_grade_attendance_info where student_lrn = '$lrn'";
    $resultStudentGradeAttendanceInfo = mysqli_query($conn, $sqlStudentGradeAttendanceInfo);
    $rowsStudentGradeAttendanceInfo = mysqli_fetch_assoc($resultStudentGradeAttendanceInfo);
    $historyData .= ' <h3> Student Grade Attendance Info</h3>';
    foreach ($resultStudentGradeAttendanceInfo as $key => $value) {
        foreach ($value as $key1 => $value1) {
            $historyData .= $key1 . ': ' . $value1 . ' <br/>';
        }
    }

    $sqlUserInfo = "select * from users_info where user_lrn = '$lrn'";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);
    $rowsUserInfo = mysqli_fetch_assoc($resultUserInfo);
    $historyData .= ' <h3> User Info</h3>';
    foreach ($rowsUserInfo as $key => $value) {
        $historyData .= $key . ': ' . $value . ' <br/>';
    }


    $sqlInsertTrash = "insert into trash_info (user_lrn,teacher_lrn,name,history,removed_date,removed_by,position) VALUES ('$lrn', '$user_lrn','$name','$historyData', now(),'$removedBy','student')";
    $resultInsertTrash = mysqli_query($conn, $sqlInsertTrash);

    $sql = "delete from students_info where lrn = '$lrn'";
    $result = mysqli_query($conn, $sql);

    $sqlUserInfo = "delete from users_info where user_lrn = '$lrn'";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

    $sqlStudentEnrollmentInfo = "delete from students_enrollment_info where students_info_lrn = '$lrn'";
    $resultStudentEnrollmentInfo = mysqli_query($conn, $sqlStudentEnrollmentInfo);

    $sqlStudentGradeInfo = "delete from students_grade_info where student_lrn = '$lrn'";
    $resultStudentGradeInfo = mysqli_query($conn, $sqlStudentGradeInfo);

    $sqlDeleteStudentGradeAttendanceInfo = "delete from students_grade_attendance_info where student_lrn = '$lrn'";
    $resultDeleteStudentGradeAttendanceInfo = mysqli_query($conn, $sqlDeleteStudentGradeAttendanceInfo);

    $sqlPromotedStudentsHistory = "delete from promoted_info where student_lrn = '$lrn'";
    $resultPromotedStudentsHistory = mysqli_query($conn, $sqlPromotedStudentsHistory);

    if ($resultInsertTrash) {
        echo '<script>';
        echo '   
              history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['deleteEnId'])) {
    $id = $_POST['deleteEnId'];
    $sql = "delete from students_enrollment_info where id = '$id'";
    $result = mysqli_query($conn, $sql);
}

if (isset($_POST['add-enrollment'])) {
    $lrn = $_POST['add-enrollment-lrn'];
    $gradeLevel = $_POST['add-enrollment-grade'];
    $section = $_POST['add-enrollment-section'];
    $schoolYear = $_POST['add-enrollment-school-year'];
    $dateEnrolled = $_POST['add-enrollment-date-enrolled'];
    $dateEnrolled = date("Y-m-d", strtotime($dateEnrolled));
    $status = $_POST['add-enrollment-status'];

    $sqlSelectLRN = "select * from students_enrollment_info where students_info_lrn = '$lrn' and grade = '$gradeLevel' ";
    $resultSelectLRN = mysqli_query($conn, $sqlSelectLRN);
    $rowsSelectLRN = mysqli_fetch_assoc($resultSelectLRN);
    if ($rowsSelectLRN) {
        echo '<script>';
        echo '   
              alert("Grade Level already exist");
                history.pushState({page: "another page"}, "another page", "?id=' . $_GET['id'] . '&&lrn=' . $lrn . '");
            window.location.reload();
            ';
        echo '</script>';
        return;
    }

    $sql = "insert into students_enrollment_info (students_info_lrn,grade,section,school_year,date_enrolled,status) VALUES ('$lrn','$gradeLevel','$section','$schoolYear','$dateEnrolled','$status')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
                history.pushState({page: "another page"}, "another page", "?id=' . $_GET['id'] . '&&lrn=' . $_GET['lrn'] . '&&added_successfully=' . $_GET['lrn'] . '");
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

    $sqlSelectLRN = "select * from students_info where lrn = '$lrn'";
    $resultSelectLRN = mysqli_query($conn, $sqlSelectLRN);
    $rowsSelectLRN = mysqli_fetch_assoc($resultSelectLRN);
    if ($rowsSelectLRN) {
        echo '<script>';
        echo '   
              alert("LRN already exist");
              window.location.href = "?id=' . $_GET['id'] . '&&lrnexist=' . $lrn . '";
            ';
        echo '</script>';
        return;
    }

    $sqlSelectUsersInfo = "select * from users_info where id = '$id'";
    $resultSelectUsersInfo = mysqli_query($conn, $sqlSelectUsersInfo);
    $rowsSelectUsersInfo = mysqli_fetch_assoc($resultSelectUsersInfo);
    $userLrn = $rowsSelectUsersInfo['user_lrn'];

    $sql = "insert into students_info (f_name,l_name,m_name,gender,b_place,c_status,age,b_date,nationality,religion,contact_number,email_address,home_address,lrn,guardian_name, addedBy,teacher_lrn) VALUES ('$firstName', '$lastName', '$middleName', '$gender', '$birthPlace', '$civilStatus', '$age', '$birthDate' , '$nationality', '$religion', '$contactNumber', '$emailAddress', '$homeAddress', '$lrn', '$guardianName', '$id', '$userLrn')";
    $result = mysqli_query($conn, $sql);

    // Hash the admin password
    $hashed_admin_password = password_hash($lastName, PASSWORD_DEFAULT);

    $sqlUserInfo = "insert into users_info (last_name,first_name,username,password,user_type,user_lrn) VALUES ('$lastName','$firstName','$lrn','$hashed_admin_password','student','$lrn')";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

    if ($resultUserInfo) {
        echo '<script>';
        echo '   
                 history.pushState({page: "another page"}, "another page", "?id=' . $_GET['id'] . '&&added_successfully=' . $id . '");
              window.location.reload();
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
           history.pushState({page: "another page"}, "another page", "?id=' . $_GET['id'] . '&&added_successfully=' . $lrn . '");
           window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['add-student-grade'])) {
    $juneClasses = $_POST['june_days_classes'];
    $junePresents = $_POST['june_days_presents'];
    $julyClasses = $_POST['july_days_classes'];
    $julyPresents = $_POST['july_days_presents'];
    $augustClasses = $_POST['august_days_classes'];
    $augustPresents = $_POST['august_days_presents'];
    $septemberClasses = $_POST['september_days_classes'];
    $septemberPresents = $_POST['september_days_presents'];
    $octoberClasses = $_POST['october_days_classes'];
    $octoberPresents = $_POST['october_days_presents'];
    $novemberClasses = $_POST['november_days_classes'];
    $novemberPresents = $_POST['november_days_presents'];
    $decemberClasses = $_POST['december_days_classes'];
    $decemberPresents = $_POST['december_days_presents'];
    $januaryClasses = $_POST['january_days_classes'];
    $januaryPresents = $_POST['january_days_presents'];
    $februaryClasses = $_POST['february_days_classes'];
    $februaryPresents = $_POST['february_days_presents'];
    $marchClasses = $_POST['march_days_classes'];
    $marchPresents = $_POST['march_days_presents'];
    $aprilClasses = $_POST['april_days_classes'];
    $aprilPresents = $_POST['april_days_presents'];
    $mayClasses = $_POST['may_days_classes'];
    $mayPresents = $_POST['may_days_presents'];
    $id = $_GET['id'];
    $studentLrn = $_GET['lrn'];
    $grade = $_POST['grade'];

    $average = $_POST['average'];



    $sqlDeleteStudentGradeAverage = "delete from students_grade_average_info where students_lrn = '$studentLrn' and grade = '$grade'";
    $resultDeleteStudentGradeAverage = mysqli_query($conn, $sqlDeleteStudentGradeAverage);

    $sqlInsertStudentGradeAverage = "insert into students_grade_average_info (students_lrn,grade,average) VALUES ('$studentLrn','$grade','$average')";
    $resultInsertStudentGradeAverage = mysqli_query($conn, $sqlInsertStudentGradeAverage);


    $sqlSelectUser = "select * from users_info where id = '$id'";
    $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
    $rowsSelectUser = mysqli_fetch_assoc($resultSelectUser);
    $teacherLrn = $rowsSelectUser['user_lrn'];

    $sqlDeleteStudentGradeAttendanceInfo = "delete from students_grade_attendance_info where student_lrn = '$studentLrn' and teacher_lrn = '$teacherLrn' and grade = '$grade'";
    $resultDeleteStudentGradeAttendanceInfo = mysqli_query($conn, $sqlDeleteStudentGradeAttendanceInfo);

    $sqlDeleteStudentGradeInfo = "delete from students_grade_info where student_lrn = '$studentLrn' and teacher_lrn = '$teacherLrn' and grade = '$grade'";
    $resultDeleteStudentGradeInfo = mysqli_query($conn, $sqlDeleteStudentGradeInfo);

    $sqlInsertStudentGradeAttendanceInfo = "insert into students_grade_attendance_info (grade,student_lrn,teacher_lrn,june_days_classes,june_days_presents,july_days_classes,july_days_presents,aug_days_classes,aug_days_presents,sep_days_classes,sep_days_presents,oct_days_classes,oct_days_presents,nov_days_classes,nov_days_presents,dec_days_classes,dec_days_presents,jan_days_classes,jan_days_presents,feb_days_classes,feb_days_presents,mar_days_classes,mar_days_presents,apr_days_classes,apr_days_presents,may_days_classes,may_days_presents) VALUES ('$grade','$studentLrn','$teacherLrn','$juneClasses','$junePresents','$julyClasses','$julyPresents','$augustClasses','$augustPresents','$septemberClasses','$septemberPresents','$octoberClasses','$octoberPresents','$novemberClasses','$novemberPresents','$decemberClasses','$decemberPresents','$januaryClasses','$januaryPresents','$februaryClasses','$februaryPresents','$marchClasses','$marchPresents','$aprilClasses','$aprilPresents','$mayClasses','$mayPresents')";
    $resultInsertStudentGradeAttendanceInfo = mysqli_query($conn, $sqlInsertStudentGradeAttendanceInfo);


    $sqlSelectTeacherSubject = "select * from teachers_subject_info tsi
                                left join teachers_info ti on ti.lrn = tsi.teachers_lrn where tsi.teachers_lrn = '$teacherLrn' and ti.grade = '$grade'";
    $resultSelectTeacherSubject = mysqli_query($conn, $sqlSelectTeacherSubject);

    $isGradeComplete = true;
    foreach ($resultSelectTeacherSubject as $key => $value) {
        $subject = $value['subject'];

        $first_ = $subject . '1';
        $first = $_POST["$first_"];

        $second_ = $subject . '2';
        $second = $_POST["$second_"];

        $third_ = $subject . '3';
        $third = $_POST["$third_"];

        $fourth_ = $subject . '4';
        $fourth = $_POST["$fourth_"];

        if($fourth == 0 || $fourth == '0'){
            $isGradeComplete = false;
        }

        $final_ = $subject . 'final';
        $final = $_POST["$final_"];

        $unit_ = $subject . 'units';
        $unit = $_POST["$unit_"];

        $status_ = $subject . 'status';
        $status = $_POST["$status_"];

        $subject_handled_by = $subject . 'subject_handled_by';
        $subject_handled_by = $_POST["$subject_handled_by"];

        $sqlInsertStudentGradeInfo = "insert into students_grade_info (student_lrn,teacher_lrn,subject,grade,first_grade,second_grade,third_grade,fourth_grade,final,units,status, subject_handled_by) VALUES ('$studentLrn','$teacherLrn','$subject','$grade','$first','$second','$third','$fourth','$final','$unit','$status','$subject_handled_by')";
        $resultInsertStudentGradeInfo = mysqli_query($conn, $sqlInsertStudentGradeInfo);
    }

    if($isGradeComplete) {
        if ($average >= 75) {
            $gradeStatus = "Passed";
        } else {
            $gradeStatus = "Failed";
        }
    }

    $sqlUpdateStudentEnrollmentInfo = "update students_enrollment_info set grade_status = '$gradeStatus' where students_info_lrn = '$studentLrn' and grade = '$grade'";
    $resultUpdateStudentEnrollmentInfo = mysqli_query($conn, $sqlUpdateStudentEnrollmentInfo);

    if ($resultInsertStudentGradeAttendanceInfo) {
        echo '<script>';
        echo '
                 history.pushState({page: "another page"}, "another page", "?id=' . $_GET['id'] . '&&lrn=' . $_GET['lrn'] . '&&added_successfully=' . $id . '");
                window.location.reload();
            ';
        echo '</script>';
    }
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

            .table-1 tr:nth-child(even) {
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
                        Student List
                    </h3>
                    <div class="r-50px p-absolute t-54px d-flex-center">
                        <img onclick="showModal('add-new-student', 'New Students')" src="../../assets/img/add.png"
                             alt="" class="logo1 c-hand" width="50" height="50">
                        &nbsp;&nbsp;&nbsp;
                        <svg class="c-hand" onclick="deleteId('student-list')" width="50" height="43" id="svg2"
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

                $search_lrn = isset($_GET['search_lrn']) ? $_GET['search_lrn'] : '';
                $id = $_GET['id'];

                $SqlSelectUser = "select * from users_info ui
                                    left join teachers_info ti on ti.lrn = ui.user_lrn
                                    where ui.id='$id'";
                $ResultSelectUser = mysqli_query($conn, $SqlSelectUser);
                $RowsSelectUser = mysqli_fetch_assoc($ResultSelectUser);
                $userLrn = $RowsSelectUser['user_lrn'];
                $grade = $RowsSelectUser['grade'];

                $sql = "SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level,
                        si.id, 
                        si.lrn, 
                        si.f_name, 
                        si.l_name, 
                        si.b_date, 
                        si.age, 
                        si.gender,
                        si.c_status,
                        si.religion, 
                        si.contact_number, 
                        si.m_name, 
                        si.b_place, 
                        si.nationality, 
                        si.email_address,
                        si.home_address, 
                        si.guardian_name, 
                        CONCAT( ui.last_name ,'', ui.first_name) as addedBy,
                        sei.grade,
                        sei.section
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
						left join users_info ui on ui.id = si.addedBy
                        left join teachers_info ti on ti.lrn = si.teacher_lrn
	                    WHERE CONCAT_WS('', si.f_name,si.l_name) LIKE '%$search_lrn%'
	                    and si.teacher_lrn = '$userLrn'
                        GROUP BY si.id order by  si.lrn DESC Limit 1";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "students".
                $total_pages = $mysqli->query("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level,
                        si.id, 
                        si.lrn, 
                        si.f_name, 
                        si.l_name, 
                        si.b_date, 
                        si.age, 
                        si.gender,
                        si.c_status,
                        si.religion, 
                        si.contact_number, 
                        si.m_name, 
                        si.b_place, 
                        si.nationality, 
                        si.email_address,
                        si.home_address, 
                        si.guardian_name, 
                        CONCAT( ui.last_name ,'', ui.first_name) as addedBy,
                        sei.grade,
                        sei.section
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
						left join users_info ui on ui.id = si.addedBy
                        left join teachers_info ti on ti.lrn = si.teacher_lrn
	                    WHERE CONCAT_WS('', si.f_name,si.l_name) LIKE '%$search_lrn%'
	                      and si.teacher_lrn = '$userLrn'
                        GROUP BY si.id order by  si.lrn DESC")->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level,
                        si.id, 
                        si.lrn, 
                        si.f_name, 
                        si.l_name, 
                        si.b_date, 
                        si.age, 
                        si.gender,
                        si.c_status,
                        si.religion, 
                        si.contact_number, 
                        si.m_name, 
                        si.b_place, 
                        si.nationality, 
                        si.email_address,
                        si.home_address, 
                        si.guardian_name, 
                        CONCAT( ui.last_name ,'', ui.first_name) as addedBy,
                        sei.grade,
                        sei.section
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
						left join users_info ui on ui.id = si.addedBy
                        left join teachers_info ti on ti.lrn = si.teacher_lrn
	                    WHERE CONCAT_WS('', si.f_name,si.l_name) LIKE '%$search_lrn%'
	                      and si.teacher_lrn = '$userLrn'
                        GROUP BY si.id order by  si.lrn DESC LIMIT ?,?")) {
                    // Calculate the page to get the results we need from our table.
                    $calc_page = ($page - 1) * $num_results_on_page;
                    $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                    $stmt->execute();
                    // Get the results...
                    $result = $stmt->get_result();
                    ?>
                    <input placeholder="search lrn" id="search_name" type="text" class="search_lrn m-b-5px"
                           onchange="searchName()"/>
                    <table class="table table-1 b-shadow-dark">
                        <thead>
                        <tr>
                            <th class="t-align-center"><label for="student-list-cb" class="d-flex-center"></label><input
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
                            <th>Section</th>
                            <th>Added By</th>
                            <th class="t-align-center">View Enrollment</th>
                            <th class="t-align-center">View Details</th>
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
                                        <input type="checkbox" class="sc-1-3 c-hand check" id="<?= $row['lrn'] ?>"/>
                                    </label></td>
                                <th scope="row"><?= $i ?> </th>
                                <td><?= $row['lrn'] ?></td>
                                <td><?= $row['f_name'] ?></td>
                                <td><?= $row['l_name'] ?></td>
                                <td><?= $row['b_date'] ?></td>
                                <td><?= $row['age'] ?></td>
                                <td><?= $row['gender'] ?></td>
                                <td><?= $row['grade'] ?></td>
                                <td><?= $row['section'] ?></td>
                                <td><?= $row['addedBy'] ?></td>
                                <td class="t-align-center">
                                    <label for="" class="t-color-blue c-hand f-weight-bold"
                                           onclick="viewStudentEnrollment('<?= $row['lrn'] ?>')"
                                    >
                                        <svg class="c-hand" width="40" height="40"
                                             style="enable-background:new 0 0 512 512;" version="1.1"
                                             viewBox="0 0 512 512" xml:space="preserve"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink"><g
                                                    id="_x34_98_x2C__student__x2C__notes__x2C__note_x2C__education">
                                                <g>
                                                    <path d="M461,93.31v390H61v-200v-190h33.52c-0.03,0.02-0.06,0.04-0.09,0.06h355.24    c0.65,0,1.3-0.02,1.95-0.06H461z"
                                                          style="fill:#F0F1F1;"/>
                                                    <path d="M71.47,294.81v-190h33.52c-0.03,0.02-0.06,0.04-0.09,0.06h355.24c0.287,0,0.573-0.019,0.86-0.026    V93.31h-9.38c-0.65,0.04-1.3,0.06-1.95,0.06H94.43c0.03-0.02,0.06-0.04,0.09-0.06H61v190v200h10.47V294.81z"
                                                          style="fill:#DEE0E0;"/>
                                                    <rect height="30" style="fill:#FF7979;" width="40" x="361"
                                                          y="403.31"/>
                                                    <polygon
                                                            points="367.75,410.685 401,410.685 401,403.31 361,403.31 361,433.31 367.75,433.31   "
                                                            style="fill:#D65B5B;"/>
                                                    <rect height="160" style="fill:#FEBE76;" width="40" x="361"
                                                          y="243.31"/>
                                                    <polygon
                                                            points="368.125,251.56 401,251.56 401,243.31 361,243.31 361,403.31 368.125,403.31   "
                                                            style="fill:#E8A664;"/>
                                                    <polygon points="381,183.31 401,243.31 361,243.31   "
                                                             style="fill:#57606F;"/>
                                                    <polygon
                                                            points="384.958,195.185 381,183.31 361,243.31 368.917,243.31   "
                                                            style="fill:#414A56;"/>
                                                    <path d="M471.9,39.58c5.64,5.76,9.1,13.7,9.1,22.46c0,16.41-12.8,30.26-29.38,31.27H94.52    c10.54-6.46,17.42-18.41,17.42-31.73c0-12.9-6.45-24.42-16.59-31.33h354.32C458.42,30.25,466.26,33.82,471.9,39.58z"
                                                          style="fill:#FFEBBE;"/>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <path d="M105.574,40.583H452.91c8.75,0,16.59,3.57,22.23,9.33c2.217,2.265,4.091,4.87,5.557,7.726       c-0.948-7.002-4.119-13.282-8.797-18.06c-5.641-5.76-13.48-9.33-22.23-9.33H95.35C99.415,33.02,102.873,36.54,105.574,40.583z"
                                                                      style="fill:#E5D0A8;"/>
                                                            </g>
                                                            <g>
                                                                <path d="M115.18,71.914c0-5.857-1.341-11.423-3.742-16.409c0.319,1.983,0.502,4.009,0.502,6.076       c0,13.32-6.88,25.27-17.42,31.73h14.048C112.742,87.231,115.18,79.819,115.18,71.914z"
                                                                      style="fill:#E5D0A8;"/>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <path d="M95.35,30.25c10.14,6.91,16.59,18.43,16.59,31.33c0,13.32-6.88,25.27-17.42,31.73H61v190H31v-210    c0-26.26,20.12-46.75,46.38-44.44L95.35,30.25z"
                                                          style="fill:#F0DCA0;"/>
                                                    <path d="M39,80.643c0-26.26,20.12-46.75,46.38-44.44l17.97,1.38c0.003,0.002,0.007,0.005,0.01,0.008    c-2.295-2.798-4.984-5.278-8.01-7.341l-17.97-1.38C51.12,26.56,31,47.05,31,73.31v210h8V80.643z"
                                                          style="fill:#DDC792;"/>
                                                    <rect height="20" width="20" x="311" y="133.31"/>
                                                    <rect height="20" width="20" x="271" y="133.31"/>
                                                    <rect height="20" width="20" x="231" y="133.31"/>
                                                    <rect height="20" width="20" x="191" y="133.31"/>
                                                    <path d="M461,488.31H61c-2.761,0-5-2.238-5-5v-390c0-2.761,2.239-5,5-5h400c2.762,0,5,2.239,5,5v390    C466,486.071,463.762,488.31,461,488.31z M66,478.31h390v-380H66V478.31z"/>
                                                    <path d="M449.67,98.37H94.43c-2.761,0-5-2.239-5-5s2.239-5,5-5h355.24c0.548,0,1.095-0.017,1.643-0.051    C465.157,87.476,476,75.933,476,62.04c0-7.175-2.725-13.909-7.672-18.962c-4.943-5.048-11.569-7.828-18.658-7.828H95.35    c-2.761,0-5-2.239-5-5s2.239-5,5-5h354.32c9.8,0,18.963,3.847,25.803,10.832C482.262,43.015,486,52.234,486,62.04    c0,19.169-14.968,35.097-34.076,36.261C451.178,98.347,450.419,98.37,449.67,98.37z"/>
                                                    <path d="M61,288.31H31c-2.761,0-5-2.238-5-5v-210c0-14.581,5.807-28.262,15.932-37.535c9.663-8.851,22.406-13.071,35.886-11.886    l17.915,1.375c0.872,0.067,1.71,0.361,2.433,0.854c11.755,8.011,18.774,21.268,18.774,35.462c0,2.761-2.239,5-5,5s-5-2.239-5-5    c0-10.453-4.968-20.246-13.353-26.451l-16.59-1.274c-10.706-0.943-20.741,2.361-28.312,9.294C40.624,50.534,36,61.527,36,73.31    v205h25c2.761,0,5,2.238,5,5S63.761,288.31,61,288.31z"/>
                                                    <path d="M94.483,98.32c-1.613,0-3.198-0.763-4.158-2.191c-1.54-2.292-0.976-5.369,1.316-6.909    c0.058-0.039,0.207-0.137,0.266-0.173c9.272-5.683,15.033-16.208,15.033-27.467c0-2.761,2.239-5,5-5s5,2.239,5,5    c0,14.704-7.581,28.488-19.785,35.979C96.329,98.073,95.401,98.32,94.483,98.32z"/>
                                                    <path d="M321,198.31H101c-2.761,0-5-2.239-5-5s2.239-5,5-5h220c2.762,0,5,2.239,5,5S323.762,198.31,321,198.31z"/>
                                                    <path d="M321,238.31H101c-2.761,0-5-2.239-5-5s2.239-5,5-5h220c2.762,0,5,2.239,5,5S323.762,238.31,321,238.31z"/>
                                                    <path d="M321,278.31H101c-2.761,0-5-2.238-5-5s2.239-5,5-5h220c2.762,0,5,2.238,5,5S323.762,278.31,321,278.31z"/>
                                                    <path d="M321,318.31H101c-2.761,0-5-2.238-5-5s2.239-5,5-5h220c2.762,0,5,2.238,5,5S323.762,318.31,321,318.31z"/>
                                                    <path d="M321,358.31H101c-2.761,0-5-2.238-5-5s2.239-5,5-5h220c2.762,0,5,2.238,5,5S323.762,358.31,321,358.31z"/>
                                                    <path d="M321,398.31H101c-2.761,0-5-2.238-5-5s2.239-5,5-5h220c2.762,0,5,2.238,5,5S323.762,398.31,321,398.31z"/>
                                                    <path d="M321,438.31h-65.18c-2.761,0-5-2.238-5-5s2.239-5,5-5H321c2.762,0,5,2.238,5,5S323.762,438.31,321,438.31z"/>
                                                    <path d="M401,408.31c-2.762,0-5-2.238-5-5v-160c0-2.761,2.238-5,5-5s5,2.239,5,5v160C406,406.071,403.762,408.31,401,408.31z"/>
                                                    <path d="M361,408.31c-2.762,0-5-2.238-5-5v-160c0-2.761,2.238-5,5-5s5,2.239,5,5v160C366,406.071,363.762,408.31,361,408.31z"/>
                                                    <path d="M401,438.31h-40c-2.762,0-5-2.238-5-5v-30c0-2.762,2.238-5,5-5h40c2.762,0,5,2.238,5,5v30    C406,436.071,403.762,438.31,401,438.31z M366,428.31h30v-20h-30V428.31z"/>
                                                    <path d="M401,248.31h-40c-1.607,0-3.116-0.772-4.057-2.077c-0.939-1.304-1.195-2.98-0.687-4.504l20-60    c0.681-2.042,2.591-3.419,4.743-3.419s4.063,1.377,4.743,3.419l20,60c0.509,1.524,0.253,3.201-0.687,4.504    C404.116,247.538,402.607,248.31,401,248.31z M367.938,238.31h26.125L381,199.122L367.938,238.31z"/>
                                                    <path d="M431,408.31h-30c-2.762,0-5-2.238-5-5s2.238-5,5-5h25v-65c0-2.762,2.238-5,5-5s5,2.238,5,5v70    C436,406.071,433.762,408.31,431,408.31z"/>
                                                </g>
                                            </g>
                                            <g id="Layer_1"/></svg>
                                    </label>
                                </td>
                                <td class="t-align-center">
                                    <svg class="c-hand"
                                         onclick="viewStudentInformation('<?= "[" . $row['lrn'] . "?" . $row['f_name'] . "?" . $row['l_name'] . "?" . $row['b_date'] . "?" . $row['age'] . "?" . $row['home_address'] . "?" . $row['guardian_name'] . "?" . $row['g_level'] . "?" . $row['c_status'] . "?" . $row['religion'] . "?" . $row['contact_number'] . "?" . $row['m_name'] . "?" . $row['b_place'] . "?" . $row['nationality'] . "?" . $row['email_address'] . "?" . $row['gender'] . "]" ?>')"
                                         enable-background="new 0 0 300 300" width="40" height="40" id="Layer_1"
                                         version="1.1" viewBox="0 0 300 300" xml:space="preserve"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g>
                                            <path d="M151.123,14.8c-74.557,0-134.998,60.44-134.998,135.002c0,74.554,60.44,134.998,134.998,134.998   c74.559,0,135.002-60.443,135.002-134.998C286.125,75.241,225.682,14.8,151.123,14.8z M151.123,265.714   c-64.013,0-115.911-51.901-115.911-115.912c0-64.018,51.898-115.911,115.911-115.911c64.019,0,115.912,51.894,115.912,115.911   C267.035,213.813,215.142,265.714,151.123,265.714z"
                                                  fill="#D77E47"/>
                                            <circle cx="151.125" cy="149.802" fill="#D77E47" r="104.431"/>
                                            <g>
                                                <path d="M139.156,93.922c3.02-2.791,6.638-4.183,10.829-4.183c4.195,0,7.785,1.392,10.793,4.183    c2.996,2.804,4.496,6.161,4.496,10.091c0,3.931-1.517,7.279-4.54,10.051c-3.021,2.768-6.602,4.151-10.749,4.151    c-4.183,0-7.801-1.379-10.829-4.151c-3.024-2.771-4.536-6.12-4.536-10.051C134.62,100.083,136.132,96.726,139.156,93.922z"
                                                      fill="#FFFFFF"/>
                                                <path d="M176.604,209.861h-49.046v-5.663c1.347-0.1,2.663-0.236,3.962-0.389c1.287-0.16,2.402-0.413,3.329-0.778    c1.665-0.613,2.824-1.516,3.505-2.679c0.666-1.163,1.011-2.699,1.011-4.616v-45.172c0-1.809-0.417-3.393-1.239-4.765    c-0.83-1.38-1.869-2.483-3.108-3.305c-0.926-0.618-2.346-1.215-4.227-1.789c-1.885-0.565-3.609-0.922-5.158-1.083v-5.659    l38.029-2.018l1.163,1.159v61.62c0,1.813,0.393,3.345,1.158,4.616c0.782,1.268,1.89,2.206,3.341,2.835    c1.035,0.466,2.183,0.887,3.413,1.239c1.248,0.365,2.535,0.618,3.883,0.778v5.667H176.604L176.604,209.861z"
                                                      fill="#FFFFFF"/>
                                            </g>
                                        </g></svg>
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
                                                href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
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
            <div id="add-new-student" class="modal-child pad-top-2em pad-bottom-2em d-none">
                <form method="post">
                    <div class="custom-grid-container" tabindex="3">
                        <div class="custom-grid-item">
                            <div class="w-70p m-l-1em">ID Number</div>
                            <input placeholder="<?php echo $lrns1 ?>" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="lrn"
                                   name="lrn"
                                   value="<?php echo $lrns1 ?>">
                            <div class="w-70p m-l-1em">Lastname</div>
                            <input placeholder="Last Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="lastName"
                                   name="lastName"
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
                            <div class="w-70p m-l-1em">Religion</div>
                            <input placeholder="Religion" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="religion"
                                   name="religion"
                                   required>
                            <div class="w-70p m-l-1em">Home</div>
                            <input placeholder="Home Address" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="homeAddress"
                                   name="homeAddress"
                                   required>
                        </div>
                        <div class="custom-grid-item">
                            <div class="w-70p m-l-1em op-0">none</div>
                            <input placeholder="<?php echo $lrn ?>" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px op-0"
                                   disabled="true"
                                   readonly="true"
                                   value="<?php echo $lrn ?>">
                            <div class="w-70p m-l-1em">Firstname</div>
                            <input placeholder="First Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="firstName"
                                   name="firstName"
                                   required>
                            <div class="w-70p m-l-1em">Birth Date</div>
                            <input placeholder="Birth Date" type="date"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="birthDate"
                                   name="birthDate"
                                   required>
                            <div class="w-70p m-l-1em">Age</div>
                            <input placeholder="Age" type="number"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="age"
                                   name="age"
                                   required>
                            <div class="w-70p m-l-1em">Contact</div>
                            <input placeholder="Contact" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="contactNumber"
                                   name="contactNumber"
                                   required>
                            <div class="w-70p m-l-1em">Guardian Name</div>
                            <input placeholder="Guardian Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="guardianName"
                                   name="guardianName"
                                   required>
                        </div>
                        <div class="custom-grid-item">
                            <div class="w-70p m-l-1em op-0">none</div>
                            <input placeholder="<?php echo $lrn ?>" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px op-0"
                                   disabled="true"
                                   readonly="true"
                                   value="<?php echo $lrn ?>">
                            <div class="w-70p m-l-1em">Middlename</div>
                            <input placeholder="Middle Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="middleName"
                                   name="middleName"
                                   required>
                            <div class="w-70p m-l-1em">Birth Place</div>
                            <input placeholder="Birth Place" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="birthPlace"
                                   name="birthPlace"
                                   required>
                            <div class="w-70p m-l-1em">Nationality</div>
                            <input placeholder="Nationality" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="nationality"
                                   name="nationality"
                                   required>
                            <div class="w-70p m-l-1em">Email</div>
                            <input placeholder="Email" type="email"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="emailAddress"
                                   name="emailAddress"
                                   required>
                        </div>
                    </div>
                    <div class="b-top-gray-3px m-1em">
                        <h2> Other Details</h2>
                        <h6>Requirements</h6>
                        <h3>NSO</h3>
                        <h3>REPORT CARD</h3>
                        <h3>CERTIFICATE TRANSFER</h3>
                    </div>
                    <div class="r-50px d-flex-end gap-1em">
                        <button type="submit"
                                class="c-hand btn-success btn"
                                name="add-new-student"
                                style="background-color: #ffffff !important; border-color: #ffffff;">
                            <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
                        </button>
                    </div>
                </form>
            </div>
            <div id="view-student-info" class="modal-child d-none">
                <h4>LRN: <label></label></h4>
                <h4>Firstname: <label></label></h4>
                <h4>Lastname: <label></label></h4>
                <h4>Middle Name: <label></label></h4>
                <h4>Gender: <label></label></h4>
                <h4>Birthdate: <label></label></h4>
                <h4>Birth Place: <label></label></h4>
                <h4>Civil Status: <label></label></h4>
                <h4>Age: <label></label></h4>
                <h4>Nationality: <label></label></h4>
                <h4>Religion: <label></label></h4>
                <h4>Contact Number: <label></label></h4>
                <h4>Email Address: <label></label></h4>
                <h4>Home Address: <label></label></h4>
                <h4>Guardian Name: <label></label></h4>

                <div class="p-absolute btm-1em r-1em">
                    <button class="c-hand btn-primary btn"
                            name="save" onclick="updateStudentInformation()"
                            style="background-color: #757575 !important; border-color: #757575;">
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
                    </button>
                </div>
            </div>
            <div id="update-student-info" class="modal-child d-none">
                <form method="post">
                    <div class="custom-grid-container" tabindex="3">
                        <div class="custom-grid-item">
                            <div class="w-70p m-l-1em">ID Number</div>
                            <input placeholder="<?php echo $lrn ?>" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-lrn"
                                   name="up-lrn"
                                   readonly="true"
                                   value="<?php echo $lrn ?>">
                            <div class="w-70p m-l-1em">Lastname</div>
                            <input placeholder="Last Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-lastName"
                                   name="up-lastName"
                                   required>
                            <div class="w-70p m-l-1em">Gender</div>
                            <select name="up-gender" id="up-gender"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="" disabled selected>Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <div class="w-70p m-l-1em">Civil Status</div>
                            <select name="up-civilStatus" id="up-civilStatus"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="" disabled selected>Civil Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                            </select>
                            <div class="w-70p m-l-1em">Religion</div>
                            <input placeholder="Religion" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-religion"
                                   name="up-religion"
                                   required>
                            <div class="w-70p m-l-1em">Home</div>
                            <input placeholder="Home Address" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-homeAddress"
                                   name="up-homeAddress"
                                   required>
                        </div>
                        <div class="custom-grid-item">
                            <input type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em op-0"
                                   disabled="true"
                                   required="false">
                            <div class="w-70p m-l-1em">Firstname</div>
                            <input placeholder="First Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-firstName"
                                   name="up-firstName"
                                   required>
                            <div class="w-70p m-l-1em">Birth Date</div>
                            <input placeholder="Birth Date" type="date"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-birthDate"
                                   name="up-birthDate"
                                   required>
                            <div class="w-70p m-l-1em">Age</div>
                            <input placeholder="Age" type="number"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-age"
                                   name="up-age"
                                   required>
                            <div class="w-70p m-l-1em">Contact</div>
                            <input placeholder="Contact" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-contactNumber"
                                   name="up-contactNumber"
                                   required>
                            <div class="w-70p m-l-1em">Guardian Name</div>
                            <input placeholder="Guardian Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-guardianName"
                                   name="up-guardianName"
                                   required>
                        </div>
                        <div class="custom-grid-item">
                            <input type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em op-0"
                                   disabled="true"
                                   required="false">
                            <div class="w-70p m-l-1em">Middlename</div>
                            <input placeholder="Middle Name" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-middleName"
                                   name="up-middleName"
                                   required>
                            <div class="w-70p m-l-1em">Birth Place</div>
                            <input placeholder="Birth Place" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-birthPlace"
                                   name="up-birthPlace"
                                   required>
                            <div class="w-70p m-l-1em">Nationality</div>
                            <input placeholder="Nationality" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-nationality"
                                   name="up-nationality"
                                   required>
                            <div class="w-70p m-l-1em">Email</div>
                            <input placeholder="Email" type="email"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-emailAddress"
                                   name="up-emailAddress"
                                   required>
                        </div>
                    </div>
                    <div class="b-top-gray-3px m-1em">
                        <div class="r-50px d-flex-end m-t-1em">
                            <label class="btn bg-hover-gray-dark-v1 m-b-0"
                                   onclick="showModal('view-student-info','Student Information', 'dark')"
                                   style="padding:0; background-color: #ffffff !important; border-color: #ffffff;">
                                <img src="../../assets/img/back.png" alt="" width="60" height="60" class="c-hand">
                            </label>
                            <button type="submit"
                                    class="c-hand btn-success btn"
                                    name="update-student-info"
                                    style="background-color: #ffffff !important; border-color: #ffffff;">
                                <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="view-student-enrollment" class="modal-child pad-bottom-2em d-none">
                <div class="d-flex-end m-b-1em">
                    <img onclick="showModal('add-enrollment', 'New Enrollment')" src="../../assets/img/add.png" alt=""
                         class="logo1 c-hand" width="50" height="50">
                    &nbsp;&nbsp;&nbsp;
                    <svg class="c-hand" onclick="deleteId('student-enrollment')" width="50" height="43" id="svg2"
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
                <?php
                if (isset($_GET['lrn'])) {
                    $lrns = $_GET['lrn'];
                    $id = $_GET['id'];
                    echo "<script>showModal('view-student-enrollment', 'Student Enrollment')</script>";

                    $sqlSelectUser = "select * from users_info ui
                                        left join teachers_info ti on ti.lrn = ui.user_lrn where ui.id='$id'";
                    $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                    $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                    $grade = $rowSelectUser['grade'];

                    $sql = "select si.l_name, si.f_name, si.m_name, sei.grade,sei.section, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si 
                            inner join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn = '$lrns' and sei.grade = '$grade'
                            GROUP BY sei.grade order by sei.id ASC";
                    $students_enrollment_info_result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($students_enrollment_info_result);
                    $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                    $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                    // Get the total number of records from our table "students".
                    $total_pages = $mysqli->query("select si.l_name, si.f_name, si.m_name, sei.grade,sei.section, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si 
                            inner join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn = '$lrns' and sei.grade = '$grade'
                            GROUP BY sei.grade order by sei.id ASC")->num_rows;
                    //  Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                    $page = isset($_GET['page_enrollment']) && is_numeric($_GET['page_enrollment']) ? $_GET['page_enrollment'] : 1;

                    // Number of results to show on each page.
                    $num_results_on_page = 5;

                    if ($stmt = $mysqli->prepare("select si.l_name, si.f_name, si.m_name, sei.grade,sei.section, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si 
                            inner join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn = '$lrns' and sei.grade = '$grade'
                            GROUP BY sei.grade order by sei.id ASC LIMIT ?,?")) {
                        //    Calculate the page to get the results we need from our table.
                        $calc_page = ($page - 1) * $num_results_on_page;
                        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                        $stmt->execute();
                        // Get the results...
                        $students_enrollment_info_result = $stmt->get_result();
                        ?>

                        <table class="table table-1">
                            <thead>
                            <tr>
                                <th class="t-align-center"><label for="student-enrollment-cb"
                                                                  class="d-flex-center"></label><input
                                            id="student-enrollment-cb" type="checkbox"
                                            onclick="checkCBStudents('student-enrollment','student-enrollment-cb')"
                                            class="sc-1-3 c-hand"/></th>
                                <th>No</th>
                                <th>Grade</th>
                                <th>Section</th>
                                <th>School Year</th>
                                <th>Date Enrolled</th>
                                <th>Status</th>
                                <th class="t-align-center">View Grade</th>
                                <th class="t-align-center">Add Grade</th>
                            </tr>
                            </thead>
                            <tbody id="student-enrollment">
                            <?php
                            $i = 0;
                            while ($row = $students_enrollment_info_result->fetch_assoc()):
                                $i++;
                                ?>
                                <tr>
                                    <td class="d-flex-center"><label>
                                            <input type="checkbox" class="sc-1-3 c-hand check" id="<?= $row['id'] ?>"/>
                                        </label></td>
                                    <th scope="row"><?= $i ?> </th>
                                    <th scope="row"><?= $row['grade'] ?> </th>
                                    <th scope="row"><?= $row['section'] ?> </th>
                                    <td><?= $row['school_year'] ?> - <?= $row['school_year'] + 1 ?></td>
                                    <td><?= $row['date_enrolled'] ?></td>
                                    <td><?= $row['status'] ?></td>
                                    <td class="t-align-center">
                                        <svg onclick="showGrade('<?= $row['f_name'] ?>','<?= $row['l_name'] ?>','<?= $row['m_name'] ?>','<?= $row['grade'] ?>', '<?= $row['school_year'] ?>')"
                                             class="c-hand" width="40" height="40" id="object" viewBox="0 0 32 32"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <style>.cls-1 {
                                                        fill: #ffd599;
                                                    }

                                                    .cls-2 {
                                                        fill: #6d6daa;
                                                    }</style>
                                            </defs>
                                            <title/>
                                            <path class="cls-1"
                                                  d="M16,3.36a18.63,18.63,0,0,0-14,0V30a18.63,18.63,0,0,1,14,0,18.63,18.63,0,0,1,14,0V3.36A18.63,18.63,0,0,0,16,3.36Z"/>
                                            <path d="M30,31a1,1,0,0,1-.38-.07,17.63,17.63,0,0,0-13.24,0,1.1,1.1,0,0,1-.76,0,17.63,17.63,0,0,0-13.24,0A1,1,0,0,1,1,30V3.36a1,1,0,0,1,.62-.93A19.72,19.72,0,0,1,16,2.28a19.72,19.72,0,0,1,14.38.15,1,1,0,0,1,.62.93V30a1,1,0,0,1-1,1ZM9,27.64a19.76,19.76,0,0,1,7,1.28,19.78,19.78,0,0,1,13-.35V4.05a17.6,17.6,0,0,0-12.62.24,1.1,1.1,0,0,1-.76,0A17.66,17.66,0,0,0,3,4.05V28.57A19.73,19.73,0,0,1,9,27.64Z"/>
                                            <path class="cls-2"
                                                  d="M16,31a1,1,0,0,1-1-1V3.37a1,1,0,0,1,2,0V30A1,1,0,0,1,16,31Z"/>
                                        </svg>
                                    </td>
                                    <td class="t-align-center">
                                        <svg width="40" height="40" class="c-hand"
                                             onclick="addGrade('<?= $row['f_name'] ?>','<?= $row['l_name'] ?>','<?= $row['m_name'] ?>','<?= $row['grade'] ?>', '<?= $row['school_year'] ?>')"
                                             style="enable-background:new 0 0 512 512;" version="1.1"
                                             viewBox="0 0 512 512" xml:space="preserve"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink"><g
                                                    id="_x35_15_x2C__best_grade_x2C__achieve_x2C__education">
                                                <g>
                                                    <polygon points="131,386 131,446 41,416   " style="fill:#57606F;"/>
                                                    <polygon points="41,416 55.25,420.75 131,395.5 131,386   "
                                                             style="fill:#414A56;"/>
                                                    <path d="M441,386c16.57,0,30,13.43,30,30s-13.43,30-30,30h-30v-60H441z"
                                                          style="fill:#FF7979;"/>
                                                    <path d="M418.75,394.166h30c6.532,0,12.571,2.094,17.497,5.637C460.911,391.503,451.602,386,441,386h-30v60    h7.75V394.166z"
                                                          style="fill:#D65B5B;"/>
                                                    <path d="M411,386v60H131v-60H411z M271,426v-20h-20v20H271z M241,426v-20h-20v20H241z M211,426v-20h-20v20    H211z"
                                                          style="fill:#D7DEED;"/>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <polygon
                                                                        points="139.25,395 411,395 411,386 131,386 131,446 139.25,446      "
                                                                        style="fill:#AFB9D2;"/>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <path d="M101,296v50H76c-24.85,0-45-20.15-45-45V111c0-24.85,20.15-45,45-45h25V296z"
                                                          style="fill:#57606F;"/>
                                                    <path d="M40,309.75v-190c0-24.85,20.15-45,45-45h16V66H76c-24.85,0-45,20.15-45,45v190    c0,14.922,7.271,28.142,18.456,36.329C43.535,329.71,40,320.145,40,309.75z"
                                                          style="fill:#414A56;"/>
                                                    <path d="M481,111v190c0,24.85-20.15,45-45,45h-25V66h25C460.85,66,481,86.15,481,111z"
                                                          style="fill:#57606F;"/>
                                                    <path d="M420.166,76.167h25c10.79,0,20.69,3.803,28.443,10.135C465.563,74.077,451.727,66,436,66h-25v280    h9.166V76.167z"
                                                          style="fill:#414A56;"/>
                                                    <path d="M101,296V66h310v280H101V296z" style="fill:#E8F9F9;"/>
                                                    <polygon
                                                            points="111.5,307.5 111.5,77.5 411,77.5 411,66 101,66 101,296 101,346 111.5,346   "
                                                            style="fill:#CCE5E4;"/>
                                                    <path d="M256,106c-41.42,0-75,33.58-75,75s33.58,75,75,75s75-33.58,75-75S297.42,106,256,106z"
                                                          style="fill:#FEBE76;"/>
                                                    <path d="M188.5,190.42c0-41.42,33.58-75,75-75c21.635,0,41.118,9.174,54.807,23.829    C304.845,119.198,281.965,106,256,106c-41.42,0-75,33.58-75,75c0,19.785,7.675,37.769,20.193,51.171    C193.18,220.236,188.5,205.875,188.5,190.42z"
                                                          style="fill:#E8A664;"/>
                                                    <polygon
                                                            points="298.55,172.12 298.55,181.06 289.78,181.06 289.78,190.42 279.47,190.42 279.47,181.06 270.7,181.06     270.7,172.12 279.47,172.12 279.47,162.75 289.78,162.75 289.78,172.12   "/>
                                                    <rect height="20" width="20" x="251" y="406"/>
                                                    <path d="M243.35,147.18l23,58.82h-12.1l-5.62-14.66H227.5L221.97,206h-12.1l23.09-58.82H243.35z M246.5,183.3l-8.35-23.11    l-8.69,23.11H246.5z"/>
                                                    <rect height="20" width="20" x="221" y="406"/>
                                                    <rect height="20" width="20" x="191" y="406"/>
                                                    <path d="M411,451H131c-2.761,0-5-2.238-5-5s2.239-5,5-5h280c2.762,0,5,2.238,5,5S413.762,451,411,451z"/>
                                                    <path d="M411,391H131c-2.761,0-5-2.238-5-5s2.239-5,5-5h280c2.762,0,5,2.238,5,5S413.762,391,411,391z"/>
                                                    <path d="M441,451h-30c-2.762,0-5-2.238-5-5v-60c0-2.762,2.238-5,5-5h30c19.299,0,35,15.701,35,35S460.299,451,441,451z M416,441    h25c13.785,0,25-11.215,25-25s-11.215-25-25-25h-25V441z"/>
                                                    <path d="M131,451c-0.531,0-1.064-0.084-1.581-0.257l-90-30C37.377,420.063,36,418.152,36,416s1.377-4.063,3.419-4.743l90-30    c1.525-0.507,3.201-0.252,4.504,0.687c1.304,0.94,2.077,2.449,2.077,4.057v60c0,1.607-0.772,3.116-2.077,4.057    C133.062,450.678,132.036,451,131,451z M56.812,416L126,439.063v-46.125L56.812,416z"/>
                                                    <path d="M101,351H76c-27.57,0-50-22.43-50-50V111c0-27.57,22.43-50,50-50h25c2.761,0,5,2.239,5,5s-2.239,5-5,5H76    c-22.056,0-40,17.944-40,40v190c0,22.056,17.944,40,40,40h25c2.761,0,5,2.238,5,5S103.761,351,101,351z"/>
                                                    <path d="M436,351h-25c-2.762,0-5-2.238-5-5s2.238-5,5-5h25c22.056,0,40-17.944,40-40V111c0-22.056-17.944-40-40-40h-25    c-2.762,0-5-2.239-5-5s2.238-5,5-5h25c27.57,0,50,22.43,50,50v190C486,328.57,463.57,351,436,351z"/>
                                                    <path d="M411,351H101c-2.761,0-5-2.238-5-5V66c0-2.761,2.239-5,5-5h310c2.762,0,5,2.239,5,5v280C416,348.762,413.762,351,411,351z     M106,341h300V71H106V341z"/>
                                                    <path d="M321,301H101c-2.761,0-5-2.238-5-5s2.239-5,5-5h220c2.762,0,5,2.238,5,5S323.762,301,321,301z"/>
                                                    <path d="M256,261c-44.112,0-80-35.888-80-80s35.888-80,80-80s80,35.888,80,80S300.112,261,256,261z M256,111    c-38.598,0-70,31.402-70,70s31.402,70,70,70c38.598,0,70-31.402,70-70S294.598,111,256,111z"/>
                                                </g>
                                            </g>
                                            <g id="Layer_1"/></svg>
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
                                                href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/teachers_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <div id="add-enrollment" class="modal-child d-none">
                <form method="post">
                    <div class="custom-grid-container" tabindex="3">
                        <div class="custom-grid-item">
                            <input placeholder="<?php echo $_GET['lrn'] ?>" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="add-enrollment-lrn"
                                   name="add-enrollment-lrn"
                                   readonly="true"
                                   value="<?php echo $_GET['lrn'] ?>">
                            <div class="w-70p m-l-1em">Grade</div>
                            <select name="add-enrollment-grade" id="add-enrollment-grade"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" onchange="selectGrade()">
                                <?php
                                $id = isset($_GET['id']) ? $_GET['id'] : '';

                                $sqlSelectUserInfo = "select * from users_info where id = '$id'";
                                $result = mysqli_query($conn, $sqlSelectUserInfo);
                                $row = mysqli_fetch_assoc($result);
                                $teacherId = $row['user_lrn'];

                                $sqlSelectTeachersInfo = "select * from teachers_info where lrn = '$teacherId'";
                                $result = mysqli_query($conn, $sqlSelectTeachersInfo);
                                $row = mysqli_fetch_assoc($result);
                                $teacherGrade = $row['grade'];
                                ?>
                                <option value="<?php echo $teacherGrade ?>">
                                    Grade <?php echo $teacherGrade ?></option>
                                <?php
                                ?>
                            </select>
                            <div class="w-70p m-l-1em">Section</div>
                            <select name="add-enrollment-section" id="add-enrollment-section"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <?php
                                $id = isset($_GET['id']) ? $_GET['id'] : '';

                                $sqlSelectUserInfo = "select * from users_info where id = '$id'";
                                $result = mysqli_query($conn, $sqlSelectUserInfo);
                                $row = mysqli_fetch_assoc($result);
                                $teacherId = $row['user_lrn'];

                                $sqlSelectTeachersInfo = "select * from teachers_info where lrn = '$teacherId'";
                                $result = mysqli_query($conn, $sqlSelectTeachersInfo);
                                $row = mysqli_fetch_assoc($result);
                                $teacherSection = $row['section'];
                                ?>
                                <option value="<?php echo $teacherSection ?>">
                                    <?php echo $teacherSection ?></option>
                                <?php
                                ?>
                            </select>


                            <!--                            --><?php
                            //                                $sql = "select * from grade_info";
                            //                                $result = mysqli_query($conn, $sql);
                            //                                if (mysqli_num_rows($result)) { ?>
                            <!--                                    <select name="add-enrollment-grade" id="add-enrollment-grade"  class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">-->
                            <!--                                        <option value=""></option>-->
                            <!--                                        --><?php
                            //                                        $i = 0;
                            //                                        while ($rows = mysqli_fetch_assoc($result)) {
                            //                                            $i++;
                            //                                            ?>
                            <!--                                                <option value="-->
                            <?php //= $rows['grade'] ?><!--">--><?php //= $rows['grade'] ?><!--</option>-->
                            <!--                                        --><?php //} ?>
                            <!--                                    </select>-->
                            <!--                                --><?php //}
                            //                             ?>

                            <div class="w-70p m-l-1em">School Year</div>
                            <input type="number" min="1900" max="2099" step="1" value="2016"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="add-enrollment-school-year"
                                   name="add-enrollment-school-year"
                                   required/>
                            <div class="w-70p m-l-1em">Date Enrolled</div>
                            <input placeholder="Date Enrolled" type="date"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="add-enrollment-date-enrolled"
                                   name="add-enrollment-date-enrolled"
                                   required>
                            <div class="w-70p m-l-1em">Status</div>
                            <select name="add-enrollment-status" id="add-enrollment-status"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="" disabled selected>Status</option>
                                <option value="continuing">continuing</option>
                                <option value="transferee">transferee</option>
                                <option value="new">new</option>
                            </select>
                        </div>
                    </div>
                    <div class="b-top-gray-3px p-absolute btm-1em w-98p">
                        <div class="r-50px d-flex-end m-t-1em">
                            <label class="btn bg-hover-gray-dark-v1 m-b-0"
                                   onclick="showModal('view-student-enrollment','Student Enrollment')"
                                   style="padding:0; background-color: #ffffff !important; border-color: #ffffff;">
                                <img src="../../assets/img/back.png" alt="" width="60" height="60" class="c-hand">
                            </label>
                            <button type="submit"
                                    class="c-hand btn-success btn"
                                    name="add-enrollment"
                                    style="background-color: #ffffff !important; border-color: #ffffff;">
                                <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="view-student-grade" class="modal-child d-none">

                <?php

                $lrn = isset($_GET['lrn']) ? $_GET['lrn'] : '';
                $id = isset($_GET['id']) ? $_GET['id'] : '';

                $sqlSelectUser = "select * from users_info ui
                                        left join teachers_info ti on ti.lrn = ui.user_lrn where ui.id='$id'";
                $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                $grade = $rowSelectUser['grade'];

                $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$lrn' and sei.grade = '$grade'
                                            group by si.lrn";
                $sqlStudents = mysqli_query($conn, $sqlStudents);
                while ($rowStudent = mysqli_fetch_assoc($sqlStudents)) {
                    ?>
                    <input type="hidden" name="grade" value="<?= $rowStudent['grade'] ?>">
                    <div>Student Name: <label
                                class="b-bottom-gray-3px w-27em t-align-center"><?= $rowStudent['l_name'] ?>
                            , <?= $rowStudent['f_name'] ?> <?= $rowStudent['m_name'] ?></label></div>
                    <div>School Name:<input type="text"
                                            class="w-27em b-bottom-gray-3px b-none t-align-center"
                                            value="<?php echo $schoolName ?>"></div>
                    <div>
                        <div class="d-inline-flex">Grade & Section: <label for=""
                                                                           class="b-bottom-gray-3px w-10em t-align-center">Grade <?= $rowStudent['grade'] ?> <?= $rowStudent['section'] ?></label>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="d-inline-flex">School Year:
                            <label for=""
                                   class="b-bottom-gray-3px w-10em t-align-center">&nbsp; <?= $rowStudent['school_year'] ?>
                                - <?= (1 + $rowStudent['school_year']) ?> </label></div>
                    </div>

                <?php } ?>
                <div>

                    <table class="table-bordered w-100p m-t-2em">
                        <col>
                        <col>
                        <col>
                        <colgroup span="4"></colgroup>
                        <col>
                        <tr>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;" class="b-bottom-none">
                                Learning Area
                                <!--                                jay-->
                            </th>
                            <th colspan="4" style="text-align:center;" class="b-bottom-none">Quarter</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;" class="b-bottom-none">
                                Final Grade
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="b-top-none b-none t-align-center">1</th>
                            <th scope="col" class="b-top-none b-none t-align-center">2</th>
                            <th scope="col" class="b-top-none b-none t-align-center">3</th>
                            <th scope="col" class="b-top-none b-none t-align-center">4</th>
                        </tr>
                        <?php

                        $studentLrn = $_GET['lrn'];
                        $id = isset($_GET['id']) ? $_GET['id'] : '';

                        $sqlSelectUser = "select * from users_info ui
                                        left join teachers_info ti on ti.lrn = ui.user_lrn where ui.id='$id'";
                        $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                        $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                        $grade = $rowSelectUser['grade'];

                        $sqlUser = "select * from students_grade_info where student_lrn='$studentLrn' and grade='$grade'";
                        $resultUsers = mysqli_query($conn, $sqlUser);
                        while ($rowUser = mysqli_fetch_assoc($resultUsers)) {
                            ?>
                            <tr>
                                <td class="t-align-center"> <?= $rowUser['subject'] ?></td>
                                <td class="t-align-center"> <?= $rowUser['first_grade'] ?></td>
                                <td class="t-align-center"> <?= $rowUser['second_grade'] ?></td>
                                <td class="t-align-center"> <?= $rowUser['third_grade'] ?></td>
                                <td class="t-align-center"> <?= $rowUser['fourth_grade'] ?></td>
                                <td class="t-align-center"> <?= $rowUser['final'] ?></td>
                            </tr>
                        <?php } ?>
                    </table>

                    <?php

                    $lrn = isset($_GET['lrn']) ? $_GET['lrn'] : '';
                    $id = isset($_GET['id']) ? $_GET['id'] : '';

                    $sqlSelectUser = "select * from users_info ui
                                        left join teachers_info ti on ti.lrn = ui.user_lrn where ui.id='$id'";
                    $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                    $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                    $grade = $rowSelectUser['grade'];

                    $sqlSelectStudentGradeAverage = "select * from students_grade_average_info where students_lrn='$lrn' and grade='$grade'";
                    $sqlStudentsGrade = mysqli_query($conn, $sqlSelectStudentGradeAverage);
                    while ($rowStudent = mysqli_fetch_assoc($sqlStudentsGrade)) {
                        ?>
                        <br>
                        <div>Average: <?= $rowStudent['average'] ?></div>
                    <?php } ?>

                    <table class="table-bordered w-100p m-t-2em">
                        <col>
                        <col>
                        <col>
                        <colgroup span="4"></colgroup>
                        <col>
                        <tr>
                            <th rowspan="1" class="t-align-center">Months</th>
                            <th colspan="1" class="t-align-center">Jun</th>
                            <th rowspan="1" class="t-align-center">July</th>
                            <th rowspan="1" class="t-align-center">Aug</th>
                            <th rowspan="1" class="t-align-center">Sep</th>
                            <th rowspan="1" class="t-align-center">Oct</th>
                            <th rowspan="1" class="t-align-center">Nov</th>
                            <th rowspan="1" class="t-align-center">Dec</th>
                            <th rowspan="1" class="t-align-center">Jan</th>
                            <th rowspan="1" class="t-align-center">Feb</th>
                            <th rowspan="1" class="t-align-center">Mar</th>
                            <th rowspan="1" class="t-align-center">Apr</th>
                            <th rowspan="1" class="t-align-center">May</th>
                            <th rowspan="1" class="t-align-center">Total</th>
                        </tr>
                        <?php
                        $id = $_GET['id'];
                        $studentLrn = $_GET['lrn'];

                        $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$studentLrn'
                                            group by si.lrn";
                        $sqlStudents = mysqli_query($conn, $sqlStudents);
                        $row = mysqli_fetch_assoc($sqlStudents);
                        $grade = $row['grade'];
                        $teacherLrn = $row['teacher_lrn'];

                        $id = isset($_GET['id']) ? $_GET['id'] : '';

                        $sqlSelectUser = "select * from users_info ui
                                        left join teachers_info ti on ti.lrn = ui.user_lrn where ui.id='$id'";
                        $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                        $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                        $tGrade = $rowSelectUser['grade'];

                        $sqlUser = "select * from students_grade_attendance_info where student_lrn='$studentLrn' and teacher_lrn='$teacherLrn' and grade='$tGrade'";
                        $resultUsers = mysqli_query($conn, $sqlUser);
                        while ($rowUser = mysqli_fetch_assoc($resultUsers)) {
                            ?>
                            <tr>
                                <th>Days of School</th>
                                <th class="t-align-center"><?= $rowUser['june_days_classes'] ? $rowUser['june_days_classes'] : ''  ?></th>
                                <th class="t-align-center"><?= $rowUser['july_days_classes'] ?  $rowUser['july_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['aug_days_classes']  ?  $rowUser['aug_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['sep_days_classes']  ?  $rowUser['sep_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['oct_days_classes']  ?  $rowUser['oct_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['nov_days_classes']  ?  $rowUser['nov_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['dec_days_classes']  ?  $rowUser['dec_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['jan_days_classes']  ?  $rowUser['jan_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['feb_days_classes']  ?  $rowUser['feb_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['mar_days_classes']  ?  $rowUser['mar_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['apr_days_classes']  ?  $rowUser['apr_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['may_days_classes']  ?  $rowUser['may_days_classes'] : '' ?></th>
                                <th class="t-align-center"><?= ($rowUser['june_days_classes'] + $rowUser['july_days_classes'] + $rowUser['aug_days_classes'] + $rowUser['sep_days_classes'] + $rowUser['oct_days_classes'] + $rowUser['nov_days_classes'] + $rowUser['dec_days_classes'] + $rowUser['jan_days_classes'] + $rowUser['feb_days_classes'] + $rowUser['mar_days_classes'] + $rowUser['apr_days_classes'] + $rowUser['may_days_classes']) ? $rowUser['june_days_classes'] + $rowUser['july_days_classes'] + $rowUser['aug_days_classes'] + $rowUser['sep_days_classes'] + $rowUser['oct_days_classes'] + $rowUser['nov_days_classes'] + $rowUser['dec_days_classes'] + $rowUser['jan_days_classes'] + $rowUser['feb_days_classes'] + $rowUser['mar_days_classes'] + $rowUser['apr_days_classes'] + $rowUser['may_days_classes'] : '' ?></th>
                            </tr>
                            <tr>
                                <th>Days Present</th>
                                <th class="t-align-center"><?= $rowUser['june_days_presents'] ?  $rowUser['june_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['july_days_presents'] ? $rowUser['july_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['aug_days_presents'] ? $rowUser['aug_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['sep_days_presents'] ? $rowUser['sep_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['oct_days_presents'] ? $rowUser['oct_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['nov_days_presents'] ? $rowUser['nov_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['dec_days_presents'] ? $rowUser['dec_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['jan_days_presents'] ? $rowUser['jan_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['feb_days_presents'] ? $rowUser['feb_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['mar_days_presents'] ? $rowUser['mar_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['apr_days_presents'] ? $rowUser['apr_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= $rowUser['may_days_presents'] ? $rowUser['may_days_presents'] : '' ?></th>
                                <th class="t-align-center"><?= ($rowUser['june_days_presents'] + $rowUser['july_days_presents'] + $rowUser['aug_days_presents'] + $rowUser['sep_days_presents'] + $rowUser['oct_days_presents'] + $rowUser['nov_days_presents'] + $rowUser['dec_days_presents'] + $rowUser['jan_days_presents'] + $rowUser['feb_days_presents'] + $rowUser['mar_days_presents'] + $rowUser['apr_days_presents'] + $rowUser['may_days_presents']) ? $rowUser['june_days_presents'] + $rowUser['july_days_presents'] + $rowUser['aug_days_presents'] + $rowUser['sep_days_presents'] + $rowUser['oct_days_presents'] + $rowUser['nov_days_presents'] + $rowUser['dec_days_presents'] + $rowUser['jan_days_presents'] + $rowUser['feb_days_presents'] + $rowUser['mar_days_presents'] + $rowUser['apr_days_presents'] + $rowUser['may_days_presents'] : '' ?></th>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
                <div class="p-absolute btm-1em r-1em action-button d-flex-center" id="print-settings">
                    <label class="btn bg-hover-gray-dark-v1 m-b-0"
                           onclick="backModal('view-student-enrollment', 'Student Enrollment','white')"
                           style="padding:0;background-color: #ffffff !important; border-color: #ffffff;">
                        <img src="../../assets/img/back.png" alt="" width="60" height="60" class="c-hand">
                    </label> &nbsp;&nbsp;
                    <svg class="c-hand" onclick="print('view-student-grade')" width="50" height="50" data-name="Layer 1"
                         id="Layer_1" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <style>.cls-1 {
                                    fill: #40bdff;
                                }

                                .cls-2 {
                                    fill: #eb5639;
                                }

                                .cls-3 {
                                    fill: #d84936;
                                }

                                .cls-4 {
                                    fill: #effafe;
                                }

                                .cls-5 {
                                    fill: #2197f7;
                                }

                                .cls-6 {
                                    fill: #e4ebed;
                                }

                                .cls-7 {
                                    fill: #263238;
                                }

                                .cls-8 {
                                    fill: #fd0;
                                }</style>
                        </defs>
                        <title/>
                        <path class="cls-1"
                              d="M236,84.62H25a8,8,0,0,0-8,8v70.77a8,8,0,0,0,8,8H51.87V145.13H39.5a2,2,0,0,1,0-4h177a2,2,0,0,1,0,4H204.13v26.26H236a8,8,0,0,0,8-8V92.62A8,8,0,0,0,236,84.62ZM85.51,102.18a2,2,0,0,1-2,2H68.38a2,2,0,0,1,0-4H83.51A2,2,0,0,1,85.51,102.18Zm-30.26,0a9.57,9.57,0,0,1-9.56,9.56H30.56a9.56,9.56,0,0,1,0-19.13H45.69A9.58,9.58,0,0,1,55.26,102.18Z"/>
                        <path class="cls-2"
                              d="M45.69,96.62H30.56a5.56,5.56,0,0,0,0,11.13H45.69a5.56,5.56,0,0,0,0-11.13Z"/>
                        <path class="cls-3" d="M45.69,96.62h-3a5.56,5.56,0,0,1,0,11.13h3a5.56,5.56,0,0,0,0-11.13Z"/>
                        <path class="cls-4"
                              d="M68.87,231.9H192.13a8,8,0,0,0,8-8V145.13H60.87V223.9A8,8,0,0,0,68.87,231.9Zm1.64-15.13h115a2,2,0,0,1,0,4h-115a2,2,0,0,1,0-4Zm115-11.13h-115a2,2,0,0,1,0-4h115a2,2,0,0,1,0,4Zm0-15.13h-115a2,2,0,0,1,0-4h115a2,2,0,0,1,0,4Zm0-15.13h-115a2,2,0,0,1,0-4h115a2,2,0,0,1,0,4Zm0-15.13h-115a2,2,0,0,1,0-4h115a2,2,0,0,1,0,4Z"/>
                        <path class="cls-4" d="M200.13,32.1a8,8,0,0,0-8-8H68.87a8,8,0,0,0-8,8V80.62H200.13Z"/>
                        <path class="cls-5"
                              d="M236,84.62h-5a8,8,0,0,1,8,8v70.77a8,8,0,0,1-8,8h5a8,8,0,0,0,8-8V92.62A8,8,0,0,0,236,84.62Z"/>
                        <path class="cls-6" d="M195.13,145.13V223.9a8,8,0,0,1-8,8h5a8,8,0,0,0,8-8V145.13Z"/>
                        <path class="cls-6" d="M192.13,24.1h-5a8,8,0,0,1,8,8V80.62h5V32.1A8,8,0,0,0,192.13,24.1Z"/>
                        <path class="cls-7"
                              d="M236,80.12H204.63v-48a12.51,12.51,0,0,0-12.5-12.5H63.87a12.51,12.51,0,0,0-12.5,12.5v48H20A12.51,12.51,0,0,0,7.5,92.62v70.77A12.51,12.51,0,0,0,20,175.88H51.37v48a12.51,12.51,0,0,0,12.5,12.5H192.13a12.51,12.51,0,0,0,12.5-12.5v-48H236a12.51,12.51,0,0,0,12.5-12.5V92.62A12.51,12.51,0,0,0,236,80.12ZM56.37,32.1a7.51,7.51,0,0,1,7.5-7.5H192.13a7.51,7.51,0,0,1,7.5,7.5v48H56.37ZM199.63,173.38V223.9a7.51,7.51,0,0,1-7.5,7.5H63.87a7.51,7.51,0,0,1-7.5-7.5V145.63H199.63Zm43.87-10a7.51,7.51,0,0,1-7.5,7.5H204.63V145.63H216.5a2.5,2.5,0,0,0,0-5H39.5a2.5,2.5,0,0,0,0,5H51.37v25.26H20a7.51,7.51,0,0,1-7.5-7.5V92.62a7.51,7.51,0,0,1,7.5-7.5H236a7.51,7.51,0,0,1,7.5,7.5Z"/>
                        <path class="cls-7"
                              d="M45.69,92.12H30.56a10.06,10.06,0,0,0,0,20.13H45.69a10.06,10.06,0,0,0,0-20.13Zm0,15.13H30.56a5.06,5.06,0,0,1,0-10.13H45.69a5.06,5.06,0,0,1,0,10.13Z"/>
                        <path class="cls-7" d="M83.51,99.68H68.38a2.5,2.5,0,0,0,0,5H83.51a2.5,2.5,0,0,0,0-5Z"/>
                        <path class="cls-7" d="M70.51,160.76h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-7" d="M70.51,175.88h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-7" d="M70.51,191h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-7" d="M70.51,206.14h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-7" d="M70.51,221.27h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-8"
                              d="M23,196H19v-4a1,1,0,0,0-2,0v4H13a1,1,0,0,0,0,2h4v4a1,1,0,0,0,2,0v-4h4a1,1,0,0,0,0-2Z"/>
                        <path class="cls-8"
                              d="M233,188.25h-4v-4a1,1,0,0,0-2,0v4h-4a1,1,0,0,0,0,2h4v4a1,1,0,0,0,2,0v-4h4a1,1,0,0,0,0-2Z"/>
                        <path class="cls-1"
                              d="M26.32,28h-4V24a1,1,0,0,0-2,0v4h-4a1,1,0,0,0,0,2h4v4a1,1,0,0,0,2,0V30h4a1,1,0,0,0,0-2Z"/>
                        <path class="cls-1"
                              d="M220,31.6a6,6,0,1,1,6-6A6,6,0,0,1,220,31.6Zm0-10a4,4,0,1,0,4,4A4,4,0,0,0,220,21.6Z"/>
                        <path class="cls-1"
                              d="M38.13,241a6,6,0,1,1,6-6A6,6,0,0,1,38.13,241Zm0-10a4,4,0,1,0,4,4A4,4,0,0,0,38.13,231Z"/>
                        <path class="cls-8"
                              d="M227.72,72.75a6,6,0,1,1,6-6A6,6,0,0,1,227.72,72.75Zm0-10a4,4,0,1,0,4,4A4,4,0,0,0,227.72,62.75Z"/>
                        <path class="cls-1"
                              d="M228.33,228.5h-2.59l1.83-1.83a1,1,0,0,0-1.41-1.41l-1.83,1.83V224.5a1,1,0,0,0-2,0v2.59l-1.83-1.83a1,1,0,0,0-1.41,1.41l1.83,1.83h-2.59a1,1,0,0,0,0,2h2.59l-1.83,1.83a1,1,0,0,0,1.41,1.41l1.83-1.83v2.59a1,1,0,0,0,2,0v-2.59l1.83,1.83a1,1,0,0,0,1.41-1.41l-1.83-1.83h2.59a1,1,0,0,0,0-2Z"/>
                    </svg>
                </div>

            </div>
            <div id="add-student-subject" class="modal-child d-none">
                <div>

                    <table class="table-bordered w-100p m-t-2em">
                        <col>
                        <col>
                        <col>
                        <colgroup span="4"></colgroup>
                        <col>
                        <tr>
                            <th rowspan="1">No.</th>
                            <th colspan="1">Subject Name</th>
                            <th rowspan="1">Description</th>
                            <th rowspan="1">Year & Section</th>
                            <th rowspan="1">Status</th>
                        </tr>
                        <tr>
                            <th scope="col">1</th>
                            <th scope="col">SCIENCE</th>
                            <th scope="col">MIND BLOWING</th>
                            <th scope="col">G5</th>
                            <th scope="col">AVALABLE</th>
                        </tr>
                        <tr>
                            <th scope="col">2</th>
                            <th scope="col">MATH</th>
                            <th scope="col">&nbsp;THE WORLDS OF</th>
                            <th scope="col">G5</th>
                            <th scope="col">AVALABLE</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">&nbsp;</th>
                        </tr>


                    </table>

                </div>
                <div class="p-absolute btm-1em r-1em action-button">
                    <label class="btn bg-hover-gray-dark-v1 m-b-0"
                           onclick="backModal('view-student-grade', 'Student Grade','white')">
                        Back
                    </label>
                    <button class="c-hand btn-success btn"
                    >Add
                    </button>
                </div>
            </div>
            <div id="add-student-grade" class="modal-child d-none">

                <form method="post">
                    <div class="custom-grid-container w-100p gap-1em" tabindex="2">
                        <div class="custom-grid-item ">
                            <div class="m-t-1em">
                                <?php

                                $lrn = isset($_GET['lrn']) ? $_GET['lrn'] : '';
                                $id = isset($_GET['id']) ? $_GET['id'] : '';

                                $sqlSelectUser = "select * from users_info ui
                                        left join teachers_info ti on ti.lrn = ui.user_lrn where ui.id='$id'";
                                $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                                $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                                $grade = $rowSelectUser['grade'];

                                $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$lrn' and sei.grade = '$grade'
                                            group by si.lrn";
                                $sqlStudents = mysqli_query($conn, $sqlStudents);
                                while ($rowStudent = mysqli_fetch_assoc($sqlStudents)) {
                                    ?>
                                    <input type="hidden" name="grade" value="<?= $rowStudent['grade'] ?>">
                                    <div>Student Name: <label for="" id="view-student-grade-name"
                                                              class="b-bottom-gray-3px w-27em t-align-center"><?= $rowStudent['l_name'] ?>
                                            , <?= $rowStudent['f_name'] ?> <?= $rowStudent['m_name'] ?></label></div>
                                    <div>School Name:<input type="text"
                                                            class="w-27em b-bottom-gray-3px b-none t-align-center"
                                                            value="<?php echo $schoolName ?>">
                                    </div>
                                    <div>
                                        <div class="d-inline-flex">Grade & Section:1 <label for=""
                                                                                            id="view-student-grade-grade"
                                                                                            class="b-bottom-gray-3px w-10em t-align-center">Grade <?= $rowStudent['grade'] ?> <?= $rowStudent['section'] ?></label>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <div class="d-inline-flex">School Year:
                                            <label for="" id="view-student-grade-school-year"
                                                   class="b-bottom-gray-3px w-10em t-align-center">&nbsp; <?= $rowStudent['school_year'] ?>
                                                - <?= (1 + $rowStudent['school_year']) ?> </label></div>
                                    </div>
                                    <div>Total No. of Yrs. : <input type="number"
                                                                    class="w-27em b-bottom-gray-3px b-none t-align-center">
                                    </div>

                                <?php } ?>


                            </div>
                            <div>
                                <table id="student-grade" class="w-100p table-bordered m-t-2em">
                                    <tr>
                                        <th class="pad-1em">Subjects</th>
                                        <th class="t-align-center">1</th>
                                        <th class="t-align-center">2</th>
                                        <th class="t-align-center">3</th>
                                        <th class="t-align-center">4</th>
                                        <th class="t-align-center">Final</th>
                                        <th class="t-align-center">Units</th>
                                        <th class="t-align-center">Passed or Failed</th>
                                        <th class="t-align-center">Subject Handled By</th>
                                    </tr>
                                    <?php
                                    $lrn = isset($_GET['lrn']) ? $_GET['lrn'] : '';
                                    $id = $_GET['id'];
                                    $sqlUser = "select * from teachers_subject_info tsi
                                            left join users_info ui on ui.user_lrn = tsi.teachers_lrn
                                            where ui.id='$id'";
                                    $resultUsers = mysqli_query($conn, $sqlUser);

                                    $sqlSelectUser = "select * from users_info ui
                                        left join teachers_info ti on ti.lrn = ui.user_lrn where ui.id='$id'";
                                    $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                                    $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                                    $grade = $rowSelectUser['grade'];

                                    $sqlSelectStudentGradeInfo = "select * from students_grade_info where student_lrn='$lrn' and grade='$grade'";
                                    $sqlStudentsGrade = mysqli_query($conn, $sqlSelectStudentGradeInfo);

                                    $ret = array();
                                    $count1 = 0;
                                    while ($rowUser1 = mysqli_fetch_assoc($sqlStudentsGrade)) {
                                        $count1++;
                                        $ret['first_grade'][$count1] = $rowUser1['first_grade'];
                                        $ret['second_grade'][$count1] = $rowUser1['second_grade'];
                                        $ret['third_grade'][$count1] = $rowUser1['third_grade'];
                                        $ret['fourth_grade'][$count1] = $rowUser1['fourth_grade'];
                                        $ret['final'][$count1] = $rowUser1['final'];
                                        $ret['units'][$count1] = $rowUser1['units'];
                                        $ret['status'][$count1] = $rowUser1['status'];
                                        $ret['subject_handled_by'][$count1] = $rowUser1['subject_handled_by'];
                                    }

                                    $count = 0;
                                    while ($rowUser = mysqli_fetch_assoc($resultUsers)) {
                                        $count++;
                                        ?>
                                        <tr>
                                            <td class="t-align-center"
                                            "> <?= $rowUser['subject'] ?> </td>
                                            <td><input onchange="getFinalScore('<?= $rowUser['subject'] ?>','1')"
                                                       type="number" id="<?= $rowUser['subject'] ?>1"
                                                       name="<?= $rowUser['subject'] ?>1"
                                                       class="w-100p b-none t-align-center"
                                                       placeholder="<?= isset($ret['first_grade'][$count]) ? $ret['first_grade'][$count] : 0 ?>">
                                            </td>
                                            <td><input onchange="getFinalScore('<?= $rowUser['subject'] ?>','2')"
                                                       type="number" id="<?= $rowUser['subject'] ?>2"
                                                       name="<?= $rowUser['subject'] ?>2"
                                                       class="w-100p b-none t-align-center"
                                                       placeholder="<?= isset($ret['second_grade'][$count]) ? $ret['second_grade'][$count] : 0 ?>">
                                            </td>
                                            <td><input onchange="getFinalScore('<?= $rowUser['subject'] ?>','3')"
                                                       type="number" id="<?= $rowUser['subject'] ?>3"
                                                       name="<?= $rowUser['subject'] ?>3"
                                                       class="w-100p b-none t-align-center"
                                                       placeholder="<?= isset($ret['third_grade'][$count]) ? $ret['third_grade'][$count] : 0 ?>">
                                            </td>
                                            <td><input onchange="getFinalScore('<?= $rowUser['subject'] ?>','4')"
                                                       type="number" id="<?= $rowUser['subject'] ?>4"
                                                       name="<?= $rowUser['subject'] ?>4"
                                                       class="w-100p b-none t-align-center"
                                                       placeholder="<?= isset($ret['fourth_grade'][$count]) ? $ret['fourth_grade'][$count] : 0 ?>">
                                            </td>
                                            <td><input readonly="true" type="number"
                                                       id="<?= $rowUser['subject'] ?>final"
                                                       name="<?= $rowUser['subject'] ?>final"
                                                       class="w-100p b-none t-align-center" placeholder="0"
                                                       value="<?= isset($ret['final'][$count]) ? $ret['final'][$count] : 0 ?>">
                                            </td>
                                            <td><input type="number" id="<?= $rowUser['subject'] ?>units"
                                                       name="<?= $rowUser['subject'] ?>units"
                                                       class="w-100p b-none t-align-center"
                                                       placeholder="<?= isset($ret['units'][$count]) ? $ret['units'][$count] : 0 ?>">
                                            </td>
                                            <td><input readonly="true" type="text" id="<?= $rowUser['subject'] ?>status"
                                                       name="<?= $rowUser['subject'] ?>status"
                                                       class="w-100p b-none t-align-center"
                                                       value="<?= isset($ret['status'][$count]) ? $ret['status'][$count] : '' ?>">
                                            </td>
                                            <td><input type="text" id="<?= $rowUser['subject'] ?>subject_handled_by"
                                                       name="<?= $rowUser['subject'] ?>subject_handled_by"
                                                       class="w-100p b-none t-align-center"
                                                       required="true"
                                                       value="<?= isset($ret['subject_handled_by'][$count]) ? $ret['subject_handled_by'][$count] : '' ?>">
                                        </tr>
                                    <?php } ?>

                                </table>
                                <br>
                                <?php
                                $id = isset($_GET['id']) ? $_GET['id'] : '';
                                $sqlSelectUser = "select * from users_info ui
                                        left join teachers_info ti on ti.lrn = ui.user_lrn where ui.id='$id'";
                                $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                                $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                                $grade = $rowSelectUser['grade'];

                                $sqlSelectStudentGradeAverage = "select * from students_grade_average_info where students_lrn='$lrn' and grade='$grade'";
                                $sqlStudentsGrade = mysqli_query($conn, $sqlSelectStudentGradeAverage);
                                $total = $sqlStudentsGrade->num_rows;
                                while ($rowStudent = mysqli_fetch_assoc($sqlStudentsGrade)) {
                                    ?>
                                    Average: <input type="text" id="average" name="average"
                                                    class="t-align-center d-none" value="<?= $rowStudent['average'] ?>"
                                                    readonly="true">
                                    <label for="" id="lbl_average"><?= $rowStudent['average'] ?></label>
                                <?php }
                                if ($total == 0) {
                                    ?>
                                    Average: <input type="text" id="average" name="average"
                                                    class="t-align-center d-none" value="0"
                                                    readonly="true">
                                    <label for="" id="lbl_average">0</label>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="custom-grid-item ">
                            <div>
                                <table class="w-100p table-bordered">
                                    <tr>
                                        <th class="t-align-center">Months</th>
                                        <th class="t-align-center">Days of Classes</th>
                                        <th class="t-align-center">Days Present</th>
                                    </tr>
                                    <?php
                                    $lrn = isset($_GET['lrn']) ? $_GET['lrn'] : '';
                                    $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$lrn'
                                            group by si.lrn";
                                    $sqlStudents = mysqli_query($conn, $sqlStudents);
                                    $row = mysqli_fetch_assoc($sqlStudents);
                                    $grade = $row['grade'];
                                    $teacherLrn = $row['teacher_lrn'];

                                    $id = isset($_GET['id']) ? $_GET['id'] : '';

                                    $sqlSelectUser = "select * from users_info ui
                                        left join teachers_info ti on ti.lrn = ui.user_lrn where ui.id='$id'";
                                    $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                                    $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
                                    $tGrade = $rowSelectUser['grade'];

                                    $sqlUser = "select * from students_grade_attendance_info where student_lrn='$lrn' and grade='$tGrade'";
                                    $resultUsers = mysqli_query($conn, $sqlUser);
                                    $total = $resultUsers->num_rows;

                                    while ($rowUser = mysqli_fetch_assoc($resultUsers)) {
                                        ?>
                                        <tr>
                                            <td class="t-align-center">June</td>
                                            <td><input type="number" id="june_days_classes" name="june_days_classes"
                                                       class="w-100p b-none t-align-center" placeholder="0"
                                                       value="<?= $rowUser['june_days_classes'] ?>"></td>
                                            <td><input type="number" id="june_days_presents" name="june_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['june_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">July</td>
                                            <td><input type="number" id="july_days_classes" name="july_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['july_days_classes'] ?>"></td>
                                            <td><input type="number" id="july_days_presents" name="july_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['july_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">August</td>
                                            <td><input type="number" id="august_days_classes" name="august_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['aug_days_classes'] ?>"></td>
                                            <td><input type="number" id="august_days_presents"
                                                       name="august_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['aug_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">September</td>
                                            <td><input type="number" id="september_days_classes"
                                                       name="september_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['sep_days_classes'] ?>"></td>
                                            <td><input type="number" id="september_days_presents"
                                                       name="september_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['sep_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">October</td>
                                            <td><input type="number" id="october_days_classes"
                                                       name="october_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['oct_days_classes'] ?>"></td>
                                            <td><input type="number" id="october_days_presents"
                                                       name="october_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['oct_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">November</td>
                                            <td><input type="number" id="november_days_classes"
                                                       name="november_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['nov_days_classes'] ?>"></td>
                                            <td><input type="number" id="november_days_presents"
                                                       name="november_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['nov_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">December</td>
                                            <td><input type="number" id="december_days_classes"
                                                       name="december_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['dec_days_classes'] ?>"></td>
                                            <td><input type="number" id="december_days_presents"
                                                       name="december_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['dec_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">January</td>
                                            <td><input type="number" id="january_days_classes"
                                                       name="january_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['jan_days_classes'] ?>"></td>
                                            <td><input type="number" id="january_days_presents"
                                                       name="january_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['jan_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">February</td>
                                            <td><input type="number" id="february_days_classes"
                                                       name="february_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['feb_days_classes'] ?>"></td>
                                            <td><input type="number" id="february_days_presents"
                                                       name="february_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['feb_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">March</td>
                                            <td><input type="number" id="march_days_classes" name="march_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['mar_days_classes'] ?>"></td>
                                            <td><input type="number" id="march_days_presents" name="march_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['mar_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">April</td>
                                            <td><input type="number" id="april_days_classes" name="april_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['apr_days_classes'] ?>"></td>
                                            <td><input type="number" id="april_days_presents" name="april_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['apr_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">May</td>
                                            <td><input type="number" id="may_days_classes" name="may_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['may_days_classes'] ?>"></td>
                                            <td><input type="number" id="may_days_presents" name="may_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value="<?= $rowUser['may_days_presents'] ?>"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">Total</td>
                                            <td><input readonly="true" type="number" id="total_days_classes"
                                                       name="total_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value=""></td>
                                            <td><input readonly="true" type="number" id="total_days_presents"
                                                       name="total_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"
                                                       value=""></td>
                                        </tr>
                                    <?php }
                                    if ($total == 0) {
                                        ?>
                                        <tr>
                                            <td class="t-align-center">June</td>
                                            <td><input type="number" id="july_days_classes" name="june_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="july_days_presents" name="june_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">July</td>
                                            <td><input type="number" id="july_days_classes" name="july_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="july_days_presents" name="july_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">August</td>
                                            <td><input type="number" id="august_days_classes" name="august_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="august_days_presents"
                                                       name="august_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">September</td>
                                            <td><input type="number" id="september_days_classes"
                                                       name="september_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="september_days_presents"
                                                       name="september_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">October</td>
                                            <td><input type="number" id="october_days_classes"
                                                       name="october_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="october_days_presents"
                                                       name="october_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">November</td>
                                            <td><input type="number" id="november_days_classes"
                                                       name="november_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="november_days_presents"
                                                       name="november_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">December</td>
                                            <td><input type="number" id="december_days_classes"
                                                       name="december_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="december_days_presents"
                                                       name="december_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">January</td>
                                            <td><input type="number" id="january_days_classes"
                                                       name="january_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="january_days_presents"
                                                       name="january_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">February</td>
                                            <td><input type="number" id="february_days_classes"
                                                       name="february_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="february_days_presents"
                                                       name="february_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">March</td>
                                            <td><input type="number" id="march_days_classes" name="march_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="march_days_presents" name="march_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">April</td>
                                            <td><input type="number" id="april_days_classes" name="april_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="april_days_presents" name="april_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">May</td>
                                            <td><input type="number" id="may_days_classes" name="may_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="may_days_presents" name="may_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        </tr>
                                        <tr>
                                            <td class="t-align-center">Total</td>
                                            <td><input type="number" id="total_days_classes" name="total_days_classes"
                                                       class="w-100p b-none  t-align-center" placeholder="?"></td>
                                            <td><input type="number" id="total_days_presents" name="total_days_presents"
                                                       class="w-100p b-none  t-align-center" placeholder="?"></td>
                                        </tr>
                                    <?php } ?>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="p-absolute btm-1em r-1em action-button">
                        <label class="btn bg-hover-gray-dark-v1 m-b-0"
                               onclick="backModal('view-student-enrollment', 'Student Enrollment','white')"
                               style="padding:0; background-color: #ffffff !important; border-color: #ffffff;">
                            <img src="../../assets/img/back.png" alt="" width="60" height="60" class="c-hand">
                        </label>
                        <button type="submit" class="c-hand btn-primary btn" name="add-student-grade"
                                style="padding:0; background-color: #ffffff !important; border-color: #ffffff;">
                            <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
                        </button>
                    </div>
                </form>

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
        var lrn = '<?php echo isset($_GET['lrn']) ? $_GET['lrn'] : '' ?>';
        if (lrn !== '') {
            $('#modal-addedSuccessfully').attr('style', 'display: none !important;')
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id']?>&&lrn=<?php echo isset($_GET['lrn']) ? $_GET['lrn'] : '' ?>');
        } else {
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id']?>');
        }
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
            var deleteEnId = false;
            idArray.forEach(function (data) {
                if (id === 'student-enrollment') {
                    $.post('', {deleteEnId: data})
                    deleteEnId = true;
                } else {
                    $.post('', {deleteStId: data})
                }
            });
            if (deleteEnId) {
                history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id']?>&&lrn=<?php echo isset($_GET['lrn']) ? $_GET['lrn'] : '' ?>');
            } else {
                history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id']?>');
            }
            window.location.reload();
        }
    }


    function searchName() {
        var search_lrn = $('.search_lrn').val();
        if (search_lrn !== '') {
            window.location.href = '?id=<?php echo $_GET['id'] ?>&&search_lrn=' + search_lrn;
        } else {
            window.location.href = '?id=<?php echo $_GET['id'] ?>';
        }
    }

    function viewStudentInformation(data) {
        data = data.replaceAll("[", "['").replaceAll("]", "']").replaceAll("?", "','");
        data = eval(data);
        $('#view-student-info h4:nth-child(1) label').text(data[0]);
        $('#view-student-info h4:nth-child(2) label').text(data[1]);
        $('#view-student-info h4:nth-child(3) label').text(data[2]);
        $('#view-student-info h4:nth-child(4) label').text(data[3]);
        $('#view-student-info h4:nth-child(5) label').text(data[4]);
        $('#view-student-info h4:nth-child(6) label').text(data[5]);
        $('#view-student-info h4:nth-child(7) label').text(data[6]);
        $('#view-student-info h4:nth-child(8) label').text(data[7]);
        $('#view-student-info h4:nth-child(9) label').text(data[8]);
        $('#view-student-info h4:nth-child(10) label').text(data[9]);
        $('#view-student-info h4:nth-child(11) label').text(data[10]);
        $('#view-student-info h4:nth-child(12) label').text(data[11]);
        $('#view-student-info h4:nth-child(13) label').text(data[12]);
        $('#view-student-info h4:nth-child(14) label').text(data[13]);
        $('#view-student-info h4:nth-child(15) label').text(data[14]);
        $('#view-student-info h4:nth-child(16) label').text(data[15]);

        showModal('view-student-info', 'Student Information', 'dark');
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

    function showGrade(fname, lname, mname, gradeLevel, schoolYear) {
        $('#view-student-grade #view-student-grade-name').text(lname + ', ' + fname + ' ' + mname + '.');
        $('#view-student-grade #view-student-grade-grade').text(gradeLevel);
        $('#view-student-grade #view-student-grade-school-year').text(schoolYear);
        showModal('view-student-grade', 'Student Grade')
    }

    function addGrade(fname, lname, mname, gradeLevel, schoolYear) {
        $('#view-student-grade #view-student-grade-name').text(lname + ', ' + fname + ' ' + mname + '.');
        $('#view-student-grade #view-student-grade-grade').text(gradeLevel);
        $('#view-student-grade #view-student-grade-school-year').text(schoolYear);
        showModal('add-student-grade', 'Student Grade')
    }

    function print(id) {
        $('#print-settings').attr('style', 'display: none !important;');
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
                        $('#print-settings').attr('style', '');
                    }, 1000);
                });
            }
        });
    }

    function selectGrade() {
        var grade = $('#add-enrollment #add-enrollment-grade').val();
        var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
        var lrn = '<?php if (isset($_GET['lrn'])) echo $_GET['lrn']?>';
        history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&searchGrade=' + grade + '&&lrn=' + lrn);
        window.location.reload();
    }

    function getFinalScore(subject, num) {
        var final = (parseInt($('#' + subject + '1').val()) + parseInt($('#' + subject + '2').val()) + parseInt($('#' + subject + '3').val()) + parseInt($('#' + subject + '4').val())) / 4;
        $('#' + subject + 'final').val(final);
        if (final >= 75) {
            $('#' + subject + 'status').val('Passed');
        } else {
            $('#' + subject + 'status').val('Failed');
        }

        var average = 0;
        var count = 0
        var totalAverage = 0;
        $('#student-grade tr').each(function () {
            var subject = $(this).find('td:nth-child(1)').text();
            var final = $(this).find('td:nth-child(6) input').val();
            if (subject !== '' && final !== '') {
                average += parseFloat(final);
                count++;
            }
        });

        totalAverage = average / count;
        $('#average').val(totalAverage);
        $('#lbl_average').text(totalAverage);
    }

    function loadPage() {
        var search_lrn = '<?php echo isset($_GET['search_lrn']) ? $_GET['search_lrn'] : '' ?>';
        if (search_lrn !== '') {
            $('.search_lrn').val(search_lrn);
        }

        var lrnexist = '<?php echo isset($_GET['lrnexist']) ? $_GET['lrnexist'] : '' ?>';
        if (lrnexist !== '') {
            showModal('add-new-student', 'Add New Student', '');
        }

        var searchGrade = '<?php echo isset($_GET['searchGrade']) ? $_GET['searchGrade'] : '' ?>';
        if (searchGrade !== '') {
            $('#add-enrollment #add-enrollment-grade').val(searchGrade);
            showModal('add-enrollment', 'New Enrollment', '');
        }

        var added_successfully = '<?php echo isset($_GET['added_successfully']) ? $_GET['added_successfully'] : '' ?>';
        if (added_successfully !== '') {
            $('#modal-addedSuccessfully').attr('style', 'display: block;')
        }
    }

    loadPage();

</script>
<link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>