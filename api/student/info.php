<?php
include_once '../../inc/api.php';
require_once '../../inc/user.php';
if ($user['Mode'] === 'student') {
    if ($_POST['StudentIntroduction'])
        api_add_error_msg(student_set('StudentIntroduction', $_POST['StudentIntroduction']));
} else api_add_error_msg('您无权执行此操作！');
api_print();
