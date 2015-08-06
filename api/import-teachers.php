<?php
require_once '../inc/password.php';
require_once '../inc/user.php';
str_getcsv(file_get_contents($_FILES['data']['tmp_name']), '\n')