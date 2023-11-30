<?php
$sname="localhost";
$uname="root";
$password="";
$mysqli = mysqli_connect('localhost', 'root', '', 'php_grading_system');
$conn = mysqli_connect('localhost', 'root', '', 'php_grading_system');
$schoolName = "Mactan Airbase Elementary School";

if (!$conn || !$mysqli) {
    echo "Connection failed!";
}

