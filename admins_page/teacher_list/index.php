<?php global $mysqli, $rows;
$var = "teacher_list";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['lrn'])) {
    $lrn = $_POST['lrn'];
    $sql = "delete from teachers_info where lrn = '$lrn'";
    $result = mysqli_query($conn, $sql);

    $sqlDelete = "delete from teachers_subject_info where teachers_info_lrn = '$lrn'";
    $resultDelete = mysqli_query($conn, $sqlDelete);

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
    $fullName = $_POST['fullName'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $civilStatus = $_POST['civilStatus'];
    $emailAddress = $_POST['emailAddress'];
    $password = $_POST['password'];
    $reTypePassword = $_POST['reTypePassword'];

    $sql = "insert into teachers_info (lrn, fullname,address,gender,civil_status,email_address,password,re_type_password) values ('$lrn','$fullName','$address','$gender','$civilStatus','$emailAddress','$password','$reTypePassword')";
    $result = mysqli_query($conn, $sql);


    $addSubject = $_POST['add-subject'];
    $array = explode(" , ", $addSubject);
    debug_to_console($array);
    for ($i = 0; $i < count($array); $i++) {
        debug_to_console($array[$i]);
        $array1 = explode(",", $array[$i]);

        $subject = $array1[0];
        $room = $array1[1];
        $grade_level = $array1[2];
        $schedule_time_in = $array1[3];
        $schedule_time_out = $array1[4];
        $schedule_day = $array1[5];

//        debug_to_console($subject);
//        debug_to_console($room);
//        debug_to_console($grade_level);
//        debug_to_console($schedule_time_in);
//        debug_to_console($schedule_time_out);
//        debug_to_console($schedule_day);

        $subject = str_replace("[", "", $subject);
        $schedule_day = str_replace("]", "", $schedule_day);

        $sql = "insert into teachers_subject_info (subject,room,grade_level,schedule_time_in, schedule_time_out,schedule_day,teachers_info_lrn) values ($subject,$room,$grade_level,$schedule_time_in,$schedule_time_out,$schedule_day,'$lrn')";
        $result = mysqli_query($conn, $sql);
    }

    if ($result) {
        echo '<script>';
        echo '
              history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
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
                color: white;
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
                                onclick="deleteTeachers('teachers-list')">Delete Selected
                        </button>
                    </div>
                </div>
                <br/>

                <?php
                $sql = "SELECT ti.id as id, ti.lrn,ti.fullname, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject SEPARATOR ', ') as subject
                FROM `teachers_subject_info` tsi
                right join teachers_info ti on ti.lrn = tsi.teachers_info_lrn GROUP BY ti.lrn order by ti.id desc";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = $row['id'] + 1;
                $lrn = 'T' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "teachers".
                $total_pages = $mysqli->query("SELECT ti.id,ti.lrn,ti.fullname, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject SEPARATOR ', ') as subject
                FROM `teachers_subject_info` tsi
                right join teachers_info ti on ti.lrn = tsi.teachers_info_lrn GROUP BY ti.lrn order by ti.id desc")->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare("SELECT ti.id,ti.lrn,ti.fullname, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject SEPARATOR ', ') as subject
                FROM `teachers_subject_info` tsi
                right join teachers_info ti on ti.lrn = tsi.teachers_info_lrn GROUP BY ti.lrn ORDER BY ti.id LIMIT ?,?")) {
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
                            <th class="t-align-center"><label for="teacher-list-cb" class="d-flex-center"></label><input
                                        id="teacher-list-cb" type="checkbox"
                                        onclick="checkCBteachers('teacher-list', 'teacher-list-cb')"
                                        class="sc-1-3 c-hand"/></th>
                            <th>No</th>
                            <th>FullName</th>
                            <th>Address</th>
                            <th>Gender</th>
                            <th>Civil Status</th>
                            <th>Subject</th>
                            <th>Email Address</th>
                            <th>Action</th>

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
                                <td><?= $row['fullname'] ?></td>
                                <td><?= $row['address'] ?></td>
                                <td><?= $row['gender'] ?></td>
                                <td><?= $row['civil_status'] ?></td>
                                <td><?= $row['subject'] ?></td>
                                <td><?= $row['email_address'] ?></td>
                                <td>
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewteacherEnrollment('<?= $row['lrn'] ?>', '<?= $row['fullname'] ?>')"
                                    >Subject Loads</label>
                                    &nbsp;
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewteacherInformation('<?= "[" . $row['lrn'] . "?" . $row['f_name'] . "?" . $row['l_name'] . "?" . $row['b_date'] . "?" . $row['age'] . "?" . $row['home_address'] . "?" . $row['guardian_name'] . "?" . $row['g_level'] . "?" . $row['c_status'] . "?" . $row['religion'] . "?" . $row['contact_number'] . "?" . $row['m_name'] . "?" . $row['b_place'] . "?" . $row['nationality'] . "?" . $row['email_address'] . "?" . $row['gender'] . "]" ?>')"
                                    >teachers</label>
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
                                        href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page > 3): ?>
                            <li class="start"><a
                                        href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page + 1 ?>">1</a>
                            </li>
                            <li class="dots">...</li>
                        <?php endif; ?>

                        <?php if ($page - 2 > 0): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                            </li><?php endif; ?>
                        <?php if ($page - 1 > 0): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                            </li><?php endif; ?>

                        <li class="currentpage"><a
                                    href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                        </li>

                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                            </li><?php endif; ?>
                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                            </li><?php endif; ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                            <li class="dots">...</li>
                            <li class="end"><a
                                        href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                            <li class="next"><a
                                        href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page + 1 ?>">Next</a>
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
                            <input placeholder="<?php echo $lrn ?>" type="hidden"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="lrn-add"
                                   name="lrn-add"
                                   readonly="true"
                                   value="<?php echo $lrn ?>">
                            <div class="w-70p m-l-1em">FullName</div>
                            <input placeholder="FullName" type="text"
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
                                <input type="text" id="add-subject" name="add-subject">
                            </div>
                            <div id="add-subject-parent">

                            </div>
                        </div>
                    </div>
                    <div class="d-flex-end">
                        <button type="submit"
                                class="c-hand btn-success btn"
                                name="add-new-teacher">Save
                        </button>
                    </div>
                </form>
            </div>
            <div id="view-teacher-enrollment" class="modal-child pad-bottom-2em d-none">
                <div class="d-flex-end gap-1em">
                    <button
                            class="btn bg-hover-gray-dark-v1" onclick="showModal('add-enrollment', 'New Enrollment')">
                        Add New
                    </button>
                    <button
                            class="btn bg-hover-gray-dark-v1"
                            onclick="deleteTeachers('teacher-enrollment')">Delete Selected
                    </button>
                </div>
                <?php

                if (isset($_GET['lrn'])) {
                    $lrns = $_GET['lrn'];
                    $name = $_GET['name'] . '"s';
                    echo "<script>showModal('view-teacher-enrollment', '$name Subjects')</script>";
                    $sql = " select id, subject,grade_level, schedule_day, room, CONCAT(`schedule_time_in`, ' - ', `schedule_time_out`) as 'schedule_time' from teachers_subject_info where teachers_info_lrn='$lrns' ";
                    $teachers_enrollment_info_result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($teachers_enrollment_info_result);
                    $lrn = $row['id'] + 1;
                    $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                    // Get the total number of records from our table "teachers".
                    $total_pages = $mysqli->query("select id, subject,grade_level, schedule_day, room, CONCAT(`schedule_time_in`, ' - ', `schedule_time_out`) as 'schedule_time' from teachers_subject_info where teachers_info_lrn='$lrns' ")->num_rows;
                    //  Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                    $page = isset($_GET['page_enrollment']) && is_numeric($_GET['page_enrollment']) ? $_GET['page_enrollment'] : 1;

                    // Number of results to show on each page.
                    $num_results_on_page = 5;

                    if ($stmt = $mysqli->prepare("select id, subject, grade_level, schedule_day, room, CONCAT(`schedule_time_in`, ' - ', `schedule_time_out`) as 'schedule_time' from teachers_subject_info where teachers_info_lrn='$lrns' ORDER BY id LIMIT ?,?")) {
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
                                <th class="t-align-center"><label for="teacher-enrollment-cb"
                                                                  class="d-flex-center"></label><input
                                            id="teacher-enrollment-cb" type="checkbox"
                                            onclick="checkCBteachers('teacher-enrollment','teacher-enrollment-cb')"
                                            class="sc-1-3 c-hand"/></th>
                                <th>No</th>
                                <th>Subject</th>
                                <th>Grade Level</th>
                                <th>Day</th>
                                <th>Room</th>
                                <th>Schedule Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="teacher-enrollment">
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
                                                href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/teacher_list/?id=<?php echo $rows['id'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">Next</a>
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

    function checkCBteachers(id, cb) {
        if ($('#' + cb).is(':checked')) {
            $('#' + id + ' input[type="checkbox"]').prop('checked', true);

        } else {
            $('#' + id + ' input[type="checkbox"]').prop('checked', false);
        }
    }

    function deleteTeachers(id) {
        var teacherID = [];
        var teacherCount = 0;
        $('#' + id + ' input[type="checkbox"]:checked').each(function () {
            teacherID.push($(this).attr('id'));
            teacherCount++;
        });
        if (teacherCount > 0) {
            var r = confirm("Are you sure you want to delete ?");
            if (r === true) {
                var isteacherEnrollment = false;
                teacherID.forEach(function (studId) {

                    if (id === 'teacher-enrollment') {
                        $.post('', {teacherEnrollmentId: studId})
                        isteacherEnrollment = true;
                    } else {
                        $.post('', {lrn: studId})
                    }
                });
                if (isteacherEnrollment) {
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

    function viewteacherEnrollment(lrn, fullname) {
        var name = fullname.split(',')[0];
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id'] ?>&&lrn=' + lrn + '&&name=' + name);
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

    var arr = [];

    function addSubject() {
        var count = arr.length;
        arr[count] = ["''", "''", "''", "''", "''"];
        $('#add-subject').val(arr);

        $('#add-subject-parent').append('<div id="' + count + '" class="add-subject-child b-1px-black b-radius-10px m-b-1em" style="background:#ed7d31"> <div onclick="arrOnclick(' + count + ')" class="w-2em t-align-center f-r c-hand t-close"> X </div>' +
        '<div class="w-70p m-l-1em m-t-1em">Subject</div><input onchange="arrOnChange(' + count + ')" placeholder="Subject" type="text" class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="subject" name="subject" required> ' +
        '<div class="w-70p m-l-1em">Room</div><input onchange="arrOnChange(' + count + ')" placeholder="room" type="text" class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="room" name="room" required> <div class="w-70p m-l-1em">Grade Level</div><input onchange="arrOnChange(' + count + ')" placeholder="Grade Level" type="text" class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px" id="grade_level" name="grade_level" required><div class="w-70p m-l-1em">Time IN</div><input onchange="arrOnChange(' + count + ')" placeholder="Schedule Time" type="time" class="h-3em  f-size-1em b-radius-10px m-1em m-t-5px" id="schedule_time_in" name="schedule_time_in" required><div class="w-70p m-l-1em">Time Out</div><input onchange="arrOnChange(' + count + ')" placeholder="Schedule Time" type="time" class="h-3em  f-size-1em b-radius-10px m-1em m-t-5px" id="schedule_time_out" name="schedule_time_out" required>  ' +
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

</script>
<link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>