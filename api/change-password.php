<?php
include_once '../inc/api.php';
require_once '../inc/config.php';
require_once '../inc/user.php';
switch ($user['Mode']) {
    case 'admin':
        addErrorMessage($config->setAdminPassword($_POST['password']));
        break;
    case 'student':
        addErrorMessage(studentChangePassword($_POST['password']));
        break;
    case 'teacher':
        addErrorMessage(teacherChangePassword($_POST['password']));
        break;
    default:
        addErrorMessage('您尚未登录！');
}
printResult();
