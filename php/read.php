<?php

global $conn;
include "db_conn.php";

$sql = "SELECT * FROM users_info WHERE id=12";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
