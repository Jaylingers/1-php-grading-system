<?php global $conn;
$var = "dashboard";
include '../header.php'; ?>

<?php
$sql = "SELECT * FROM users_info";
$result = mysqli_query($conn, $sql);
?>
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 25% 25% 25% 25%;
        padding: 5em;
        justify-items: center;
    }

    .grid-item {
        background: rgb(186 186 186);
        border-radius: 50%;
        height: 16em;
        width: 78%;
        margin-bottom: 2em;
    }
</style>

<div class="d-flex-end p-absolute w-100p t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px">


        <div class="m-2em d-flex-align-start ">
            <div class="d-flex-center bg-gray-light pad-3em w-100p b-radius-10px">
                <div class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center"><img
                            src="https://cdn3.iconfinder.com/data/icons/school-and-education-4-8/48/181-512.png" alt=""
                            class="w-7em m-3-6px"/>
                    <div class="h-8em"> Students</div>
                </div>
                <div class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center"><img
                            src="https://cdn0.iconfinder.com/data/icons/high-school-12/340/teacher_book_education_school_college_people_study-512.png"
                            alt=""
                            class="w-7em m-3-6px"/>
                    <div class="h-8em"> Teacher</div>
                </div>
                <div class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center"><img
                            src="https://cdn3.iconfinder.com/data/icons/font-awesome-solid/576/eye-512.png" alt=""
                            class="w-7em m-3-6px"/>
                    <div class="h-8em"> Page Visited</div>
                </div>
            </div>
        </div>

        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px">
                <div class="pad-1em b-bottom-gray-3px f-weight-bold">
                    Teacher
                </div>
                <div class="pad-1em f-weight-bold">
                    Grade 1 Teacher
                </div>
                <div class="grid-container">
                    <?php
                    $i = 0;
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $i++;
                        ?>
                        <div class="grid-item d-flex-center">
                            <!--                            --><?php //echo $i;
                            ?>
                            <div class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center">
                                <img
                                        src="https://cdn0.iconfinder.com/data/icons/high-school-12/340/teacher_book_education_school_college_people_study-512.png"
                                        alt=""
                                        class="w-50p h-8em m-3-6px"/>
                                <?php if ($i === 2) { ?>
                                    <div class="h-12em w-1em t-color-red f-weight-bold f-size-19px"> NEW</div>
                                <?php } else { ?>
                                    <!--                                    <div class="h-8em w-1em t-color-green"> Teacher</div>-->
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>
