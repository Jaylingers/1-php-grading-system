<?php global $conn;
$var = "student_info";
include '../../students_page/header.php'; ?>
<div style="background: #14ae5c;">
    <div class="d-flex-center w-100p">
        <div id="content" class=" w-79-8p h-100p b-r-7px">
            <div class="m-2em b-6px-gray h-43em bg-gray b-1px-black">

                <div class="w-100p b-bottom-black-1px pad-1em t-color-white f-weight-bold">
                    STUDENT INFORMATION
                </div>
                <div class="f-weight-bold t-color-white d-flex-center h-88p l-height-27px">
                    <div>
                        <?php
                        $id = $_GET['id'];
                        $sql = "select * from users_info ui
                                left join students_info si on si.lrn = ui.user_lrn 
                                left join students_enrollment_info sei on sei.students_info_lrn = si.lrn
                                where ui.id='$id'";
                        $result = mysqli_query($conn, $sql);
                        while ($rowStudent = mysqli_fetch_assoc($result)) {
                        ?>


                        lrn: <label for="" id="lrn"> <?= $rowStudent['lrn'] ?></label> <br>
                        Firstname: <label for="" id="f-name"><?= $rowStudent['f_name'] ?></label> <br>
                        Middlename:<label for="" id="m-name"><?= $rowStudent['m_name'] ?></label> <br>
                        Lastname: <label for="" id="l-name"><?= $rowStudent['l_name'] ?></label> <br>
                        Birtdate: <label for="" id="b-date"><?= $rowStudent['b_date'] ?></label> <br>
                        Age: <label for="" id="age`"><?= $rowStudent['age'] ?></label> <br>
                        Address: <label for="" id="address"><?= $rowStudent['home_address'] ?></label> <br>
                        Guardian Name: <label for="" id="g-name"><?= $rowStudent['guardian_name'] ?></label> <br>
                        Enrolled Grade: <label for="" id="e-grade"><?= $rowStudent['grade'] ?></label> <br>
                <?php } ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>