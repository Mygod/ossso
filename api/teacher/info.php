<?php
include_once '../../inc/api.php';
require_once '../../inc/user.php';
if ($user['Mode'] === 'teacher') {
    if ($_POST['TeacherName']) api_add_error_msg(teacher_set('TeacherName', $_POST['TeacherName']));
} else api_add_error_msg('您无权执行此操作！');
api_print();
