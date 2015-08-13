<?php
include_once '../../inc/api.php';
require_once '../../inc/user.php';
if ($user['Mode'] === 'teacher') {
    if ($_POST['Name']) api_add_error_msg(teacher_set('TeacherName', $_POST['Name']));
} else api_add_error_msg('您无权执行此操作！');
api_print();
