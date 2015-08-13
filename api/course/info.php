<?php
require_once '../../inc/api.php';
require_once '../../inc/course.php';
require_once '../../inc/user.php';
if ($user) {
    if (!api_add_error_msg($r = course_info($_POST, $user))) $result['course'] = $r;
} else api_add_error_msg('请先登录！');
api_print();
