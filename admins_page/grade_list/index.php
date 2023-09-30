<?php global $mysqli, $rows;
$var = "grade_list";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['add_grade'])) {
    $grade = $_POST['grade'];
    if ($grade == date("Y")) {
        $current = 'Yes';
    } else {
        $current = 'No';
    }
    $sql = "insert into grade_info (grade) values ('$grade')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
              alert("added successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

if (isset($_POST['update_grade'])) {
    $id = $_POST['id'];
    $grade = $_POST['grade'];
    if ($grade == date("Y")) {
        $current = 'Yes';
    } else {
        $current = 'No';
    }
    $sql = "update grade_info set grade = '$grade' where id = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<script>';
        echo '   
              alert("updated successfully");
                history.pushState({page: "another page"}, "another page", "?id=' . $rows['id'] . '");
                    window.location.reload();
            ';
        echo '</script>';
    }
}

?>

<div class="d-flex-end p-absolute w-100p h-100p t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px contents one_page">

        <style>
            .table-1 tbody tr th, .table-1 tbody tr td {
                border-bottom: 0 !important;
                border-top: 0 !important;
            }

            .table-1 thead tr th, .table-1 thead tr td {
                border-top: 0 !important;
                border-bottom: 3px solid #ddd;
            }

            tr:nth-child(even) {
                background-color: #fbe4d5;
            }

            .table-1 thead {
                background-color: #ed7d31;
                color: white;
            }
        </style>


        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px pad-1em">


                <div class="custom-grid-container" tabindex="2">
                    <div class="custom-grid-item pad-1em">
                        <div class="f-weight-bold d-flex" style="    border: 1px solid gray;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;">
                            <h3 class="m-t-13px m-l-18px">
                                List of Grade
                            </h3>
                        </div>

                        <?php
                        $sql = "select * from grade_info Limit 1";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $lrn = $row['id'] + 1;
                        $lrns1 = 'S' . str_pad($lrn, 7, "0", STR_PAD_LEFT);

                        // Get the total number of records from our table "students".
                        $total_pages = $mysqli->query("select * from grade_info")->num_rows;
                        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                        // Number of results to show on each page.
                        $num_results_on_page = 10;

                        if ($stmt = $mysqli->prepare("select * from grade_info LIMIT ?,?")) {
                            // Calculate the page to get the results we need from our table.
                            $calc_page = ($page - 1) * $num_results_on_page;
                            $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                            $stmt->execute();
                            // Get the results...
                            $result = $stmt->get_result();
                            ?>

                            <table class="table table-1 b-shadow-dark">
                                <thead>
                                <tr>
                                    <th class="t-align-center"><label for="grade-list-cb"
                                                                      class="d-flex-center"></label><input
                                                id="grade-list-cb" type="checkbox"
                                                onclick="checkAllCB('grade-list', 'grade-list-cb')"
                                                class="sc-1-3 c-hand"/></th>
                                    <th>No</th>
                                    <th>Grade</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="grade-list">
                                <?php
                                $i = 0;
                                while ($row = $result->fetch_assoc()):
                                    $i++;
                                    ?>
                                    <tr>
                                        <td class="d-flex-center"><label>
                                                <input type="checkbox" class="sc-1-3 c-hand check"
                                                       id="<?= $row['id'] ?>"/>
                                            </label></td>
                                        <th scope="row"><?= $i ?> </th>
                                        <td><?= $row['grade'] ?></td>
                                        <td>
                                            <label for="" class="t-color-red c-hand f-weight-bold"
                                                   onclick="editSchoolYear('<?= $row['id'] ?>','<?= $row['grade'] ?>')">
                                                Edit</label>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>

                            <?php
                            $stmt->close();
                        }
                        ?>
                        Total Records: <?= $total_pages ?>
                        <div class="m-2em d-flex-end m-t-n1em">
                            <div class="d-flex-center">
                                <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                                    <ul class="pagination">
                                        <?php if ($page > 1): ?>
                                            <li class="prev"><a
                                                        href="/1-php-grading-system/admins_page/subject_list/?id=<?php echo $rows['id'] ?>&&page=<?php echo $page - 1 ?>">Prev</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page > 3): ?>
                                            <li class="start"><a
                                                        href="/1-php-grading-system/admins_page/subject_list/?id=<?php echo $rows['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">1</a>
                                            </li>
                                            <li class="dots">...</li>
                                        <?php endif; ?>

                                        <?php if ($page - 2 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/subject_list/?id=<?php echo $rows['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page - 1 > 0): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/subject_list/?id=<?php echo $rows['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                                            </li><?php endif; ?>

                                        <li class="currentpage"><a
                                                    href="/1-php-grading-system/admins_page/subject_list/?id=<?php echo $rows['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page ?>"><?php echo $page ?></a>
                                        </li>

                                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/subject_list/?id=<?php echo $rows['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                                            </li><?php endif; ?>
                                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                                            <li class="page"><a
                                                    href="/1-php-grading-system/admins_page/subject_list/?id=<?php echo $rows['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                                            </li><?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                                            <li class="dots">...</li>
                                            <li class="end"><a
                                                        href="/1-php-grading-system/admins_page/subject_list/?id=<?php echo $rows['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                                            <li class="next"><a
                                                        href="/1-php-grading-system/admins_page/subject_list/?id=<?php echo $rows['id'] ?><?php if (isset($_GET['searchSubject'])): ?>&&searchSubject=<?php echo $_GET['searchSubject'] ?><?php endif; ?>&&page=<?php echo $page + 1 ?>">Next</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="custom-grid-item pad-1em">
                        <div class="b-shadow-dark">


                            <div class="pad-1em  f-weight-bold d-flex">
                                <h3>
                                    Add Grades
                                </h3>
                            </div>

                            <form method="post">
                                <div class="custom-grid-container" tabindex="1">
                                    <div class="custom-grid-item ">
                                        <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Grade:</div>
                                        <input type="number" placeholder=" grade" id="grade"
                                               class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                               name="grade">
                                    </div>
                                </div>
                                <div class="d-flex-end pad-1em">
                                    <label class="btn bg-hover-gray-dark-v2 m-b-0 t-color-white" onclick="cancel()">
                                        Cancel
                                    </label> &nbsp; &nbsp;
                                    <button type="submit"
                                            class="c-hand btn-success btn"
                                            name="add_grade">Add
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="myModal">
                    <script src="../../assets/js/js_header.js"></script>

                    <div class="modal-content">
                        <div id="top-icon"
                             class="top-icon h-100p d-flex-center p-absolute w-3em c-hand f-size-26px w-2em bg-hover-white t-color-white"
                             onclick="tops()" style="left: -97px;top: -97px;height: 61px;">☰
                        </div>
                        <div class="modal-header a-center">
                        </div>
                        <div class="modal-body">
                            <div id="update-school-year" class="modal-child d-none">
                                <form method="post">
                                    <div class="custom-grid-container" tabindex="1">
                                        <div class="custom-grid-item ">
                                            <input placeholder="id" type="hidden"
                                                   class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   id="id"
                                                   name="id"
                                                   readonly="true">
                                            <div class="d-inline-flex m-l-1em w-29p d-flex-end"> Grade:</div>
                                            <input type="number" placeholder=" grade" id="grade"
                                                   class="h-3em w-50p f-size-1em b-radius-10px m-1em m-t-5px"
                                                   name="grade">
                                        </div>
                                    </div>
                                    <div class="d-flex-end pad-1em">
                                        <label class="btn bg-hover-gray-dark-v2 m-b-0 t-color-white"
                                               onclick="closeModal()">
                                            Close
                                        </label> &nbsp; &nbsp;
                                        <button type="submit"
                                                class="c-hand btn-success btn"
                                                name="update_grade">Save
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
</div>

<script>

    function checkAllCB(id, cb) {
        if ($('#' + cb).is(':checked')) {
            $('#' + id + ' input[type="checkbox"]').prop('checked', true);

        } else {
            $('#' + id + ' input[type="checkbox"]').prop('checked', false);
        }
    }

    function cancel() {
        $('#lastname').val('');
        $('#firstname').val('');
        $('#grade').val('');
        $('#subject').val('');
        $('#username').val('');
        $('#password').val('');
        $('#user_type').val('');
    }

    function editSchoolYear(id, grade) {
        $('#update-school-year #id').val(id);
        $('#update-school-year #grade').val(grade);
        showModal('update-school-year', 'Manage Grade', '', 'small')
    }

    function searchSubject() {
        var search = $('#search_name').val();
        if (search !== '') {
            window.location.href = '?id=<?php echo $rows['id'] ?>&&searchSubject=' + search;
        } else {
            window.location.href = '?id=<?php echo $rows['id'] ?>';
        }
    }

    function loadPage() {
        var searchSubject = '<?php echo isset($_GET['searchSubject']) ? $_GET['searchSubject'] : '' ?>';
        if (searchSubject !== '') {
            $('#search_name').val(searchSubject);
        }
    }

    loadPage();
</script>

