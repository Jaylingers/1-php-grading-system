<?php global $mysqli, $rows;
$var = "add_new_user";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['add_user'])) {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the admin password
    $hashed_admin_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "insert into users_info (last_name,first_name,username,password,user_type) VALUES ('$lastname','$firstname','$username','$hashed_admin_password','admin')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&added_successfully=' . $lastname . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the admin password
    $hashed_admin_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "update users_info set last_name='$lastname',first_name='$firstname',username='$username',password='$hashed_admin_password' where id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&added_successfully=' . $id . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['deleteId'])) {
    $deleteId = $_POST['deleteId'];
    $id =$_POST['deleteId'];

    $sqlSelectUser = "select * from users_info where id='$deleteId'";
    $resultSelectUser = mysqli_query($conn, $sqlSelectUser);
    $rowSelectUser = mysqli_fetch_assoc($resultSelectUser);
    $userType = $rowSelectUser['user_type'];
    $lrn = $rowSelectUser['user_lrn'];

    if($userType === 'teacher'){

        $sqlSelectRemovedBy = "select CONCAT(first_name, ' ', last_name) as 'name' from users_info where id = '$id'";
        $resultSelectRemovedBy = mysqli_query($conn, $sqlSelectRemovedBy);
        $rowsSelectRemovedBy = mysqli_fetch_assoc($resultSelectRemovedBy);
        $removedBy = '';
        foreach ($rowsSelectRemovedBy as $key => $value) {
            $removedBy .= $value;
        }

        $sqlStudentInfo = "select * from teachers_info where lrn = '$lrn'";
        $resultStudentInfo = mysqli_query($conn, $sqlStudentInfo);
        $rowsStudentInfo = mysqli_fetch_assoc($resultStudentInfo);
        $name = $rowsStudentInfo['first_name'] . ' ' . $rowsStudentInfo['last_name'];
        $historyData = '';
        $historyData .= ' <h3> Teachers Info</h3>';
        foreach ($rowsStudentInfo as $key => $value) {
            $historyData .= $key . ': ' . $value . ' <br/>';
        }

        $sqlStudentSubjectInfo = "select * from teachers_subject_info where teachers_lrn = '$lrn'";
        $resultStudentSubjectInfo = mysqli_query($conn, $sqlStudentSubjectInfo);
        $rowsStudentSubjectInfo = mysqli_fetch_assoc($resultStudentSubjectInfo);
        $historyData .= ' <h3> Teachers Subject Info</h3>';
        foreach ($rowsStudentSubjectInfo as $key => $value) {
            $historyData .= $key . ': ' . $value . ' <br/>';
        }

        $sqlUserInfo = "select * from users_info where user_lrn = '$lrn'";
        $resultUserInfo = mysqli_query($conn, $sqlUserInfo);
        $rowsUserInfo = mysqli_fetch_assoc($resultUserInfo);
        $historyData .= ' <h3> Users Info</h3>';
        foreach ($rowsUserInfo as $key => $value) {
            $historyData .= $key . ': ' . $value . ' <br/>';
        }

        $sqlInsertTrash = "insert into trash_info (user_lrn,name,history,removed_date,removed_by,position) VALUES ('$lrn', '$name','$historyData', now(),'$removedBy','teacher')";
        $resultInsertTrash = mysqli_query($conn, $sqlInsertTrash);

        $sql = "delete from teachers_info where lrn = '$lrn'";
        $result = mysqli_query($conn, $sql);

        $sqlDelete = "delete from teachers_subject_info where teachers_lrn = '$lrn'";
        $resultDelete = mysqli_query($conn, $sqlDelete);

        $sqlDeleteUser = "delete from users_info where user_lrn = '$lrn'";
        $resultDeleteUser = mysqli_query($conn, $sqlDeleteUser);
    } else if($userType === 'student')
    {
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

        $tLrn = $_GET['Tlrn'];

        $sqlInsertTrash = "insert into trash_info (user_lrn,teacher_lrn,name,history,removed_date,removed_by,position) VALUES ('$lrn','$tLrn', '$name','$historyData', now(),'$removedBy','student')";
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
    } else {
        $sqlDeleteuserInfo = "delete from users_info where id = '$id'";
        $resultDeleteuserInfo = mysqli_query($conn, $sqlDeleteuserInfo);
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
?>

<div class="d-flex-end p-absolute w-100p bottom-0 t-60px">
    <div id="content"
         class="bg-off-white w-79-8p h-100p b-r-7px contents one_page <?= $rows['dark_mode'] === '1' ? 'bg-dark' : '' ?>">

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
            #search_name {
  width: 40%;
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


                <div class="custom-grid-container" tabindex="2">
                    <div class="custom-grid-item pad-1em">
                        <div class="f-weight-bold d-flex" style="    border: 1px solid gray;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;">
                            <h3 class="m-t-13px m-l-18px">
                                List of Users
                            </h3>

                            <div class="w-74p d-flex-end">
                                <input placeholder="search name" id="search_name" type="text" class="m-1em"
                                       onchange="searchName()"/>
                                <svg class="c-hand" onclick="deleteId('user-list')" height="43" id="svg2"
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
                                                  style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;direction:ltr;block-progression:tb;writing-mode:lr-tb;baseline-shift:baseline;text-anchor:start;white-space:normal;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:none;stroke-width:4.99999952;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate"/>
                                        </g>
                                    </g>
                                </svg>
                            </div>

                        </div>

                        <?php
                        $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
                        $sql = "select * from users_info WHERE CONCAT_WS('', first_name,last_name) LIKE '%$searchName%' order by id desc Limit 1 ";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                        $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                        // Get the total number of records from our table "students".
                        $total_pages = $mysqli->query("select * from users_info WHERE CONCAT_WS('', first_name,last_name) LIKE '%$searchName%' order by id desc")->num_rows;
                        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        // Number of results to show on each page.
                        $num_results_on_page = 10;

                        if ($stmt = $mysqli->prepare("select * from users_info WHERE CONCAT_WS('', first_name,last_name) LIKE '%$searchName%' order by id desc LIMIT ?,?")) {
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
                                    <th class="t-align-center"><label for="user-list-cb"
                                                                      class="d-flex-center"></label><input
                                                id="user-list-cb" type="checkbox"
                                                onclick="checkCBStudents('user-list', 'user-list-cb')"
                                                class="sc-1-3 c-hand"/></th>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Type</th>
                                    <th>Edit</th>
                                </tr>
                                </thead>
                                <tbody id="user-list">
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
                                        <td><?= $row['last_name'] ?> <?= $row['first_name'] ?></td>
                                        <td><?= $row['username'] ?></td>
                                        <td><?= strlen($row['password']) > 10 ? substr($row['password'], 0, 10) . '...' : $row['password'] ?></td>
                                        <td><?= $row['user_type'] ?></td>
                                        <td>
                                            <label for="" class="t-color-red c-hand f-weight-bold"
                                                   onclick="editUser('<?= $row['id'] ?>','<?= $row['last_name'] ?>','<?= $row['first_name'] ?>','<?= $row['username'] ?>','<?= $row['password'] ?>', )">
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
                                                        <linearGradient gradientUnits="userSpaceOnUse"
                                                                        id="linear-gradient"
                                                                        x1="22.98" x2="26.48" y1="23.9" y2="28.27">
                                                            <stop offset="0.04" stop-color="#fbb480"/>
                                                            <stop offset="1" stop-color="#c27c4a"/>
                                                        </linearGradient>
                                                        <linearGradient id="linear-gradient-2" x1="7.85" x2="11.63"
                                                                        xlink:href="#linear-gradient" y1="35.07"
                                                                        y2="39.53"/>
                                                        <linearGradient id="linear-gradient-3" x1="7.26" x2="12.14"
                                                                        xlink:href="#linear-gradient" y1="33.38"
                                                                        y2="38.26"/>
                                                        <linearGradient id="linear-gradient-4" x1="35.06" x2="41.75"
                                                                        xlink:href="#linear-gradient" y1="9.61"
                                                                        y2="16.3"/>
                                                        <linearGradient id="linear-gradient-5" x1="32.45" x2="41.29"
                                                                        xlink:href="#linear-gradient" y1="6.23"
                                                                        y2="17.91"/>
                                                        <linearGradient
                                                                gradientTransform="translate(21.95 -5.88) rotate(44.99)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-6"
                                                                x1="17.07" x2="22.48" y1="22.56" y2="27.98">
                                                            <stop offset="0.01" stop-color="#ffdc2e"/>
                                                            <stop offset="1" stop-color="#f79139"/>
                                                        </linearGradient>
                                                        <linearGradient
                                                                gradientTransform="translate(28.21 -8.47) rotate(45)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-7"
                                                                x1="22.57" x2="26.35" y1="28.06" y2="31.84">
                                                            <stop offset="0.01" stop-color="#f46000"/>
                                                            <stop offset="1" stop-color="#de722c"/>
                                                        </linearGradient>
                                                        <linearGradient
                                                                gradientTransform="translate(25.08 -7.17) rotate(45)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-8"
                                                                x1="20.21" x2="24.85" y1="25.7" y2="30.35">
                                                            <stop offset="0.01" stop-color="#f99d46"/>
                                                            <stop offset="1" stop-color="#f46000"/>
                                                        </linearGradient>
                                                        <linearGradient
                                                                gradientTransform="translate(23.66 -19.41) rotate(44.98)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-9"
                                                                x1="34.09"
                                                                x2="36.35" y1="17.69" y2="19.95">
                                                            <stop offset="0.01" stop-color="#a1a1a1"/>
                                                            <stop offset="1" stop-color="#828282"/>
                                                        </linearGradient>
                                                        <linearGradient
                                                                gradientTransform="translate(17.4 -16.81) rotate(44.98)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-10"
                                                                x1="27.79" x2="30.61" y1="11.39" y2="14.22">
                                                            <stop offset="0.01" stop-color="#fafafa"/>
                                                            <stop offset="1" stop-color="#dedede"/>
                                                        </linearGradient>
                                                        <linearGradient
                                                                gradientTransform="translate(20.55 -18.12) rotate(45)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-11"
                                                                x1="30.43" x2="34.61" y1="14.03" y2="18.21">
                                                            <stop offset="0.01" stop-color="#d4d4d4"/>
                                                            <stop offset="1" stop-color="#a6a6a6"/>
                                                        </linearGradient>
                                                        <linearGradient
                                                                gradientTransform="translate(23.67 -19.41) rotate(44.99)"
                                                                gradientUnits="userSpaceOnUse" id="linear-gradient-12"
                                                                x1="33.9"
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
                                                        <linearGradient
                                                                gradientTransform="translate(20.55 -18.12) rotate(45)"
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
                                                                  transform="translate(-13.96 25.93) rotate(-45)"
                                                                  width="24.8"
                                                                  x="11.92" y="28.03"/>
                                                            <rect class="cls-9" height="5.27"
                                                                  transform="translate(-12.66 22.8) rotate(-45)"
                                                                  width="24.8"
                                                                  x="8.79" y="24.05"/>
                                                            <rect class="cls-10" height="3.58"
                                                                  transform="translate(-3.02 30.45) rotate(-44.98)"
                                                                  width="7.63"
                                                                  x="31.46" y="17.08"/>
                                                            <rect class="cls-11" height="3.58"
                                                                  transform="translate(-0.43 24.2) rotate(-44.98)"
                                                                  width="7.62"
                                                                  x="25.2" y="10.83"/>
                                                            <rect class="cls-12" height="5.27"
                                                                  transform="translate(-1.72 27.34) rotate(-45)"
                                                                  width="7.62"
                                                                  x="28.33" y="13.11"/>
                                                            <rect class="cls-13" height="3.58"
                                                                  transform="translate(-3.02 30.46) rotate(-44.99)"
                                                                  width="6.15"
                                                                  x="32.19" y="17.08"/>
                                                            <rect class="cls-14" height="3.58"
                                                                  transform="translate(-0.43 24.2) rotate(-44.99)"
                                                                  width="6.15"
                                                                  x="25.94" y="10.83"/>
                                                            <rect class="cls-15" height="5.27"
                                                                  transform="translate(-1.72 27.34) rotate(-45)"
                                                                  width="6.15"
                                                                  x="29.06" y="13.11"/>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </label>
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
                                                        href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page > 3): ?>
                                            <li class="start"><a
                                                        href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                            </li>
                                            <li class="dots">...</li>
                                        <?php endif; ?>

                                        <?php if ($page - 2 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page - 1 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                            </li><?php endif; ?>

                                        <li class="currentpage"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                        </li>

                                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                            </li><?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                            <li class="dots">...</li>
                                            <li class="end"><a
                                                        href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                            <li class="next"><a
                                                        href="/1-php-grading-system/admins_page/add_new_user/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="custom-grid-item pad-1em" id="grid-a">
                        <div class="b-shadow-dark">


                            <div class="pad-1em  f-weight-bold d-flex">
                                <h3>
                                    Add User
                                </h3>
                            </div>

                            <form method="post">
                                <div class="custom-grid-container add" tabindex="1">
                                    <div class="custom-grid-item ">
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Last Name:</div>
                                        <input placeholder="Last Name" type="text"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               id="lastname"
                                               name="lastname"
                                               required>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> First Name:</div>
                                        <input placeholder="First Name" type="text"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               id="firstname"
                                               name="firstname"
                                               required>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Username:</div>
                                        <input placeholder="Username" type="text"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               id="username"
                                               name="username"
                                               required>
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Password:</div>
                                        <input placeholder="Password" type="password"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               id="password"
                                               name="password"
                                               required>
                                    </div>
                                </div>
                                <div class="d-flex-end pad-1em">
                                    <svg onclick="cancel()" class="c-hand" width="50" height="50" data-name="Layer 1" id="Layer_1" viewBox="0 0 140 140" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#ccbeb0;}.cls-2{fill:#525354;}.cls-3{fill:#d77165;}</style></defs><title/><circle class="cls-1" cx="70" cy="70" r="64"/><rect class="cls-2" height="98" rx="1" ry="1" transform="translate(-29.7 70.29) rotate(-45)" width="24" x="58" y="22"/><rect class="cls-2" height="98" rx="1" ry="1" transform="translate(69.29 170.7) rotate(-135)" width="24" x="58" y="22"/><rect class="cls-3" height="98" rx="1" ry="1" transform="translate(-28.99 70) rotate(-45)" width="24" x="58" y="21"/><rect class="cls-3" height="98" rx="1" ry="1" transform="translate(70 168.99) rotate(-135)" width="24" x="58" y="21"/></svg>

                                    <button type="submit"
                                            class="c-hand btn-success btn"
                                            name="add_user"
                                            style="background-color: #ffffff !important; border-color: #ffffff;">
                                        <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50"
                                             height="50"
                                        >
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
                            <div id="update-user" class="modal-child d-none">
                                <form method="post">
                                    <div class="custom-grid-container" tabindex="1">
                                        <div class="custom-grid-item ">
                                            <input placeholder="id" type="hidden"
                                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="id"
                                                   name="id"
                                                   readonly="true">
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Last Name:</div>
                                            <input placeholder="Last Name" type="text"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="lastname"
                                                   name="lastname"
                                                   required>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> First Name:</div>
                                            <input placeholder="First Name" type="text"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="firstname"
                                                   name="firstname"
                                                   required>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Username:</div>
                                            <input placeholder="Username" type="text"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="username"
                                                   name="username"
                                                   required>
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Password:</div>
                                            <input placeholder="Password" type="password"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="password"
                                                   name="password"
                                                   required>

                                        </div>
                                    </div>
                                    <div class="d-flex-end pad-1em">
                                        <svg onclick="closeModal()" class="c-hand" width="50" height="50" data-name="Layer 1" id="Layer_1" viewBox="0 0 140 140" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#ccbeb0;}.cls-2{fill:#525354;}.cls-3{fill:#d77165;}</style></defs><title/><circle class="cls-1" cx="70" cy="70" r="64"/><rect class="cls-2" height="98" rx="1" ry="1" transform="translate(-29.7 70.29) rotate(-45)" width="24" x="58" y="22"/><rect class="cls-2" height="98" rx="1" ry="1" transform="translate(69.29 170.7) rotate(-135)" width="24" x="58" y="22"/><rect class="cls-3" height="98" rx="1" ry="1" transform="translate(-28.99 70) rotate(-45)" width="24" x="58" y="21"/><rect class="cls-3" height="98" rx="1" ry="1" transform="translate(70 168.99) rotate(-135)" width="24" x="58" y="21"/></svg>
                                        <button type="submit"
                                                class="c-hand btn-success btn"
                                                name="update_user" style="background-color: #ffffff !important; border-color: #ffffff;">
                                            <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
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

    $(document).on('click', '#modal-delete-cancel', function (e) {
        $('#modal-delete').attr('style', 'display: none !important;')
        $('#modal-checkbox').attr('style', 'display: none !important;')

    });

    $(document).on('click', '#modal-success', function (e) {
        $('#modal-addedSuccessfully').attr('style', 'display: none !important;')
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>');
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
            idArray.forEach(function (id) {
                $.post('', {deleteId: id})
            });
            history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>');
            window.location.reload();
        }
    }

    function cancel() {
        $('.add #lastname').val('');
        $('.add #firstname').val('');
        $('.add #username').val('');
        $('.add #password').val('');
    }

    function editUser(id, lastname, firstname, username, password) {
        $('#update-user #id').val(id);
        $('#update-user #lastname').val(lastname);
        $('#update-user #firstname').val(firstname);
        $('#update-user #username').val(username);
        $('#update-user #password').val(password);


        showModal('update-user', 'Manage Account', '', 'small')
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

        var added_successfully = '<?php echo isset($_GET['added_successfully']) ? $_GET['added_successfully'] : '' ?>';
        if (added_successfully !== '') {
            $('#modal-addedSuccessfully').attr('style', 'display: block;')
        }
    }

    loadPage();
</script>

