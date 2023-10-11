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
        foreach ($studentInfo as $key => $value) {
            if ($value !== " ") {
                $val = str_replace(" ", "", $value);
                echo '<script> console.log("' . $val . '"); </script>';
            }
        }

        $studentGradeInfo = str_replace("Student Grade Info</h3>", "", $history[3]);
        if ($studentGradeInfo !== " ") {
            echo '<script> console.log("Student Grade Info"); </script>';
            $studentGradeInfo = explode("<br/>", $studentGradeInfo);
//        echo '<script> console.log("' . implode($studentGradeInfo) . '"); </script>';
            foreach ($studentGradeInfo as $key => $value) {
                if ($value !== " ") {
                    $val = str_replace(" ", "", $value);
                    echo '<script> console.log("' . $val . '"); </script>';
                }
            }

            $insertStudentGradeInfo = "insert into students_grade_info (students_info_lrn,grade_level,subject,first_grading,second_grading,third_grading,fourth_grading,final_grading) VALUES ('$sLrn','$studentGradeInfo[1]','$studentGradeInfo[2]','$studentGradeInfo[3]','$studentGradeInfo[4]','$studentGradeInfo[5]','$studentGradeInfo[6]','$studentGradeInfo[7]')";
            $result = mysqli_query($conn, $insertStudentGradeInfo);
        }

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
            foreach ($studentEnrollmentInfo as $key => $value) {
                if ($value !== " ") {
                    $val = str_replace(" ", "", $value);
                    echo '<script> console.log("' . $val . '"); </script>';
                }
            }
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