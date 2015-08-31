<?php
require_once '../../inc/api.php';
require_once '../../inc/course.php';
require_once '../../inc/user.php';
$list = course_export($_POST['CourseID']);
csv_init('course_' . $_POST['CourseID']);
foreach ($list as $row) fputcsv($fout, $row);
