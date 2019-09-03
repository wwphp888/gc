<?php
/*
功能：左侧菜单
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2018-08-17 18:04
*/
require_once(SKL_CONTROLLER_PATH.'AdminBase.class.php');
class Lmenu extends AdminBase{

function __construct(){  
  
  parent::__construct(); 

}

//输出左侧菜单
public function index(){

  $this->get_role_info(); 

	//角色权限转字符串
  require_once(SKL_G_FUNCTIONS_PATH.'skl_node_access_str.php');
  $node_access=skl_node_access_str($this->assign_role_info['admin_role_access']);

  require_once(SKL_CONTROLLER_VIEW_PATH.'index.php');

}


}
?>