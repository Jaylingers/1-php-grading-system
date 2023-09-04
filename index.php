<?php $var = "index";
include 'students_page/header.php';
?>

<?php

global $conn;
include "db_conn.php";

$sql = "SELECT * FROM admins_info WHERE id=12";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<div style="background: #14ae5c;">
    <div class="container">
        INDEX PAGE
    </div>
</div>