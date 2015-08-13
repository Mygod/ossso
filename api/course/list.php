<?php
require_once '../../inc/api.php';
require_once '../../inc/course.php';
require_once '../../inc/user.php';
if ($user) if (is_string($r = course_list())) api_add_error_msg($r); else $result['courses'] = $r;
else api_add_error_msg('请先登录！');
api_print();
