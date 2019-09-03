<?php
//配置MYSQL数据库连接信息
#数据库连接
include_once '../ewmadmin/inc/config.php';
$mysql_server_name	=	$host; 	//数据库服务器名称
$mysql_username		=	$user; 		// 连接数据库用户名
$mysql_password		=	$password;			// 连接数据库密码
$mysql_database		=	$db; 		// 数据库的名字
$mysql_conn = mysql_connect($mysql_server_name, $mysql_username, $mysql_password);
?>