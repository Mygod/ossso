<?php
include_once '../inc/api.php';
require_once '../inc/config.php';
require_once '../inc/user.php';
if ($user['Mode'] === 'admin') {
    if ($_POST['siteName']) api_add_error_msg($config->setSiteName($_POST['siteName']));
} else api_add_error_msg('您无权执行此操作！');
api_print();
