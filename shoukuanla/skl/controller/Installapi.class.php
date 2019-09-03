<?php
/*
功能：安装api接口
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-24 18:04
*/
class Installapi extends ShoukuanlaBase{
private $base64;
function __construct(){ 
  
	require_once(SKL_ClASS_PATH.'ShoukuanlaBase64.class.php');
  $this->base64=new ShoukuanlaBase64();
}

//创建表
public function createtable(){

	$data=json_decode($this->base64->decrypt_url($_POST['skl_install_password']),true);
  $install_file_path=& $data['install_file_path'];
	if($install_file_path == '' || !file_exists($install_file_path)){ exit; }

	$this->_newDb();
	$this->_newCfg();//加载当前模块配置
	$this->_newCfg('',SKL_MODULE_NAME_2);//加载指定模块配置

	//添加管理员和配置信息表，开始
	require_once(SKL_G_CONTROLLER_PATH.'AddAdminField.class.php');
	$AddAdminField=new AddAdminField($this);
	$arr=$AddAdminField->index();
	//添加管理员和配置信息表，结束

	if($arr['status'] != 'success'){ skl_error($arr);	}

  skl_ajax($arr);
}

//获取模块配置
public function getcfg(){

	$data=json_decode($this->base64->decrypt_url($_POST['skl_install_password']),true);
  $install_file_path=& $data['install_file_path'];
	if($install_file_path == '' || !file_exists($install_file_path)){ exit; }

  $cfg1=skl_C(SKL_MODULE_NAME_1);
	$cfg2=skl_C(SKL_MODULE_NAME_2);
  if(empty($cfg1) || empty($cfg2)){  skl_error('配置信息获取失败！'); }

  $cfg_info=json_encode(array('status'=>'success','cfg'=>array_merge($cfg1,$cfg2)));
	echo $this->base64->encrypt_url($cfg_info);
}

}
?>