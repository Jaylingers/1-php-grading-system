<?php global $conn;
$var = "student_info";
include '../../students_page/header.php'; ?>
<div style="background: #14ae5c;" class="page-1">
    <div class="d-flex-center w-100p" >
        <div id="content" class=" w-79-8p h-100p b-r-7px">
            <div class="m-2em b-6px-gray h-43em bg-white b-1px-black">
            <style>
    .enhanced-header {
      width: 100%;
      border-bottom: 1px solid black;
      padding: 1em;
      margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
      color: black;
      font-weight: bold;
      font-size: 2.3em; /* Adjust the font size as needed */
      font-family: 'Your Chosen Font', sans-serif; /* Replace 'Your Chosen Font' with the desired font family */
      animation: fadeInUp 0.5s ease; /* Add a fade-in animation with 0.5s duration */

    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
            <div class="enhanced-header">
                STUDENT INFORMATION
            </div>
            <div class="f-weight-bold t-color-black d-flex-center h-88p l-height-27px" style="font-family: 'Your Chosen Font', sans-serif; font-size: 18px; transition: all 0.3s ease; border-radius: 10px; padding: 20px;">
  <div style="font-family: 'Your Chosen Font', sans-serif; font-size: 18px; transition: all 0.3s ease;">
    <?php
    $id = $_GET['id'];
    $sql = "select * from users_info ui
            left join students_info si on si.lrn = ui.user_lrn 
            left join students_enrollment_info sei on sei.students_info_lrn = si.lrn
            where ui.id='$id' group by si.lrn";
    $result = mysqli_query($conn, $sql);
    while ($rowStudent = mysqli_fetch_assoc($result)) {
    ?>

    Lrn: <label for="" id="lrn"><?= $rowStudent['lrn'] ?></label> <br>
    Firstname: <label for="" id="f-name"><?= $rowStudent['f_name'] ?></label> <br> 
    Middlename: <label for="" id="m-name"><?= $rowStudent['m_name'] ?></label> <br>
    Lastname: <label for="" id="l-name"><?= $rowStudent['l_name'] ?></label> <br>
    Birtdate: <label for="" id="b-date"><?= $rowStudent['b_date'] ?></label> <br>
    Age: <label for="" id="age"><?= $rowStudent['age'] ?></label> <br>
    Address: <label for="" id="address"><?= $rowStudent['home_address'] ?></label> <br>
    Guardian Name: <label for="" id="g-name"><?= $rowStudent['guardian_name'] ?></label> <br>
    <?php } ?>
  </div>
</div>


                </div>

            </div>
        </div>
    </div>
</div>