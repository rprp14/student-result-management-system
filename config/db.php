<?php
$conn = mysqli_connect("localhost", "root", "root", "student_result_db");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
