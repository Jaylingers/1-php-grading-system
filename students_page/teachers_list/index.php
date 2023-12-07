<?php global $conn, $mysqli;
$var = "teachers_list";
include '../../students_page/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../../assets/css/student/change_password.css"/>
</head>
<style>
    /* Style for the custom select */
.custom-select {
    border: 1px solid #ccc; /* Border style */
    border-radius: 5px;
    padding: 8px; /* Padding */
    box-sizing: border-box; /* Include padding in the width/height calculation */
}

/* Style for the options within the drop-down */
.custom-select option {
    padding: 8px; /* Adjust padding as needed */
}

</style>
<body>
<div>
    <div class="d-flex-center w-100p">
        <div id="content" class=" w-79-8p h-100p b-r-7px">
            <div class="m-2em bg-white ">


                <div class="m-2em d-flex-align-start m-2em ">
                    <div class="w-100p b-radius-10px pad-1em" id="mobile-scroll">
                        <div class="mobile-width pad-1em b-bottom-dark-3px f-weight-bold">
                            <div id="gradeLabel" class="pad-left-5p m-t-4em">
                                Choose Grade:
                                <select name="grade" id="grade" onchange="chooseGrade(this.value)"
        class="c-hand h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px custom-select w-14em">
                                    <option value="" selected></option>
                                    <option value="1">Grade 1</option>
                                    <option value="2">Grade 2</option>
                                    <option value="3">Grade 3</option>
                                    <option value="4">Grade 4</option>
                                    <option value="5">Grade 5</option>
                                    <option value="6">Grade 6</option>
                                </select>
                                <br> <br>
                               Search Name: <input placeholder="search lrn" id="search_name" type="text" class="search_lrn m-b-5px w-12em"
                                       onchange="search()"/>
                            </div>

                        </div>
                        <div class="grid-container mobile-width">
                            <?php

                            $category = "teacher";

                            if ($category === 'teacher') {
                            $grade = isset($_GET['grade']) ? $_GET['grade'] : '';
                            $searchName = isset($_GET['search_name']) ? $_GET['search_name'] : '';

                            // Build the SQL query
                            $sql = "SELECT ti.*, ui.*, GROUP_CONCAT(tsi.subject) AS subjects_taught
                            FROM teachers_info ti
                            LEFT JOIN teachers_subject_info tsi ON tsi.teachers_lrn = ti.lrn
                            LEFT JOIN users_info ui ON ui.user_lrn = ti.lrn";

                            // Check if userType is empty
                            if (!empty($grade) || !empty($searchName)) {
                                $sql .= " WHERE";

                                if (!empty($grade)) {
                                    $sql .= " ti.grade = '$grade'";
                                }

                                if (!empty($searchName)) {
                                    // Use 'AND' if both conditions are present
                                    if (!empty($grade)) {
                                        $sql .= " AND";
                                    }

                                    // You can modify this condition based on your database structure (e.g., searching in both first_name and last_name)
                                    $sql .= " (ti.last_name LIKE '%$searchName%' OR ti.first_name LIKE '%$searchName%')";
                                }
                            }

                            $sql .= " GROUP BY ti.lrn";

                            $result = mysqli_query($conn, $sql);

                            // Get total number of rows for pagination
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
                                    <div class="grid-item d-flex-center b-shadow-dark" style="position: relative">
                                        <div class="t-align-center t-color-white" style="margin:1em">
                                            <?php if ($rows['img_path']) { ?>
                                                <img
                                                        src="<?php echo $rows['img_path'] ?>"
                                                        alt=""
                                                />
                                            <?php } else { ?>
                                                <img
                                                        src="../../assets/users_img/noImage.png"
                                                        alt=""
                                                />
                                            <?php } ?>
                                            <div style="color: green; font-weight: bolder; ">
                                                <br>
                                                <?php
                                                $date_added = date('Y-m-d', strtotime($rows['date_added']));
                                                $current_date = date('Y-m-d');

                                                echo ($date_added === $current_date) ? '<label style="    background: #2eff00;
    padding: 3px;
    border-radius: 6px;
    color: white;">New</label>' : '';
                                                ?>
                                            </div>
                                            <div class="t-color-white">

                                                <br>
                                                Name: <?php echo $rows['last_name'] . ', ' . $rows['first_name'] ?>
                                                <br>
                                                Grade: <?php echo $rows['grade'] ?> <br>
                                                Phone: <?php echo $rows['contact_number'] ?>
                                                
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>

                                <?php $stmt->close();
                            } ?>

                        </div>

                        <?php } ?>

                        <div class="w-100p t-align-right" style="text-align: right">
                            <div class="m-2em d-flex-end m-t-n1em pad-2em">
                                <div class="d-flex-center">
                                    <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                                        <ul class="pagination" style="    display: inline-flex;
    gap: 4px;">
                                            <?php if ($page > 1): ?>
                                                <li class="prev"><a
                                                            href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                                </li>
                                            <?php endif; ?>

                                            <?php if ($page > 3): ?>
                                                <li class="start"><a
                                                            href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                                </li>
                                                <li class="dots">...</li>
                                            <?php endif; ?>

                                            <?php if ($page - 2 > 0): ?>
                                                <li class="page"><a
                                                        href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                                </li><?php endif; ?>
                                            <?php if ($page - 1 > 0): ?>
                                                <li class="page"><a
                                                        href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                                </li><?php endif; ?>

                                            <li class="currentpage"><a
                                                        href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                            </li>

                                            <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                                <li class="page"><a
                                                        href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                                </li><?php endif; ?>
                                            <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                                <li class="page"><a
                                                        href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?>&&category=<?php echo $_GET['category'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?><?php if (isset($_GET['user'])): ?>&&user=<?php echo $_GET['user'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                                </li><?php endif; ?>

                                            <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                                <li class="dots">...</li>
                                                <li class="end"><a
                                                            href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                                </li>
                                            <?php endif; ?>

                                            <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                                <li class="next"><a
                                                            href="/1-php-grading-system/students_page/teachers_list/?id=<?php echo $_GET['id'] ?><?php if (isset($_GET['grade'])): ?>&&grade=<?php echo $_GET['grade'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
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
        </div>
    </div>

</div>
<script>
    function chooseGrade(grade) {
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>&&grade=' + grade+'&&search_name=<?php echo  isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>');
        window.location.reload();
    }

    function loadPage() {

        var grade = '<?php echo isset($_GET['grade']) ? $_GET['grade'] : '' ?>';
        if (grade !== '') {
            $('#grade').val(grade);
        }

        var search_name = '<?php echo isset($_GET['search_name']) ? $_GET['search_name'] : '' ?>';
        if (search_name !== '') {
            $('#search_name').val(search_name);
        }
    }

    function search(status) {
        var search_name = $('#search_name').val();
        window.location.href = '?id=<?php echo $_GET['id'] ?>&&grade=<?php echo  isset($_GET['grade']) ? $_GET['grade'] : '' ?>&&search_name=' + search_name;
    }

    loadPage();
</script>
</body>