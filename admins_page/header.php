<?php global $var, $var1; ?>
<?php
global $conn;
include "../../db_conn.php";

session_start();
if (!isset($_SESSION['user_type'])) {
    header("Location: /1-php-grading-system/admins_page/404");
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users_info WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['user_type'] === 'student') {
        $sqlStudent = "SELECT * FROM students_info si
             left join users_info ui on ui.user_lrn = si.lrn
             WHERE ui.id='$id'";
        $resultStudent = mysqli_query($conn, $sqlStudent);
        $rows = mysqli_fetch_assoc($resultStudent);
    } else if ($row['user_type'] === 'teacher') {
        $sqlTeacher = "SELECT * FROM teachers_info ti
             left join users_info ui on ui.user_lrn = ti.lrn
             WHERE ui.id='$id'";
        $resultTeacher = mysqli_query($conn, $sqlTeacher);
        $rows = mysqli_fetch_assoc($resultTeacher);
    } else {
        $rows = $row;
    }

    if (isset($_POST['logout'])) {
        unset($_SESSION['user_type']); // remove it now we have used it
        unset($_SESSION['ids']); // remove it now we have used it
        header("Location: /1-php-grading-system/");
    }
}

if (isset($_POST['editProfile'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $id = $_GET['id'];

    $selectUserInfo = "SELECT * FROM users_info WHERE id='$id'";
    $resultUserInfo = mysqli_query($conn, $selectUserInfo);
    $rowUserInfo = mysqli_fetch_assoc($resultUserInfo);
    $pw = $rowUserInfo['password'];

    if($pw !== $password){
        // Hash the admin password
        $hashed_admin_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users_info SET first_name='$firstname', last_name='$lastname', username='$username', password='$hashed_admin_password', email='$email' WHERE id='$id'";
        $result = mysqli_query($conn, $sql);

    } else {
        $sql = "UPDATE users_info SET first_name='$firstname', last_name='$lastname', username='$username', email='$email' WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
    }

    if ($result) {
        echo '<script>';
        echo '   
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '&&updateProfile=success");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['saveImage'])) {
    $darkMode = $_POST['saveImage'];
    $id = $_POST['id'];

    $sql = "UPDATE users_info SET img_path = '../../assets/users_img/$id.png' WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

}

if (isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $fileTmpName = $file['tmp_name'];
    $id = $_POST['id']; // Replace with the actual way you get the user ID

    $uploadDir = '../../assets/users_img/';
    $uploadPath = $uploadDir . $id . '.png'; // Adjust the file path as needed

    if (move_uploaded_file($fileTmpName, $uploadPath)) {
        echo 'Image uploaded successfully.';
    } else {
        echo 'Error uploading file.';
    }
}

if (isset($_POST['darkMode'])) {
    $darkMode = $_POST['darkMode'];
    $id = $_POST['id'];

    $sql = "UPDATE users_info SET dark_mode='$darkMode' WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    // Update dark mode session
    $_SESSION['dark_mode'] = $darkMode;
}

$id = $_GET['id'];
// Fetch dark mode setting from the database
$sqlDarkMode = "SELECT dark_mode FROM users_info WHERE id='$id'";
$resultDarkMode = mysqli_query($conn, $sqlDarkMode);
$rowDarkMode = mysqli_fetch_assoc($resultDarkMode);
$darkModeFromDB = $rowDarkMode['dark_mode'];

?>

<!DOCTYPE html>
<html>
<title>MABES GRADE INQUIRY</title>
<link rel="shortcut icon" href="../../assets/img/mabes.png"/>
<head>
    <link rel="stylesheet" href="../../assets/css/style_custom.css">
    <link rel="stylesheet" href="../../assets/css/admin_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <script src="../../assets/js/js_header.js" defer></script>
</head>
<body onload="loadTopArrow()" <?php echo (isset($rows) && !empty($rows)) ? (isset($darkModeFromDB) && $darkModeFromDB == 1 ? 'class="dark-theme"' : '') : 'style="display:none;"'; ?>>


<div id="modal-delete" class="modal2">
    <div class="square">
        <div class="modal-content">
            <div id="modal-delete" class="modal-content1">
                <div class="modal-logo  d-flex-center">
                    <svg class="c-hand" id="svg2"
                         version="1.1" viewBox="0 0 99.999995 99.999995" width="60" height="60"
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
                <div class="modal-short-msg d-flex-center">
                    <h1> Are you sure? </h1>
                </div>
                <div class="modal-long-msg  d-flex-center">
                    <h7>
                        Do you really want to delete these records? This process cannot be undone.
                    </h7>
                </div>
                <div class="modal-msg-choice d-flex-center">
                    <div class="modal-msg-choice-yes btn">
                        <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-delete-cancel">Cancel
                        </button>
                    </div>
                    <div class="modal-msg-choice-no">
                        <button class="btn-primary btn" id="modal-delete-ok">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-logout" class="modal2">
    <div class="square">
        <div class="modal-content">
            <div class="modal-content1">
                <div class="modal-logo  d-flex-center">
                    <img src="../../assets/img/logout.png" width="60" height="60" alt="">
                </div>
                <div class="modal-short-msg d-flex-center">
                    <h1> Are you sure? </h1>
                </div>
                <div class="modal-long-msg  d-flex-center">
                    <h7>
                        Do you really want to logout? This process cannot be undone.
                    </h7>
                </div>
                <div class="modal-msg-choice d-flex-center">
                    <div class="modal-msg-choice-yes btn">
                        <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-cancel">Cancel</button>
                    </div>
                    <div class="modal-msg-choice-no">
                        <button class="btn-primary btn" id="modal-ok">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-checkbox" class="modal2">
    <div class="square">
        <div class="modal-content">
            <div class="modal-content1">
                <div class="modal-logo  d-flex-center">
                    <img src="../../assets/img/warning.png" width="60" height="60" alt="">
                </div>
                <div class="modal-short-msg d-flex-center">
                    <h1> Warning!</h1>
                </div>
                <div class="modal-long-msg  d-flex-center">
                    <h7>
                        Before taking any action, please make sure to choose at least one checkbox.
                    </h7>
                </div>
                <div class="modal-msg-choice d-flex-center">
                    <div class="modal-msg-choice-yes btn">
                        <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-delete-cancel">Ok
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-addedSuccessfully" class="modal2">
    <div class="square">
        <div class="modal-content">
            <div class="modal-content1">
                <div class="modal-logo  d-flex-center">
                    <img src="../../assets/img/added.png" width="60" height="60" alt="">
                </div>
                <div class="modal-short-msg d-flex-center">
                    <h1> Success!</h1>
                </div>
                <div class="modal-long-msg  d-flex-center">
                    <h7>
                        Successfully added/updated!
                    </h7>
                </div>
                <div class="modal-msg-choice d-flex-center">
                    <div class="modal-msg-choice-yes btn">
                        <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-success">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-recover" class="modal2">
    <div class="square">
        <div class="modal-content">
            <div class="modal-content1">
                <div class="modal-logo  d-flex-center">
                    <svg class="c-hand" width="60" height="60"
                         onclick="recoverStudentInformation('<?= $row['id'] ?>')"
                         style="enable-background:new 0 0 500 500.002;" version="1.1"
                         viewBox="0 0 500 500.002" xml:space="preserve"
                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g
                                id="memory-sd-card">
                            <g>
                                <path d="M250.001,0C111.93,0,0,111.94,0,250.006c0,138.076,111.93,249.996,250.001,249.996    C388.07,500.002,500,388.082,500,250.006C500,111.94,388.07,0,250.001,0z"
                                      style="fill:#7DBEBD;"/>
                                <g id="_x32_2">
                                    <path d="M153.948,366.656c-8.673-0.019-15.695-7.039-15.712-15.712l0,0v-128.5     c0.152-7.81,3.837-16.712,9.246-22.339l0,0l57.512-57.517c5.632-5.394,14.533-9.086,22.346-9.241l0,0v4.492v4.487     c-4.55-0.165-12.891,3.308-15.988,6.626l0,0l-57.517,57.498c-3.318,3.109-6.779,11.448-6.619,15.993l0,0v128.5     c0.009,3.662,3.066,6.723,6.733,6.723l0,0h192.106c3.667,0,6.723-3.061,6.728-6.723l0,0V149.059     c-0.005-3.662-3.061-6.728-6.728-6.728l0,0H227.34v-0.005v-4.487v-4.502l118.714,0.01c8.668,0.019,15.697,7.034,15.712,15.712     l0,0v201.885c-0.015,8.673-7.044,15.693-15.712,15.712l0,0H153.948L153.948,366.656z"
                                          style="fill:#656D78;"/>
                                    <g>
                                        <path d="M227.726,139.735c-6.071,0-14.55,3.517-18.839,7.8l-56.53,56.542      c-4.298,4.293-7.807,12.772-7.807,18.831v126.323c0,6.059,4.972,11.026,11.035,11.026h188.838      c6.062,0,11.033-4.967,11.033-11.026v-198.46c0-6.059-4.972-11.036-11.033-11.036H227.726z"
                                              style="fill:#656D78;"/>
                                    </g>
                                    <g>
                                        <path d="M181.385,318.341h-17.728c-1.344,0-2.445,1.096-2.445,2.444v39.38c7.381,0,15.021,0,22.598,0      v-39.38C183.81,319.437,182.709,318.341,181.385,318.341z"
                                              style="fill:#7C838C;"/>
                                        <path d="M220.231,318.341h-17.72c-1.349,0-2.44,1.096-2.44,2.444v39.38c7.374,0,15.009,0,22.595,0v-39.38      C222.666,319.437,221.565,318.341,220.231,318.341z"
                                              style="fill:#7C838C;"/>
                                        <path d="M259.084,318.341h-17.733c-1.336,0-2.425,1.096-2.425,2.444v39.38c7.369,0,15.006,0,22.588,0      v-39.38C261.514,319.437,260.414,318.341,259.084,318.341z"
                                              style="fill:#7C838C;"/>
                                        <path d="M297.933,318.341h-17.725c-1.341,0-2.435,1.096-2.435,2.444v39.38c7.376,0,15.013,0,22.598,0      v-39.38C300.37,319.437,299.274,318.341,297.933,318.341z"
                                              style="fill:#7C838C;"/>
                                        <path d="M336.788,318.341h-17.727c-1.334,0-2.433,1.096-2.433,2.444v39.38c7.378,0,15.009,0,22.593,0      v-39.38C339.221,319.437,338.125,318.341,336.788,318.341z"
                                              style="fill:#7C838C;"/>
                                    </g>
                                    <path d="M304.867,288.711c0,2.935-2.406,5.351-5.356,5.351h-95.085c-2.952,0-5.363-2.416-5.363-5.351     v-91.323c0-2.939,2.411-5.355,5.363-5.355h95.085c2.95,0,5.356,2.416,5.356,5.355V288.711z"
                                          style="fill:#FFFFFF;"/>
                                    <g>
                                        <path d="M227.949,230.709c-0.104-2.057-0.245-4.516-0.218-6.37h-0.071      c-0.505,1.722-1.111,3.566-1.863,5.613l-2.597,7.155h-1.441l-2.391-7.024c-0.696-2.086-1.293-3.992-1.705-5.744h-0.044      c-0.053,1.853-0.157,4.313-0.296,6.525l-0.385,6.325h-1.817l1.031-14.756h2.431l2.515,7.141c0.609,1.82,1.116,3.434,1.492,4.967      h0.061c0.374-1.499,0.898-3.119,1.565-4.967l2.622-7.141h2.43l0.914,14.756h-1.855L227.949,230.709z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M234.262,232.252c0.044,2.595,1.712,3.672,3.641,3.672c1.375,0,2.209-0.238,2.93-0.548      l0.327,1.378c-0.682,0.301-1.843,0.655-3.517,0.655c-3.272,0-5.225-2.149-5.225-5.351c0-3.187,1.887-5.704,4.977-5.704      c3.449,0,4.385,3.046,4.385,4.997c0,0.383-0.054,0.693-0.078,0.902H234.262z M239.906,230.864      c0.024-1.217-0.5-3.134-2.668-3.134c-1.938,0-2.801,1.795-2.959,3.134H239.906z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M244.167,229.463c0-1.091-0.022-1.994-0.082-2.862h1.676l0.087,1.698h0.078      c0.582-1.004,1.574-1.945,3.318-1.945c1.443,0,2.544,0.868,2.995,2.125h0.044c0.337-0.592,0.745-1.058,1.189-1.382      c0.628-0.475,1.336-0.742,2.338-0.742c1.407,0,3.486,0.912,3.486,4.594v6.243h-1.885v-5.996c0-2.042-0.735-3.27-2.307-3.27      c-1.086,0-1.938,0.82-2.268,1.756c-0.095,0.272-0.151,0.611-0.151,0.956v6.553h-1.885v-6.35c0-1.688-0.752-2.915-2.214-2.915      c-1.201,0-2.079,0.965-2.379,1.94c-0.124,0.272-0.158,0.602-0.158,0.936v6.388h-1.882V229.463z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M272.036,231.815c0,3.91-2.722,5.613-5.292,5.613c-2.865,0-5.072-2.096-5.072-5.448      c0-3.556,2.321-5.627,5.256-5.627C269.965,226.353,272.036,228.56,272.036,231.815z M263.622,231.912      c0,2.319,1.332,4.075,3.216,4.075c1.843,0,3.221-1.742,3.221-4.118c0-1.785-0.905-4.065-3.175-4.065      C264.609,227.803,263.622,229.899,263.622,231.912z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M274.467,229.899c0-1.247-0.027-2.324-0.09-3.299h1.683l0.061,2.076h0.087      c0.49-1.422,1.654-2.324,2.94-2.324c0.224,0,0.371,0.019,0.543,0.073v1.804c-0.194-0.044-0.38-0.063-0.652-0.063      c-1.364,0-2.321,1.024-2.579,2.469c-0.051,0.277-0.085,0.577-0.085,0.912v5.641h-1.908V229.899z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M282.875,226.6l2.326,6.253c0.237,0.703,0.504,1.533,0.67,2.168h0.048      c0.197-0.635,0.41-1.441,0.682-2.212l2.103-6.209h2.035l-2.888,7.538c-1.382,3.643-2.333,5.506-3.636,6.646      c-0.946,0.834-1.885,1.154-2.36,1.247l-0.485-1.625c0.485-0.146,1.113-0.451,1.676-0.936c0.533-0.417,1.189-1.159,1.625-2.149      c0.087-0.194,0.152-0.349,0.152-0.456c0-0.111-0.039-0.267-0.129-0.514l-3.917-9.75H282.875z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M227.379,246.659h-0.044l-2.469,1.334l-0.383-1.47l3.114-1.659h1.635v14.237h-1.853V246.659z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M234.417,259.102v-1.184l1.521-1.47c3.631-3.454,5.275-5.302,5.3-7.437      c0-1.45-0.693-2.789-2.828-2.789c-1.286,0-2.362,0.655-3.008,1.213l-0.623-1.368c0.989-0.82,2.387-1.441,4.033-1.441      c3.058,0,4.351,2.101,4.351,4.138c0,2.624-1.906,4.749-4.902,7.631l-1.138,1.057v0.044h6.386v1.606H234.417z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M245.497,255.483c0-1.795,1.06-3.061,2.818-3.808l-0.017-0.063      c-1.586-0.752-2.266-1.97-2.266-3.202c0-2.251,1.914-3.784,4.41-3.784c2.758,0,4.138,1.722,4.138,3.512      c0,1.194-0.594,2.489-2.341,3.309v0.073c1.766,0.698,2.865,1.96,2.865,3.682c0,2.469-2.124,4.138-4.841,4.138      C247.289,259.339,245.497,257.569,245.497,255.483z M253.156,255.4c0-1.732-1.206-2.571-3.126-3.11      c-1.673,0.475-2.566,1.577-2.566,2.93c-0.07,1.445,1.031,2.712,2.848,2.712C252.041,257.932,253.156,256.86,253.156,255.4z       M247.857,248.284c0,1.422,1.074,2.188,2.717,2.625c1.222-0.422,2.168-1.295,2.168-2.59c0-1.131-0.686-2.314-2.413-2.314      C248.74,246.004,247.857,247.052,247.857,248.284z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M268.813,258.446c-0.846,0.291-2.542,0.81-4.531,0.81c-2.232,0-4.07-0.567-5.511-1.96      c-1.274-1.222-2.069-3.192-2.069-5.476c0.027-4.405,3.056-7.64,7.989-7.64c1.712,0,3.046,0.374,3.689,0.684l-0.458,1.552      c-0.793-0.349-1.771-0.631-3.272-0.631c-3.582,0-5.933,2.231-5.933,5.923c0,3.745,2.258,5.962,5.695,5.962      c1.249,0,2.106-0.175,2.54-0.393v-4.4h-2.998v-1.523h4.859V258.446z"
                                              style="fill:#E9E9EA;"/>
                                        <path d="M271.58,259.102c0.049-0.728,0.09-1.805,0.09-2.736v-12.811h1.901v6.66h0.044      c0.681-1.184,1.906-1.96,3.616-1.96c2.637,0,4.489,2.198,4.465,5.413c0,3.784-2.386,5.671-4.751,5.671      c-1.53,0-2.763-0.602-3.544-1.998h-0.066l-0.097,1.761H271.58z M273.571,254.852c0,0.238,0.044,0.485,0.087,0.703      c0.366,1.339,1.494,2.251,2.886,2.251c2.02,0,3.226-1.649,3.226-4.075c0-2.12-1.099-3.944-3.153-3.944      c-1.317,0-2.54,0.902-2.938,2.367c-0.039,0.219-0.109,0.485-0.109,0.796V254.852z"
                                              style="fill:#E9E9EA;"/>
                                    </g>
                                </g>
                            </g>
                        </g>
                        <g id="Layer_1"/></svg>
                </div>
                <div class="modal-short-msg d-flex-center">
                    <h1> Are you sure? </h1>
                </div>
                <div class="modal-long-msg  d-flex-center">
                    <h7>
                        Do you really want to recover this account? This process cannot be undone.
                    </h7>
                </div>
                <div class="modal-msg-choice d-flex-center">
                    <div class="modal-msg-choice-yes btn">
                        <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-recover-cancel">Cancel
                        </button>
                    </div>
                    <div class="modal-msg-choice-no">
                        <button class="btn-primary btn" id="modal-recover-ok">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myModalAdminSettings" style="z-index: 9999999 !important; width: 100% !important;  display: none;">
    <div class="modal-content" style="width: 65%; zoom: 0.8;">

        <div class="modal-header a-center">
        </div>
        <div class="modal-body" style="overflow: hidden; background: #adadad;">
            <div id="show-profile-info" class="modal-child d-none h-100p">
                <div class="custom-grid-container h-100p" tabindex="2">
                    <div class="custom-grid-item h-100p d-flex-center" style="width: 37em; margin-left: 2em">
                        <?php
                        if (!empty($rows['img_path'])) {
                            ?>
                            <img id="view-image" class="pad-1em b-shadow-dark"
                                 src="<?= $rows['img_path'] ?>"
                                 alt=""
                                 style="width: 86%; height: 35em; border-radius: 50%;">
                            <?php
                        } else {
                            ?>
                            <img id="view-image" class="pad-1em b-shadow-dark" src="../../assets/users_img/noImage.png" alt=""
                                 style="width: 86%; height: 35em; border-radius: 50%;">
                            <?php
                        }
                        ?>
                        <div style="
                               width: 17em;
                                height: 21em;
                                display: flex;
                                align-items: flex-end;
                                justify-content: flex-end;
                                position: fixed;">
                            <div>
                                <img onclick="$('#image').click()" src="../../assets/img/camera.png"
                                     alt="teacher image"
                                     class="c-hand p-absolute bg-hover-gray-dark-v2 mobile-image1" style=" height: 3em;
                                    width: 4em;
                                    object-fit: contain;
                                    border-radius: 50%;
                                    padding: 5px;">
                            </div>
<!--                            <form method="post" enctype="multipart/form-data">-->
                                <input type="hidden" id="lrn" name="lrn"> <br>
                                <input type="file" name="image" id="image" class="d-none"> <br> <br>
<!--                            </form>-->
                        </div>
                    </div>
                    <div id="editProfile" class="custom-grid-item b-shadow-dark pad-1em"
                         style="background: #d6d6d6; height: 41em;">


                        <div id="display">
                            LRN: <?= isset($rows['lrn']) ? $rows['lrn'] : $rows['id'] ?><br>
                            First Name: <label for=""> <?= $rows['first_name'] ?> </label>
                            <br>
                            Last Name: <label for=""> <?= $rows['last_name'] ?> </label>
                            <br>
                            UserName: <label for=""> <?= $rows['username'] ?> </label>
                            <br>
                            Password: <label for=""> <?= $rows['password'] ?> </label>
                            <br>
                            Email: <label for=""> <?= isset($rows['email']) ? $rows['email'] : 'none' ?> </label>
                            <br>
                            User Type: <label for=""> <?= $rows['user_type'] ?> </label>
                            <br>
                            <div>
                                <button id="edit"
                                        class="btn btn-success bg-hover-gray-dark-v1"
                                        style="position: absolute; right: 24px; bottom: 29px;"
                                        onclick="edit()">
                                    Edit
                                </button>
                            </div>

                        </div>
                        <div id="editForm" class="d-none">
                            <form method="post">
                                LRN: <?= isset($rows['lrn']) ? $rows['lrn'] : $rows['id'] ?><br>
                                First Name: <input
                                        value="<?= $rows['first_name'] ?>" id="firstname" type="text"
                                        name="firstname"
                                        class=" m-b-5px"/>
                                <br>
                                Last Name: <input
                                        value="<?= $rows['last_name'] ?>" id="lastname" type="text" name="lastname"
                                        class=" m-b-5px"/>
                                <br>
                                UserName: <input
                                        value="<?= $rows['username'] ?>" id="username" type="text"
                                        class=" m-b-5px" name="username"/>
                                <br>
                                Password: <input
                                        value="<?= $rows['password'] ?>" id="password" type="text"
                                        class=" m-b-5px" name="password"/>
                                <br>
                                Email:
                                <input
                                        value="<?= $rows['email'] ?>"
                                        id="email" type="email" name="email"
                                        class=" m-b-5px"/> <br>
                                User Type: <input
                                        value="<?= $rows['user_type'] ?>" id="user_type" type="text"
                                        class=" m-b-5px"
                                        readonly="true"/>
                                <br>

                                <div>
                                    <button id="saveButton" type="submit" class="c-hand btn-success btn "
                                            name="editProfile"
                                            style="position: absolute; right: 24px; bottom: 29px;">
                                        Save
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
<div id="modal-exists" class="modal2">
    <div class="square">
        <div class="modal-content">
            <div class="modal-content1">
                <div class="modal-logo  d-flex-center">
                    <img src="../../assets/img/warning.png" width="60" height="60" alt="">
                </div>
                <div class="modal-short-msg d-flex-center">
                    <h1> Warning!</h1>
                </div>
                <div class="modal-long-msg  d-flex-center">
                    <h7>
                        This account already exists!
                    </h7>
                </div>
                <div class="modal-msg-choice d-flex-center">
                    <div class="modal-msg-choice-yes btn">
                        <button class="modal-msg-choice-yes-btn btn btn-warning" id="modal-exists-ok">Ok
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="b-shadow-dark p-fixed w-20p h-100p bg-gray-light b-top-right-radius-10 z-i-99999" id="side">
    <div class="d-flex-center t-color-white b-bottom-white-3px f-weight-bold h-4em" id="side-a">
        <a href="/1-php-grading-system/"> <img
                    src="../../assets/img/mabes.png" alt=""
                    class="w-38px m-3-6px"/></a>
        <p class="m-0 b-right-2px-white pad-left-6px pad-right-6px t-color-green"> MABES </p>
        <p class=" m-0 pad-left-6px">GRADE INQUIRY</p>
        <div id="x-hide-show-side-bar"
             class="c-hand p-absolute r-0 d-flex-center w-2-5em h-60px f-weight-100 bg-hover-blue b-top-right-radius-10"
             onclick="tops()">x
        </div>
    </div>

    <div id="sideTab" class="pad-1em t-color-white f-weight-bold">
        <div class="m-t-3em"></div>
        <div class="tab-dashboard d-none h-4em d-flex-center ">
            <div class="p-absolute" style="margin-right: 11em;">
                <svg class="svg-1" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z"/>
                </svg>
            </div>
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('dashboard')" <?php if ($var === "dashboard") { ?> style="background: #bababa;"  <?php } ?> >
                Dashboard <?= $var1 ?>
            </div>
            <div class="d-flex-end w-4em "></div>
        </div>
        <div class="tab-addUser d-none h-4em d-flex-center m-t-5px ">
            <div class="p-absolute" style="margin-right: 11em;">
                <svg class="svg-1" height="24px" id="Capa_1" style="enable-background:new 0 0 100 90;" version="1.1"
                     viewBox="0 0 100 90" width="100px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink"><g>
                        <path d="M61.885,67.24c-12.457-4.491-16.438-8.282-16.438-16.395c0-4.871,3.803-3.28,5.473-12.2c0.693-3.7,4.053-0.06,4.695-8.507   c0-3.366-1.834-4.203-1.834-4.203s0.932-4.982,1.297-8.816C55.531,12.342,52.289,0,35,0S14.469,12.342,14.922,17.119   c0.365,3.834,1.297,8.816,1.297,8.816s-1.834,0.837-1.834,4.203c0.643,8.447,4.002,4.807,4.693,8.507   c1.672,8.919,5.475,7.329,5.475,12.2c0,8.113-3.98,11.904-16.438,16.395C6.615,67.78,3.039,68.621,0,69.933V90h80   c0,0,0-7.396,0-10.526C80,76.341,74.381,71.746,61.885,67.24z M85,40V25H75v15H60v10h15v15h10V50h15V40H85z"/>
                    </g>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/>
                    <g/></svg>
            </div>
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('add_user')" <?php if ($var === "add_user" || $var === "add_student" || $var === "add_new_user" || $var === "add_teacher" || $var === "student_list") { ?> style="background: #bababa;"  <?php } ?>>
                Add User
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft','userTab')"></div>
            </div>
        </div>
        <div class="tab-addUser d-none ov-hidden transition-0-5s " id="userTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('add_teacher')" <?php if ($var === "add_teacher" || $var === "student_list") { ?> style="background: #bababa;"  <?php } ?>>
                    Teacher
                </div>
            </div>
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('add_new_user')" <?php if ($var === "add_new_user") { ?> style="background: #bababa;"  <?php } ?>>
                    Admin
                </div>
            </div>
        </div>
        <div class="tab-teacherInfo d-none h-5em  d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('teacher_info')" <?php if ($var === "teacher_info") { ?> style="background: #bababa;"  <?php } ?>>
                Teacher Information
            </div>
            <div class="d-flex-end w-4em"></div>
        </div>
        <div class="tab-masterlist d-none h-4em d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('masterlist')" <?php if ($var === "masterlist" || $var === "add_student" || $var === "student_list_masterlist") { ?> style="background: #bababa;"  <?php } ?>>
                Masterlist
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_masterlist" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_masterlist','masterlistTab')"></div>
            </div>
        </div>
        <div class="tab-masterlist d-none ov-hidden transition-0-5s " id="masterlistTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('add_student')" <?php if ($var === "add_student") { ?> style="background: #bababa;"  <?php } ?>>
                    Add Student
                </div>
            </div>
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('student_list_masterlist')" <?php if ($var === "student_list_masterlist") { ?> style="background: #bababa;"  <?php } ?>>
                    Student List
                </div>
            </div>
        </div>
        <div class="tab-records d-none h-4em d-flex-center m-t-5px ">
            <div class="p-absolute" style="margin-right: 11em;">
                <svg class="svg-1" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 16 2 C 14.74 2 13.850156 2.89 13.410156 4 L 5 4 L 5 29 L 27 29 L 27 4 L 18.589844 4 C 18.149844 2.89 17.26 2 16 2 z M 16 4 C 16.55 4 17 4.45 17 5 L 17 6 L 20 6 L 20 8 L 12 8 L 12 6 L 15 6 L 15 5 C 15 4.45 15.45 4 16 4 z M 7 6 L 10 6 L 10 10 L 22 10 L 22 6 L 25 6 L 25 27 L 7 27 L 7 6 z M 9 13 L 9 15 L 11 15 L 11 13 L 9 13 z M 13 13 L 13 15 L 23 15 L 23 13 L 13 13 z M 9 17 L 9 19 L 11 19 L 11 17 L 9 17 z M 13 17 L 13 19 L 23 19 L 23 17 L 13 17 z M 9 21 L 9 23 L 11 23 L 11 21 L 9 21 z M 13 21 L 13 23 L 23 23 L 23 21 L 13 21 z"/>
                </svg>
            </div>
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('add_records')" <?php if ($var === "add_records" || $var === "promote_student" || $var === "subject_list") { ?> style="background: #bababa;"  <?php } ?>>
                Records
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_records" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_records','recordsTab')"></div>
            </div>
        </div>
        <div class="tab-records d-none ov-hidden transition-0-5s " id="recordsTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('promote_student')" <?php if ($var === "promote_student") { ?> style="background: #bababa;"  <?php } ?>>
                    Promote Student
                </div>
            </div>

            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('subject_list')" <?php if ($var === "subject_list") { ?> style="background: #bababa;"  <?php } ?>>
                    Subject List
                </div>
            </div>
        </div>
        <div class="tab-maintenance d-none h-4em d-flex-center m-t-5px ">
            <div class="p-absolute" style="margin-right: 11em;">
                <svg class="svg-1" width="100" height="24" id="Layer_1" style="enable-background:new 0 0 30 30;"
                     version="1.1" viewBox="0 0 30 30" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink"><path
                            d="M16.758,21.401c0,0,4.496,4.496,4.819,4.819c1.04,1.04,2.725,1.04,3.764,0c1.04-1.04,1.04-2.725,0-3.765  c-0.323-0.323-4.819-4.819-4.819-4.819L16.758,21.401z"/>
                    <path d="M23.998,11.003l-3.201-0.8l-0.8-3.201l3.706-3.706c-2.129-0.677-4.551-0.176-6.24,1.512c-2.41,2.41-1.639,5.547,0.772,7.957  c2.41,2.41,5.547,3.182,7.957,0.771c1.689-1.689,2.19-4.111,1.512-6.239L23.998,11.003z"/>
                    <polygon points="12.5,11.5 9,8 8,5 4,3 2,5 4,9 7,10 10.5,13.5 "/>
                    <path d="M17.879,8.879c-3.364,3.364-12.636,12.636-13,13c-1.172,1.172-1.172,3.071,0,4.243c1.172,1.172,3.071,1.172,4.243,0  c0.364-0.364,9.636-9.636,13-13L17.879,8.879z M7,25c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1s1,0.448,1,1C8,24.552,7.552,25,7,25z  "/></svg>
            </div>
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('maintenance')" <?php if ($var === "maintenance" || $var === "school_year" || $var === "grade_list") { ?> style="background: #bababa;"  <?php } ?>>
                &nbsp;&nbsp;&nbsp;&nbsp; Maintenance
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_maintenance" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_maintenance','maintenanceTab')"></div>
            </div>
        </div>
        <div class="tab-maintenance d-none ov-hidden transition-0-5s " id="maintenanceTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('school_year')" <?php if ($var === "school_year") { ?> style="background: #bababa;"  <?php } ?>>
                    Student School Year
                </div>
            </div>
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('grade_list')" <?php if ($var === "grade_list") { ?> style="background: #bababa;"  <?php } ?>>
                    Grade and Section List
                </div>
            </div>
        </div>
        <div class="tab-studentInfo d-none h-4em  d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('student_info')" <?php if ($var === "student_info") { ?> style="background: #bababa;"  <?php } ?>>
                Student Info
            </div>
            <div class="d-flex-end w-4em m-t-5px"></div>
        </div>
        <div class="tab-studentRecord d-none h-4em d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('studentRecord')" <?php if ($var === "studentRecord" || $var === "grade" || $var === "report") { ?> style="background: #bababa;"  <?php } ?>>
                Student Record
            </div>
            <div class="d-flex-end w-4em">
                <div id="arrowLeftButton_studentRecord" class="w-1-5em h-1-5em c-hand "
                     onclick="saveKeyOnLocalStorage(this,'studArrowLeft_studentRecord','studentRecordTab')"></div>
            </div>
        </div>
        <div class="tab-studentRecord d-none ov-hidden transition-0-5s " id="studentRecordTab" style="height: 0">
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('grade')" <?php if ($var === "grade") { ?> style="background: #bababa;"  <?php } ?>>
                    Grade
                </div>
            </div>
            <div class=" h-4em d-flex-end m-t-5px">
                <div class="d-flex-center w-4em"><img
                            src="https://cdn4.iconfinder.com/data/icons/essential-part-5/32/444-Arrow_Left-512.png"
                            alt="" class="w-18px c-hand rotate"></div>
                <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                     onclick="selectTab('report')" <?php if ($var === "report") { ?> style="background: #bababa;"  <?php } ?>>
                    Report
                </div>
            </div>
        </div>
        <div class="tab-teacherList d-none h-5em  d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('teacher_list')" <?php if ($var === "teacherList") { ?> style="background: #bababa;"  <?php } ?>>
                Teacher List
            </div>
            <div class="d-flex-end w-4em"></div>
        </div>
        <div class="tab-notification d-none h-4em  d-flex-center m-t-5px ">
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('notification')" <?php if ($var === "notification") { ?> style="background: #bababa;"  <?php } ?>>
                Notification
            </div>
            <div class="d-flex-end w-4em m-t-5px"></div>
        </div>
        <div class="tab-trash d-none h-4em  d-flex-center m-t-5px ">
            <div class="p-absolute" style="margin-right: 11em;">
                <svg class="svg-1" width="100" height="24" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <title/>
                    <g data-name="1" id="_1">
                        <path d="M356.65,450H171.47a41,41,0,0,1-40.9-40.9V120.66a15,15,0,0,1,15-15h237a15,15,0,0,1,15,15V409.1A41,41,0,0,1,356.65,450ZM160.57,135.66V409.1a10.91,10.91,0,0,0,10.9,10.9H356.65a10.91,10.91,0,0,0,10.91-10.9V135.66Z"/>
                        <path d="M327.06,135.66h-126a15,15,0,0,1-15-15V93.4A44.79,44.79,0,0,1,230.8,48.67h66.52A44.79,44.79,0,0,1,342.06,93.4v27.26A15,15,0,0,1,327.06,135.66Zm-111-30h96V93.4a14.75,14.75,0,0,0-14.74-14.73H230.8A14.75,14.75,0,0,0,216.07,93.4Z"/>
                        <path d="M264.06,392.58a15,15,0,0,1-15-15V178.09a15,15,0,1,1,30,0V377.58A15,15,0,0,1,264.06,392.58Z"/>
                        <path d="M209.9,392.58a15,15,0,0,1-15-15V178.09a15,15,0,0,1,30,0V377.58A15,15,0,0,1,209.9,392.58Z"/>
                        <path d="M318.23,392.58a15,15,0,0,1-15-15V178.09a15,15,0,0,1,30,0V377.58A15,15,0,0,1,318.23,392.58Z"/>
                        <path d="M405.81,135.66H122.32a15,15,0,0,1,0-30H405.81a15,15,0,0,1,0,30Z"/>
                    </g>
                </svg>
            </div>
            <div class="d-flex-center h-100p w-80p bg-hover-gray-dark c-hand b-radius-05em"
                 onclick="selectTab('trash')" <?php if ($var === "trash") { ?> style="background: #bababa;"  <?php } ?>>
                Trash
            </div>
            <div class="d-flex-end w-4em"></div>
        </div>
    </div>
</div>

<div id="settings-details" class="p-absolute j-content-center z-i-999910" style="     position: fixed;
    height: 14em;
    width: 14em;
    top: 64px;
    right: 17px;
    display: none;
    background: #e6e6e6;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
">

    <style>
        .admin-settings {
            border-radius: 13px;
        }

        .admin-settings:hover {
            background: #808080a8;
        }
    </style>
    <!--    <form action="index.php" method="post">-->
    <div class="custom-grid-container w-100p pad-1em  settings-1" tabindex="1">
        <div class="custom-grid-item d-flex-start c-hand admin-settings"
             onclick="showModalInfo('<?= $rows['user_type'] ?>','<?= $rows['last_name'] ?>','profile')">
            <div class=" b-bottom-gray-1px w-100p h-100p d-flex-start settings-1">

                <!--                <img src="../../assets/img/profile1.png" alt="" style="width: 2em; height: 2em"> -->
                <svg class=" settings-1" style="width: 2em; height: 2em" viewBox="0 0 32 32"
                     xmlns="http://www.w3.org/2000/svg"><title/>
                    <g id="about">
                        <path class="settings-1"
                              d="M16,16A7,7,0,1,0,9,9,7,7,0,0,0,16,16ZM16,4a5,5,0,1,1-5,5A5,5,0,0,1,16,4Z"/>
                        <path class="settings-1"
                              d="M17,18H15A11,11,0,0,0,4,29a1,1,0,0,0,1,1H27a1,1,0,0,0,1-1A11,11,0,0,0,17,18ZM6.06,28A9,9,0,0,1,15,20h2a9,9,0,0,1,8.94,8Z"/>
                    </g>
                </svg>
                <label for=""
                       class="c-hand m-t-9px f-weight-bold  settings-1">Profile</label>
            </div>
        </div>
        <div class="custom-grid-item d-flex-start c-hand admin-settings settings-1"
             onclick="darkMode()">
            <div class="settings-1 b-bottom-gray-1px w-100p h-100p d-flex-start">
                <img class="settings-1" src="../../assets/img/darkMode.png" alt="" style="width: 2em; height: 2em">
                <label for=""
                       class="settings-1 c-hand m-t-9px f-weight-bold">Dark
                    Mode</label>
                <div class="settings-1  transition-0-5s bg-gray" id="circle-parent" style="
    height: 2em;
    width: 4em;
    border-radius: 17px;
    display: flex;
    align-items: center;
    margin-left: 12px;
 ">
                    <div class="settings-1" id="circle-child" style="
    height: 18px;
    width: 18px;
    border-radius: 50%;
    background: white;
   "></div>
                </div>
            </div>

        </div>
        <div class="custom-grid-item d-flex-start c-hand admin-settings settings-1" onclick="logout()">
            <div class=" b-bottom-gray-1px w-100p h-100p d-flex-start settings-1">
                <img class="settings-1" src="../../assets/img/logout.png" alt="" style="width: 2em; height: 2em">
                <label for=""
                       class="settings-1 c-hand m-t-9px f-weight-bold">Logout</label>
            </div>
        </div>
    </div>
    <!--    </form>-->
</div>

<div id="top" class="p-fixed  w-82p d-flex r-0 h-4em z-i-9999 bg-blue b-shadow-dark">
    <div class="w-30p "><label id="top-icon"
                               class="h-100p w-3em t-align-center d-flex-center c-hand d-none f-size-26px w-2em bg-hover-white"
                               for="" onclick="tops()">
            ☰</label></div>
    <div class="d-flex-end w-70p m-r-13px">
        <!--        <input type="text" placeholder="Search...">-->
        <div class="d-flex-center m-l-13px m-r-13px" id="user-name">
            Hello, <label for=""
                          class="m-b-0 m-l-3px">   <?= $rows['user_type'] ?> <?= $rows['last_name'] ?>  </label>
        </div>
        <?php if ($rows['img_path'] == '') { ?>
            <img id="settings" src="../../assets/users_img/noImage.png"
                 style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;"
                 alt="" class="w-32px c-hand">
        <?php } else { ?>
            <img id="settings" src="<?= $rows['img_path'] ?>"
                 style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover !important;"
                 alt="" class="w-32px c-hand">
        <?php } ?>


        <!--        <img id="settings" src="https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-arrow-down-b-512.png"-->
        <!--             class="w-18px m-r-13px c-hand transition-0-5s" alt="" />-->

    </div>

</div>

<div id="writeMessage" class="p-fixed d-flex-center c-hand d-none" style="
    width: 3.5em;
    height: 3.5em;
    border-radius: 50%;

    bottom: 10px;
    right: 10px;
    z-index: 99999;">
    <img src="../../assets/img/writeMessage.png" alt="sds" style="height: 2em; width: 2em">
</div>

</body>
</html>

<script>

    document.body.onclick = function (e) {
        if (e.target.id === 'settings') {
            if ($('#settings-details').hasClass("d-flex")) {
                $('#settings-details').removeClass("d-flex")
                x = 0;
            } else {
                x = 1;
                $('#settings-details').addClass("d-flex")
            }
        } else if (e.target.className.includes('settings-1')) {
            $('#settings-details').addClass("d-flex")
        } else {
            $('#settings-details').removeClass("d-flex")
        }
    }

    function settings() {
        $('#settings').toggleClass("rotate")
        $('#settings-details').toggleClass("d-flex")
    }

    function tops() {
        $('#top').toggleClass("pressed")
        $('#side').toggleClass("collapsed")
        $('#content').toggleClass("pressed")
        $('#top-icon').toggleClass("d-flex")
        $('#myModal').toggleClass("full-width")
        saveKeyOnLocalStorage('#top-icon', 'topArrow', 'top')
        localStorage.getItem('topArrow') === '1' ? $('.top-icon').css('display', 'none') : $('.top-icon').css('display', '');
    }

    function selectTab(tab) {
        if (tab === 'add_user') {
            saveKeyOnLocalStorage('#arrowLeftButton', 'studArrowLeft', 'userTab');
        } else if (tab === 'add_records') {
            saveKeyOnLocalStorage('#arrowLeftButton_records', 'studArrowLeft_records', 'recordsTab');
        } else if (tab === 'maintenance') {
            saveKeyOnLocalStorage('#arrowLeftButton_maintenance', 'studArrowLeft_maintenance', 'maintenanceTab');
        } else if (tab === 'masterlist') {
            saveKeyOnLocalStorage('#arrowLeftButton_masterlist', 'studArrowLeft_masterlist', 'masterlistTab');
        } else if (tab === 'studentRecord') {
            saveKeyOnLocalStorage('#arrowLeftButton_studentRecord', 'studArrowLeft_studentRecord', 'studentRecordTab');
        } else {
            window.location.href = "/1-php-grading-system/admins_page/" + tab + "/?id=" + <?= $rows['id'] ?>;
        }
    }

    function saveKeyOnLocalStorage(e, keyName, tabName) {

        if (tabName === 'userTab' || tabName === 'recordsTab' || tabName === 'maintenanceTab' || tabName === 'masterlistTab' || tabName === 'studentRecordTab') {
            $('#' + tabName).toggleClass(
                tabName === 'userTab' || tabName === 'recordsTab' || tabName === 'maintenanceTab' || tabName === 'masterlistTab' || tabName === 'studentRecordTab' ? 'h-8-8em'
                    : 'none')
            if (localStorage.getItem(keyName) === '1') {
                $(e).removeClass('bg-img-2')
                $(e).addClass('bg-img-1')
            } else {
                $(e).removeClass('bg-img-1')
                $(e).addClass('bg-img-2')
            }
        } else {

        }

        localStorage.getItem(keyName) === '1' ? localStorage.setItem(keyName, '0') : localStorage.setItem(keyName, '1');
    }

    function loadStudArrowLeft() {
        let get = localStorage.getItem('studArrowLeft');

        if (get === '1') {
            $('#arrowLeftButton').addClass('bg-img-2')
            $('#userTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton').addClass('bg-img-1')
            $('#userTab').removeClass('h-8-8em')
        }

        let get_rec = localStorage.getItem('studArrowLeft_records');

        if (get_rec === '1') {
            $('#arrowLeftButton_records').addClass('bg-img-2')
            $('#recordsTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton_records').addClass('bg-img-1')
            $('#recordsTab').removeClass('h-8-8em')
        }

        let get_main = localStorage.getItem('studArrowLeft_maintenance');

        if (get_main === '1') {
            $('#arrowLeftButton_maintenance').addClass('bg-img-2')
            $('#maintenanceTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton_maintenance').addClass('bg-img-1')
            $('#maintenanceTab').removeClass('h-8-8em')
        }

        let get_masterlist = localStorage.getItem('studArrowLeft_masterlist');
        if (get_masterlist === '1') {
            $('#arrowLeftButton_masterlist').addClass('bg-img-2')
            $('#masterlistTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton_masterlist').addClass('bg-img-1')
            $('#masterlistTab').removeClass('h-8-8em')
        }

        let get_studentRecord = localStorage.getItem('studArrowLeft_studentRecord');
        if (get_studentRecord === '1') {
            $('#arrowLeftButton_studentRecord').addClass('bg-img-2')
            $('#studentRecordTab').addClass('h-8-8em')
        } else {
            $('#arrowLeftButton_studentRecord').addClass('bg-img-1')
            $('#studentRecordTab').removeClass('h-8-8em')
        }
    }

    function loadTopArrow() {
        let get = localStorage.getItem('topArrow');

        if (get === '0') {
            $('#side').addClass('collapsed')
            $('#top').toggleClass("pressed")
            $('#top-icon').toggleClass("d-flex")
            $('#content').addClass('pressed')
            $('#myModal').addClass('full-width')
        }
    }

    function viewUserTabs() {
        var user_type = "<?= $rows['user_type'] ?>";
        if (user_type) {
            if (user_type.toLowerCase() === 'admin') {
                $('.tab-dashboard').removeClass('d-none')
                $('.tab-addUser').removeClass('d-none')
                $('.tab-records').removeClass('d-none')
                $('.tab-maintenance').removeClass('d-none')
                $('.tab-trash').removeClass('d-none')
            } else if (user_type.toLowerCase() === 'teacher') {
                $('.tab-teacherInfo').removeClass('d-none')
                $('.tab-masterlist').removeClass('d-none')
                $('.tab-records').removeClass('d-none')
                $('.tab-trash').removeClass('d-none')
                $('.records_student_list').addClass('d-none')
                $('.tab-notification').removeClass('d-none')
            } else if (user_type === 'student') {
                $('.tab-studentInfo').removeClass('d-none')
                $('.tab-studentRecord').removeClass('d-none')
                $('.tab-teacherList').removeClass('d-none')
                $('.tab-notification').removeClass('d-none')
            }
        }
    }

    function showModalInfo(userType, lastname, status) {
        showModalAdminSettings('show-profile-info', 'WELCOME ' + userType.toUpperCase() + ' ' + lastname.toUpperCase() + '!', '', '')
    }

    function edit() {
        $('#editProfile #display').addClass('d-none')
        $('#editProfile #editForm').removeClass('d-none')
    }

    function logout() {
        $('#modal-logout').attr('style', 'display: block !important;')
    }

    $(document).on('click', '#modal-cancel', function (e) {
        $('#modal-logout').attr('style', 'display: none !important;')
    });

    $(document).on('click', '#modal-ok', function (e) {
        Post('', {logout: 'logout'});
    });

    $(document).on('click', '#modal-addedSuccessfully', function (e) {
        $('#modal-addedSuccessfully').attr('style', 'display: none !important;')
    });

    $(document).ready(function () {
        loadStudArrowLeft();
        viewUserTabs();
        var updateProfile = '<?php echo isset($_GET['updateProfile']) ? $_GET['updateProfile'] : '' ?>';
        if (updateProfile) {
            $('#modal-addedSuccessfully').attr('style', 'display: block !important;')
        }
    });


    function showModalAdminSettings(id, title, theme, size) {
        $('#myModalAdminSettings .modal-header').empty();
        $('#myModalAdminSettings .modal-header').append('<h2>' + title + '</h2>');
        $('.modal-header').append('<span class="close" onclick="closeModal()">&times;</span>');
        if (theme === 'dark') {
            $('#myModalAdminSettings .modal-content').css('background-color', '#757575');
            $('#myModalAdminSettings .modal-content').css('color', 'white');
            $('#myModalAdminSettings .modal-header').css('border-bottom', '3px solid black');
            $('#myModalAdminSettings .modal-body').addClass('d-flex-center');
        } else if (theme === 'gray') {
            $('#myModalAdminSettings .modal-content').css('background-color', '#c3c3c3');
            $('#myModalAdminSettings .modal-content').css('color', 'white');
            $('#myModalAdminSettings .modal-header').css('border-bottom', '3px solid black');
        } else {
            $('#myModalAdminSettings .modal-content').css('background-color', '#fff');
            $('#myModalAdminSettings .modal-header').css('border-bottom', '3px solid #80808038');
            $('#myModalAdminSettings .modal-content').css('color', 'black');
            $('#myModalAdminSettings .modal-body').removeClass('d-flex-center');
        }

        $('#myModalAdminSettings').css('display', 'block');
        $('body').css('overflow', 'hidden');
        $('#myModalAdminSettings .modal-body .modal-child').addClass('d-none');
        $('#' + id).removeClass('d-none');
        localStorage.getItem('topArrow') === '1' ? $('.top-icon').css('display', 'none') : $('.top-icon').css('display', '');

        if (size === 'small') {
            $('#myModalAdminSettings .modal-content').css('width', '50%');
            $('#myModalAdminSettings .modal-body').css('height', '37em');
        }
    }

    function loadPage() {
        var added_successfully = '<?php echo isset($_GET['ProfileImgSaved']) ? $_GET['ProfileImgSaved'] : '' ?>';
        if (added_successfully !== '') {
            showModalInfo('<?php  echo isset($_GET['type']) ? $_GET['type'] : ''  ?>', '<?php echo isset($_GET['lastname']) ? $_GET['lastname'] : ''  ?>', 'profile')
        }
    }

    loadPage();

    const input = document.getElementById('image');
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file.');
            input.value = '';
        } else {


            $('#view-image').attr('src', URL.createObjectURL(file));
            $('#settings').attr('src', URL.createObjectURL(file));

            $.ajax({
                type: 'POST',
                url: 'index.php', // Replace with the actual PHP script URL
                data: {saveImage: 1, id: <?php echo $_GET['id'] ?> },
                success: function (response) {
                    console.log('Dark mode session updated successfully.');
                },
                error: function (error) {
                    console.error('Error updating dark mode session:', error);
                }
            });

            const formData = new FormData();
            formData.append('image', file);
            formData.append('id',  <?php echo $_GET['id'] ?>)

            $.ajax({
                type: 'POST',
                url: 'index.php', // Replace with the actual PHP script URL
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Handle success response if needed
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Handle error response if needed
                    console.error(error);
                }
            });

        }
    });

    function darkMode() {
        document.body.classList.toggle('dark-theme');

        var darkMode = document.body.classList.contains('dark-theme') ? 1 : 0;

        // Use AJAX to update the dark mode session in PHP
        $.ajax({
            type: 'POST',
            url: 'index.php', // Replace with the actual PHP script URL
            data: {darkMode: darkMode, id: <?php echo $_GET['id'] ?> },
            success: function (response) {
                console.log('Dark mode session updated successfully.');
            },
            error: function (error) {
                console.error('Error updating dark mode session:', error);
            }
        });

    }

    $(document).ready(function () {
        // Fetch dark mode setting from the database
        var darkModeFromDB = <?php echo $darkModeFromDB; ?>;

        // Set 'dark-theme' class if dark mode is enabled
        if (darkModeFromDB === 1) {
            document.body.classList.add('dark-theme');
        }
    });

</script>