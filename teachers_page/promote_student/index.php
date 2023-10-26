<?php global $mysqli, $rows;
$var = "promote_student";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['promoteStudent'])) {
    $promoteStudent = $_POST['promoteStudent'];
    $promoteStudent = explode(',', $promoteStudent);
    $lrn = $promoteStudent[0];
    $grade = $promoteStudent[1];
    $section = $promoteStudent[2];
    $grade_plus = $grade + 1;
    $grade_status = $promoteStudent[3];
    $sql = "UPDATE students_enrollment_info SET grade = '$grade_plus', grade_status='promoted', section=''  WHERE students_info_lrn = '$lrn' and grade = '$grade'";
    $result = mysqli_query($conn, $sql);

    $sqlUpdateStudentinfo = "UPDATE students_info SET teacher_lrn=''  WHERE lrn = '$lrn'";
    $resultUpdateStudentinfo = mysqli_query($conn, $sqlUpdateStudentinfo);

    $sqlDeletePromoteStudents = "DELETE FROM promoted_students WHERE student_lrn = '$lrn'";
    $resultDeletePromoteStudents = mysqli_query($conn, $sqlDeletePromoteStudents);

    $id = $_GET['id'];
    $sqlUserInfo = "SELECT * FROM `users_info` where id = '$id'";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);
    $row = mysqli_fetch_assoc($resultUserInfo);
    $teacher_lrn = $row['user_lrn'];

    $sqlInsertPromoteStudents = "INSERT INTO promoted_students (student_lrn, teacher_lrn, section) VALUES ('$lrn', '$teacher_lrn', '$section')";
    $resultInsertPromoteStudents = mysqli_query($conn, $sqlInsertPromoteStudents);

    $sqlInsertPromoteStudentsHistory = "INSERT INTO promoted_students_history (student_lrn, grade, section, date) VALUES ('$lrn', '$grade', '$section',now())";
    $resultInsertPromoteStudentsHistory = mysqli_query($conn, $sqlInsertPromoteStudentsHistory);
}

if (isset($_POST['removeStudents'])) {
    $removePromotedStudents = $_POST['removeStudents'];
    $removePromotedStudents = explode(',', $removePromotedStudents);
    $lrn_promoted = $removePromotedStudents[0];
    $grade_promoted = $removePromotedStudents[1];
    $grade_minus = $grade_promoted - 1;

    $sqlSelectPromotedStudents = "SELECT * FROM `promoted_students` where student_lrn = '$lrn_promoted'";
    $resultSelectPromotedStudents = mysqli_query($conn, $sqlSelectPromotedStudents);
    $row = mysqli_fetch_assoc($resultSelectPromotedStudents);
    $teacher_lrn = $row['teacher_lrn'];
    $section = $row['section'];

    $sql = "UPDATE students_enrollment_info SET grade = '$grade_minus', grade_status='Passed', section='$section' WHERE students_info_lrn = '$lrn_promoted' and grade = '$grade_promoted'";
    $result = mysqli_query($conn, $sql);

    $sqlUpdateStudentinfo = "UPDATE students_info SET teacher_lrn='$teacher_lrn'  WHERE lrn = '$lrn_promoted'";
    $resultUpdateStudentinfo = mysqli_query($conn, $sqlUpdateStudentinfo);

    $sqlDeletePromoteStudents = "DELETE FROM promoted_students WHERE student_lrn = '$lrn_promoted'";
    $resultDeletePromoteStudents = mysqli_query($conn, $sqlDeletePromoteStudents);
}

