<?php
require_once '../../inc/api.php';
require_once '../../inc/password.php';
require_once '../../inc/user.php';
if ($user['Mode'] !== 'admin') fatal_alert('您无权执行此操作！');
$fin = fopen($_FILES['file']['tmp_name'], 'r');
if (!$fin) fatal_alert('文件上传失败！');
csv_init('teachers');
while (!feof($fin)) if (count($row = fgetcsv($fin)) === 2 &&
    !teacher_add($row[0], $row[2] = generate_password(), $row[1])) fputcsv($fout, $row);
