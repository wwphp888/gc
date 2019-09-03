<?php
/*
获取完整的请求地址
返回值(string)：https://www.shoukuanla.net:443/a/index.php?m=index&c=Index
修改时间：2019-01-18 18:58
*/
function skl_get_request_url(){
	require_once(SKL_SYS_FUNCTIONS_PATH.'skl_is_https.php');

	$url='http';
	$is_https=skl_is_https();
	if($is_https){		$url.='s'; 	}
	$url.='://';

	if((!$is_https && $_SERVER['SERVER_PORT'] == 80) || ($is_https && $_SERVER['SERVER_PORT'] == 443)){
		 $url.=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}else{
		 $url.=$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	}
	return $url;

}

?>