<?php
require_once '../../inc/api.php';
require_once '../../inc/course.php';
require_once '../../inc/user.php';
api_add_error_msg($user['Mode'] === 'teacher' ? course_delete($_POST['CourseID']) : '您无权执行此操作！');
api_print();
