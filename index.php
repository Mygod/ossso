<?php
require_once 'inc/config.php';
require_once 'inc/user.php';
?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="generator" content="Open Source Subject Selection Online">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $config->getSiteName() ?></title>
    <meta name="theme-color" content="#303F9F">
    <meta name="msapplication-TileColor" content="#3372DF">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="<?= $config->getSiteName() ?>">
    <script src="/bower_components/webcomponentsjs/webcomponents-lite.min.js"></script>
    <link rel="import" href="elements/elements.html">
</head>
<body unresolved class="fullbleed">
<?php
switch ($user['Mode']) {
    case 'admin':
    case 'student':
    case 'teacher':
        include "${user['Mode']}.php";
        break;
    default:
        include 'login.php';
        break;
}
?>
</body>
</html>
