<?php global $conn;
$var = "teachers_list";
include '../../students_page/header.php'; ?>
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
        margin-bottom: 6em;
    }
</style>
<div style="background: #14ae5c;" class="page-1">
    <div class="d-flex-center w-100p">
        <div id="content" class=" w-79-8p h-100p b-r-7px">
            <div class="m-2em bg-white ">


                <div class="m-2em d-flex-align-start m-2em ">
                    <div class="w-100p b-radius-10px pad-1em" id="mobile-scroll">
                        <div class="mobile-width pad-1em b-bottom-gray-3px f-weight-bold">
                            <div id="gradeLabel">
                                Choose Grade:
                                <select name="grade" id="grade" onchange="chooseGrade(this.value)"
                                        class="c-hand h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px">
                                    <option value="" selected></option>
                                    <option value="1">Grade 1</option>
                                    <option value="2">Grade 2</option>
                                    <option value="3">Grade 3</option>
                                    <option value="4">Grade 4</option>
                                    <option value="5">Grade 5</option>
                                    <option value="6">Grade 6</option>
                                </select>
                            </div>

                        </div>
                        <div class="grid-container mobile-width">
                            <?php
                            $category = "teacher";
                            $grade = isset($_GET['grade']) ? $_GET['grade'] : '';

                            if ($grade === '') {
                                $sql = "select * from teachers_info ti
                                          left join teachers_subject_info tsi on tsi.teachers_lrn = ti.lrn
                                        left join users_info ui on ui.user_lrn = ti.lrn";
                                $result = mysqli_query($conn, $sql);
                            } else {
                                $sql = "select * from teachers_info ti
                                          left join teachers_subject_info tsi on tsi.teachers_lrn = ti.lrn
                                        left join users_info ui on ui.user_lrn = ti.lrn
                                        where ti.grade = $grade
                                        group by tsi.teachers_lrn";
                                $result = mysqli_query($conn, $sql);
                            }
                            if (mysqli_num_rows($result)) { ?>
                                <?php
                                $i = 0;
                                while ($rows = mysqli_fetch_assoc($result)) {
                                    $i++;
                                    ?>
                                    <div class="grid-item d-flex-center">
                                        <div class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center m-b-2em" style="position: relative;">
                                            <?php if ($rows['img_path']) { ?>
                                                <img
                                                        src="<?php echo $rows['img_path'] ?>"
                                                        alt=""
                                                        class="w-50p h-8em m-3-6px"
                                                        style="height: 100%;
                                            border-radius: 50%;
                                            width: 17em !important;"/>
                                            <?php } else { ?>
                                                <img
                                                        src="../../assets/users_img/noImage.png"
                                                        alt=""
                                                        class="w-50p h-8em m-3-6px"
                                                        style="height: 100%;
                                                            border-radius: 50%;
                                                            width: 17em !important;"/>
                                            <?php } ?>
                                           
                                            <div style="position: absolute;     margin-top: 20em;">
                                                <?php if ($category === 'pageVisited') { ?>
                                                    name: <?php echo $rows['last_name'] . ', ' . $rows['first_name'] ?>
                                                    <br>
                                                    date visited: <?php echo $rows['date_visited'] ?>
                                                <?php } else if ($category === 'teacher') { ?>
                                                    <br>
                                                    name: <?php echo $rows['last_name'] . ', ' . $rows['first_name'] ?>
                                                    <br>
                                                    grade: <?php echo $rows['grade'] ?> <br>
                                                    subject: <?php echo $rows['subject'] ?>
                                                <?php } else { ?>
                                                    name: <?php echo $rows['last_name'] . ', ' . $rows['first_name'] ?>
                                                    <br>
                                                    grade: <?php echo $rows['grade'] ?>
                                                <?php } ?>
                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<script>
    function chooseGrade(grade) {
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>&&grade=' + grade);
        window.location.reload();
    }
    function loadPage() {

        var grade = '<?php echo isset($_GET['grade']) ? $_GET['grade'] : '' ?>';
        if (grade !== '') {
            $('#grade').val(grade);
        }

    }

    loadPage();
</script>