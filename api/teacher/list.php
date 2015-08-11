<?php
require_once '../../inc/api.php';
require_once '../../inc/user.php';
if ($user['Mode'] === 'admin') if (is_string($r = teacher_list())) api_add_error_msg($r); else $result['teachers'] = $r;
else api_add_error_msg('您无权执行此操作！');
api_print();
