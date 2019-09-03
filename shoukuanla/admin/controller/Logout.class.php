<?php
/*
功能：退出登录
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2018-09-29 18:58
*/
class Logout{

public function index(){
  
   require_once(SKL_G_FUNCTIONS_PATH.'skl_startSession.php');
   skl_startSession();
	 unset($_SESSION['skl_admin_info']);

	 header('location: '.skl_U('Login/index'));

}


}
?>