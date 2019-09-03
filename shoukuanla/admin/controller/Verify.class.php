<?php
/*
功能：验证码
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2018-05-29 18:58
*/
class Verify{

//输出验证码
//浏览器访问 /shoukuanla/admin.php?c=Verify
public function index(){
  require_once(SKL_ClASS_PATH.'VerifyCode.class.php');
  $VerifyCode=new VerifyCode(array('imageH'=>45,'imageW'=>165,'fontSize'=>22));	
	$VerifyCode->entry('admin'); 
}


}
?>