if (isset($_POST['addStudents'])) {
    $addPromotedStudents = $_POST['addStudents'];
    $addPromotedStudents = explode(',', $addPromotedStudents);
    $lrn_promoted = $addPromotedStudents[0];
    $grade_promoted = $addPromotedStudents[1];

    $id = $_GET['id'];
    $sqlUserInfo = "SELECT * FROM `users_info` ui
                    left join teachers_info ti on ti.lrn = ui.user_lrn
                    where ui.id = '$id'";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);
    $row = mysqli_fetch_assoc($resultUserInfo);
    $teacher_lrn = $row['user_lrn'];
    $section = $row['section'];


    $sql = "UPDATE students_enrollment_info SET grade = '$grade_promoted', grade_status='promoted', section='$section' WHERE students_info_lrn = '$lrn_promoted' and grade = '$grade_promoted'";
    $result = mysqli_query($conn, $sql);

    $sqlUpdateStudentinfo = "UPDATE students_info SET teacher_lrn='$teacher_lrn'  WHERE lrn = '$lrn_promoted'";
    $resultUpdateStudentinfo = mysqli_query($conn, $sqlUpdateStudentinfo);

    $sqlDeletePromoteStudents = "DELETE FROM promoted_students WHERE student_lrn = '$lrn_promoted'";
    $resultDeletePromoteStudents = mysqli_query($conn, $sqlDeletePromoteStudents);

    $sqlDeleteGradeInfo = "DELETE FROM students_grade_info WHERE student_lrn = '$lrn_promoted'";
    $resultDeleteGradeInfo = mysqli_query($conn, $sqlDeleteGradeInfo);

    $sqlDeletGradeAttendance = "DELETE FROM students_grade_attendance_info WHERE student_lrn = '$lrn_promoted'";
    $resultDeletGradeAttendance = mysqli_query($conn, $sqlDeletGradeAttendance);

    $sqlDeleteGradeAverageInfo = "DELETE FROM students_grade_average_info WHERE students_lrn = '$lrn_promoted'";
    $resultDeleteGradeAverageInfo = mysqli_query($conn, $sqlDeleteGradeAverageInfo);



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
                        Promote Students
                    </h3>
                    <div class="r-50px p-absolute t-54px">
                        <button
                                class="btn bg-hover-gray-dark-v1"
                                onclick="viewPromote('add promoted students')">
                            Add Promoted Students(Grade <?php
                            $id = $_GET['id'];
                            $sqlSelectUser = "SELECT * FROM `users_info` ui
                                                left join teachers_info ti on ti.lrn = ui.user_lrn
                                                where ui.id = '$id'";
                            $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                            $row = mysqli_fetch_assoc($resultSelectUser);
                            echo $row['grade'] ?>)
                        </button>
                        <button
                                class="btn bg-hover-gray-dark-v1"
                                onclick="viewPromote('')">
                            View Promote
                        </button>

                    </div>
                </div>
                <br/>

                <div class="f-right m-t-19px m-r-13px">
                    <button type="submit"
                            class="c-hand bg-hover-skyBlue btn"
                            onclick="promoteStudent()">Promote
                    </button>
                </div>
                <br/> <br/><br/>

                <?php
                $id = $_GET['id'];
                $sqlSelectUser = "SELECT * FROM `users_info` where id = '$id'";
                $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                $row = mysqli_fetch_assoc($resultSelectUser);
                $teacher_lrn = $row['user_lrn'];

                $sqlSelectTeacher = "SELECT * FROM `teachers_info` where lrn = '$teacher_lrn'";
                $resultSelectTeacher = mysqli_query($conn, $sqlSelectTeacher);
                $rows = mysqli_fetch_assoc($resultSelectTeacher);
                $teacher_grade = $rows['grade'];
                $teacher_section = $rows['section'];

                $sql = "SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        where sei.grade='$teacher_grade'
                        and sei.section='$teacher_section'
                        and si.teacher_lrn = '$teacher_lrn'
                        GROUP BY si.id order by si.lrn DESC Limit 1";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "students".
                $total_pages = $mysqli->query("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        where sei.grade='$teacher_grade'
                         and sei.section='$teacher_section'
                          and si.teacher_lrn = '$teacher_lrn'
                        GROUP BY si.id order by si.lrn DESC")->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        where sei.grade='$teacher_grade'
                         and sei.section='$teacher_section'
                          and si.teacher_lrn = '$teacher_lrn'
                        GROUP BY si.id order by si.lrn DESC LIMIT ?,?")) {
                // Calculate the page to get the results we need from our table.
                $calc_page = ($page - 1) * $num_results_on_page;
                $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                $stmt->execute();
                // Get the results...
                $result = $stmt->get_result();
                ?>

                <table class="table table-1 b-shadow-dark ">
                    <thead>
                    <tr>
                        <th class="t-align-center"><label for="student-list-cb"
                                                          class="d-flex-center"></label><input
                                    id="student-list-cb" type="checkbox"
                                    onclick="checkCBStudents('student-list', 'student-list-cb')"
                                    class="sc-1-3 c-hand"/></th>
                        <th>No</th>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Grade</th>
                        <th>Section</th>
                        <th>Status</th>
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
                                           id="<?= $row['lrn'] ?>,<?= $row['g_level'] ?>,<?= $row['section'] ?>,<?= $row['grade_status'] ?>"/>
                                </label></td>
                            <th scope="row"><?= $i ?> </th>
                            <td><?= $row['lrn'] ?></td>
                            <td><?= $row['l_name'] ?> <?= $row['f_name'] ?></td>
                            <td><?= $row['gender'] ?></td>
                            <td><?= $row['g_level'] ?></td>
                            <td><?= $row['section'] ?></td>
                            <td><?= $row['grade_status'] === 'promoted' ? 'promoted(cant promote again, wait for final grades)' : $row['grade_status'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>

                <?php
                $stmt->close();

                ?>
                Total Records: <?= $total_pages ?>

                <div class="m-2em d-flex-end m-t-n1em">
                    <div class="d-flex-center">
                        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="prev"><a
                                                href="/1-php-grading-system/teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page + 1 ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php endif;
                        }
                        ?>
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
                            <input placeholder="Civil Status" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-civilStatus"
                                   name="up-civilStatus"
                                   required>
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
            <div id="view-promoted-students" class="modal-child pad-bottom-2em d-none">
                <div class="m-2em d-flex-align-start">
                    <div class="bg-white w-100p b-radius-10px ">
                        <div class="f-right  m-r-13px">
                            <button type="submit"
                                    class="c-hand bg-hover-skyBlue btn"
                                    onclick="removePromotedStudents()">Remove
                            </button>
                        </div>
                        <br/> <br/>
                        <?php


                        $sqlSelectUser = "SELECT * FROM `users_info` where id = '$id'";
                        $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                        $row = mysqli_fetch_assoc($resultSelectUser);
                        $teacher_lrn = $row['user_lrn'];

                        $sqlSelectTeacher = "SELECT * FROM `teachers_info` where lrn = '$teacher_lrn'";
                        $resultSelectTeacher = mysqli_query($conn, $sqlSelectTeacher);
                        $rows = mysqli_fetch_assoc($resultSelectTeacher);
                        $grade = $rows['grade'] + 1;
                        $section = $rows['section'];

                        $sql = "SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_students ps on ps.student_lrn = sei.students_info_lrn
                        where sei.grade='$grade' and ps.section='$section'
                        GROUP BY si.id order by si.lrn DESC Limit 1";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                        $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                        // Get the total number of records from our table "students".
                        $total_pages = $mysqli->query("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_students ps on ps.student_lrn = sei.students_info_lrn
                        where sei.grade='$grade'  and ps.section='$section'
                        GROUP BY si.id order by si.lrn DESC")->num_rows;
                        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        // Number of results to show on each page.
                        $num_results_on_page = 10;

                        if ($stmt = $mysqli->prepare("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_students ps on ps.student_lrn = sei.students_info_lrn
                        where sei.grade='$grade'  and ps.section='$section'
                        GROUP BY si.id order by si.lrn DESC LIMIT ?,?")) {
                        // Calculate the page to get the results we need from our table.
                        $calc_page = ($page - 1) * $num_results_on_page;
                        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                        $stmt->execute();
                        // Get the results...
                        $result = $stmt->get_result();
                        ?>

                        <table class="table table-1 b-shadow-dark ">
                            <thead>
                            <tr>
                                <th class="t-align-center"><label for="student-list-cb"
                                                                  class="d-flex-center"></label><input
                                            id="student-list-cb" type="checkbox"
                                            onclick="checkCBStudents('student-list', 'student-list-cb')"
                                            class="sc-1-3 c-hand"/></th>
                                <th>No</th>
                                <th>LRN</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Grade</th>
                                <th>Section</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody id="promoted-students">
                            <?php
                            $i = 0;
                            while ($row = $result->fetch_assoc()):

                                $i++;
                                ?>
                                <tr>
                                    <td class="d-flex-center"><label>
                                            <input type="checkbox" class="sc-1-3 c-hand check"
                                                   id="<?= $row['lrn'] ?>,<?= $row['g_level'] ?>"/>
                                        </label></td>
                                    <th scope="row"><?= $i ?> </th>
                                    <td><?= $row['lrn'] ?></td>
                                    <td><?= $row['l_name'] ?> <?= $row['f_name'] ?></td>
                                    <td><?= $row['gender'] ?></td>
                                    <td><?= $row['g_level'] === 0 ? 'not enrolled' : $row['g_level'] ?></td>
                                    <td><?= $row['section'] ?></td>
                                    <td><?= $row['grade_status'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>

                        <?php
                        $stmt->close();

                        ?>
                        Total Records: <?= $total_pages ?>

                        <div class="m-2em d-flex-end m-t-n1em">
                            <div class="d-flex-center">
                                <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                                    <ul class="pagination">
                                        <?php if ($page > 1): ?>
                                            <li class="prev"><a
                                                        href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&promoted=1&&page=<?php echo $page - 1 ?>">Prev</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page > 3): ?>
                                            <li class="start"><a
                                                        href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&promoted=1&&page=<?php echo $page + 1 ?>">1</a>
                                            </li>
                                            <li class="dots">...</li>
                                        <?php endif; ?>

                                        <?php if ($page - 2 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&promoted=1&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page - 1 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&promoted=1&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                            </li><?php endif; ?>

                                        <li class="currentpage"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&promoted=1&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                        </li>

                                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&promoted=1&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&promoted=1&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                            </li><?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                            <li class="dots">...</li>
                                            <li class="end"><a
                                                        href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&promoted=1&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                            <li class="next"><a
                                                        href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&promoted=1&&page=<?php echo $page + 1 ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="add-promoted-students" class="modal-child pad-bottom-2em d-none">
                <div class="m-2em d-flex-align-start">
                    <div class="bg-white w-100p b-radius-10px ">
                        <div class="f-right  m-r-13px">
                            <button type="submit"
                                    class="c-hand bg-hover-skyBlue btn"
                                    onclick="addPromotedStudents()">Add
                            </button>
                        </div>
                        <br/> <br/>
                        <?php
                        $sqlSelectUser = "SELECT * FROM `users_info` where id = '$id'";
                        $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                        $row = mysqli_fetch_assoc($resultSelectUser);
                        $teacher_lrn = $row['user_lrn'];

                        $sqlSelectTeacher = "SELECT * FROM `teachers_info` where lrn = '$teacher_lrn'";
                        $resultSelectTeacher = mysqli_query($conn, $sqlSelectTeacher);
                        $rows = mysqli_fetch_assoc($resultSelectTeacher);
                        $grade = $rows['grade'];

                        $sql = "SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_students ps on ps.student_lrn = sei.students_info_lrn
                        where sei.grade='$grade' 
                        GROUP BY si.id order by si.lrn DESC Limit 1";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                        $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                        // Get the total number of records from our table "students".
                        $total_pages = $mysqli->query("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_students ps on ps.student_lrn = sei.students_info_lrn
                        where sei.grade='$grade' 
                        GROUP BY si.id order by si.lrn DESC")->num_rows;
                        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        // Number of results to show on each page.
                        $num_results_on_page = 10;

                        if ($stmt = $mysqli->prepare("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_students ps on ps.student_lrn = sei.students_info_lrn
                        where sei.grade='$grade'  
                        GROUP BY si.id order by si.lrn DESC LIMIT ?,?")) {
                        // Calculate the page to get the results we need from our table.
                        $calc_page = ($page - 1) * $num_results_on_page;
                        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                        $stmt->execute();
                        // Get the results...
                        $result = $stmt->get_result();
                        ?>

                        <table class="table table-1 b-shadow-dark ">
                            <thead>
                            <tr>
                                <th class="t-align-center"><label for="student-list-cb"
                                                                  class="d-flex-center"></label><input
                                            id="student-list-cb" type="checkbox"
                                            onclick="checkCBStudents('student-list', 'student-list-cb')"
                                            class="sc-1-3 c-hand"/></th>
                                <th>No</th>
                                <th>LRN</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Grade</th>
                                <th>Section</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody id="promoted-students">
                            <?php
                            $i = 0;
                            while ($row = $result->fetch_assoc()):

                                $i++;
                                ?>
                                <tr>
                                    <td class="d-flex-center"><label>
                                            <input type="checkbox" class="sc-1-3 c-hand check"
                                                   id="<?= $row['lrn'] ?>,<?= $row['g_level'] ?>"/>
                                        </label></td>
                                    <th scope="row"><?= $i ?> </th>
                                    <td><?= $row['lrn'] ?></td>
                                    <td><?= $row['l_name'] ?> <?= $row['f_name'] ?></td>
                                    <td><?= $row['gender'] ?></td>
                                    <td><?= $row['g_level'] === 0 ? 'not enrolled' : $row['g_level'] ?></td>
                                    <td><?= $row['section'] ?></td>
                                    <td><?= $row['grade_status'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>

                        <?php
                        $stmt->close();

                        ?>
                        Total Records: <?= $total_pages ?>

                        <div class="m-2em d-flex-end m-t-n1em">
                            <div class="d-flex-center">
                                <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                                    <ul class="pagination">
                                        <?php if ($page > 1): ?>
                                            <li class="prev"><a
                                                        href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&addPromoted=1&&page=<?php echo $page - 1 ?>">Prev</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page > 3): ?>
                                            <li class="start"><a
                                                        href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&addPromoted=1&&page=<?php echo $page + 1 ?>">1</a>
                                            </li>
                                            <li class="dots">...</li>
                                        <?php endif; ?>

                                        <?php if ($page - 2 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&addPromoted=1&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page - 1 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&addPromoted=1&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                            </li><?php endif; ?>

                                        <li class="currentpage"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&addPromoted=1&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                        </li>

                                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&addPromoted=1&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&addPromoted=1&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                            </li><?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                            <li class="dots">...</li>
                                            <li class="end"><a
                                                        href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&addPromoted=1&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                            <li class="next"><a
                                                        href="/1-php-grading-system//teachers_page/promote_student/?id=<?php echo $_GET['id'] ?>&&addPromoted=1&&page=<?php echo $page + 1 ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
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

    function SearchGrade(status) {
        if (status === 'promoted_student') {
            var grade_promoted = $('#search_grade_promoted').val();
            var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
            var grade = '<?php if (isset($_GET['grade'])) echo $_GET['grade']?>';
            var section = '<?php if (isset($_GET['section'])) echo $_GET['section']?>';
            history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&grade=' + grade + '&&section=' + section + '&&grade_promoted=' + grade_promoted);
            window.location.reload();
        } else {
            var grade = $('#search_grade').val();
            var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
            history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&grade=' + grade);
            window.location.reload();
        }
    }

    function SearchSection(status) {
        if (status === 'promoted_student') {
            var section_promoted = $('#search_section_promoted').val();
            var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
            var grade_promoted = '<?php if (isset($_GET['grade_promoted'])) echo $_GET['grade_promoted']?>';
            var grade = '<?php if (isset($_GET['grade'])) echo $_GET['grade']?>';
            var section = '<?php if (isset($_GET['section'])) echo $_GET['section']?>';
            history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&grade=' + grade + '&&section=' + section + '&&grade_promoted=' + grade_promoted + '&&section_promoted=' + section_promoted);
            window.location.reload();
        } else {
            var section = $('#search_section').val();
            var id = '<?php if (isset($_GET['id'])) echo $_GET['id']?>';
            var grade = '<?php if (isset($_GET['grade'])) echo $_GET['grade']?>';
            history.pushState({page: 'another page'}, 'another page', '?id=' + id + '&&grade=' + grade + '&&section=' + section);
            window.location.reload();
        }

    }


    function promoteStudent() {
        var studentID = [];
        var studentCount = 0;
        var isFailed = false;
        $('#student-list input[type="checkbox"]:checked').each(function () {
            studentID.push($(this).attr('id'));
            studentCount++;
        });
        if (studentCount > 0) {
            studentID.forEach(function (studId) {
                if (!studId.includes('Passed')) {
                    isFailed = true;
                }
            });
            if (isFailed) {
                alert('Failed student cannot be promoted!')
            } else {
                var r = confirm("Are you sure you want to promote ?");
                if (r === true) {
                    console.log(studentID)
                    studentID.forEach(function (studId) {
                        $.post('', {promoteStudent: studId})
                    });
                    alert('Successfully promoted!')
                    window.location.reload();
                }
            }

        } else {
            alert('Please select a student!');
        }


    }

    function removePromotedStudents() {
        var studentID = [];
        var studentCount = 0;
        $('#promoted-students input[type="checkbox"]:checked').each(function () {
            studentID.push($(this).attr('id'));
            studentCount++;
        });
        if (studentCount > 0) {
            var r = confirm("Are you sure you want to remove ?");
            if (r === true) {
                studentID.forEach(function (studId) {
                    $.post('', {removeStudents: studId})
                });
                alert('Successfully removed!')
                window.location.reload();
            }
        } else {
            alert('Please select a student!');
        }
    }

    function addPromotedStudents() {
        var studentID = [];
        var studentCount = 0;
        $('#promoted-students input[type="checkbox"]:checked').each(function () {
            studentID.push($(this).attr('id'));
            studentCount++;
        });
        if (studentCount > 0) {
            var r = confirm("Are you sure you want to remove ?");
            if (r === true) {
                studentID.forEach(function (studId) {
                    $.post('', {addStudents: studId})
                });
                alert('Successfully removed!')
                window.location.reload();
            }
        } else {
            alert('Please select a student!');
        }
    }

    function viewPromote(status) {
        if (status === 'add promoted students') {
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id']?>&&addPromoted=1')
        } else {
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id']?>&&promoted=1')
        }
        window.location.reload();
    }

    function loadPage() {
        var promoted = '<?php if (isset($_GET['promoted'])) echo $_GET['promoted']?>';
        var addPromoted = '<?php if (isset($_GET['addPromoted'])) echo $_GET['addPromoted']?>';

        if (promoted) {
            showModal('view-promoted-students', 'Promoted Students')
        }
        if (addPromoted) {
            showModal('add-promoted-students', 'Add Promoted Students')
        }

    }

    loadPage();
</script>
<link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>