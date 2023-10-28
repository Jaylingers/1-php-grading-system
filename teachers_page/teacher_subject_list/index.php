<?php global $mysqli, $rows;
$var = "teacher_subject_list";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if(isset($_POST['add_subject'])) {

  $subject = $_POST['subject'];
    $room = $_POST['room'];
    $grade = $_POST['grade'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $schedule_day = $_POST['schedule_day'];

    $id = $_GET['id'];
    $sqlUser = "select * from users_info where id='$id'";
    $resultUser = mysqli_query($conn, $sqlUser);
    $rowUser = mysqli_fetch_assoc($resultUser);
    $userLrn = $rowUser['user_lrn'];

    $sqlCheckTeachersSubjectInfo = "select * from teachers_subject_info where subject='$subject' and teachers_lrn='$userLrn'";
    $resultCheckTeachersSubjectInfo = mysqli_query($conn, $sqlCheckTeachersSubjectInfo);
    $rowCheckTeachersSubjectInfo = mysqli_fetch_assoc($resultCheckTeachersSubjectInfo);
    if ($rowCheckTeachersSubjectInfo['subject'] == $subject) {
        echo '<script>';
        echo '
              alert("Subject already exist");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                    window.location.reload();
            ';
        echo '</script>';
        exit();
    }



    $sqlTeachersSubjectInfo = "insert into teachers_subject_info (subject, room, grade, schedule_time_in, schedule_time_out, schedule_day, teachers_lrn) values ('$subject', '$room', '$grade', '$time_in', '$time_out', '$schedule_day', '$userLrn')";
    $resultTeachersSubjectInfo = mysqli_query($conn, $sqlTeachersSubjectInfo);
    if ($resultTeachersSubjectInfo) {
        echo '<script>';
        echo '
              alert("added successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

if(isset($_POST['update-subject'])) {
    $id = $_POST['id'];
    $subject = $_POST['subject'];
    $room = $_POST['room'];
    $grade = $_POST['grade'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $schedule_day = $_POST['schedule_day'];

    $sqlUpdateTeachersSubjectInfo = "update teachers_subject_info set subject='$subject', room='$room', grade='$grade', schedule_time_in='$time_in', schedule_time_out='$time_out', schedule_day='$schedule_day' where id='$id'";
    $resultUpdateTeachersSubjectInfo = mysqli_query($conn, $sqlUpdateTeachersSubjectInfo);
    if ($resultUpdateTeachersSubjectInfo) {
        echo '<script>';
        echo '
              alert("updated successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $grade = $_POST['grade'];
    $subject = $_POST['subject'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $sql = "update users_info set last_name='$lastname',first_name='$firstname',grade='$grade',subject='$subject',username='$username',password='$password',user_type='$user_type' where id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
              alert("updated successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}
if(isset($_POST['deleteId'])) {
    $deleteId = $_POST['deleteId'];
    $sqlDeleteTeachersSubjectInfo = "delete from teachers_subject_info where id='$deleteId'";
    $resultDeleteTeachersSubjectInfo = mysqli_query($conn, $sqlDeleteTeachersSubjectInfo);
    if ($resultDeleteTeachersSubjectInfo) {
        echo '<script>';
        echo '
              alert("deleted successfully");
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


                <div class="custom-grid-container" tabindex="2">
                    <div class="custom-grid-item pad-1em">
                        <div class="f-weight-bold d-flex" style="    border: 1px solid gray;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;">
                            <h3 class="m-t-13px m-l-18px">
                                List of Subjects
                            </h3>

                            <div class="w-69p d-flex-end">
                                <input placeholder="search name" id="search_name" type="text" class="m-1em"
                                       onchange="searchSubject()"/>
                                <button
                                        class="btn bg-hover-gray-dark-v1"
                                        onclick="deleteSubjects('subject-list')">Delete Selected
                                </button>
                            </div>

                        </div>

                        <?php
                        $searchSubject = isset($_GET['searchSubject']) ? $_GET['searchSubject'] : '';
                        $id = $_GET['id'];

                        $sqlUser = "select * from users_info where id='$id'";
                        $resultUser = mysqli_query($conn, $sqlUser);
                        $rowUser = mysqli_fetch_assoc($resultUser);
                        $userLrn = $rowUser['user_lrn'];

                        $sql = "select * from teachers_subject_info WHERE subject LIKE '%$searchSubject%' and teachers_lrn='$userLrn' order by id desc Limit 1";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                        $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                        // Get the total number of records from our table "students".
                        $total_pages = $mysqli->query("select * from teachers_subject_info WHERE subject LIKE '%$searchSubject%' and teachers_lrn='$userLrn' order by id desc")->num_rows;
                        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        // Number of results to show on each page.
                        $num_results_on_page = 10;

                        if ($stmt = $mysqli->prepare("select * from teachers_subject_info WHERE subject LIKE '%$searchSubject%' and teachers_lrn='$userLrn' order by id desc LIMIT ?,?")) {
                            // Calculate the page to get the results we need from our table.
                            $calc_page = ($page - 1) * $num_results_on_page;
                            $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                            $stmt->execute();
                            // Get the results...
                            $result = $stmt->get_result();
                            ?>

                            <table class="table table-1 b-shadow-dark">
                                <thead>
                                <tr>
                                    <th class="t-align-center"><label for="subject-list-cb"
                                                                      class="d-flex-center"></label><input
                                                id="subject-list-cb" type="checkbox"
                                                onclick="checkCBStudents('subject-list', 'subject-list-cb')"
                                                class="sc-1-3 c-hand"/></th>
                                    <th>No</th>
                                    <th>Lrn</th>
                                    <th>Subject</th>
                                    <th>Room</th>
                                    <th>Grade</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Schedule Day</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="subject-list">
                                <?php
                                $i = 0;
                                while ($row = $result->fetch_assoc()):
                                    $i++;
                                    ?>
                                    <tr>
                                        <td class="d-flex-center"><label>
                                                <input type="checkbox" class="sc-1-3 c-hand check"
                                                       id="<?= $row['id'] ?>"/>
                                            </label></td>
                                        <th scope="row"><?= $i ?> </th>
                                        <td><?= $row['teachers_lrn'] ?> </td>
                                        <td><?= $row['subject'] ?></td>
                                        <td><?= $row['room'] ?></td>
                                        <td><?= $row['grade'] ?></td>
                                        <td><?= $row['schedule_time_in'] ?></td>
                                        <td><?= $row['schedule_time_out'] ?></td>
                                        <td><?= $row['schedule_day'] ?></td>
                                        <td>
                                            <label for="" class="t-color-red c-hand f-weight-bold"
                                                   onclick="editUser('<?= $row['id'] ?>','<?= $row['subject'] ?>','<?= $row['room'] ?>', '<?= $row['grade'] ?>', '<?= $row['schedule_time_in'] ?>', '<?= $row['schedule_time_out'] ?>', '<?= $row['schedule_day'] ?>')">
                                                Edit</label>
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
                                                        href="/1-php-grading-system/teachers_page/teacher_subject_list/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page > 3): ?>
                                            <li class="start"><a
                                                        href="/1-php-grading-system/teachers_page/teacher_subject_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                            </li>
                                            <li class="dots">...</li>
                                        <?php endif; ?>

                                        <?php if ($page - 2 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/teachers_page/teacher_subject_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page - 1 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/teachers_page/teacher_subject_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                            </li><?php endif; ?>

                                        <li class="currentpage"><a
                                                    href="/1-php-grading-system/teachers_page/teacher_subject_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                        </li>

                                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/teachers_page/teacher_subject_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/teachers_page/teacher_subject_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                            </li><?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                            <li class="dots">...</li>
                                            <li class="end"><a
                                                        href="/1-php-grading-system/teachers_page/teacher_subject_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                            <li class="next"><a
                                                        href="/1-php-grading-system/teachers_page/teacher_subject_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="custom-grid-item pad-1em">
                        <div class="b-shadow-dark">


                            <div class="pad-1em  f-weight-bold d-flex">
                                <h3>
                                    Add New Subject
                                </h3>
                            </div>

                            <form method="post">
                                <div class="custom-grid-container" tabindex="1">
                                    <div class="custom-grid-item ">
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Subject Name:</div>
                                        <select name="subject" id="subject"
                                                class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px">
                                            <option value="" disabled selected> </option>
                                            <?php
                                            $sqlSubject = "select * from subject_list_info";
                                            $resultSubject = mysqli_query($conn, $sqlSubject);
                                            while ($rowSubject = mysqli_fetch_assoc($resultSubject)) {
                                                ?>
                                                <option value="<?= $rowSubject['name'] ?>"><?= $rowSubject['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Room:</div>
                                        <input type="text" id="room" name="room" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"/>
                                        <br>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Grade:</div>
                                        <?php
                                        $id = $_GET['id'];
                                        $sqlUser = "select * from users_info where id='$id'";
                                        $resultUser = mysqli_query($conn, $sqlUser);
                                        $rowUser = mysqli_fetch_assoc($resultUser);
                                        $userLrn = $rowUser['user_lrn'];

                                        $sqlTeacher = "select * from teachers_info where lrn='$userLrn'";
                                        $resultTeacher = mysqli_query($conn, $sqlTeacher);
                                        while ($rowGrade= mysqli_fetch_assoc($resultTeacher)) {
                                            ?>
                                            <input type="text" id="grade" name="grade" value="<?= $rowGrade['grade'] ?>" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px" readonly="true"/>
                                        <?php } ?>
                                        <br>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Schedule_time_in:</div>
                                        <input type="time" id="time_in" name="time_in" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"/>
                                        <br>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Schedule_time_out:</div>
                                        <input type="time" id="time_out" name="time_out" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"/>
                                        <br>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Schedule_day:</div>
                                        <input type="text" id="schedule_day" name="schedule_day" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"/>
                                    </div>
                                </div>
                                <div class="d-flex-end pad-1em">
                                    <label class="btn bg-hover-gray-dark-v2 m-b-0 t-color-white" onclick="cancel()">
                                        Cancel
                                    </label> &nbsp; &nbsp;
                                    <button type="submit"
                                            class="c-hand btn-success btn"
                                            name="add_subject">Add
                                    </button>
                                </div>
                            </form>
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
                            <div id="update-subject" class="modal-child d-none">
                                <form method="post">
                                    <div class="custom-grid-container" tabindex="1">
                                        <div class="custom-grid-item ">
                                            <input type="hidden" id="id" name="id" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px" readonly="true"/>
                                            <br>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Subject Name:</div>
                                            <select name="subject" id="subject"
                                                    class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px">
                                                <option value="" disabled selected> </option>
                                                <?php
                                                $sqlSubject = "select * from subject_list_info";
                                                $resultSubject = mysqli_query($conn, $sqlSubject);
                                                while ($rowSubject = mysqli_fetch_assoc($resultSubject)) {
                                                    ?>
                                                    <option value="<?= $rowSubject['name'] ?>"><?= $rowSubject['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Room:</div>
                                            <input type="text" id="room" name="room" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"/>
                                            <br>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Grade:</div>
                                            <?php
                                            $id = $_GET['id'];
                                            $sqlUser = "select * from users_info where id='$id'";
                                            $resultUser = mysqli_query($conn, $sqlUser);
                                            $rowUser = mysqli_fetch_assoc($resultUser);
                                            $userLrn = $rowUser['user_lrn'];

                                            $sqlTeacher = "select * from teachers_info where lrn='$userLrn'";
                                            $resultTeacher = mysqli_query($conn, $sqlTeacher);
                                            while ($rowGrade= mysqli_fetch_assoc($resultTeacher)) {
                                                ?>
                                                <input type="text" id="grade" name="grade" value="<?= $rowGrade['grade'] ?>" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px" readonly="true"/>
                                            <?php } ?>
                                            <br>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Schedule_time_in:</div>
                                            <input type="time" id="time_in" name="time_in" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"/>
                                            <br>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Schedule_time_out:</div>
                                            <input type="time" id="time_out" name="time_out" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"/>
                                            <br>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Schedule_day:</div>
                                            <input type="text" id="schedule_day" name="schedule_day" class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"/>
                                        </div>
                                    </div>
                                    <div class="d-flex-end pad-1em">
                                        <label class="btn bg-hover-gray-dark-v2 m-b-0 t-color-white"
                                               onclick="closeModal()">
                                            Close
                                        </label> &nbsp; &nbsp;
                                        <button type="submit"
                                                class="c-hand btn-success btn"
                                                name="update-subject">Save
                                        </button>
                                    </div>
                                </form>
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

    function cancel() {
        $('#lastname').val('');
        $('#firstname').val('');
        $('#grade').val('');
        $('#subject').val('');
        $('#username').val('');
        $('#password').val('');
        $('#user_type').val('');
    }

    function editUser(id,subject,room,grade,time_in,time_out,schedule_day) {
        $('#update-subject #id').val(id);
        $('#update-subject #subject').val(subject);
        $('#update-subject #room').val(room);
        $('#update-subject #grade').val(grade);
        $('#update-subject #time_in').val(time_in);
        $('#update-subject #time_out').val(time_out);
        $('#update-subject #schedule_day').val(schedule_day);
        showModal('update-subject', 'Manage Subject', '', 'small')
    }

    function searchSubject() {
        var search = $('#search_name').val();
        if (search !== '') {
            window.location.href = '?id=<?php echo $_GET['id'] ?>&&searchSubject=' + search;
        } else {
            window.location.href = '?id=<?php echo $_GET['id'] ?>';
        }
    }

    function deleteSubjects(id){
        var checked = [];
        $('#' + id + ' input[type="checkbox"]:checked').each(function () {
            checked.push($(this).attr('id'));
        });
        if (checked.length > 0) {
            var r = confirm("Are you sure you want to delete ?");
            if (r === true) {
                checked.forEach(function (deleteId) {
                    $.post('', {deleteId: deleteId})
                });
                window.location.reload();
            }
        } else {
            alert('Please select atleast one checkbox to delete');
        }
    }

    function loadPage() {
        var searchSubject = '<?php echo isset($_GET['searchSubject']) ? $_GET['searchSubject'] : '' ?>';
        if (searchSubject !== '') {
            $('#search_name').val(searchSubject);
        }
    }

    loadPage();
</script>

