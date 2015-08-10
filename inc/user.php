<?php
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/student.php';
require_once dirname(__FILE__) . '/teacher.php';

if (($uid = $_COOKIE['uid']) && $password = $_COOKIE['password']) if ($uid == 'admin') {
    if ($password == $config->getAdminPassword()) $user = ['Mode' => 'admin'];
} elseif (ctype_digit($uid)) student_login(); else teacher_login();
