<?php
require_once '../../inc/api.php';
require_once '../../inc/user.php';
api_add_error_msg($user['Mode'] === 'admin' ? student_remove($_POST['StudentID']) : '您无权执行此操作！');
api_print();
