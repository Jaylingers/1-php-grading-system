<?php global $conn, $mysqli;
$var = "dashboard";
include '../header.php'; ?>

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
<div class="d-flex-end p-absolute w-100p t-60px">
    <div id="content"
         class="bg-off-white w-79-8p h-100p b-r-7px contents one_page ">


        <div class="m-2em d-flex-align-start " id="grid-b">
            <div id="categories" class="d-flex-center bg-gray-light pad-3em w-100p b-radius-10px">
                <div id="students" class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center c-hand"
                     onclick="chooseCategory('students')"><img
                            src="https://cdn3.iconfinder.com/data/icons/school-and-education-4-8/48/181-512.png" alt=""
                            class="w-7em m-3-6px"/>
                    <div class="h-8em"> Students</div>
                    <div class="h-8em"
                         style=" position: absolute; margin-left: 3.5em;margin-top: 6em;font-weight: bolder;font-size: 2em;">
                        <?php
                        $sql = "select count(*) as total from students_info";
                        $result = mysqli_query($conn, $sql);
                        $rows = mysqli_fetch_assoc($result);
                        echo $rows['total'];
                        ?>
                    </div>
                </div>
                <div id="teacher" class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center c-hand"
                     onclick="chooseCategory('teacher')"><img
                            src="https://cdn0.iconfinder.com/data/icons/high-school-12/340/teacher_book_education_school_college_people_study-512.png"
                            alt=""
                            class="w-7em m-3-6px"/>
                    <div class="h-8em"> Teacher</div>
                    <div class="h-8em"
                         style=" position: absolute; margin-left: 3.5em;margin-top: 6em;font-weight: bolder;font-size: 2em;">
                        <?php
                        $sql = "select count(*) as total from teachers_info";
                        $result = mysqli_query($conn, $sql);
                        $rows = mysqli_fetch_assoc($result);
                        echo $rows['total'];
                        ?>
                    </div>
                </div>
                <div id="pageVisited" class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center c-hand"
                     onclick="chooseCategory('pageVisited')"><img
                            src="https://cdn3.iconfinder.com/data/icons/font-awesome-solid/576/eye-512.png" alt=""
                            class="w-7em m-3-6px"/>
                    <div class="h-8em"> Page Visited</div>
                    <div class="h-8em"
                         style=" position: absolute; margin-left: 3.5em;margin-top: 6em;font-weight: bolder;font-size: 2em;">
                        <?php
                        $sql = "select count(*) as total from page_visited_info";
                        $result = mysqli_query($conn, $sql);
                        $rows = mysqli_fetch_assoc($result);
                        echo $rows['total'];
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="m-2em d-flex-align-start" id="grid-b">
            <div class="bg-white w-100p b-radius-10px">
                <?php
                $category = isset($_GET['category']) ? $_GET['category'] : '';

                if ($category) {
                ?>
                <div class="pad-1em b-bottom-gray-3px f-weight-bold">
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

                    <div id="userLabel">
                        Choose User:
                        <select name="user" id="user" onchange="chooseUser(this.value)"
                                class="c-hand h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px">
                            <option value="" selected></option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>

                </div>
                <div class="grid-container">
                                    <?php

                                    $category = isset($_GET['category']) ? $_GET['category'] : '';

                                    if ($category === 'teacher') {
                                    $grade = isset($_GET['grade']) ? $_GET['grade'] : '';
                                    // Build the SQL query
                                    $sql = "SELECT ti.*, ui.*, GROUP_CONCAT(tsi.subject) AS subjects_taught
                                FROM teachers_info ti
                                LEFT JOIN teachers_subject_info tsi ON tsi.teachers_lrn = ti.lrn
                                LEFT JOIN users_info ui ON ui.user_lrn = ti.lrn";
                                    // Check if userType is empty
                                    if (!empty($grade)) {
                                        $sql .= "  where ti.grade = '$grade'";
                                    }
                                    $sql .= " GROUP BY ti.lrn";
                                    $result = mysqli_query($conn, $sql);

                                    $total_pages = $mysqli->query($sql)->num_rows;
                                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                                    $num_results_on_page = 12;

                                    $sql .= " LIMIT ?,?";
                                    if ($stmt = $mysqli->prepare($sql)) {
                                        $calc_page = ($page - 1) * $num_results_on_page;
                                        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        ?>
                                        <?php
                                        $i = 0;
                                        while ($rows = $result->fetch_assoc()):
                                            $i++;
                                            ?>
                                            <div class="grid-item d-flex-center">
                                                <div class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center m-b-2em">
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
                                                    <div class="h-12em w-1em t-color-red f-weight-bold f-size-19px"
                                                         style="position: absolute; margin-left: 10em">
                                                        <?php
                                                        $date_added = date('Y-m-d', strtotime($rows['date_added']));
                                                        $current_date = date('Y-m-d');

                                                        echo ($date_added === $current_date) ? 'New' : '';
                                                        ?>
                                                    </div>
                                                    <div style="position: absolute;     margin-top: 20em;" id="mobile-b">

                                                            <br>
                                                            name: <?php echo $rows['last_name'] . ', ' . $rows['first_name'] ?> <br>
                                                            grade: <?php echo $rows['grade'] ?> <br>
                                                            subject: <?php echo $rows['subjects_taught'] ?>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>

                                        <?php $stmt->close();
                                    } ?>
                </div>




                <?php } else if ($category === 'students') {
                $grade = isset($_GET['grade']) ? $_GET['grade'] : '';
                // Build the SQL query
                $sql = "select si.f_name as first_name, si.l_name as last_name, ui.img_path, sei.grade,si.date_added from students_info si
                                         left join students_enrollment_info sei on sei.students_info_lrn = si.lrn
                                        left join users_info ui on ui.user_lrn = si.lrn ";
                // Check if userType is empty
                if (!empty($grade)) {
                    $sql .= " where sei.grade = '$grade'";
                }
                $result = mysqli_query($conn, $sql);

                $total_pages = $mysqli->query($sql)->num_rows;
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                $num_results_on_page = 12;

                $sql .= " LIMIT ?,?";
                if ($stmt = $mysqli->prepare($sql)) {
                    $calc_page = ($page - 1) * $num_results_on_page;
                    $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    ?>
                    <?php
                    $i = 0;
                    while ($rows = $result->fetch_assoc()):
                        $i++;
                        ?>
                        <div class="grid-item d-flex-center">
                            <div class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center m-b-2em">
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
                                <div class="h-12em w-1em t-color-red f-weight-bold f-size-19px"
                                     style="position: absolute; margin-left: 10em">
                                    <?php
                                    $date_added = date('Y-m-d', strtotime($rows['date_added']));
                                    $current_date = date('Y-m-d');

                                    echo ($date_added === $current_date) ? 'New' : '';
                                    ?>
                                </div>
                                <div style="position: absolute;     margin-top: 20em;" id="mobile-b">

                                        name: <?php echo $rows['last_name'] . ', ' . $rows['first_name'] ?> <br>
                                        grade: <?php echo $rows['grade'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>

                    <?php $stmt->close();
                } ?>
            </div>





            <?php } else if ($category === 'pageVisited') {

            $userType = isset($_GET['user']) ? $_GET['user'] : '';

            // Build the SQL query
            $sql = "SELECT * FROM page_visited_info pvi
                                     LEFT JOIN users_info ui ON ui.id = pvi.user_id";
            // Check if userType is empty
            if (!empty($userType)) {
                $sql .= " WHERE ui.user_type = '$userType'";
            }
            $sql .= " ORDER BY pvi.date_visited DESC";
            $result = mysqli_query($conn, $sql);

            $total_pages = $mysqli->query($sql)->num_rows;
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
            $num_results_on_page = 12;

            $sql .= " LIMIT ?,?";
            if ($stmt = $mysqli->prepare($sql)) {
                $calc_page = ($page - 1) * $num_results_on_page;
                $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>
                <?php
                $i = 0;
                while ($rows = $result->fetch_assoc()):
                    $i++;
                    ?>
                    <div class="grid-item d-flex-center">
                        <div class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center m-b-2em">
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
                            <div class="h-12em w-1em t-color-red f-weight-bold f-size-19px"
                                 style="position: absolute; margin-left: 10em">
                                <?php
                                $date_added = date('Y-m-d', strtotime($rows['date_visited']));
                                $current_date = date('Y-m-d');

                                echo ($date_added === $current_date) ? 'New' : '';
                                ?>

                            </div>
                            <div style="position: absolute;     margin-top: 20em;" id="mobile-b">
                                    name: <?php echo $rows['last_name'] . ', ' . $rows['first_name'] ?> <br>
                                    date visited: <?php echo $rows['date_visited'] ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>

                <?php
                $stmt->close();
            }
            ?>
        </div>

        <?php }?>
        <div class="w-100p t-align-right">
            <div class="m-2em d-flex-end m-t-n1em">
                <div class="d-flex-center">
                    <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                                <li class="prev"><a
                                            href="/1-php-grading-system/admins_page/dashboard/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                </li>
                            <?php endif; ?>

                            <?php if ($page > 3): ?>
                                <li class="start"><a
                                            href="/1-php-grading-system/admins_page/dashboard/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                </li>
                                <li class="dots">...</li>
                            <?php endif; ?>

                            <?php if ($page - 2 > 0): ?>
                                <li class="page"><a
                                        href="/1-php-grading-system/admins_page/dashboard/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                </li><?php endif; ?>
                            <?php if ($page - 1 > 0): ?>
                                <li class="page"><a
                                        href="/1-php-grading-system/admins_page/dashboard/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                </li><?php endif; ?>

                            <li class="currentpage"><a
                                        href="/1-php-grading-system/admins_page/dashboard/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                            </li>

                            <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                <li class="page"><a
                                        href="/1-php-grading-system/admins_page/dashboard/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                </li><?php endif; ?>
                            <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                <li class="page"><a
                                        href="/1-php-grading-system/admins_page/dashboard/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                </li><?php endif; ?>

                            <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                <li class="dots">...</li>
                                <li class="end"><a
                                            href="/1-php-grading-system/admins_page/dashboard/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                <li class="next"><a
                                            href="/1-php-grading-system/admins_page/dashboard/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        } else {
            ?>
            <div class="t-align-center "><br>
                Please Choose Category <br>
                <br>
            </div>
        <?php } ?>
    </div>

</div>


</div>
</div>

<script>
    function chooseCategory(category) {
        $('#categories div').map(function () {
            if ($(this).attr('id') !== undefined) {
                $('#' + $(this).attr('id')).css('background', 'rgb(186 186 186)');
            }
        });
        $('#' + category).css('background', 'white');
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>&&category=' + category);
        window.location.reload();
    }

    function chooseGrade(grade) {
        var category = '<?php echo isset($_GET['category']) ? $_GET['category'] : '' ?>';
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>&&category=' + category + '&&grade=' + grade);
        window.location.reload();
    }

    function chooseUser(user) {
        var category = '<?php echo isset($_GET['category']) ? $_GET['category'] : '' ?>';
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>&&category=' + category + '&&user=' + user);
        window.location.reload();
    }

    function loadPage() {
        var category = '<?php echo isset($_GET['category']) ? $_GET['category'] : '' ?>';
        if (category !== '') {
            $('#' + category).css('background', 'white');
            if (category === 'pageVisited') {
                $('#gradeLabel').hide();
            } else {
                $('#userLabel').hide();
            }
        }
        var grade = '<?php echo isset($_GET['grade']) ? $_GET['grade'] : '' ?>';
        if (grade !== '') {
            $('#grade').val(grade);
        }
        var user = '<?php echo isset($_GET['user']) ? $_GET['user'] : '' ?>';
        if (user !== '') {
            $('#user').val(user);
        }
    }

    loadPage();
</script>
