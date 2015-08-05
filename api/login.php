<?php
include_once '../inc/api.php';
require_once '../inc/user.php';
if ($user) $result['success'] = true; else $result['errorMessage'] = '用户名或密码错误！';
printResult();
