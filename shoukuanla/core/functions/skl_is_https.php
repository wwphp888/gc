<?php
/*
判断是不是https请求
修改时间：2018-12-13 18:58
*/
function skl_is_https(){

	if(!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off'){
		 return true;
	}elseif(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
		 return true;
	}elseif(!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
		 return true;
	}
	return false;
}

?>