<?php global $mysqli;
$var = "student_list";
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
if (isset($_POST['save'])) {

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
    $gradeLevel = $_POST['gradeLevel'];
    $lrn = $_POST['lrn'];

    $sql = "insert into students_info (f_name,l_name,m_name,gender,b_place,c_status,age,b_date,nationality,religion,contact_number,email_address,home_address,lrn,g_level,guardian_name) VALUES ('$firstName', '$lastName', '$middleName', '$gender', '$birthPlace', '$civilStatus', '$age', '$birthDate' , '$nationality', '$religion', '$contactNumber', '$emailAddress', '$homeAddress', '$lrn', '$gradeLevel', '$guardianName')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
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

if (isset($_POST['update'])) {
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
    $gradeLevel = $_POST['up-gradeLevel'];
    $lrn = $_POST['up-lrn'];
    $sql = "update students_info set f_name = '$firstName', l_name = '$lastName', m_name = '$middleName', gender = '$gender', b_date='$birthDate', b_place = '$birthPlace', c_status = '$civilStatus'
                     , age = '$age', nationality = '$nationality', religion = '$religion', contact_number = '$contactNumber', email_address = '$emailAddress', home_address = '$homeAddress', guardian_name='$guardianName', g_level='$gradeLevel' where lrn = '$lrn'";
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

?>

<div class="d-flex-end p-absolute w-100p h-100p t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px">

        <style>
            .table-1 tbody tr th, .table-1 tbody tr td {
                border-bottom: 0 !important;
                border-top: 0 !important;
            }

            .table-1 thead tr th, .table-1 thead tr td {
                border-top: 0 !important;
                border-bottom: 3px solid #ddd;
            }
        </style>

        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px">

                <div class="pad-1em  f-weight-bold d-flex">
                    <h3>
                        STUDENT LIST
                    </h3>
                    <div class="r-50px p-absolute t-54px">
                        <button
                                class="btn bg-hover-gray-dark-v1" onclick="showModal('add-new-student', 'New Student')">
                            Add New
                        </button>
                        <button
                                class="btn bg-hover-gray-dark-v1"
                                onclick="deleteStudents('student-list')">Delete Selected
                        </button>
                    </div>
                </div>
                <br/>

                <?php
                $sql = "select * from students_info order by id desc";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $lrn = $row['id'] + 1;
                $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                // Get the total number of records from our table "students".
                $total_pages = $mysqli->query('SELECT * FROM students_info')->num_rows;
                // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                // Number of results to show on each page.
                $num_results_on_page = 10;

                if ($stmt = $mysqli->prepare('SELECT * FROM students_info ORDER BY id LIMIT ?,?')) {
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
                            <th>LRN</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Birthdate</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Grade Level</th>
                            <th>Option</th>
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
                                <td><?= $row['g_level'] === 0 ? 'not enrolled' : $row['g_level'] ?></td>
                                <td>
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewStudentEnrollment('<?= $row['lrn'] ?>')"
                                    >View Enrollment</label>
                                    &nbsp;
                                    <label for="" class="t-color-red c-hand f-weight-bold"
                                           onclick="viewStudentInformation('<?= "[" . $row['lrn'] . "?" . $row['f_name'] . "?" . $row['l_name'] . "?" . $row['b_date'] . "?" . $row['age'] . "?" . $row['home_address'] . "?" . $row['guardian_name'] . "?" . $row['g_level'] . "?" . $row['c_status'] . "?" . $row['religion'] . "?" . $row['contact_number'] . "?" . $row['m_name'] . "?" . $row['b_place'] . "?" . $row['nationality'] . "?" . $row['email_address'] . "?" . $row['gender'] . "]" ?>')"
                                    >View Details</label>
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
    <script>
        function showModal(id, title, theme) {
            $('.modal-header').empty();
            $('.modal-header').append('<h2>' + title + '</h2>');
            $('.modal-header').append('<span class="close" onclick="closeModal()">&times;</span>');
            if (theme === 'dark') {
                $('.modal-content').css('background-color', '#757575');
                $('.modal-content').css('color', 'white');
                $('.modal-header').css('border-bottom', '3px solid black');
                $('.modal-body').addClass('d-flex-center');
            } else {
                $('.modal-content').css('background-color', '#fff');
                $('.modal-header').css('border-bottom', '3px solid #80808038');
                $('.modal-content').css('color', 'black');
                $('.modal-body').removeClass('d-flex-center');
            }


            $('#myModal').css('display', 'block');
            $('body').css('overflow', 'hidden');
            $('.modal-body .modal-child').css('display', 'none');
            $('#' + id).css('display', 'block');
            localStorage.getItem('topArrow') === '1' ? $('.top-icon').css('display', 'none') : $('.top-icon').css('display', '');
        }
    </script>
    <div class="modal-content">
        <div id="top-icon"
             class="top-icon h-100p d-flex-center p-absolute w-3em c-hand f-size-26px w-2em bg-hover-white t-color-white"
             onclick="tops()" style="left: -97px;top: -97px;height: 61px;">☰
        </div>
        <div class="modal-header a-center">
        </div>
        <div class="modal-body">
            <div id="add-new-student" class="modal-child pad-top-2em pad-bottom-2em">
                <form method="post">
                    <div class="custom-grid-container" tabindex="3">
                        <div class="custom-grid-item d-inline-grid">
                            <div class="w-70p m-l-1em">ID Number</div>
                            <input placeholder="<?php echo $lrn ?>" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="lrn"
                                   name="lrn"
                                   readonly="true"
                                   value="<?php echo $lrn ?>">
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
                            <input placeholder="Civil Status" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="civilStatus"
                                   name="civilStatus"
                                   required>
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
                        <div class="custom-grid-item d-inline-grid">
                            <input type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em op-0"
                                   disabled="true"
                                   required="false">
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
                        <div class="custom-grid-item d-inline-grid">
                            <input type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em op-0"
                                   disabled="true"
                                   required="false">
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
                            <div class="w-70p m-l-1em">Grade Level</div>
                            <input placeholder="Grade Level" type="number"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="gradeLevel"
                                   name="gradeLevel"
                                   required>
                        </div>
                    </div>
                    <div class="b-top-gray-3px m-1em">
                        <div class="m-t-2em">
                            <h2> Other Details</h2>
                            <h6>Requirements</h6>
                            <h3>NSO</h3>
                            <h3>REPORT CARD</h3>
                            <h3>CERTIFICATE TRANSFER</h3>
                        </div>
                        <div class="r-50px d-flex-end gap-1em">
                            <button type="submit"
                                    class="c-hand btn-success btn"
                                    name="save">Submit
                            </button>
                            <button
                                    class="btn bg-hover-gray-dark-v1" onclick="closeModal()">
                                Close
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="view-student-info" class="modal-child">
                <h4>LRN: <label></label></h4>
                <h4>Firstname: <label></label></h4>
                <h4>Lastname: <label></label></h4>
                <h4>Birthdate: <label></label></h4>
                <h4>Age: <label></label></h4>
                <h4>Address: <label></label></h4>
                <h4>Guardian Name: <label></label></h4>
                <h4>Enrolled Grade: <label></label></h4>

                <h4 class="d-none">Civil Status: <label></label></h4>
                <h4 class="d-none">Religion: <label></label></h4>
                <h4 class="d-none">Contact Number: <label></label></h4>
                <h4 class="d-none">Middle Name: <label></label></h4>
                <h4 class="d-none">Birth Place: <label></label></h4>
                <h4 class="d-none">Nationality: <label></label></h4>
                <h4 class="d-none">Email address: <label></label></h4>
                <h4 class="d-none">Gender: <label></label></h4>
                <div class="p-absolute btm-1em r-1em">
                    <button class="c-hand btn-primary btn"
                            name="save" onclick="updateStudentInformation()">Update
                    </button>
                    <button
                            class="btn bg-hover-gray-dark-v1" onclick="closeModal()">
                        Close
                    </button>
                </div>
            </div>
            <div id="update-student-info" class="modal-child">
                <form method="post">
                    <div class="custom-grid-container" tabindex="3">
                        <div class="custom-grid-item d-inline-grid">
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
                        <div class="custom-grid-item d-inline-grid">
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
                        <div class="custom-grid-item d-inline-grid">
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
                            <div class="w-70p m-l-1em">Grade Level</div>
                            <input placeholder="Grade Level" type="text"
                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                   id="up-gradeLevel"
                                   name="up-gradeLevel"
                                   required>
                        </div>
                    </div>
                    <div class="b-top-gray-3px m-1em">
                        <div class="r-50px d-flex-end gap-1em m-t-1em">
                            <button type="submit"
                                    class="c-hand btn-success btn"
                                    name="update">Submit
                            </button>
                            <button
                                    class="btn bg-hover-gray-dark-v1" onclick="closeModal()">
                                Close
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="view-student-enrollment" class="modal-child pad-bottom-2em">

                <div class="d-flex-end gap-1em">
                    <button
                            class="btn bg-hover-gray-dark-v1" onclick="showModal('add-new-student', 'New Student')">
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
                    echo "<script>showModal('view-student-enrollment', ' Student Enrollment')</script>";


                    $sql = "select * from students_enrollment_info where students_info_id = '$lrns' ";
                    $students_enrollment_info_result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($students_enrollment_info_result);
                    $lrn = $row['id'] + 1;
                    $lrn = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                    // Get the total number of records from our table "students".
                    $total_pages = $mysqli->query("SELECT * FROM students_enrollment_info where students_info_id = '$lrns' ")->num_rows;
                    //  Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                    $page = isset($_GET['page_enrollment']) && is_numeric($_GET['page_enrollment']) ? $_GET['page_enrollment'] : 1;

                    // Number of results to show on each page.
                    $num_results_on_page = 5;

                    if ($stmt = $mysqli->prepare("SELECT * FROM students_enrollment_info where students_info_id = '$lrns' ORDER BY id LIMIT ?,?")) {
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
                                               onclick="viewStudentEnrollment()">View Grade</label>
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
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page_enrollment=<?php echo $page - 1 ?>&&lrn=<?php echo $lrns ?>">Prev</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page_enrollment=<?php echo $page + 1 ?>&&lrn=<?php echo $lrns ?>">1</a>
                                    </li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page_enrollment=<?php echo $page - 2 ?>&&lrn=<?php echo $lrns ?>"><?php echo $page - 2 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page_enrollment=<?php echo $page - 1 ?>&&lrn=<?php echo $lrns ?>"><?php echo $page - 1 ?></a>
                                    </li><?php endif; ?>

                                <li class="currentpage"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page_enrollment=<?php echo $page ?>&&lrn=<?php echo $lrns ?>"><?php echo $page ?></a>
                                </li>

                                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page_enrollment=<?php echo $page + 1 ?>&&lrn=<?php echo $lrns ?>"><?php echo $page + 1 ?></a>
                                    </li><?php endif; ?>
                                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                    <li class="page"><a
                                            href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page_enrollment=<?php echo $page + 2 ?>&&lrn=<?php echo $lrns ?>"><?php echo $page + 2 ?></a>
                                    </li><?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page_enrollment=<?php echo ceil($total_pages / $num_results_on_page) ?>&&lrn=<?php echo $lrns ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                    <li class="next"><a
                                                href="/1-php-grading-system/admins_page/student_list/?id=<?php echo $rows['id'] ?>&&page_enrollment=<?php echo $page + 1 ?>&&lrn=<?php echo $lrns ?>">Next</a>
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
<?php ?>

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
                studentID.forEach(function (studId) {
                    if (id === 'student-enrollment') {
                        $.post('', {studentEnrollmentId: studId})
                    } else {
                        $.post('', {id: studId})
                    }
                    alert('Successfully deleted!')
                    history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id'] ?>');
                    window.location.reload();
                });
            }
        } else {
            alert('Please select a student!');
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
        $('#up-religion').val($('#view-student-info h4:nth-child(10) label').text());
        $('#up-homeAddress').val($('#view-student-info h4:nth-child(6) label').text());

        var birthDate = new Date($('#view-student-info h4:nth-child(4) label').text());
        var month = String(birthDate.getMonth() + 1).length === 1 ? '0' + (birthDate.getMonth() + 1) : (birthDate.getMonth() + 1);
        $('#up-birthDate').val(birthDate.getFullYear() + '-' + month + '-' + birthDate.getDate())

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
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $rows['id'] ?>&&lrn=' + lrn);
        window.location.reload();
    }

</script>


