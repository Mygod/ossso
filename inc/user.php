<?php
require_once 'config.php';
require_once 'student.php';
require_once 'teacher.php';

if (($uid = $_COOKIE['uid']) && $password = $_COOKIE['password']) if ($uid == 'admin') {
    if ($password == $config->getAdminPassword()) $user = ['Mode' => 'admin'];
} elseif (ctype_digit($uid)) studentLogin(); else teacherLogin();
