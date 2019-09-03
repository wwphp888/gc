<?php

if($_SERVER['SERVER_NAME']==''){
	$url =  'http://'.'www.dailaimeigw.cn'.$_SERVER['REQUEST_URI'];
	header("location:".$url);
	die;
}

if(get_magic_quotes_gpc()){


	function stripslashes_deep($value){


		$value = is_array($value) ?


		array_map('stripslashes_deep', $value) :


		stripslashes($value);


		return $value;


	}


	$_REQUEST = array_map('stripslashes_deep', $_REQUEST);


	$_POST = array_map('stripslashes_deep', $_POST);


	$_GET = array_map('stripslashes_deep', $_GET);


	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);


}





unset($_GET['m']);


if(version_compare(PHP_VERSION,'5.3.0','<'))  die('PHP 版本必须大于等于5.3.0 !');





define('DIR_SECURE_CONTENT', 'powered by http://www.dragondean.cn');





// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false


define('APP_DEBUG', true);





if(!APP_DEBUG){


	ini_set('display_errors', false);


}





header("Content-type:text/html;charset=utf-8");





define('APP_PATH','./Application/');


require './#DFrame/DFrame.php';