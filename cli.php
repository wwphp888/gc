<?php
// 执行1000次sql查询测试
define('CLI', true);

$_GET['m'] = 'Home';
$_GET['c'] = 'Cli';
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('PHP 版本必须大于等于5.3.0 !');

define('DIR_SECURE_CONTENT', 'access denied');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', false);

define('APP_PATH','./Application/');
require './#DFrame/DFrame.php';