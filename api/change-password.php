<?php
include_once '../inc/api.php';
require_once '../inc/config.php';
require_once '../inc/user.php';
switch ($user['Mode']) {
    case 'admin':
        api_add_error_msg($config->setAdminPassword($_POST['password']));
        break;
    case 'student':
        api_add_error_msg(student_change_password($_POST['password']));
        break;
    case 'teacher':
        api_add_error_msg(teacher_change_password($_POST['password']));
        break;
    default:
        api_add_error_msg('您尚未登录！');
}
api_print();
