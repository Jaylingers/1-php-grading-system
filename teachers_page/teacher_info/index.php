<?php global $mysqli, $rows;
$var = "teacher_info";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

?>

<div class="d-flex-end p-absolute w-100p bottom-0 t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px contents one_page  ">

        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px pad-1em">
                <?php
                $id = $_GET['id'];

                $sql = "select * from teachers_info ti
                        left join users_info ui on ui.user_lrn = ti.lrn
                        where ui.id='$id'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result)) { ?>

                <div>
                    <?php
                    $i = 0;
                    while ($rows = mysqli_fetch_assoc($result)) {
                    $i++;
                    ?>
                    <div class="d-flex">
                        <div class="w-30p m-l-4em m-t-2em">

                            <?php if ($rows['img_path'] == '') { ?>
                                <img id="view-image" src="../../assets/users_img/noImage.png" alt="teacher image"
                                     style="height: 22em; width: 2em; object-fit: fill; border-radius: 50%;"
                                     class="w-100p">
                            <?php } else { ?>
                                <img id="view-image" src="<?= $rows['img_path'] ?>" alt="teacher image"
                                     style="height: 22em; width: 2em; object-fit: fill; border-radius: 50%;"
                                     class="w-100p">
                            <?php } ?>

                            <div>

                            </div>
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" id="lrn" name="lrn" value="<?= $rows['lrn'] ?>"> <br>
                                <input type="file" name="image" id="image" class="d-none"> <br> <br>
                                <button id="saveButton-teachers" type="submit"
                                        class="c-hand btn-success btn d-none"
                                        name="saveImage">Save
                                </button>
                            </form>
                        </div>
                        <style> 
                    .user-info-form {
                     margin-left: 4rem;
                     width: 45rem;
                     padding: 20px;
                     border-radius: 8px;
                        box-shadow: 0 0 10px gray;
                    }
                    .form-group {
                    margin-bottom: 15px;
                    width: 30rem;
                     margin-left: 6rem;
                    }

                    label {
                   display: block;
                   margin-bottom: 5px;
                }

                input {
                width: 100%;
                padding: 8px;
                 box-sizing: border-box;
                border: 1px solid #ccc;
                border-radius: 4px;
                outline: none;
                font-size: 14px;
                }

                input[readonly] {
                background-color: #eee;
                }
                        </style>
                        <div class="user-info-form">
        <div class="form-group">
            <h1>TEACHER INFORMATION</h1>
            <br>
            <label for="lrn">LRN NUMBER:</label>
            <input type="text" id="lrn" value="<?= $rows['lrn'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" value="<?= $rows['first_name'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" value="<?= $rows['last_name'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="username">User:</label>
            <input type="text" id="username" value="<?= $rows['username'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="Email">Email:</label>
            <input type="text" id="Email" value="<?= $rows['email_address'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="userType">User Type:</label>
            <input type="text" id="userType" value="<?= $rows['user_type'] ?>" readonly>
        </div>
    </div>

                        <?php } ?>
                    </div>

                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>
