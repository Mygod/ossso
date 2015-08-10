<?php
include_once '../inc/api.php';
require_once '../inc/user.php';
if (!$user) api_add_error_msg('用户名或密码错误！');
api_print();
