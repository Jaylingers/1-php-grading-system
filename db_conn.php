<?php
$mysqli = mysqli_connect('localhost:33306', 'root', 'root', 'php_grading_system');
$conn = mysqli_connect('localhost:33306', 'root', 'root', 'php_grading_system');
$schoolName = "Mactan Airbase Elementary School";

if (!$conn || !$mysqli) {
    echo "Connection failed!";
}

