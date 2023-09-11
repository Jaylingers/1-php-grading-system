<?php global $mysqli, $rows;
$var = "teacher_list";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "delete from teachers_info where id = '$id'";
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
if (isset($_POST['add-new-teacher'])) {
    $fullName = $_POST['fullName'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $civilStatus = $_POST['civilStatus'];
    $subject = $_POST['subject'];
    $emailAddress = $_POST['emailAddress'];
    $password = $_POST['password'];
    $reTypePassword = $_POST['reTypePassword'];
    $room = $_POST['room'];
    $scheduleTime = $_POST['scheduleTime'];
    $day = $_POST['day'];
    $sql = "insert into teachers_info (fullname,address,gender,civil_status,subject,email_address,password,re_type_password,room,schedule_time,day) values ('$fullName','$address','$gender','$civilStatus','$subject','$emailAddress','$password','$reTypePassword','$room','$scheduleTime','$day')";
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

<div class="d-flex-end p-absolute w-100p h-100p t-60px">
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
            }
        </style>

        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px">

                <div class="pad-1em  f-weight-bold d-flex">
                    <h3>
                        TEACHER LIST
                    </h3>
                    <div class="r-50px p-absolute t-54px">
                        <button
                                class="btn btn-success bg-hover-gray-dark-v1"
                                onclick="showModal('add-new-teacher', 'New Teachers','gray')">
                            Add Teacher
                        </button>
                        <button
                                class="btn bg-hover-gray-dark-v1"
                                onclick="deleteStudents('student-list')">Delete Selected
                        </button>
                    </div>
                </div>
                <br/>

                <?php
                $sql = "SELECT ti.id, ti.lrn,ti.fullname, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject_name SEPARATOR ', ') as subject_name
                FROM `teachers_subject_info` tsi
                inner join teachers_info ti on ti.lrn = tsi.teachers_info_lrn";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = $row['id'] + 1;
                $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "students".
                $total_pages = $mysqli->query("SELECT ti.id,ti.lrn,ti.fullname, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject_name SEPARATOR ', ') as subject_name
                FROM `teachers_subject_info` tsi
                inner join teachers_info ti on ti.lrn = tsi.teachers_info_lrn")->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare("SELECT ti.id,ti.lrn,ti.fullname, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject_name SEPARATOR ', ') as subject_name
                FROM `teachers_subject_info` tsi
                inner join teachers_info ti on ti.lrn = tsi.teachers_info_lrn ORDER BY ti.id LIMIT ?,?")) {
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
                            <th class="t-align-center"><label for="student-list-cb" class="d-flex-center"></label><input
                                        id="student-list-cb" type="checkbox"
                                        onclick="checkCBStudents('student-list', 'student-list-cb')"
                                        class="sc-1-3 c-hand"/></th>
                            <th>No</th>
                            <th>Fullname</th>
                            <th>Address</th>
                            <th>Gender</th>
                            <th>Civil Status</th>
                            <th>Subject</th>
                            <th>Email Address</th>
                            <th>Action</th>

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
                                <td><?= $row['fullname'] ?></td>
                                <td><?= $row['address'] ?></td>
                                <td><?= $row['gender'] ?></td>
                                <td><?= $row['civil_status'] ?></td>
                                <td><?= $row['subject_name'] ?></td>
                                <td><?= $row['email_address'] ?></td>
                                <td>
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewStudentEnrollment('<?= $row['lrn'] ?>')"
                                    >Subject Loads</label>
                                    &nbsp;
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewStudentInformation('<?= "[" . $row['lrn'] . "?" . $row['f_name'] . "?" . $row['l_name'] . "?" . $row['b_date'] . "?" . $row['age'] . "?" . $row['home_address'] . "?" . $row['guardian_name'] . "?" . $row['g_level'] . "?" . $row['c_status'] . "?" . $row['religion'] . "?" . $row['contact_number'] . "?" . $row['m_name'] . "?" . $row['b_place'] . "?" . $row['nationality'] . "?" . $row['email_address'] . "?" . $row['gender'] . "]" ?>')"
                                    >Students</label>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>

                    <?php
                    $stmt->close();
                }
                ?>
            </div>
        </div>

        <div class="m-2em d-flex-end m-t-n1em">
            <div class="d-flex-center">
                <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="prev"><a
                                        href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page > 3): ?>
                            <li class="start"><a
                                        href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page + 1 ?>">1</a>
                            </li>
                            <li class="dots">...</li>
                        <?php endif; ?>

                        <?php if ($page - 2 > 0): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                            </li><?php endif; ?>
                        <?php if ($page - 1 > 0): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                            </li><?php endif; ?>

                        <li class="currentpage"><a
                                    href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                        </li>

                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                            </li><?php endif; ?>
                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                            </li><?php endif; ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                            <li class="dots">...</li>
                            <li class="end"><a
                                        href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                            <li class="next"><a
                                        href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page + 1 ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
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
                    <div class="custom-grid-container" tabindex="2">
                        <div class="custom-grid-item ">
                            <div class="w-70p m-l-1em">Fullname</div>
                            <input placeholder="Fullname" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="fullName"
                                   name="fullName"
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
                            <input placeholder="Civil Status" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="civilStatus"
                                   name="civilStatus"
                                   required>

                            <div class="w-70p m-l-1em">Email</div>
                            <input placeholder="Email Address" type="email"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="emailAddress"
                                   name="emailAddress"
                                   required>
                            <div class="w-70p m-l-1em">Password</div>
                            <input placeholder="Password" type="password"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="password"
                                   name="password"
                                   required>
                            <div class="w-70p m-l-1em">Re-type Password</div>
                            <input placeholder="Re-type Password" type="password"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="reTypePassword"
                                   name="reTypePassword"
                                   required>
                        </div>
                        <div class="custom-grid-item">
                            <div>
                                Add Subject &nbsp;<label class="btn btn-primary" onclick="addSubject()">+</label>
                            </div>
                            <div id="subject-list">

                            </div>

                            <!--                        <div class="w-70p m-l-1em">Subject</div>-->
                            <!--                        <input placeholder="Subject" type="text"-->
                            <!--                               class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"-->
                            <!--                               id="subject"-->
                            <!--                               name="subject"-->
                            <!--                               required>-->
                            <!--                        <div class="w-70p m-l-1em">Room</div>-->
                            <!--                        <input placeholder="Room" type="text"-->
                            <!--                               class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"-->
                            <!--                               id="room"-->
                            <!--                               name="room"-->
                            <!--                               required>-->
                            <!--                        <div class="w-70p m-l-1em">Schedule Time</div>-->
                            <!--                        <input placeholder="Schedule Time" type="time"-->
                            <!--                               class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"-->
                            <!--                               id="scheduleTime"-->
                            <!--                               name="scheduleTime"-->
                            <!--                               required>-->
                            <!--                        <div class="w-70p m-l-1em">Day</div>-->
                            <!--                        <input placeholder="Day" type="number"-->
                            <!--                               class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"-->
                            <!--                               id="day"-->
                            <!--                               name="day"-->
                            <!--                               required>-->


                        </div>
                    </div>
                    <div class="p-absolute btm-1em r-1em">
                        <button type="submit"
                                class="c-hand btn-success btn"
                                name="add-new-teacher">Save
                        </button>
                    </div>
                </form>
            </div>
            <div id="view-student-enrollment" class="modal-child pad-bottom-2em d-none">
                <div class="d-flex-end gap-1em">
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
                    $sql = "select CONCAT(si.l_name, ', ', si.f_name,' ', si.m_name) as 'fullname', sei.grade, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si inner join students_enrollment_info sei on si.lrn = sei.students_info_id where si.lrn = '$lrns' ";
                    $students_enrollment_info_result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($students_enrollment_info_result);
                    $lrn = $row['id'] + 1;
                    $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                    // Get the total number of records from our table "students".
                    $total_pages = $mysqli->query("select CONCAT(si.l_name, ', ', si.f_name,' ', si.m_name) as 'fullname', sei.grade, sei.school_year, sei.date_enrolled, sei.status,sei.id from students_info si inner join students_enrollment_info sei on si.lrn = sei.students_info_id where si.lrn = '$lrns' ")->num_rows;
                    //  Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                    $page = isset($_GET['page_enrollment']) && is_numeric($_GET['page_enrollment']) ? $_GET['page_enrollment'] : 1;

                    // Number of results to show on each page.
                    $num_results_on_page = 5;

                    if ($stmt = $mysqli->prepare("select CONCAT(si.l_name, ', ', si.f_name,' ', si.m_name) as 'fullname', sei.grade, sei.school_year, sei.date_enrolled, sei.status, sei.id  from students_info si inner join students_enrollment_info sei on si.lrn = sei.students_info_id where si.lrn = '$lrns' ORDER BY si.id LIMIT ?,?")) {
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
                                    <td><?= $row['school_year'] ?></td>
                                    <td><?= $row['date_enrolled'] ?></td>
                                    <td><?= $row['status'] ?></td>

                                    <td>
                                        <label for="" class="t-color-red c-hand f-weight-bold"
                                               onclick="showGrade('<?= $row['fullname'] ?>','<?= $row['grade'] ?>', '<?= $row['school_year'] ?>')">View
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
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">Next</a>
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
                    history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id'] ?>');
                }
                alert('Successfully deleted!')
                window.location.reload();

            }
        } else {
            alert('Please select a teacher!');
        }
    }

    function viewStudentEnrollment(lrn) {
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id'] ?>&&lrn=' + lrn);
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
        xdialog.confirm('Choose Print Orientation? hmmm...', function () {
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

    function addSubject() {
        $('#subject-list').append('<div> 1 </div>')
    }

</script>
<link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>