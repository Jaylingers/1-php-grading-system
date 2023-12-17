<?php global $mysqli, $rows;
$var = "school_year";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

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

            #search_name {
                width: 20%;
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

                <div class="m-t-19px m-l-13px f-weight-bold d-flex">
                    <h3>
                        Student School Year
                    </h3>

                </div>
                <br/>
                <?php
                $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
                $from = isset($_GET['from']) ? $_GET['from'] : null;
                $to = isset($_GET['to']) ? $_GET['to'] : null;

                // Extract year from the 'from' parameter
                $from = date('Y', strtotime($from));

                // Extract year from the 'to' parameter
                $to = date('Y', strtotime($to));

                $sql = "SELECT 
                        si.id, 
                        si.lrn, 
                        si.f_name, 
                        si.l_name, 
                        si.m_name,
                        si.gender,
                        si.b_date, 
                        si.b_place, 
                        si.c_status,
                        si.age, 
                        si.nationality, 
                        si.religion, 
                        si.contact_number, 
                        si.email_address,
                        si.home_address, 
                        si.guardian_name, 
                        CONCAT( ui.last_name ,'', ui.first_name) as addedBy,
                        sei.grade,
                          sei.section,
                        sei.school_year
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
						left join users_info ui on ui.id = si.addedBy
                        left join teachers_info ti on ti.lrn = si.teacher_lrn
	                  WHERE CONCAT_WS('', si.f_name,si.l_name) LIKE '%$searchName%'
                          AND sei.school_year >= '$from' AND sei.school_year <= '$to'
                        GROUP BY si.id order by  sei.school_year DESC Limit 1";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "students".
                $total_pages = $mysqli->query("SELECT 
                        si.id, 
                        si.lrn, 
                        si.f_name, 
                        si.l_name, 
                        si.m_name,
                        si.gender,
                        si.b_date, 
                        si.b_place, 
                        si.c_status,
                        si.age, 
                        si.nationality, 
                        si.religion, 
                        si.contact_number, 
                        si.email_address,
                        si.home_address, 
                        si.guardian_name, 
                        CONCAT( ui.last_name ,'', ui.first_name) as addedBy,
                        sei.grade,
                        sei.section,
                        sei.school_year
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
						left join users_info ui on ui.id = si.addedBy
                        left join teachers_info ti on ti.lrn = si.teacher_lrn
	                       WHERE CONCAT_WS('', si.f_name,si.l_name) LIKE '%$searchName%'
                          AND sei.school_year >= '$from' AND sei.school_year <= '$to'
                        GROUP BY si.id order by  sei.school_year DESC")->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare("SELECT 
                       si.id, 
                        si.lrn, 
                        si.f_name, 
                        si.l_name, 
                        si.m_name,
                        si.gender,
                        si.b_date, 
                        si.b_place, 
                        si.c_status,
                        si.age, 
                        si.nationality, 
                        si.religion, 
                        si.contact_number, 
                        si.email_address,
                        si.home_address, 
                        si.guardian_name, 
                        CONCAT( ui.last_name ,'', ui.first_name) as addedBy,
                        sei.grade,
                         sei.section,
                        sei.school_year
                        FROM `students_info` si 
                        left join students_enrollment_info sei on sei.students_info_lrn = si.lrn 
						left join users_info ui on ui.id = si.addedBy
                        left join teachers_info ti on ti.lrn = si.teacher_lrn
	                        WHERE CONCAT_WS('', si.f_name,si.l_name) LIKE '%$searchName%'
                          AND sei.school_year >= '$from' AND sei.school_year <= '$to'
                        GROUP BY si.id order by  sei.school_year DESC LIMIT ?,?")) {
                    // Calculate the page to get the results we need from our table.
                    $calc_page = ($page - 1) * $num_results_on_page;
                    $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                    $stmt->execute();
                    // Get the results...
                    $result = $stmt->get_result();
                    ?>
                    <input placeholder="search name" id="search_name" type="hidden" class=" m-b-5px"
                           onchange="search('name')"/> <br>
                    <input placeholder="from" id="search_name" type="date" class="search-from m-b-5px" onchange="search('date')"/>
                    <input placeholder="to" id="search_name" type="date" class="search-to m-b-5px" onchange="search('date')"/>
<!--                    <button class="btn btn-success" onclick="search('date')">Search</button>-->
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
                            <th>School Year</th>
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
                                <td><?= $row['school_year'] ?></td>
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
                                                href="/1-php-grading-system/admins_page/school_year/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&from=<?php echo $_GET['from']  ?>&to=<?php echo $_GET['to'] ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/school_year/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&from=<?php echo $_GET['from']  ?>&to=<?php echo $_GET['to'] ?>&&page=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/school_year/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&from=<?php echo $_GET['from']  ?>&to=<?php echo $_GET['to'] ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/school_year/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&from=<?php echo $_GET['from']  ?>&to=<?php echo $_GET['to'] ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/school_year/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&from=<?php echo $_GET['from']  ?>&to=<?php echo $_GET['to'] ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/school_year/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&from=<?php echo $_GET['from']  ?>&to=<?php echo $_GET['to'] ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/school_year/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&from=<?php echo $_GET['from']  ?>&to=<?php echo $_GET['to'] ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/school_year/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&from=<?php echo $_GET['from']  ?>&to=<?php echo $_GET['to'] ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/school_year/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['searchName'])): ?>&&searchName=<?php echo $_GET['searchName'] ?><?php endif; ?>&&from=<?php echo $_GET['from']  ?>&to=<?php echo $_GET['to'] ?>&&page=<?php echo $page + 1 ?>">Next</a>
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

<style>
    .btn {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
    }

    .btn-success {
        background-color: #28a745; /* Adjust color as needed */
    }

    .btn:hover {
        background-color: #218838; /* Adjust hover color as needed */
    }

</style>
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
                                <option value="Divorced">Divorced</option>
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
                    <div class="r-50px d-flex-end gap-1em">
                        <button type="submit" class="c-hand btn-success btn" name="add-new-student">Submit</button>
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
                            name="save" onclick="updateStudentInformation()"
                            style="background-color: #757575 !important; border-color: #757575;">
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
                                <linearGradient gradientUnits="userSpaceOnUse" id="linear-gradient"
                                                x1="22.98" x2="26.48" y1="23.9" y2="28.27">
                                    <stop offset="0.04" stop-color="#fbb480"/>
                                    <stop offset="1" stop-color="#c27c4a"/>
                                </linearGradient>
                                <linearGradient id="linear-gradient-2" x1="7.85" x2="11.63"
                                                xlink:href="#linear-gradient" y1="35.07" y2="39.53"/>
                                <linearGradient id="linear-gradient-3" x1="7.26" x2="12.14"
                                                xlink:href="#linear-gradient" y1="33.38" y2="38.26"/>
                                <linearGradient id="linear-gradient-4" x1="35.06" x2="41.75"
                                                xlink:href="#linear-gradient" y1="9.61" y2="16.3"/>
                                <linearGradient id="linear-gradient-5" x1="32.45" x2="41.29"
                                                xlink:href="#linear-gradient" y1="6.23" y2="17.91"/>
                                <linearGradient gradientTransform="translate(21.95 -5.88) rotate(44.99)"
                                                gradientUnits="userSpaceOnUse" id="linear-gradient-6"
                                                x1="17.07" x2="22.48" y1="22.56" y2="27.98">
                                    <stop offset="0.01" stop-color="#ffdc2e"/>
                                    <stop offset="1" stop-color="#f79139"/>
                                </linearGradient>
                                <linearGradient gradientTransform="translate(28.21 -8.47) rotate(45)"
                                                gradientUnits="userSpaceOnUse" id="linear-gradient-7"
                                                x1="22.57" x2="26.35" y1="28.06" y2="31.84">
                                    <stop offset="0.01" stop-color="#f46000"/>
                                    <stop offset="1" stop-color="#de722c"/>
                                </linearGradient>
                                <linearGradient gradientTransform="translate(25.08 -7.17) rotate(45)"
                                                gradientUnits="userSpaceOnUse" id="linear-gradient-8"
                                                x1="20.21" x2="24.85" y1="25.7" y2="30.35">
                                    <stop offset="0.01" stop-color="#f99d46"/>
                                    <stop offset="1" stop-color="#f46000"/>
                                </linearGradient>
                                <linearGradient
                                        gradientTransform="translate(23.66 -19.41) rotate(44.98)"
                                        gradientUnits="userSpaceOnUse" id="linear-gradient-9" x1="34.09"
                                        x2="36.35" y1="17.69" y2="19.95">
                                    <stop offset="0.01" stop-color="#a1a1a1"/>
                                    <stop offset="1" stop-color="#828282"/>
                                </linearGradient>
                                <linearGradient gradientTransform="translate(17.4 -16.81) rotate(44.98)"
                                                gradientUnits="userSpaceOnUse" id="linear-gradient-10"
                                                x1="27.79" x2="30.61" y1="11.39" y2="14.22">
                                    <stop offset="0.01" stop-color="#fafafa"/>
                                    <stop offset="1" stop-color="#dedede"/>
                                </linearGradient>
                                <linearGradient gradientTransform="translate(20.55 -18.12) rotate(45)"
                                                gradientUnits="userSpaceOnUse" id="linear-gradient-11"
                                                x1="30.43" x2="34.61" y1="14.03" y2="18.21">
                                    <stop offset="0.01" stop-color="#d4d4d4"/>
                                    <stop offset="1" stop-color="#a6a6a6"/>
                                </linearGradient>
                                <linearGradient
                                        gradientTransform="translate(23.67 -19.41) rotate(44.99)"
                                        gradientUnits="userSpaceOnUse" id="linear-gradient-12" x1="33.9"
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
                                <linearGradient gradientTransform="translate(20.55 -18.12) rotate(45)"
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
                                          transform="translate(-13.96 25.93) rotate(-45)" width="24.8"
                                          x="11.92" y="28.03"/>
                                    <rect class="cls-9" height="5.27"
                                          transform="translate(-12.66 22.8) rotate(-45)" width="24.8"
                                          x="8.79" y="24.05"/>
                                    <rect class="cls-10" height="3.58"
                                          transform="translate(-3.02 30.45) rotate(-44.98)" width="7.63"
                                          x="31.46" y="17.08"/>
                                    <rect class="cls-11" height="3.58"
                                          transform="translate(-0.43 24.2) rotate(-44.98)" width="7.62"
                                          x="25.2" y="10.83"/>
                                    <rect class="cls-12" height="5.27"
                                          transform="translate(-1.72 27.34) rotate(-45)" width="7.62"
                                          x="28.33" y="13.11"/>
                                    <rect class="cls-13" height="3.58"
                                          transform="translate(-3.02 30.46) rotate(-44.99)" width="6.15"
                                          x="32.19" y="17.08"/>
                                    <rect class="cls-14" height="3.58"
                                          transform="translate(-0.43 24.2) rotate(-44.99)" width="6.15"
                                          x="25.94" y="10.83"/>
                                    <rect class="cls-15" height="5.27"
                                          transform="translate(-1.72 27.34) rotate(-45)" width="6.15"
                                          x="29.06" y="13.11"/>
                                </g>
                            </g>
                        </svg>
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
                                <option value="Divorced">Divorced</option>
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
                        <div class="r-50px d-flex-end m-t-1em">
                            <label class=" bg-hover-gray-dark-v1 m-b-0"
                                   onclick="showModal('view-student-info','Student Information', 'dark')"
                                   style="background-color: #ffffff !important; border-color: #ffffff;">
                                <img src="../../assets/img/back.png" alt="" width="60" height="60" class="c-hand">
                            </label>
                            <button type="submit"
                                    class="c-hand btn-success btn"
                                    name="update-student-info"
                                    style="background-color: #ffffff !important; border-color: #ffffff;">
                                <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="view-student-enrollment" class="modal-child pad-bottom-2em d-none">
                <div class="d-flex-end gap-1em m-b-1em">
                    <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50"
                         onclick="showModal('add-enrollment', 'New Enrollment')">
                    <svg class="c-hand" onclick="deleteId('student-enrollment')" height="43" id="svg2"
                         version="1.1" viewBox="0 0 99.999995 99.999995" width="50"
                         xmlns="http://www.w3.org/2000/svg"
                         xmlns:svg="http://www.w3.org/2000/svg">
                        <defs id="defs4">
                            <filter id="filter4510" style="color-interpolation-filters:sRGB">
                                <feFlood flood-color="rgb(0,0,0)" flood-opacity="0.470588" id="feFlood4512"
                                         result="flood"/>
                                <feComposite id="feComposite4514" in="flood" in2="SourceGraphic" operator="in"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur4516" in="composite1" result="blur"
                                                stdDeviation="5"/>
                                <feOffset dx="0" dy="4.7" id="feOffset4518" result="offset"/>
                                <feComposite id="feComposite4520" in="SourceGraphic" in2="offset" operator="over"
                                             result="composite2"/>
                            </filter>
                            <filter id="filter5064" style="color-interpolation-filters:sRGB">
                                <feFlood flood-color="rgb(206,242,245)" flood-opacity="0.835294" id="feFlood5066"
                                         result="flood"/>
                                <feComposite id="feComposite5068" in="flood" in2="SourceGraphic" operator="out"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur5070" in="composite1" result="blur"
                                                stdDeviation="5.9"/>
                                <feOffset dx="0" dy="-8.1" id="feOffset5072" result="offset"/>
                                <feComposite id="feComposite5074" in="offset" in2="SourceGraphic" operator="atop"
                                             result="composite2"/>
                            </filter>
                            <filter id="filter5364" style="color-interpolation-filters:sRGB">
                                <feFlood flood-color="rgb(0,0,0)" flood-opacity="0.835294" id="feFlood5366"
                                         result="flood"/>
                                <feComposite id="feComposite5368" in="flood" in2="SourceGraphic" operator="in"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur5370" in="composite1" result="blur"
                                                stdDeviation="5"/>
                                <feOffset dx="0" dy="4.2" id="feOffset5372" result="offset"/>
                                <feComposite id="feComposite5374" in="SourceGraphic" in2="offset" operator="over"
                                             result="fbSourceGraphic"/>
                                <feColorMatrix id="feColorMatrix5592" in="fbSourceGraphic"
                                               result="fbSourceGraphicAlpha"
                                               values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/>
                                <feFlood flood-color="rgb(254,255,189)" flood-opacity="1" id="feFlood5594"
                                         in="fbSourceGraphic" result="flood"/>
                                <feComposite id="feComposite5596" in="flood" in2="fbSourceGraphic" operator="out"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur5598" in="composite1" result="blur"
                                                stdDeviation="7.6"/>
                                <feOffset dx="0" dy="-8.1" id="feOffset5600" result="offset"/>
                                <feComposite id="feComposite5602" in="offset" in2="fbSourceGraphic" operator="atop"
                                             result="composite2"/>
                            </filter>
                            <filter id="filter4400" style="color-interpolation-filters:sRGB">
                                <feFlood flood-color="rgb(0,0,0)" flood-opacity="0.470588" id="feFlood4402"
                                         result="flood"/>
                                <feComposite id="feComposite4404" in="flood" in2="SourceGraphic" operator="in"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur4406" in="composite1" result="blur"
                                                stdDeviation="5"/>
                                <feOffset dx="0" dy="5" id="feOffset4408" result="offset"/>
                                <feComposite id="feComposite4410" in="SourceGraphic" in2="offset" operator="over"
                                             result="fbSourceGraphic"/>
                                <feColorMatrix id="feColorMatrix4640" in="fbSourceGraphic"
                                               result="fbSourceGraphicAlpha"
                                               values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/>
                                <feFlood flood-color="rgb(255,253,180)" flood-opacity="1" id="feFlood4642"
                                         in="fbSourceGraphic" result="flood"/>
                                <feComposite id="feComposite4644" in="flood" in2="fbSourceGraphic" operator="out"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur4646" in="composite1" result="blur"
                                                stdDeviation="5"/>
                                <feOffset dx="0" dy="-5" id="feOffset4648" result="offset"/>
                                <feComposite id="feComposite4650" in="offset" in2="fbSourceGraphic" operator="atop"
                                             result="composite2"/>
                            </filter>
                            <filter id="filter4678" style="color-interpolation-filters:sRGB">
                                <feFlood flood-color="rgb(255,253,180)" flood-opacity="1" id="feFlood4680"
                                         result="flood"/>
                                <feComposite id="feComposite4682" in="flood" in2="SourceGraphic" operator="out"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur4684" in="composite1" result="blur"
                                                stdDeviation="5"/>
                                <feOffset dx="0" dy="-7" id="feOffset4686" result="offset"/>
                                <feComposite id="feComposite4688" in="offset" in2="SourceGraphic" operator="atop"
                                             result="composite2"/>
                            </filter>
                            <filter id="filter5045" style="color-interpolation-filters:sRGB">
                                <feFlood flood-color="rgb(255,250,175)" flood-opacity="1" id="feFlood5047"
                                         result="flood"/>
                                <feComposite id="feComposite5049" in="flood" in2="SourceGraphic" operator="out"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur5051" in="composite1" result="blur"
                                                stdDeviation="5"/>
                                <feOffset dx="0" dy="-6" id="feOffset5053" result="offset"/>
                                <feComposite id="feComposite5055" in="offset" in2="SourceGraphic" operator="atop"
                                             result="composite2"/>
                            </filter>
                            <filter id="filter4607" style="color-interpolation-filters:sRGB;">
                                <feFlood flood-color="rgb(255,247,180)" flood-opacity="1" id="feFlood4609"
                                         result="flood"/>
                                <feComposite id="feComposite4611" in="flood" in2="SourceGraphic" operator="out"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur4613" in="composite1" result="blur"
                                                stdDeviation="5"/>
                                <feOffset dx="0" dy="-6" id="feOffset4615" result="offset"/>
                                <feComposite id="feComposite4617" in="offset" in2="SourceGraphic" operator="atop"
                                             result="composite2"/>
                            </filter>
                            <filter id="filter4507" style="color-interpolation-filters:sRGB;">
                                <feFlood flood-color="rgb(255,249,199)" flood-opacity="1" id="feFlood4509"
                                         result="flood"/>
                                <feComposite id="feComposite4511" in="flood" in2="SourceGraphic" operator="out"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur4513" in="composite1" result="blur"
                                                stdDeviation="3"/>
                                <feOffset dx="0" dy="-2.60417" id="feOffset4515" result="offset"/>
                                <feComposite id="feComposite4517" in="offset" in2="SourceGraphic" operator="atop"
                                             result="fbSourceGraphic"/>
                                <feColorMatrix id="feColorMatrix4687" in="fbSourceGraphic"
                                               result="fbSourceGraphicAlpha"
                                               values="0 0 0 -1 0 0 0 0 -1 0 0 0 0 -1 0 0 0 0 1 0"/>
                                <feFlood flood-color="rgb(255,244,153)" flood-opacity="1" id="feFlood4689"
                                         in="fbSourceGraphic" result="flood"/>
                                <feComposite id="feComposite4691" in="flood" in2="fbSourceGraphic" operator="out"
                                             result="composite1"/>
                                <feGaussianBlur id="feGaussianBlur4693" in="composite1" result="blur"
                                                stdDeviation="3.4"/>
                                <feOffset dx="0" dy="-3.9" id="feOffset4695" result="offset"/>
                                <feComposite id="feComposite4697" in="offset" in2="fbSourceGraphic" operator="atop"
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
                <?php
                if (isset($_GET['lrn'])) {
                    $lrns = $_GET['lrn'];
                    $TGrade = $_GET['Tgrade'];
                    echo "<script>showModal('view-student-enrollment', 'Student Enrollment')</script>";
                    $sql = "select sei.grade, sei.section,si.l_name, si.f_name, si.m_name, sei.grade, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si 
                            inner join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn = '$lrns' 
                            GROUP BY sei.grade order by sei.id ASC";
                    $students_enrollment_info_result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($students_enrollment_info_result);
                    $lrn = isset($row['id']) ? $row['id'] + 1 : 0;
                    $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                    // Get the total number of records from our table "students".
                    $total_pages = $mysqli->query("select sei.grade, sei.section,si.l_name, si.f_name, si.m_name, sei.grade, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si 
                            inner join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn = '$lrns' 
                            GROUP BY sei.grade order by sei.id ASC")->num_rows;
                    //  Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                    $page = isset($_GET['page_enrollment']) && is_numeric($_GET['page_enrollment']) ? $_GET['page_enrollment'] : 1;

                    // Number of results to show on each page.
                    $num_results_on_page = 5;

                    if ($stmt = $mysqli->prepare("select sei.grade, sei.section,si.l_name, si.f_name, si.m_name, sei.grade, sei.school_year, sei.date_enrolled, sei.status, sei.id from students_info si 
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
                                <th class="t-align-center">View Grade</th>
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

                                    <td class="t-align-center">
                                        <svg onclick="showGrade('<?= $row['f_name'] ?>','<?= $row['l_name'] ?>','<?= $row['m_name'] ?>','<?= $row['grade'] ?>', '<?= $row['school_year'] ?>')"
                                             class="c-hand" width="40" height="40" id="object" viewBox="0 0 32 32"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <style>.cls-1 {
                                                        fill: #ffd599;
                                                    }

                                                    .cls-2 {
                                                        fill: #6d6daa;
                                                    }</style>
                                            </defs>
                                            <title/>
                                            <path class="cls-1"
                                                  d="M16,3.36a18.63,18.63,0,0,0-14,0V30a18.63,18.63,0,0,1,14,0,18.63,18.63,0,0,1,14,0V3.36A18.63,18.63,0,0,0,16,3.36Z"/>
                                            <path d="M30,31a1,1,0,0,1-.38-.07,17.63,17.63,0,0,0-13.24,0,1.1,1.1,0,0,1-.76,0,17.63,17.63,0,0,0-13.24,0A1,1,0,0,1,1,30V3.36a1,1,0,0,1,.62-.93A19.72,19.72,0,0,1,16,2.28a19.72,19.72,0,0,1,14.38.15,1,1,0,0,1,.62.93V30a1,1,0,0,1-1,1ZM9,27.64a19.76,19.76,0,0,1,7,1.28,19.78,19.78,0,0,1,13-.35V4.05a17.6,17.6,0,0,0-12.62.24,1.1,1.1,0,0,1-.76,0A17.66,17.66,0,0,0,3,4.05V28.57A19.73,19.73,0,0,1,9,27.64Z"/>
                                            <path class="cls-2"
                                                  d="M16,31a1,1,0,0,1-1-1V3.37a1,1,0,0,1,2,0V30A1,1,0,0,1,16,31Z"/>
                                        </svg>
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
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=<?php echo $_GET['Tgrade'] ?>&&Tsection=<?php echo $_GET['Tsection'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=<?php echo $_GET['Tgrade'] ?>&&Tsection=<?php echo $_GET['Tsection'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=<?php echo $_GET['Tgrade'] ?>&&Tsection=<?php echo $_GET['Tsection'] ?>&&Tlrn=<?php echo $_GET['Tlrn'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=<?php echo $_GET['Tgrade'] ?>&&Tsection=<?php echo $_GET['Tsection'] ?>&&Tlrn=<?php echo $_GET['Tlrn'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=<?php echo $_GET['Tgrade'] ?>&&Tsection=<?php echo $_GET['Tsection'] ?>&&Tlrn=<?php echo $_GET['Tlrn'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=<?php echo $_GET['Tgrade'] ?>&&Tsection=<?php echo $_GET['Tsection'] ?>&&Tlrn=<?php echo $_GET['Tlrn'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=<?php echo $_GET['Tgrade'] ?>&&Tsection=<?php echo $_GET['Tsection'] ?>&&Tlrn=<?php echo $_GET['Tlrn'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=<?php echo $_GET['Tgrade'] ?>&&Tsection=<?php echo $_GET['Tsection'] ?>&&Tlrn=<?php echo $_GET['Tlrn'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $_GET['id'] ?>&&Tgrade=<?php echo $_GET['Tgrade'] ?>&&Tsection=<?php echo $_GET['Tsection'] ?>&&Tlrn=<?php echo $_GET['Tlrn'] ?>&&lrn=<?php echo $lrns ?>&&page_enrollment=<?php echo $page + 1 ?>">Next</a>
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
                                    class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                                <option value="<?php echo $_GET['Tgrade'] ?>">
                                    Grade <?php echo $_GET['Tgrade'] ?></option>
                            </select>
                            <div class="w-70p m-l-1em">Section</div>
                            <input type="text" class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="add-enrollment-section"
                                   name="add-enrollment-section"
                                   value="<?php echo $_GET['Tsection'] ?>"
                                   readonly="true"
                                   required/>
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
                        <div class="r-50px d-flex-end m-t-1em">
                            <label class="btn bg-hover-gray-dark-v1 m-b-0"
                                   onclick="showModal('view-student-enrollment','Student Enrollment')"
                                   style="padding:0; background-color: #ffffff !important; border-color: #ffffff;">
                                <img src="../../assets/img/back.png" alt="" width="60" height="60" class="c-hand">
                            </label>
                            <button type="submit"
                                    class="c-hand btn-success btn"
                                    name="add-enrollment"
                                    style="background-color: #ffffff !important; border-color: #ffffff;">
                                <img src="../../assets/img/add.png" alt="" class="logo1 c-hand" width="50" height="50">
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="view-student-grade" class="modal-child d-none">

                <?php

                $lrn = isset($_GET['lrn']) ? $_GET['lrn'] : '';
                $Sgrade = isset($_GET['Sgrade']) ? $_GET['Sgrade'] : '';
                if (isset($_GET['Sgrade'])) {
                    echo "<script>showModal('view-student-grade', 'Student Grade')</script>";
                }


                $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$lrn' and sei.grade='$Sgrade'
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
                        $Sgrade = isset($_GET['Sgrade']) ? $_GET['Sgrade'] : '';
                        $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$studentLrn' and sei.grade='$Sgrade'
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

                    <?php
                    $Sgrade = $_GET['Sgrade'];
                    $Slrn = $_GET['lrn'];
                    $id = $_GET['id'];
                    $sqlStudents = "select * from students_info si 
                                             left join users_info ui on si.lrn = ui.user_lrn
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$Slrn' and sei.grade='$Sgrade'
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
                        $studentLrn = $_GET['lrn'];
                        $Sgrade = isset($_GET['Sgrade']) ? $_GET['Sgrade'] : '';
                        $sqlStudents = "select * from students_info si 
                                            left join students_enrollment_info sei on si.lrn = sei.students_info_lrn where si.lrn='$studentLrn' and sei.grade='$Sgrade'
                                            group by si.lrn";
                        $sqlStudents = mysqli_query($conn, $sqlStudents);
                        $row = mysqli_fetch_assoc($sqlStudents);
                        $grade = $row['grade'];
                        $teacherLrn = $row['teacher_lrn'];

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
                <div class="p-absolute btm-1em r-1em action-button d-flex-center">
                    <img onclick="backModal('view-student-enrollment', 'Student Enrollment','white')"
                         src="../../assets/img/back.png" alt="" width="60" height="60" class="c-hand">
                    &nbsp;&nbsp;&nbsp;
                    <svg class="c-hand" onclick="print('view-student-grade')" width="50" height="50" data-name="Layer 1"
                         id="Layer_1" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <style>.cls-1 {
                                    fill: #40bdff;
                                }

                                .cls-2 {
                                    fill: #eb5639;
                                }

                                .cls-3 {
                                    fill: #d84936;
                                }

                                .cls-4 {
                                    fill: #effafe;
                                }

                                .cls-5 {
                                    fill: #2197f7;
                                }

                                .cls-6 {
                                    fill: #e4ebed;
                                }

                                .cls-7 {
                                    fill: #263238;
                                }

                                .cls-8 {
                                    fill: #fd0;
                                }</style>
                        </defs>
                        <title/>
                        <path class="cls-1"
                              d="M236,84.62H25a8,8,0,0,0-8,8v70.77a8,8,0,0,0,8,8H51.87V145.13H39.5a2,2,0,0,1,0-4h177a2,2,0,0,1,0,4H204.13v26.26H236a8,8,0,0,0,8-8V92.62A8,8,0,0,0,236,84.62ZM85.51,102.18a2,2,0,0,1-2,2H68.38a2,2,0,0,1,0-4H83.51A2,2,0,0,1,85.51,102.18Zm-30.26,0a9.57,9.57,0,0,1-9.56,9.56H30.56a9.56,9.56,0,0,1,0-19.13H45.69A9.58,9.58,0,0,1,55.26,102.18Z"/>
                        <path class="cls-2"
                              d="M45.69,96.62H30.56a5.56,5.56,0,0,0,0,11.13H45.69a5.56,5.56,0,0,0,0-11.13Z"/>
                        <path class="cls-3" d="M45.69,96.62h-3a5.56,5.56,0,0,1,0,11.13h3a5.56,5.56,0,0,0,0-11.13Z"/>
                        <path class="cls-4"
                              d="M68.87,231.9H192.13a8,8,0,0,0,8-8V145.13H60.87V223.9A8,8,0,0,0,68.87,231.9Zm1.64-15.13h115a2,2,0,0,1,0,4h-115a2,2,0,0,1,0-4Zm115-11.13h-115a2,2,0,0,1,0-4h115a2,2,0,0,1,0,4Zm0-15.13h-115a2,2,0,0,1,0-4h115a2,2,0,0,1,0,4Zm0-15.13h-115a2,2,0,0,1,0-4h115a2,2,0,0,1,0,4Zm0-15.13h-115a2,2,0,0,1,0-4h115a2,2,0,0,1,0,4Z"/>
                        <path class="cls-4" d="M200.13,32.1a8,8,0,0,0-8-8H68.87a8,8,0,0,0-8,8V80.62H200.13Z"/>
                        <path class="cls-5"
                              d="M236,84.62h-5a8,8,0,0,1,8,8v70.77a8,8,0,0,1-8,8h5a8,8,0,0,0,8-8V92.62A8,8,0,0,0,236,84.62Z"/>
                        <path class="cls-6" d="M195.13,145.13V223.9a8,8,0,0,1-8,8h5a8,8,0,0,0,8-8V145.13Z"/>
                        <path class="cls-6" d="M192.13,24.1h-5a8,8,0,0,1,8,8V80.62h5V32.1A8,8,0,0,0,192.13,24.1Z"/>
                        <path class="cls-7"
                              d="M236,80.12H204.63v-48a12.51,12.51,0,0,0-12.5-12.5H63.87a12.51,12.51,0,0,0-12.5,12.5v48H20A12.51,12.51,0,0,0,7.5,92.62v70.77A12.51,12.51,0,0,0,20,175.88H51.37v48a12.51,12.51,0,0,0,12.5,12.5H192.13a12.51,12.51,0,0,0,12.5-12.5v-48H236a12.51,12.51,0,0,0,12.5-12.5V92.62A12.51,12.51,0,0,0,236,80.12ZM56.37,32.1a7.51,7.51,0,0,1,7.5-7.5H192.13a7.51,7.51,0,0,1,7.5,7.5v48H56.37ZM199.63,173.38V223.9a7.51,7.51,0,0,1-7.5,7.5H63.87a7.51,7.51,0,0,1-7.5-7.5V145.63H199.63Zm43.87-10a7.51,7.51,0,0,1-7.5,7.5H204.63V145.63H216.5a2.5,2.5,0,0,0,0-5H39.5a2.5,2.5,0,0,0,0,5H51.37v25.26H20a7.51,7.51,0,0,1-7.5-7.5V92.62a7.51,7.51,0,0,1,7.5-7.5H236a7.51,7.51,0,0,1,7.5,7.5Z"/>
                        <path class="cls-7"
                              d="M45.69,92.12H30.56a10.06,10.06,0,0,0,0,20.13H45.69a10.06,10.06,0,0,0,0-20.13Zm0,15.13H30.56a5.06,5.06,0,0,1,0-10.13H45.69a5.06,5.06,0,0,1,0,10.13Z"/>
                        <path class="cls-7" d="M83.51,99.68H68.38a2.5,2.5,0,0,0,0,5H83.51a2.5,2.5,0,0,0,0-5Z"/>
                        <path class="cls-7" d="M70.51,160.76h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-7" d="M70.51,175.88h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-7" d="M70.51,191h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-7" d="M70.51,206.14h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-7" d="M70.51,221.27h115a2.5,2.5,0,0,0,0-5h-115a2.5,2.5,0,0,0,0,5Z"/>
                        <path class="cls-8"
                              d="M23,196H19v-4a1,1,0,0,0-2,0v4H13a1,1,0,0,0,0,2h4v4a1,1,0,0,0,2,0v-4h4a1,1,0,0,0,0-2Z"/>
                        <path class="cls-8"
                              d="M233,188.25h-4v-4a1,1,0,0,0-2,0v4h-4a1,1,0,0,0,0,2h4v4a1,1,0,0,0,2,0v-4h4a1,1,0,0,0,0-2Z"/>
                        <path class="cls-1"
                              d="M26.32,28h-4V24a1,1,0,0,0-2,0v4h-4a1,1,0,0,0,0,2h4v4a1,1,0,0,0,2,0V30h4a1,1,0,0,0,0-2Z"/>
                        <path class="cls-1"
                              d="M220,31.6a6,6,0,1,1,6-6A6,6,0,0,1,220,31.6Zm0-10a4,4,0,1,0,4,4A4,4,0,0,0,220,21.6Z"/>
                        <path class="cls-1"
                              d="M38.13,241a6,6,0,1,1,6-6A6,6,0,0,1,38.13,241Zm0-10a4,4,0,1,0,4,4A4,4,0,0,0,38.13,231Z"/>
                        <path class="cls-8"
                              d="M227.72,72.75a6,6,0,1,1,6-6A6,6,0,0,1,227.72,72.75Zm0-10a4,4,0,1,0,4,4A4,4,0,0,0,227.72,62.75Z"/>
                        <path class="cls-1"
                              d="M228.33,228.5h-2.59l1.83-1.83a1,1,0,0,0-1.41-1.41l-1.83,1.83V224.5a1,1,0,0,0-2,0v2.59l-1.83-1.83a1,1,0,0,0-1.41,1.41l1.83,1.83h-2.59a1,1,0,0,0,0,2h2.59l-1.83,1.83a1,1,0,0,0,1.41,1.41l1.83-1.83v2.59a1,1,0,0,0,2,0v-2.59l1.83,1.83a1,1,0,0,0,1.41-1.41l-1.83-1.83h2.59a1,1,0,0,0,0-2Z"/>
                    </svg>
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
        </div>
    </div>
</div>


<script>


    function search(status) {
        if (status === "name") {
            var search = $('#search_name').val();
            window.location.href = '?id=<?php echo $_GET['id'] ?>&&searchName=' + search;
        } else {
            var from = $('.search-from').val();
            var to = $('.search-to').val();
            window.location.href = '?id=<?php echo $_GET['id'] ?>&&from=' + from + '&&to=' + to;
        }
    }

    function loadPage() {
        var searchName = '<?php echo isset($_GET['searchName']) ? $_GET['searchName'] : '' ?>';
        if (searchName !== '') {
            $('#search_name').val(searchName);
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

    loadPage();

</script>
<link href="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/gh/xxjapp/xdialog@3/xdialog.min.js"></script>