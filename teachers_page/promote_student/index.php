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
    $date = date('Y');

    $sqlInsertStudentEnrollment = "INSERT INTO students_enrollment_info (students_info_lrn, grade, section, school_year,date_enrolled,status,grade_status) VALUES ('$lrn', '$grade_plus', '',$date,now(),'Enrolled','Promoted')";
    $resultInsertStudentEnrollment = mysqli_query($conn, $sqlInsertStudentEnrollment);

    $sqlUpdateStudentinfo = "UPDATE students_info SET teacher_lrn=''  WHERE lrn = '$lrn'";
    $resultUpdateStudentinfo = mysqli_query($conn, $sqlUpdateStudentinfo);

    $id = $_GET['id'];
    $sqlUserInfo = "SELECT * FROM `users_info` where id = '$id'";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);
    $row = mysqli_fetch_assoc($resultUserInfo);
    $teacher_lrn = $row['user_lrn'];

    $sqlInsertPromoteStudentsHistory = "INSERT INTO promoted_info (student_lrn, teacher_lrn, grade, section, date) VALUES ('$lrn', '$teacher_lrn', '$grade_plus', '$section',now())";
    $resultInsertPromoteStudentsHistory = mysqli_query($conn, $sqlInsertPromoteStudentsHistory);

    $sqlInsertPromotedInfo = "INSERT INTO promoted_students_history (student_lrn, teacher_lrn, grade, section, date) VALUES ('$lrn', '$teacher_lrn', '$grade', '$section',now())";
    $resultInsertPromoteStudentsHistory = mysqli_query($conn, $sqlInsertPromotedInfo);

}

