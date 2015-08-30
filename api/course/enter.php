<?php
require_once '../../inc/api.php';
require_once '../../inc/course.php';
require_once '../../inc/user.php';
if ($user['Mode'] === 'student') {
    api_add_error_msg(course_enter($_POST['CourseID']));
    if (!api_add_error_msg($r = course_info($_POST))) $result['course'] = $r;
} else api_add_error_msg('您无权执行此操作！');
api_print();
