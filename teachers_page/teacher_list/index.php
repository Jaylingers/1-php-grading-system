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

    $sqlDelete = "delete from teachers_subject_info where teachers_lrn = '$lrn'";
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
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $civilStatus = $_POST['civilStatus'];
    $emailAddress = $_POST['emailAddress'];

    $sql = "insert into teachers_info (lrn, first_name, last_name,address,gender,civil_status,email_address) values ('$lrn','$firstName','$lastName','$address','$gender','$civilStatus','$emailAddress')";
    $result = mysqli_query($conn, $sql);

    $sqlUserInfo = "insert into users_info (last_name,first_name,username,password,user_type,user_lrn) VALUES ('$lastName','$firstName','$lrn','$lastName','teacher','$lrn')";
    $resultUserInfo = mysqli_query($conn, $sqlUserInfo);

//    $addSubject = $_POST['add-subject'];
//    $array = explode(" , ", $addSubject);
//    debug_to_console($array);
//    for ($i = 0; $i < count($array); $i++) {
//        debug_to_console($array[$i]);
//        $array1 = explode(",", $array[$i]);
//
//        $subject = $array1[0];
//        $room = $array1[1];
//        $grade_level = $array1[2];
//        $schedule_time_in = $array1[3];
//        $schedule_time_out = $array1[4];
//        $schedule_day = $array1[5];
//
////        debug_to_console($subject);
////        debug_to_console($room);
////        debug_to_console($grade_level);
////        debug_to_console($schedule_time_in);
////        debug_to_console($schedule_time_out);
////        debug_to_console($schedule_day);
//
//        $subject = str_replace("[", "", $subject);
//        $schedule_day = str_replace("]", "", $schedule_day);
//
//        $sql = "insert into teachers_subject_info (subject,room,grade_level,schedule_time_in, schedule_time_out,schedule_day,teachers_lrn) values ($subject,$room,$grade_level,$schedule_time_in,$schedule_time_out,$schedule_day,'$lrn')";
//        $result = mysqli_query($conn, $sql);
//    }

    if ($result) {
        echo '<script>';
        echo '
        alert("Successfully Added");
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

if (isset($_POST['add-new-subject'])) {
    $lrn = $_GET['lrn'];
    $name = $_GET['name'] . '"s';
    $subject = $_POST['subject'];
    $room = $_POST['room'];
    $grade_level = $_POST['grade-level'];
    $time_in = $_POST['time-in'];
    $time_out = $_POST['time-out'];
    $schedule_day = $_POST['schedule-day'];

    $sql = "insert into teachers_subject_info (subject,room,grade_level,schedule_time_in, schedule_time_out,schedule_day,teachers_lrn) values ('$subject','$room','$grade_level','$time_in','$time_out','$schedule_day','$lrn')";
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
    $sqlUpdate = "update teachers_subject_info set subject='$subject', room='$room', grade_level='$grade_level', schedule_time_in='$time_in', schedule_time_out='$time_out', schedule_day='$schedule_day' where id='$id'";
    $resultUpdate = mysqli_query($conn, $sqlUpdate);
}

if (isset($_POST['studentID'])) {
    $student_lrn = $_POST['studentID'];
    $teacher_lrn = $_GET['teachers_lrn'];
    $grade = $_GET['searchGrade'];

    $sqlSelect = "select * from teachers_subject_info where teachers_lrn='$teacher_lrn' and grade_level='$grade'";
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

                <div class="pad-1em  f-weight-bold d-flex">
                    <h3>
                        TEACHER LIST
                    </h3>
                    <div class="r-50px p-absolute t-54px">
                        <button
                                class="btn btn-success bg-hover-gray-dark-v1"
                                onclick="showModal('add-new-teacher', 'New Teachers','','small')">
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
                $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
                $sql = "SELECT ti.id as id, ti.lrn,ti.first_name, ti.last_name, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject SEPARATOR ', ') as subject
                FROM `teachers_subject_info` tsi
                right join teachers_info ti on ti.lrn = tsi.teachers_lrn 
                WHERE CONCAT_WS('', ti.first_name, ti.last_name) LIKE '%$searchName%'
                GROUP BY ti.lrn order by ti.id desc";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                $lrn = 'T' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "teachers".
                $total_pages = $mysqli->query("SELECT ti.id,ti.lrn,ti.first_name, ti.last_name, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject SEPARATOR ', ') as subject
                FROM `teachers_subject_info` tsi
                right join teachers_info ti on ti.lrn = tsi.teachers_lrn
                WHERE CONCAT_WS('', ti.first_name, ti.last_name) LIKE '%$searchName%'
                GROUP BY ti.lrn order by ti.id desc")->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare("SELECT ti.id,ti.lrn,ti.first_name, ti.last_name, ti.address, ti.gender, ti.civil_status, ti.email_address,
                GROUP_CONCAT( tsi.subject SEPARATOR ', ') as subject
                FROM `teachers_subject_info` tsi
                right join teachers_info ti on ti.lrn = tsi.teachers_lrn 
                WHERE CONCAT_WS('', ti.first_name, ti.last_name) LIKE '%$searchName%'
                GROUP BY ti.lrn ORDER BY ti.id LIMIT ?,?")) {
                    // Calculate the page to get the results we need from our table.
                    $calc_page = ($page - 1) * $num_results_on_page;
                    $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                    $stmt->execute();
                    // Get the results...
                    $result = $stmt->get_result();
                    ?>
                    <input placeholder="search name" id="search_name" type="text" class=" m-b-5px"
                           onchange="searchName()"/>
                    <table class="table table-1  b-shadow-dark ">
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
                                <td><?= $row['last_name'] ?> <?= $row['first_name'] ?></td>
                                <td><?= $row['address'] ?></td>
                                <td><?= $row['gender'] ?></td>
                                <td><?= $row['civil_status'] ?></td>
                                <td><?= $row['subject'] ?></td>
                                <td><?= $row['email_address'] ?></td>
                                <td>
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewTeacherEnrollment('<?= $row['lrn'] ?>', '<?= $row['last_name'] ?>')"
                                    >Subject Loads</label>
                                    &nbsp;
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewTeacherStudents('<?= $row['lrn'] ?>', '<?= $row['last_name'] ?>')"
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
                                   readonly="true"
                                   value="<?php echo $lrn ?>">
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
                    $sql = " select id, subject,grade_level, schedule_day, room, CONCAT(`schedule_time_in`, ' - ', `schedule_time_out`) as 'schedule_time', schedule_time_in, schedule_time_out from teachers_subject_info where teachers_lrn='$lrns' ";
                    $teachers_enrollment_info_result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($teachers_enrollment_info_result);
                    $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                    $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                    // Get the total number of records from our table "teachers".
                    $total_pages = $mysqli->query("select id, subject,grade_level, schedule_day, room, CONCAT(`schedule_time_in`, ' - ', `schedule_time_out`) as 'schedule_time', schedule_time_in, schedule_time_out from teachers_subject_info where teachers_lrn='$lrns' ")->num_rows;
                    //  Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                    $page = isset($_GET['page_enrollment']) && is_numeric($_GET['page_enrollment']) ? $_GET['page_enrollment'] : 1;

                    // Number of results to show on each page.
                    $num_results_on_page = 5;

                    if ($stmt = $mysqli->prepare("select id, subject, grade_level, schedule_day, room, CONCAT(`schedule_time_in`, ' - ', `schedule_time_out`) as 'schedule_time', schedule_time_in, schedule_time_out from teachers_subject_info where teachers_lrn='$lrns' ORDER BY id LIMIT ?,?")) {
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
                <select name="student_grade" id="student_grade" onchange="changeStudentGrade()" >
                    <option value="" disabled selected>Grade</option>
                    <option value="1">Grade 1</option>
                    <option value="2">Grade 2</option>
                    <option value="3">Grade 3</option>
                    <option value="4">Grade 4</option>
                    <option value="5">Grade 5</option>
                    <option value="6">Grade 6</option>
                </select>
<!--                --><?php
//                if (isset($_GET['teachers_lrn'])) {
//                    $teacher_lrn = $_GET['teachers_lrn'];
//                    $sql = "select * from teachers_subject_info where teachers_lrn='$teacher_lrn' GROUP BY grade_level order by grade_level ASC";
//                    $result = mysqli_query($conn, $sql);
//
//                    if (mysqli_num_rows($result)) { ?>
<!--                        <select name="student_grade" id="student_grade" onchange="changeStudentGrade()">-->
<!--                            <option value=""></option>-->
<!--                            --><?php
//                            $i = 0;
//                            while ($rows = mysqli_fetch_assoc($result)) {
//                                $i++;
//                                ?>
<!--                                --><?php //if ($rows['grade_level'] == $_GET['searchGrade']) { ?>
<!--                                    <option value="--><?php //= $rows['grade_level'] ?><!--"-->
<!--                                            selected> --><?php //= $rows['grade_level'] ?><!-- </option>-->
<!--                                --><?php //} else { ?>
<!--                                    <option value="--><?php //= $rows['grade_level'] ?><!--">--><?php //= $rows['grade_level'] ?><!--</option>-->
<!--                                --><?php //} ?>
<!--                            --><?php //} ?>
<!--                        </select>-->
<!--                    --><?php //}
//                } ?>

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
                var status = '';
                teacherID.forEach(function (teachSubjID) {

                    if (id === 'teacher-subject') {
                        $.post('', {teacherSubjectID: teachSubjID})
                        status = 'teacher-subject';
                    } else if (id === 'teacher-students') {
                        teachSubjID = teachSubjID.split(',');
                        $.post('', {teacherStudentID: teachSubjID})
                        status = 'teacher-student';
                    } else {
                        $.post('', {lrn: teachSubjID})
                    }

                });
                if (status === 'teacher-subject') {
                    <?php
                    if (isset($_GET['lrn'])) {
                    ?>
                    history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id']?>&&lrn=<?php echo $_GET['lrn']?>&&name=<?php echo $_GET['name']?>');
                    <?php } ?>
                } else if (status === 'teacher-student') {
                    <?php
                    if (isset($_GET['teachers_lrn'])) {
                    ?>
                    history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id']?>&&teachers_lrn=<?php echo $_GET['teachers_lrn']?>&&name=<?php echo $_GET['name']?>');
                    <?php } ?>
                } else {
                    history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>');
                }
                alert('Successfully deleted!')
                window.location.reload();

            }
        } else {
            if (id === 'teacher-subject') {
                alert('Please select a subject!');
            } else if (id === 'teacher-students') {
                alert('Please select a student!');
            } else {
                alert('Please select a teacher!');
            }

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

    function searchName() {
        var search = $('#search_name').val();
        if (search !== '') {
            window.location.href = '?id=<?php echo $_GET['id'] ?>&&searchName=' + search;
        } else {
            window.location.href = '?id=<?php echo $_GET['id'] ?>';
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