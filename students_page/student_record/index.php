<?php global $conn, $schoolName;
$var = "student_record";
include '../../students_page/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<div class="m-t-4em pad-2em">
    <div class="d-flex-center w-100p">
        <div id="content" class=" w-79-8p h-100p b-r-7px">
            <div class="m-2em  h-43em bg-white ">


                <div class="m-2em d-flex-align-start">
                    <div class="bg-white w-100p b-radius-10px pad-1em">
                        <?php
                        $id = $_GET['id'];
                        $sql = "select * from users_info ui
                                    left join students_info si on si.lrn = ui.user_lrn
                                    left join students_enrollment_info se on se.students_info_lrn = si.lrn
                                    where ui.id='$id' order by  se.grade ASC Limit 1";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                        $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                        // Get the total number of records from our table "students".
                        $total_pages = $mysqli->query("select * from users_info ui
                                    left join students_info si on si.lrn = ui.user_lrn
                                    left join students_enrollment_info se on se.students_info_lrn = si.lrn
                                    where ui.id='$id' order by  se.grade ASC")->num_rows;
                        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        // Number of results to show on each page.
                        $num_results_on_page = 10;

                        if ($stmt = $mysqli->prepare(" select * from users_info ui
                                    left join students_info si on si.lrn = ui.user_lrn
                                    left join students_enrollment_info se on se.students_info_lrn = si.lrn
                                    where ui.id='$id' order by se.grade ASC LIMIT ?,?")) {
                            // Calculate the page to get the results we need from our table.
                            $calc_page = ($page - 1) * $num_results_on_page;
                            $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                            $stmt->execute();
                            // Get the results...
                            $result = $stmt->get_result();
                            ?>

                            <table class="custom-table b-shadow-dark">
                                <thead>
                                <tr>
                                    <th>Grade</th>
                                    <th>Section</th>
                                    <th>School Year</th>
                                    <th>Date Enrolled</th>
                                    <th class="t-align-center">Options</th>
                                </tr>
                                </thead>
                                <tbody id="student-list">
                                <?php
                                $i = 0;
                                while ($row = $result->fetch_assoc()):
                                    $i++;
                                    ?>
                                    <tr>
                                        <td><?= $row['grade'] ?></td>
                                        <td><?= $row['section'] ?></td>
                                        <td><?= $row['school_year'] ?></td>
                                        <td><?= $row['date_enrolled'] ?></td>
                                        <td class="t-align-center">
                                            <button style="background-color: blue; color: #FFFFFF; cursor: pointer; font-weight: bold; padding: 10px 10px; border: none; border-radius: 5px;"
                                                    onclick="viewStudentGrade('<?= $row['grade'] ?>')"
                                            >
                                                View Grade
                                            </button>
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

                <div id="myModal" style="width: 100% !important; display: none">
                    <script src="../../assets/js/js_header.js"></script>

                    <div class="modal-content">
                        <div id="top-icon"
                             class="top-icon h-100p d-flex-center p-absolute w-3em c-hand f-size-26px w-2em bg-hover-white t-color-white"
                             onclick="tops()" style="left: -97px;top: -97px;height: 61px;">☰
                        </div>
                        <div class="modal-header a-center">
                        </div>
                        <div class="modal-body">

                            <div id="view-student-grade" class="modal-child d-none">
                                <?php
                                if (isset($_GET['grade'])) {
                                    echo "<script>showModal('view-student-grade', 'Student Grade', 'white')</script>";
                                    ?>
                                    <?php
                                    $sGrade = $_GET['grade'];
                                    $id = $_GET['id'];
                                    $sqlStudents = "select * from students_info si 
                                           left join users_info ui on si.lrn = ui.user_lrn
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where ui.id='$id' and sei.grade='$sGrade'
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
                                                <th rowspan="2" style="vertical-align : middle;text-align:center;"
                                                    class="b-bottom-none">
                                                    Learning Area
                                                    <!--                                jay-->
                                                </th>
                                                <th colspan="4" style="text-align:center;" class="b-bottom-none">Quarter
                                                </th>
                                                <th rowspan="2" style="vertical-align : middle;text-align:center;"
                                                    class="b-bottom-none">
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
                                            $sGrade = $_GET['grade'];
                                            $id = $_GET['id'];

                                            $sqlStudents = "select * from students_info si 
                                        left join users_info ui on si.lrn = ui.user_lrn
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where ui.id='$id' and sei.grade='$sGrade'
                                            group by si.lrn";
                                            $sqlStudents = mysqli_query($conn, $sqlStudents);
                                            $row = mysqli_fetch_assoc($sqlStudents);
                                            $grade = $row['grade'];
                                            $studentLrn = $row['students_info_lrn'];

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
                                        $sGrade = $_GET['grade'];
                                        $id = $_GET['id'];
                                        $sqlStudents = "select * from students_info si 
                                             left join users_info ui on si.lrn = ui.user_lrn
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where ui.id='$id' and sei.grade='$sGrade'
                                            group by si.lrn";
                                        $sqlStudents = mysqli_query($conn, $sqlStudents);
                                        $row = mysqli_fetch_assoc($sqlStudents);
                                        $grade = $row['grade'];
                                        $lrn = $row['lrn'];

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
                                            $sGrade = $_GET['grade'];
                                            $sqlStudents = "select * from students_info si 
                                        left join users_info ui on si.lrn = ui.user_lrn 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where ui.id='$id' and sei.grade='$sGrade'
                                            group by si.lrn";
                                            $sqlStudents = mysqli_query($conn, $sqlStudents);
                                            $row = mysqli_fetch_assoc($sqlStudents);
                                            $grade = $row['grade'];
                                            $teacherLrn = $row['teacher_lrn'];
                                            $studentLrn = $row['students_info_lrn'];

                                            $sqlUser = "select * from students_grade_attendance_info where student_lrn='$studentLrn' and grade='$grade'";
                                            $resultUsers = mysqli_query($conn, $sqlUser);
                                            while ($rowUser = mysqli_fetch_assoc($resultUsers)) {
                                                ?>
                                                <tr>
                                                    <th>Days of School</th>
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
                                                    <th>Days Present</th>
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
                                        <button class="c-hand btn-primary btn"
                                                onclick="print('view-student-grade')">Print
                                        </button>
                                    </div>

                                <?php } ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewStudentGrade(grade) {
            history.pushState(null, null, "?id=" + <?php echo $_GET['id'] ?> + "&&grade=" + grade);
            window.location.reload();

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
    </script>
    <link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>
</div>
</body>

