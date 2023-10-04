<?php global $conn;
$var = "dashboard";
include '../header.php'; ?>


<div class="d-flex-end p-absolute w-100p t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px one_page" >


        <div class="m-2em d-flex-align-start ">
            <div id="categories" class="d-flex-center bg-gray-light pad-3em w-100p b-radius-10px">
                <div id="students" class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center c-hand" onclick="chooseCategory('students')"><img
                            src="https://cdn3.iconfinder.com/data/icons/school-and-education-4-8/48/181-512.png" alt=""
                            class="w-7em m-3-6px"/>
                    <div class="h-8em"> Students</div>
                </div>
                <div id="teacher" class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center c-hand" onclick="chooseCategory('teacher')"><img
                            src="https://cdn0.iconfinder.com/data/icons/high-school-12/340/teacher_book_education_school_college_people_study-512.png"
                            alt=""
                            class="w-7em m-3-6px"/>
                    <div class="h-8em"> Teacher</div>
                </div>
                <div id="pageVisited" class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center c-hand" onclick="chooseCategory('pageVisited')"><img
                            src="https://cdn3.iconfinder.com/data/icons/font-awesome-solid/576/eye-512.png" alt=""
                            class="w-7em m-3-6px"/>
                    <div class="h-8em"> Page Visited</div>
                </div>
            </div>
        </div>

        <div class="m-2em d-flex-align-start">
            <div class="bg-white w-100p b-radius-10px">

                <?php
                $category = isset($_GET['category']) ? $_GET['category'] : '';
                if($category !== '') {
                    ?>

                    <div class="pad-1em b-bottom-gray-3px f-weight-bold">

                        Choose Grade:
                        <select name="grade" id="grade"
                                class="h-3em w-80p f-size-1em b-radius-10px m-1em m-t-5px">
                            <option value="" disabled selected>Grade</option>
                            <option value="1">Grade 1</option>
                            <option value="2">Grade 2</option>
                            <option value="3">Grade 3</option>
                            <option value="4">Grade 4</option>
                            <option value="5">Grade 5</option>
                            <option value="6">Grade 6</option>
                        </select>

                    </div>

                    <div class="grid-container">
                        <?php
                        $sql = "select * from users_info";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result)) { ?>
                            <?php
                            $i = 0;
                            while ($rows = mysqli_fetch_assoc($result)) {
                                $i++;
                                ?>
                                <div class="grid-item d-flex-center">
                                    <div class=" b-radius-50p w-20em h-16em m-1em bg-gray-dark d-flex-center">
                                        <img
                                                src="https://cdn0.iconfinder.com/data/icons/high-school-12/340/teacher_book_education_school_college_people_study-512.png"
                                                alt=""
                                                class="w-50p h-8em m-3-6px"/>
                                        <div class="h-12em w-1em t-color-red f-weight-bold f-size-19px"> NEW</div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php }
                        ?>
                    </div>

                    <?php
                }
                else {
                    ?>
                    <div class="t-align-center "><br>
                        Please Choose Category <br>
                        <br>
                    </div>
                <?php  } ?>
            </div>

    </div>
</div>

<script>
    function chooseCategory(category) {
        $('#categories div').map(function () {
            if($(this).attr('id') !== undefined) {
                $('#' +$(this).attr('id') ).css('background', 'rgb(186 186 186)');
            }
        });
        $('#'+category).css('background', 'white');
        history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>&&category=' + category);
        window.location.reload();
    }

    function loadPage() {
        var category = '<?php echo isset($_GET['category']) ? $_GET['category'] : '' ?>';
        if (category !== '') {
            $('#'+category).css('background', 'white');
        }
    }
    loadPage();
</script>
