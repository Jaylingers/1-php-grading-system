<?php global $mysqli;
$var = "student_behavior";
include '../header.php'; ?>

<div class="d-flex-end p-absolute w-100p h-100p t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px">


        <?php

        // Get the total number of records from our table "students".
        $total_pages = $mysqli->query('SELECT * FROM admins_info')->num_rows;
        // Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
        // Number of results to show on each page.
        $num_results_on_page = 2;

        if ($stmt = $mysqli->prepare('SELECT * FROM admins_info ORDER BY username LIMIT ?,?')) {
        // Calculate the page to get the results we need from our table.
        $calc_page = ($page - 1) * $num_results_on_page;
        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
        $stmt->execute();
        // Get the results...
        $result = $stmt->get_result();
        ?>

        <style>
            .table-1 tbody tr th, .table-1 tbody tr td {
                border-bottom: 0 !important;
                border-top: 0 !important;
            }

            .table-1 thead tr th, .table-1 thead tr td {
                border-top: 0 !important;
                border-bottom: 3px solid #ddd;
            }
        </style>


        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px">

                <div class="pad-1em  f-weight-bold d-flex">
                    <h3>
                        STUDENT LIST
                    </h3>
                    <div class="r-50px p-absolute t-54px">
                        <button
                                class="btn bg-hover-gray-dark-v1">Add New
                        </button>
                        <button
                                class="btn bg-hover-gray-dark-v1">Delete Selected
                        </button>
                    </div>
                </div>
                <br/>


                <table class="table table-1 ">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>LRN</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Birthdate</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Grade Level</th>
                        <th>Option</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>

                        <tr>
                            <th scope="row"></th>
                            <td><?= $row['username'] ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <label for="" class="t-color-red c-hand f-weight-bold">View Enrollment</label>
                                &nbsp;
                                &nbsp; &nbsp;
                                <label for="" class="t-color-red c-hand f-weight-bold">View Details</label>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>

                <?php
                $stmt->close();
                }
                ?>


            </div>
        </div>
        <div class="m-2em d-flex-end m-t-n1em">
            <div class="d-flex-center">
                <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="prev"><a
                                        href="/1-php-grading-system/admins_page/student_behavior/?id=1&&page=<?php echo $page - 1 ?>">Prev</a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page > 3): ?>
                            <li class="start"><a
                                        href="/1-php-grading-system/admins_page/student_behavior/?id=1&&page=1">1</a>
                            </li>
                            <li class="dots">...</li>
                        <?php endif; ?>

                        <?php if ($page - 2 > 0): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/student_behavior/?id=1&&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
                            </li><?php endif; ?>
                        <?php if ($page - 1 > 0): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/student_behavior/?id=1&&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
                            </li><?php endif; ?>

                        <li class="currentpage"><a
                                    href="/1-php-grading-system/admins_page/student_behavior/?id=1&&page=<?php echo $page ?>"><?php echo $page ?></a>
                        </li>

                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/student_behavior/?id=1&&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
                            </li><?php endif; ?>
                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1): ?>
                            <li class="page"><a
                                    href="/1-php-grading-system/admins_page/student_behavior/?id=1&&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
                            </li><?php endif; ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2): ?>
                            <li class="dots">...</li>
                            <li class="end"><a
                                        href="/1-php-grading-system/admins_page/student_behavior/?id=1&&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                            <li class="next"><a
                                        href="/1-php-grading-system/admins_page/student_behavior/?id=1&&page=<?php echo $page + 1 ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
