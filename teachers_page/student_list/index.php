<?php global $mysqli, $rows, $schoolName;
$var = "student_list";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['id'])) {
    $lrn = $_POST['id'];

    $id = $_GET['id'];
    $sqlSelectRemovedBy = "select CONCAT(first_name, ' ', last_name) as 'name' from users_info where id = '$id'";
    $resultSelectRemovedBy = mysqli_query($conn, $sqlSelectRemovedBy);
    $rowsSelectRemovedBy = mysqli_fetch_assoc($resultSelectRemovedBy);
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

    $sqlInsertTrash = "insert into trash_info (user_lrn,name,history,removed_date,removed_by,position) VALUES ('$lrn', '$name','$historyData', now(),'$removedBy','student')";
    $resultInsertTrash = mysqli_query($conn, $sqlInsertTrash);

    $sql = "delete from students_info where lrn = '$lrn'";
    $result = mysqli_query($conn, $sql);

    $sqlUserInfo = "delete from users_info where user_lrn = '$lrn'";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

    $sqlStudentEnrollmentInfo = "delete from students_enrollment_info where students_info_lrn = '$lrn'";
    $resultStudentEnrollmentInfo = mysqli_query($conn, $sqlStudentEnrollmentInfo);

    $sqlStudentGradeInfo = "delete from students_grade_info where student_lrn = '$lrn'";
    $resultStudentGradeInfo = mysqli_query($conn, $sqlStudentGradeInfo);

    $sqlPromotedStudents = "delete from promoted_students where student_lrn = '$lrn'";
    $resultPromotedStudents = mysqli_query($conn, $sqlPromotedStudents);

    $sqlPromotedStudentsHistory = "delete from promoted_students_history where student_lrn = '$lrn'";
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

if (isset($_POST['studentEnrollmentId'])) {
    $id = $_POST['studentEnrollmentId'];
    $sql = "delete from students_enrollment_info where id = '$id'";
    $result = mysqli_query($conn, $sql);
}

if (isset($_POST['add-enrollment'])) {
    $lrn = $_POST['add-enrollment-lrn'];
    $gradeLevel = $_POST['add-enrollment-grade'];
    $section = $_POST['add-enrollment-section'];
    $schoolYear = $_POST['add-enrollment-school-year'];
    $schoolYear = date("Y-m-d", strtotime($schoolYear));
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
              alert("saved successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $_GET['id'] . '&&lrn=' . $lrn . '");
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

    $sqlUserInfo = "insert into users_info (last_name,first_name,username,password,user_type,user_lrn) VALUES ('$lastName','$firstName','$lrn','$lastName','student','$lrn')";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

    if ($resultUserInfo) {
        echo '<script>';
        echo '   
              alert("saved successfully");
              window.location.href = "?id=' . $_GET['id'] . '&datasavedsuccessfully";
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


    $sqlSelectTeacherSubject = "select * from teachers_subject_info where teachers_lrn = '$teacherLrn' and grade = '$grade'";
    $resultSelectTeacherSubject = mysqli_query($conn, $sqlSelectTeacherSubject);
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

        $final_ = $subject . 'final';
        $final = $_POST["$final_"];

        $unit_ = $subject . 'units';
        $unit = $_POST["$unit_"];
        $status = "jay";
        $sqlInsertStudentGradeInfo = "insert into students_grade_info (student_lrn,teacher_lrn,subject,grade,first_grade,second_grade,third_grade,fourth_grade,final,units,status) VALUES ('$studentLrn','$teacherLrn','$subject','$grade','$first','$second','$third','$fourth','$final','$unit','$status')";
        $resultInsertStudentGradeInfo = mysqli_query($conn, $sqlInsertStudentGradeInfo);
    }

    if ($resultInsertStudentGradeAttendanceInfo) {
        echo '<script>';
        echo '
              alert("saved successfully");
              window.location.href = "?id=' . $rows['id'] . '&datasavedsuccessfully";
            ';
        echo '</script>';
    }
}

?>

<div class="d-flex-end p-absolute w-100p bottom-0 t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px contents one_page">

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
        </style>

        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px pad-1em">

                <div class="m-t-19px m-l-13px f-weight-bold d-flex">
                    <h3>
                        Student List
                    </h3>
                    <div class="r-50px p-absolute t-54px">
                        <button
                                class="btn bg-hover-gray-dark-v1"
                                onclick="showModal('add-new-student', 'New Students')">
                            Add New
                        </button>
                        <button
                                class="btn bg-hover-gray-dark-v1"
                                onclick="deleteStudents('student-list')">Delete Selected
                        </button>
                    </div>
                </div>
                <br/>

                <?php

                $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
                $id = $_GET['id'];

                $SqlSelectUser = "select * from users_info where id = '$id'";
                $ResultSelectUser = mysqli_query($conn, $SqlSelectUser);
                $RowsSelectUser = mysqli_fetch_assoc($ResultSelectUser);
                $userLrn = $RowsSelectUser['user_lrn'];

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
	                    WHERE CONCAT_WS('', si.f_name,si.l_name) LIKE '%$searchName%'
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
	                    WHERE CONCAT_WS('', si.f_name,si.l_name) LIKE '%$searchName%'
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
	                    WHERE CONCAT_WS('', si.f_name,si.l_name) LIKE '%$searchName%'
	                      and si.teacher_lrn = '$userLrn'
                        GROUP BY si.id order by  si.lrn DESC LIMIT ?,?")) {
                    // Calculate the page to get the results we need from our table.
                    $calc_page = ($page - 1) * $num_results_on_page;
                    $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                    $stmt->execute();
                    // Get the results...
                    $result = $stmt->get_result();
                    ?>
                    <input placeholder="search name" id="search_name" type="text" class=" m-b-5px"
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
                                <td>
                                    <label for="" class="t-color-blue c-hand f-weight-bold"
                                           onclick="viewStudentEnrollment('<?= $row['lrn'] ?>')"
                                    >View Enrollment</label>
                                    <label for="" class="t-color-blue c-hand f-weight-bold"
                                           onclick="viewStudentInformation('<?= "[" . $row['lrn'] . "?" . $row['f_name'] . "?" . $row['l_name'] . "?" . $row['b_date'] . "?" . $row['age'] . "?" . $row['home_address'] . "?" . $row['guardian_name'] . "?" . $row['g_level'] . "?" . $row['c_status'] . "?" . $row['religion'] . "?" . $row['contact_number'] . "?" . $row['m_name'] . "?" . $row['b_place'] . "?" . $row['nationality'] . "?" . $row['email_address'] . "?" . $row['gender'] . "]" ?>')"
                                    >&nbsp;&nbsp; View Details</label>

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
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
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
                                <option value="Devorced">Devorced</option>
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
                                name="add-new-student">Submit
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
                            name="save" onclick="updateStudentInformation()">Update
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
                                <option value="Devorced">Devorced</option>
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
                        <div class="r-50px d-flex-end gap-1em m-t-1em">
                            <label class="btn bg-hover-gray-dark-v1 m-b-0"
                                   onclick="showModal('view-student-info','Student Information', 'dark')">
                                Back
                            </label>
                            <button type="submit"
                                    class="c-hand btn-success btn"
                                    name="update-student-info">Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="view-student-enrollment" class="modal-child pad-bottom-2em d-none">
                <div class="d-flex-end gap-1em m-b-1em">
                    <button
                            class="btn bg-hover-gray-dark-v1" onclick="showModal('add-enrollment', 'New Enrollment')">
                        Add New
                    </button>
                    <button
                            class="btn bg-hover-gray-dark-v1"
                            onclick="deleteStudents('student-enrollment')">Delete Selected
                    </button>
                </div>
                <?php
                if (isset($_GET['lrn'])) {
                    $lrns = $_GET['lrn'];
                    echo "<script>showModal('view-student-enrollment', 'Student Enrollment')</script>";
                    $sql = "select si.l_name, si.f_name, si.m_name, sei.grade,sei.section, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si 
                            inner join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn = '$lrns'
                            GROUP BY sei.grade order by sei.id ASC";
                    $students_enrollment_info_result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($students_enrollment_info_result);
                    $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                    $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                    // Get the total number of records from our table "students".
                    $total_pages = $mysqli->query("select si.l_name, si.f_name, si.m_name, sei.grade,sei.section, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si 
                            inner join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn = '$lrns'
                            GROUP BY sei.grade order by sei.id ASC")->num_rows;
                    //  Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                    $page = isset($_GET['page_enrollment']) && is_numeric($_GET['page_enrollment']) ? $_GET['page_enrollment'] : 1;

                    // Number of results to show on each page.
                    $num_results_on_page = 5;

                    if ($stmt = $mysqli->prepare("select si.l_name, si.f_name, si.m_name, sei.grade,sei.section, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si 
                            inner join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn = '$lrns'
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
                                <th>Action</th>
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

                                    <td>
                                        <label for="" class="t-color-red c-hand f-weight-bold"
                                               onclick="showGrade('<?= $row['f_name'] ?>','<?= $row['l_name'] ?>','<?= $row['m_name'] ?>','<?= $row['grade'] ?>', '<?= $row['school_year'] ?>')">
                                            View Grade</label>
                                        <label for="" class="t-color-red c-hand f-weight-bold"
                                               onclick="addGrade('<?= $row['f_name'] ?>','<?= $row['l_name'] ?>','<?= $row['m_name'] ?>','<?= $row['grade'] ?>', '<?= $row['school_year'] ?>')">
                                            &nbsp; &nbsp;Add Grade</label>
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
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">Next</a>
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
                                <option value="0" selected></option>
                                <?php
                                $sql = "select * from grade_info";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?php echo $row['grade'] ?>">
                                        Grade <?php echo $row['grade'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <div class="w-70p m-l-1em">Section</div>
                            <select name="add-enrollment-section" id="add-enrollment-section"
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="0" selected></option>
                                <?php
                                $grade = isset($_GET['searchGrade']) ? $_GET['searchGrade'] : '';
                                $sql = "select * from grade_info where grade = '$grade'";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?php echo $row['section'] ?>">
                                        Grade <?php echo $row['section'] ?></option>
                                    <?php
                                }
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
                        <div class="r-50px d-flex-end gap-1em m-t-1em">
                            <label class="btn bg-hover-gray-dark-v1 m-b-0"
                                   onclick="showModal('view-student-enrollment','Student Enrollment')">
                                Back
                            </label>
                            <button type="submit"
                                    class="c-hand btn-success btn"
                                    name="add-enrollment">Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="view-student-grade" class="modal-child d-none">

                <?php

                $lrn = isset($_GET['lrn']) ? $_GET['lrn'] : '';
                $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$lrn'
                                            group by si.lrn";
                $sqlStudents = mysqli_query($conn, $sqlStudents);
                while ($rowStudent = mysqli_fetch_assoc($sqlStudents)) {
                    ?>
                    <input type="hidden" name="grade" value="<?= $rowStudent['grade'] ?>">
                    <div>Student Name: <label
                                class="b-bottom-gray-3px w-27em t-align-center"><?= $rowStudent['l_name'] ?>
                            , <?= $rowStudent['f_name'] ?> <?= $rowStudent['m_name'] ?></label></div>
                    <div>School Name:<input type="text"
                                            class="w-27em b-bottom-gray-3px b-none t-align-center" value="<?php echo $schoolName ?>"></div>
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

                        $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$studentLrn'
                                            group by si.lrn";
                        $sqlStudents = mysqli_query($conn, $sqlStudents);
                        $row = mysqli_fetch_assoc($sqlStudents);
                        $grade = $row['grade'];

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

                        $sqlUser = "select * from students_grade_attendance_info where student_lrn='$studentLrn' and teacher_lrn='$teacherLrn' and grade='$grade'";
                        $resultUsers = mysqli_query($conn, $sqlUser);
                        while ($rowUser = mysqli_fetch_assoc($resultUsers)) {
                            ?>
                            <tr>
                                <th >Days of School</th>
                                <th class="t-align-center"><?= $rowUser['june_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['july_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['aug_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['sep_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['oct_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['nov_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['dec_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['jan_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['feb_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['mar_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['apr_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['may_days_classes'] ?></th>
                                <th class="t-align-center"><?= $rowUser['june_days_classes'] + $rowUser['july_days_classes'] + $rowUser['aug_days_classes'] + $rowUser['sep_days_classes'] + $rowUser['oct_days_classes'] + $rowUser['nov_days_classes'] + $rowUser['dec_days_classes'] + $rowUser['jan_days_classes'] + $rowUser['feb_days_classes'] + $rowUser['mar_days_classes'] + $rowUser['apr_days_classes'] + $rowUser['may_days_classes'] ?></th>
                            </tr>
                            <tr>
                                <th >Days Present</th>
                                <th class="t-align-center"><?= $rowUser['june_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['july_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['aug_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['sep_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['oct_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['nov_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['dec_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['jan_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['feb_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['mar_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['apr_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['may_days_presents'] ?></th>
                                <th class="t-align-center"><?= $rowUser['june_days_presents'] + $rowUser['july_days_presents'] + $rowUser['aug_days_presents'] + $rowUser['sep_days_presents'] + $rowUser['oct_days_presents'] + $rowUser['nov_days_presents'] + $rowUser['dec_days_presents'] + $rowUser['jan_days_presents'] + $rowUser['feb_days_presents'] + $rowUser['mar_days_presents'] + $rowUser['apr_days_presents'] + $rowUser['may_days_presents'] ?></th>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
                <div class="p-absolute btm-1em r-1em action-button">
                    <label class="btn bg-hover-gray-dark-v1 m-b-0"
                           onclick="backModal('view-student-enrollment', 'Student Enrollment','white')">
                        Back
                    </label>
                    <button class="c-hand btn-primary btn"
                            onclick="print('view-student-grade')">Print
                    </button>
                    <button class="c-hand btn-success btn"
                            onclick="showModal('add-student-subject', 'Subject List')">Add Subject
                    </button>
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
                                $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$lrn'
                                            group by si.lrn";
                                $sqlStudents = mysqli_query($conn, $sqlStudents);
                                while ($rowStudent = mysqli_fetch_assoc($sqlStudents)) {
                                    ?>
                                    <input type="hidden" name="grade" value="<?= $rowStudent['grade'] ?>">
                                    <div>Student Name: <label for="" id="view-student-grade-name"
                                                              class="b-bottom-gray-3px w-27em t-align-center"><?= $rowStudent['l_name'] ?>
                                            , <?= $rowStudent['f_name'] ?> <?= $rowStudent['m_name'] ?></label></div>
                                    <div>School Name:<input type="text"
                                                            class="w-27em b-bottom-gray-3px b-none t-align-center" value="<?php echo $schoolName ?>">
                                    </div>
                                    <div>
                                        <div class="d-inline-flex">Grade & Section: <label for=""
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
                                <table class="w-100p table-bordered m-t-2em">
                                    <tr>
                                        <th class="pad-1em">Subjects</th>
                                        <th class="t-align-center">1</th>
                                        <th class="t-align-center">2</th>
                                        <th class="t-align-center">3</th>
                                        <th class="t-align-center">4</th>
                                        <th class="t-align-center">Final</th>
                                        <th class="t-align-center">Units</th>
                                        <th class="t-align-center">Passed or Failed</th>
                                    </tr>
                                    <?php
                                    $id = $_GET['id'];
                                    $sqlUser = "select * from teachers_subject_info tsi
                                            left join users_info ui on ui.user_lrn = tsi.teachers_lrn
                                            where ui.id='$id'";
                                    $resultUsers = mysqli_query($conn, $sqlUser);
                                    while ($rowUser = mysqli_fetch_assoc($resultUsers)) {
                                        ?>
                                        <tr>
                                            <td> <?= $rowUser['subject'] ?></td>
                                            <td><input type="number" id="<?= $rowUser['subject'] ?>1"
                                                       name="<?= $rowUser['subject'] ?>1"
                                                       class="w-100p b-none t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="<?= $rowUser['subject'] ?>2"
                                                       name="<?= $rowUser['subject'] ?>2"
                                                       class="w-100p b-none t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="<?= $rowUser['subject'] ?>3"
                                                       name="<?= $rowUser['subject'] ?>3"
                                                       class="w-100p b-none t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="<?= $rowUser['subject'] ?>4"
                                                       name="<?= $rowUser['subject'] ?>4"
                                                       class="w-100p b-none t-align-center" placeholder="0"></td>
                                            <td><input readonly="true" type="number" id="<?= $rowUser['subject'] ?>final"
                                                       name="<?= $rowUser['subject'] ?>final"
                                                       class="w-100p b-none t-align-center" placeholder="0"></td>
                                            <td><input type="number" id="<?= $rowUser['subject'] ?>units"
                                                       name="<?= $rowUser['subject'] ?>units"
                                                       class="w-100p b-none t-align-center" placeholder="0"></td>
                                            <td><input readonly="true" type="text" id="<?= $rowUser['subject'] ?>status"
                                                       name="<?= $rowUser['subject'] ?>status"
                                                       class="w-100p b-none t-align-center" placeholder="?"></td>
                                        </tr>
                                    <?php } ?>
                                </table>

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
                                    <tr>
                                        <td class="t-align-center">June</td>
                                        <td><input type="number" id="june_days_classes" name="june_days_classes"
                                                   class="w-100p b-none t-align-center" placeholder="0"></td>
                                        <td><input type="number" id="june_days_presents" name="june_days_presents"
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
                                        <td><input type="number" id="august_days_presents" name="august_days_presents"
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
                                        <td><input type="number" id="october_days_classes" name="october_days_classes"
                                                   class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        <td><input type="number" id="october_days_presents" name="october_days_presents"
                                                   class="w-100p b-none  t-align-center" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td class="t-align-center">November</td>
                                        <td><input type="number" id="november_days_classes" name="november_days_classes"
                                                   class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        <td><input type="number" id="november_days_presents"
                                                   name="november_days_presents"
                                                   class="w-100p b-none  t-align-center" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td class="t-align-center">December</td>
                                        <td><input type="number" id="december_days_classes" name="december_days_classes"
                                                   class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        <td><input type="number" id="december_days_presents"
                                                   name="december_days_presents"
                                                   class="w-100p b-none  t-align-center" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td class="t-align-center">January</td>
                                        <td><input type="number" id="january_days_classes" name="january_days_classes"
                                                   class="w-100p b-none  t-align-center" placeholder="0"></td>
                                        <td><input type="number" id="january_days_presents" name="january_days_presents"
                                                   class="w-100p b-none  t-align-center" placeholder="0"></td>
                                    </tr>
                                    <tr>
                                        <td class="t-align-center">February</td>
                                        <td><input type="number" id="february_days_classes" name="february_days_classes"
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
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="p-absolute btm-1em r-1em action-button">
                        <label class="btn bg-hover-gray-dark-v1 m-b-0"
                               onclick="backModal('view-student-enrollment', 'Student Enrollment','white')">
                            Back
                        </label>
                        <button type="submit" class="c-hand btn-primary btn" name="add-student-grade">Save
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

    function searchName() {
        var search = $('#search_name').val();
        if (search !== '') {
            window.location.href = '?id=<?php echo $_GET['id'] ?>&&searchName=' + search;
        } else {
            window.location.href = '?id=<?php echo $_GET['id'] ?>';
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
                        $.post('', {id: studId})
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

    function selectGrade() {
        var grade = $('#add-enrollment #add-enrollment-grade').val();
        var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
        var lrn = '<?php if (isset($_GET['lrn'])) echo $_GET['lrn']?>';
        history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&searchGrade=' + grade + '&&lrn=' + lrn);
        window.location.reload();
    }

    function loadPage() {
        var searchName = '<?php echo isset($_GET['searchName']) ? $_GET['searchName'] : '' ?>';
        if (searchName !== '') {
            $('#search_name').val(searchName);
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
    }

    loadPage();

</script>
<link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>