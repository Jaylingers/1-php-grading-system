<?php

$sname = "localhost";
$uname = "root";
$password = "root";

$db_name = "php_grading_system";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

$mysqli = mysqli_connect('localhost', 'root', 'root', 'php_grading_system');

if (!$conn) {
    echo "Connection failed!";
}