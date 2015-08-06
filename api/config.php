<?php
include_once '../inc/api.php';
require_once '../inc/config.php';
require_once '../inc/user.php';
if ($user['Mode'] === 'admin') {
    if ($_POST['siteName']) addErrorMessage($config->setSiteName($_POST['siteName']));
} else addErrorMessage('您无权执行此操作！');
printResult();
