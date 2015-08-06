<?php
include_once '../inc/api.php';
require_once '../inc/user.php';
if (!$user) addErrorMessage('用户名或密码错误！');
printResult();
