<?php global $mysqli, $rows;
$var = "add_new_user";
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

if (isset($_POST['add-enrollment'])) {
    $lrn = $_POST['add-enrollment-lrn'];
    $gradeLevel = $_POST['add-enrollment-grade'];
    $schoolYear = $_POST['add-enrollment-school-year'];
    $schoolYear = date("Y-m-d", strtotime($schoolYear));
    $dateEnrolled = $_POST['add-enrollment-date-enrolled'];
    $dateEnrolled = date("Y-m-d", strtotime($dateEnrolled));
    $status = $_POST['add-enrollment-status'];
    $sql = "insert into students_enrollment_info (students_info_lrn,grade,school_year,date_enrolled,status) VALUES ('$lrn','$gradeLevel','$schoolYear','$dateEnrolled','$status')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
              alert("saved successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&lrn=' . $lrn . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['add-new-student'])) {
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
    $lrn = $_POST['lrn'];

    $sql = "insert into students_info (f_name,l_name,m_name,gender,b_place,c_status,age,b_date,nationality,religion,contact_number,email_address,home_address,lrn,guardian_name) VALUES ('$firstName', '$lastName', '$middleName', '$gender', '$birthPlace', '$civilStatus', '$age', '$birthDate' , '$nationality', '$religion', '$contactNumber', '$emailAddress', '$homeAddress', '$lrn', '$guardianName')";
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

if (isset($_POST['update-student-info'])) {
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
    $lrn = $_POST['up-lrn'];
    $sql = "update students_info set f_name = '$firstName', l_name = '$lastName', m_name = '$middleName', gender = '$gender', b_date='$birthDate', b_place = '$birthPlace', c_status = '$civilStatus'
                     , age = '$age', nationality = '$nationality', religion = '$religion', contact_number = '$contactNumber', email_address = '$emailAddress', home_address = '$homeAddress', guardian_name='$guardianName' where lrn = '$lrn'";
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
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px contents one_page">

   add user
    </div>
</div>

