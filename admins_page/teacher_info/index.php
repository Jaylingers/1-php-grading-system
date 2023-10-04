<?php global $mysqli, $rows;
$var = "teacher_info";
include '../header.php'; ?>

<?php
global $conn;
include "../../db_conn.php";

if (isset($_POST['saveImage'])) {
    $file = $_FILES['image'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];
    $lrn = $_POST['lrn'];

    if ($fileError === UPLOAD_ERR_OK) {
        if (strpos($fileType, 'image') !== false) {
            $newFileName = uniqid('', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            move_uploaded_file($fileTmpName, '../../assets/users_img/' . "$lrn" . '.png');

            $sql = "UPDATE users_info SET img_path = '../../assets/users_img/$lrn.png' WHERE user_lrn = '$lrn'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo '<script>';
                echo '
                   history.pushState({page: "another page"}, "another page", "?id=' . $_GET['id'] . '&&imgSaved=true");
                    window.location.reload();';
                echo '</script>';
            } else {
                echo '<script>';
                echo ' alert("An error occurred while uploading your file. Please try again.");';
                echo '</script>';
            }
        }
    } else {
        echo '<script>';
        echo ' alert("An error occurred while uploading your file. Please try again.");';
        echo '</script>';
    }
}
?>

<div class="d-flex-end p-absolute w-100p bottom-0 t-60px">
    <div id="content" class="bg-off-white w-79-8p h-100p b-r-7px contents one_page">

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
                            <img onclick="$('#image').click()" src="../../assets/img/camera.png" alt="teacher image"
                                 class="c-hand p-absolute bg-hover-gray-dark-v2" style=" height: 3em;
                                    width: 4em;
                                    object-fit: contain;
                                    border-radius: 50%;
                                    margin-top: 17em;
                                    margin-left: 20em;
                                    padding: 5px;">
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
                                <button id="saveButton" type="submit"
                                        class="c-hand btn-success btn d-none"
                                        name="saveImage">Save
                                </button>
                            </form>
                        </div>
                        <div class="m-l-13px">
                            LRN NUMBER: <?= $rows['lrn'] ?> <br>
                            First Name: <?= $rows['first_name'] ?> <br>
                            Last Name: <?= $rows['last_name'] ?> <br>
                            User: <?= $rows['username'] ?> <br>
                            User Type: <?= $rows['user_type'] ?>
                        </div>

                        <?php } ?>
                    </div>

                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('image');
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file.');
                input.value = '';
            } else {
                console.log(file)
                $('#view-image').attr('src', URL.createObjectURL(file));
                setTimeout(() => {
                    $('#saveButton').click();
                }, 2000)
            }
        });

        function loadPage() {
            if (window.location.href.includes('imgSaved=true')) {
                alert('Image saved successfully');
                history.pushState({page: 'another page'}, 'another page', '?id=<?php echo $_GET['id'] ?>');
            }
        }

        loadPage();
    </script>