if (isset($_POST['removeStudents'])) {
    $removePromotedStudents = $_POST['removeStudents'];
    $removePromotedStudents = explode(',', $removePromotedStudents);
    $lrn_promoted = $removePromotedStudents[0];
    $grade_promoted = $removePromotedStudents[1];

    $sqlSelectPromotedStudents = "SELECT * FROM `promoted_students_history` where student_lrn = '$lrn_promoted' and grade = '$grade_promoted'";
    $resultSelectPromotedStudents = mysqli_query($conn, $sqlSelectPromotedStudents);
    $row = mysqli_fetch_assoc($resultSelectPromotedStudents);
    $teacher_lrn = $row['teacher_lrn'];
    $section = $row['section'];

    $sql = "UPDATE students_enrollment_info SET grade = '$grade_promoted', grade_status='Passed', section='$section' WHERE students_info_lrn = '$lrn_promoted' and grade = '$grade_promoted'";
    $result = mysqli_query($conn, $sql);

    $sqlUpdateStudentinfo = "UPDATE students_info SET teacher_lrn='$teacher_lrn'  WHERE lrn = '$lrn_promoted'";
    $resultUpdateStudentinfo = mysqli_query($conn, $sqlUpdateStudentinfo);

    $sqlDeletePromotedStudentsHistory = "DELETE FROM promoted_students_history WHERE student_lrn = '$lrn_promoted' and grade = '$grade_promoted'";
    $resultDeletePromotedStudentsHistory = mysqli_query($conn, $sqlDeletePromotedStudentsHistory);

    $sqlDeleteStudentEnrollment = "DELETE FROM students_enrollment_info WHERE students_info_lrn = '$lrn_promoted' and grade > '$grade_promoted'";
    $resultDeleteStudentEnrollment = mysqli_query($conn, $sqlDeleteStudentEnrollment);

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

    $sqlDeletePromotedStudentsHistory = "DELETE FROM promoted_info WHERE student_lrn = '$lrn_promoted'";
    $resultDeletePromotedStudentsHistory = mysqli_query($conn, $sqlDeletePromotedStudentsHistory);

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
        </style>

        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px pad-1em">

                <div class="m-t-19px m-l-13px f-weight-bold d-flex">
                    <h3 id="mobile-a">
                        Promote Students
                    </h3>
                    <div class="r-50px p-absolute t-54px">
                        <?php
                        $id = $_GET['id'];
                        $sqlSelectUser = "SELECT * FROM `users_info` ui
                                                left join teachers_info ti on ti.lrn = ui.user_lrn
                                                where ui.id = '$id'";
                        $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                        $row = mysqli_fetch_assoc($resultSelectUser);
                        if ($row['grade'] !== "1") { ?>

                            <button
                                    class="btn bg-hover-gray-dark-v1 d-inline-flex d-flex-center"
                                    onclick="viewPromote('add promoted students')">
                                <svg fill="none" width="35" height="35" viewBox="0 0 48 48"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 28H24" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="4"/>
                                    <path d="M8 37H24" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="4"/>
                                    <path d="M8 19H40" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="4"/>
                                    <path d="M8 10H40" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="4"/>
                                    <path d="M30 33H40" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="4"/>
                                    <path d="M35 28L35 38" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="4"/>
                                </svg>
                                &nbsp; Add Promoted Students(Grade <?= $row['grade'] ?>)
                            </button>
                        <?php } ?>
                        <button
                                class="btn bg-hover-gray-dark-v1 d-inline-flex d-flex-center"
                                onclick="viewPromote('')">
                            <svg fill="none" width="35" height="35" viewBox="0 0 48 48"
                                 xmlns="http://www.w3.org/2000/svg">
                                <rect fill="white" fill-opacity="0.01" height="48" width="48"/>
                                <path d="M5 10L8 13L14 7" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="4"/>
                                <path d="M5 24L8 27L14 21" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="4"/>
                                <path d="M5 38L8 41L14 35" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="4"/>
                                <path d="M21 24H43" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="4"/>
                                <path d="M21 38H43" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="4"/>
                                <path d="M21 10H43" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="4"/>
                            </svg>
                            &nbsp; View Promote
                        </button>

                    </div>
                </div>
                <br/>

                <div class="f-right m-t-19px m-r-13px " onclick="promote()">
                    <button type="submit"
                            class="c-hand bg-hover-skyBlue btn d-inline-flex d-flex-center"
                            >
                        <svg fill="none" width="30" height="30" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 14C10.2091 14 12 12.2091 12 10C12 7.79086 10.2091 6 8 6C5.79086 6 4 7.79086 4 10C4 12.2091 5.79086 14 8 14Z"
                                  fill="#2F88FF" stroke="black" stroke-linejoin="round" stroke-width="4"/>
                            <path d="M8 26C9.10457 26 10 25.1046 10 24C10 22.8954 9.10457 22 8 22C6.89543 22 6 22.8954 6 24C6 25.1046 6.89543 26 8 26Z"
                                  stroke="black" stroke-linejoin="round" stroke-width="4"/>
                            <path d="M8 40C9.10457 40 10 39.1046 10 38C10 36.8954 9.10457 36 8 36C6.89543 36 6 36.8954 6 38C6 39.1046 6.89543 40 8 40Z"
                                  stroke="black" stroke-linejoin="round" stroke-width="4"/>
                            <path d="M20 24H44" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="4"/>
                            <path d="M20 38H44" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="4"/>
                            <path d="M20 10H44" stroke="black" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="4"/>
                        </svg>
                        &nbsp; Promote
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
                        <input placeholder="from" id="search_name" type="date" class="search-from m-b-5px" onchange="search('date')"/>
                        <input placeholder="to" id="search_name" type="date" class="search-to m-b-5px" onchange="search('date')"/>
                        <div class="f-right  m-r-13px">
                            <button type="submit"
                                    class="c-hand bg-hover-skyBlue btn"
                                    onclick="removePromotedStudents()"
                                    style="background-color: #ffffff !important; border-color: #ffffff;">
                                <svg class="c-hand" height="43" id="svg2"
                                     version="1.1" viewBox="0 0 99.999995 99.999995" width="50"
                                     xmlns="http://www.w3.org/2000/svg"
                                     xmlns:svg="http://www.w3.org/2000/svg">
                                    <defs id="defs4">
                                        <filter id="filter4510" style="color-interpolation-filters:sRGB">
                                            <feFlood flood-color="rgb(0,0,0)" flood-opacity="0.470588" id="feFlood4512"
                                                     result="flood"/>
                                            <feComposite id="feComposite4514" in="flood" in2="SourceGraphic"
                                                         operator="in"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur4516" in="composite1" result="blur"
                                                            stdDeviation="5"/>
                                            <feOffset dx="0" dy="4.7" id="feOffset4518" result="offset"/>
                                            <feComposite id="feComposite4520" in="SourceGraphic" in2="offset"
                                                         operator="over"
                                                         result="composite2"/>
                                        </filter>
                                        <filter id="filter5064" style="color-interpolation-filters:sRGB">
                                            <feFlood flood-color="rgb(206,242,245)" flood-opacity="0.835294"
                                                     id="feFlood5066"
                                                     result="flood"/>
                                            <feComposite id="feComposite5068" in="flood" in2="SourceGraphic"
                                                         operator="out"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur5070" in="composite1" result="blur"
                                                            stdDeviation="5.9"/>
                                            <feOffset dx="0" dy="-8.1" id="feOffset5072" result="offset"/>
                                            <feComposite id="feComposite5074" in="offset" in2="SourceGraphic"
                                                         operator="atop"
                                                         result="composite2"/>
                                        </filter>
                                        <filter id="filter5364" style="color-interpolation-filters:sRGB">
                                            <feFlood flood-color="rgb(0,0,0)" flood-opacity="0.835294" id="feFlood5366"
                                                     result="flood"/>
                                            <feComposite id="feComposite5368" in="flood" in2="SourceGraphic"
                                                         operator="in"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur5370" in="composite1" result="blur"
                                                            stdDeviation="5"/>
                                            <feOffset dx="0" dy="4.2" id="feOffset5372" result="offset"/>
                                            <feComposite id="feComposite5374" in="SourceGraphic" in2="offset"
                                                         operator="over"
                                                         result="fbSourceGraphic"/>
                                            <feColorMatrix id="feColorMatrix5592" in="fbSourceGraphic"
                                                           result="fbSourceGraphicAlpha"
                                                           values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/>
                                            <feFlood flood-color="rgb(254,255,189)" flood-opacity="1" id="feFlood5594"
                                                     in="fbSourceGraphic" result="flood"/>
                                            <feComposite id="feComposite5596" in="flood" in2="fbSourceGraphic"
                                                         operator="out"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur5598" in="composite1" result="blur"
                                                            stdDeviation="7.6"/>
                                            <feOffset dx="0" dy="-8.1" id="feOffset5600" result="offset"/>
                                            <feComposite id="feComposite5602" in="offset" in2="fbSourceGraphic"
                                                         operator="atop"
                                                         result="composite2"/>
                                        </filter>
                                        <filter id="filter4400" style="color-interpolation-filters:sRGB">
                                            <feFlood flood-color="rgb(0,0,0)" flood-opacity="0.470588" id="feFlood4402"
                                                     result="flood"/>
                                            <feComposite id="feComposite4404" in="flood" in2="SourceGraphic"
                                                         operator="in"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur4406" in="composite1" result="blur"
                                                            stdDeviation="5"/>
                                            <feOffset dx="0" dy="5" id="feOffset4408" result="offset"/>
                                            <feComposite id="feComposite4410" in="SourceGraphic" in2="offset"
                                                         operator="over"
                                                         result="fbSourceGraphic"/>
                                            <feColorMatrix id="feColorMatrix4640" in="fbSourceGraphic"
                                                           result="fbSourceGraphicAlpha"
                                                           values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/>
                                            <feFlood flood-color="rgb(255,253,180)" flood-opacity="1" id="feFlood4642"
                                                     in="fbSourceGraphic" result="flood"/>
                                            <feComposite id="feComposite4644" in="flood" in2="fbSourceGraphic"
                                                         operator="out"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur4646" in="composite1" result="blur"
                                                            stdDeviation="5"/>
                                            <feOffset dx="0" dy="-5" id="feOffset4648" result="offset"/>
                                            <feComposite id="feComposite4650" in="offset" in2="fbSourceGraphic"
                                                         operator="atop"
                                                         result="composite2"/>
                                        </filter>
                                        <filter id="filter4678" style="color-interpolation-filters:sRGB">
                                            <feFlood flood-color="rgb(255,253,180)" flood-opacity="1" id="feFlood4680"
                                                     result="flood"/>
                                            <feComposite id="feComposite4682" in="flood" in2="SourceGraphic"
                                                         operator="out"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur4684" in="composite1" result="blur"
                                                            stdDeviation="5"/>
                                            <feOffset dx="0" dy="-7" id="feOffset4686" result="offset"/>
                                            <feComposite id="feComposite4688" in="offset" in2="SourceGraphic"
                                                         operator="atop"
                                                         result="composite2"/>
                                        </filter>
                                        <filter id="filter5045" style="color-interpolation-filters:sRGB">
                                            <feFlood flood-color="rgb(255,250,175)" flood-opacity="1" id="feFlood5047"
                                                     result="flood"/>
                                            <feComposite id="feComposite5049" in="flood" in2="SourceGraphic"
                                                         operator="out"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur5051" in="composite1" result="blur"
                                                            stdDeviation="5"/>
                                            <feOffset dx="0" dy="-6" id="feOffset5053" result="offset"/>
                                            <feComposite id="feComposite5055" in="offset" in2="SourceGraphic"
                                                         operator="atop"
                                                         result="composite2"/>
                                        </filter>
                                        <filter id="filter4607" style="color-interpolation-filters:sRGB;">
                                            <feFlood flood-color="rgb(255,247,180)" flood-opacity="1" id="feFlood4609"
                                                     result="flood"/>
                                            <feComposite id="feComposite4611" in="flood" in2="SourceGraphic"
                                                         operator="out"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur4613" in="composite1" result="blur"
                                                            stdDeviation="5"/>
                                            <feOffset dx="0" dy="-6" id="feOffset4615" result="offset"/>
                                            <feComposite id="feComposite4617" in="offset" in2="SourceGraphic"
                                                         operator="atop"
                                                         result="composite2"/>
                                        </filter>
                                        <filter id="filter4507" style="color-interpolation-filters:sRGB;">
                                            <feFlood flood-color="rgb(255,249,199)" flood-opacity="1" id="feFlood4509"
                                                     result="flood"/>
                                            <feComposite id="feComposite4511" in="flood" in2="SourceGraphic"
                                                         operator="out"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur4513" in="composite1" result="blur"
                                                            stdDeviation="3"/>
                                            <feOffset dx="0" dy="-2.60417" id="feOffset4515" result="offset"/>
                                            <feComposite id="feComposite4517" in="offset" in2="SourceGraphic"
                                                         operator="atop"
                                                         result="fbSourceGraphic"/>
                                            <feColorMatrix id="feColorMatrix4687" in="fbSourceGraphic"
                                                           result="fbSourceGraphicAlpha"
                                                           values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/>
                                            <feFlood flood-color="rgb(255,244,153)" flood-opacity="1" id="feFlood4689"
                                                     in="fbSourceGraphic" result="flood"/>
                                            <feComposite id="feComposite4691" in="flood" in2="fbSourceGraphic"
                                                         operator="out"
                                                         result="composite1"/>
                                            <feGaussianBlur id="feGaussianBlur4693" in="composite1" result="blur"
                                                            stdDeviation="3.4"/>
                                            <feOffset dx="0" dy="-3.9" id="feOffset4695" result="offset"/>
                                            <feComposite id="feComposite4697" in="offset" in2="fbSourceGraphic"
                                                         operator="atop"
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
                            </button>
                        </div>
                        <br/>
                        <?php
                        $from = isset($_GET['from']) ? $_GET['from'] : '';
                        $to = isset($_GET['to']) ? $_GET['to'] : '';

                        // Extract year from the 'from' parameter
                        $from = date('Y', strtotime($from));

                        // Extract year from the 'to' parameter
                        $to = date('Y', strtotime($to));


                        $sqlSelectUser = "SELECT * FROM `users_info` where id = '$id'";
                        $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                        $row = mysqli_fetch_assoc($resultSelectUser);
                        $teacher_lrn = $row['user_lrn'];

                        $sqlSelectTeacher = "SELECT * FROM `teachers_info` where lrn = '$teacher_lrn'";
                        $resultSelectTeacher = mysqli_query($conn, $sqlSelectTeacher);
                        $rows = mysqli_fetch_assoc($resultSelectTeacher);
                        $grade = $rows['grade'];
                        $section = $rows['section'];

                        $sql = "SELECT sei.school_year,sei.date_enrolled,GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,ps.date as date_promoted,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_students_history ps on ps.student_lrn = sei.students_info_lrn
                        where sei.grade='$grade' and ps.section='$section'
                       AND sei.school_year >= '$from' AND sei.school_year <= '$to'
                        GROUP BY si.id order by si.lrn DESC Limit 1";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                        $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                        // Get the total number of records from our table "students".
                        $total_pages = $mysqli->query("SELECT sei.school_year,sei.date_enrolled,GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,ps.date as date_promoted,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_students_history ps on ps.student_lrn = sei.students_info_lrn
                        where sei.grade='$grade'  and ps.section='$section'
                        AND sei.school_year >= '$from' AND sei.school_year <= '$to'
                        GROUP BY si.id order by si.lrn DESC")->num_rows;
                        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        // Number of results to show on each page.
                        $num_results_on_page = 10;

                        if ($stmt = $mysqli->prepare("SELECT sei.school_year,sei.date_enrolled,GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,ps.date as date_promoted,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_students_history ps on ps.student_lrn = sei.students_info_lrn
                        where sei.grade='$grade'  and ps.section='$section'
                        AND sei.school_year >= '$from' AND sei.school_year <= '$to'
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
                                <th>School Year</th>
                                <th>Date Enrolled</th>
                                <th>Date Promoted</th>
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
                                    <td><?= $row['g_level'] ?></td>
                                    <td><?= $row['section'] ?></td>
                                    <td><?= $row['school_year'] ?></td>
                                    <td><?= $row['date_enrolled'] ?></td>
                                    <td><?= $row['date_promoted'] ?></td>
                                    <td>Promoted to Grade <?= $row['g_level'] + 1 ?></td>
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
                                    onclick="addPromotedStudents()"
                                    style="background-color: #ffffff !important; border-color: #ffffff;">
                                <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
                            </button>
                        </div>
                        <br/> <br/>
                        <?php
                        $sqlSelectUser = "select * from teachers_info ti
                                            left join users_info ui on ui.user_lrn=ti.lrn 
                                            where ui.id='$id'";
                        $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
                        $row = mysqli_fetch_assoc($resultSelectUser);
                        $grade = $row['grade'];

                        $sql = "SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_info psh on psh.student_lrn = sei.students_info_lrn and sei.grade = psh.grade
                        where psh.grade='$grade' 
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
                       inner join promoted_info psh on psh.student_lrn = sei.students_info_lrn and sei.grade = psh.grade
                        where psh.grade='$grade' 
                        GROUP BY si.id order by si.lrn DESC")->num_rows;
                        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        // Number of results to show on each page.
                        $num_results_on_page = 10;

                        if ($stmt = $mysqli->prepare("SELECT GROUP_CONCAT( sei.grade SEPARATOR ', ') as g_level, si.id, si.lrn, si.f_name, si.l_name, si.b_date, si.age, si.gender,
                        sei.section,si.c_status, si.religion, si.contact_number, si.m_name, si.b_place, si.nationality, si.email_address,si.home_address, si.guardian_name, sei.grade_status
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
                        inner join promoted_info psh on psh.student_lrn = sei.students_info_lrn and sei.grade = psh.grade
                        where psh.grade='$grade' 
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

    $(document).on('click', '#modal-delete-cancel', function (e) {
        $('#modal-delete').attr('style', 'display: none !important;')
        $('#modal-checkbox').attr('style', 'display: none !important;')
        $('#modal-promote').attr('style', 'display: none;')
        $('#modal-failed-students').attr('style', 'display: none;')
    });

    $(document).on('click', '#modal-promote-cancel', function (e) {
        $('#modal-promote').attr('style', 'display: none;')
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

    $(document).on('click', '#modal-promote-ok', function (e) {
        promoteAction();
        $('#modal-promote').attr('style', 'display: none !important;')
    });

    function promote() {
        var idArray = [];
        var count = 0;
        var isFailed = false;
        $('#student-list input[type="checkbox"]:checked').each(function () {
            idArray.push($(this).attr('id'));
            count++;
        });
        if (count > 0) {
            idArray.forEach(function (studId) {
                if (!studId.includes('Passed')) {
                    isFailed = true;
                }
            });
            if (isFailed) {
                $('#modal-failed-students').attr('style', 'display: block;')
            } else {
                $('#modal-promote').attr('style', 'display: block;')
            }
        } else {
            $('#modal-checkbox').attr('style', 'display: block;')
        }
    }

    function promoteAction() {
        var idArray = [];
        var count = 0;
        $('#student-list input[type="checkbox"]:checked').each(function () {
            idArray.push($(this).attr('id'));
            count++;
        });
        if (count > 0) {
            idArray.forEach(function (data) {
                $.post('', {promoteStudent: data})
            });
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id']?>');
            window.location.reload();
        }
    }

    $(document).on('click', '#modal-remove-promote-students-cancel', function (e) {
        $('#modal-remove-promote-students').attr('style', 'display: none;')
    });

    $(document).on('click', '#modal-remove-promote-students-ok', function (e) {
        removePromotedStudentsAction();
        $('#modal-remove-promote-students').attr('style', 'display: none;')
    });

    function removePromotedStudents() {
        var count = 0;
        $('#promoted-students input[type="checkbox"]:checked').each(function () {
            count++;
        });
        if (count > 0) {
                $('#modal-remove-promote-students').attr('style', 'display: block;')
        } else {
            $('#modal-checkbox').attr('style', 'display: block;')
        }
    }

    function removePromotedStudentsAction() {
        var idArray = [];
        var count = 0;
        $('#promoted-students input[type="checkbox"]:checked').each(function () {
            idArray.push($(this).attr('id'));
            count++;
        });
        if (count > 0) {
            idArray.forEach(function (data) {
                $.post('', {removeStudents: data})
            });
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id']?>');
            window.location.reload();
        }
    }

    $(document).on('click', '#modal-add-cancel', function (e) {
        $('#modal-add').attr('style', 'display: none;')
    });

    $(document).on('click', '#modal-add-ok', function (e) {
        $('#modal-add').attr('style', 'display: none;')
        addPromotedStudentsAction();
    });

    function addPromotedStudents() {
        var count = 0;
        $('#promoted-students input[type="checkbox"]:checked').each(function () {
            count++;
        });
        if (count > 0) {
            $('#modal-add').attr('style', 'display: block;')
        } else {
            $('#modal-checkbox').attr('style', 'display: block;')
        }
    }

    function addPromotedStudentsAction() {
        var idArray = [];
        var count = 0;
        $('#promoted-students input[type="checkbox"]:checked').each(function () {
            idArray.push($(this).attr('id'));
            count++;
        });
        if (count > 0) {
            idArray.forEach(function (data) {
                $.post('', {addStudents: data})
            });
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id']?>');
            window.location.reload();
        }
    }

    function viewPromote(status) {
        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        var nextYear = currentYear + 1;

        if (status === 'add promoted students') {
            history.pushState({ page: 'another page' }, 'another page', `?id=<?php echo $_GET['id']?>&&addPromoted=1`);
        } else {
            var fromDate = `${currentYear}-12-08`;
            var toDate = `${nextYear}-12-09`;
            history.pushState({ page: 'another page' }, 'another page', `?id=<?php echo $_GET['id']?>&&promoted=1&&from=${fromDate}&&to=${toDate}`);
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

        var added_successfully = '<?php echo isset($_GET['added_successfully']) ? $_GET['added_successfully'] : '' ?>';
        if (added_successfully !== '') {
            $('#modal-addedSuccessfully').attr('style', 'display: block;')
        }
        
        var from = '<?php echo isset($_GET['from']) ? $_GET['from'] : '' ?>';
        if (from !== '') {
            $('.search-from').val(from);
        }

        var to = '<?php echo isset($_GET['to']) ? $_GET['to'] : '' ?>';
        if (to !== '') {
            $('.search-to').val(to);
        }



    }

    function search(status) {
            var from = $('.search-from').val();
            var to = $('.search-to').val();
            window.location.href = '?id=<?php echo $_GET['id'] ?>&&promoted=<?php echo  isset($_GET['promoted']) ? $_GET['promoted'] : '' ?>&&from=' + from + '&&to=' + to;
    }
    loadPage();
</script>
<link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>