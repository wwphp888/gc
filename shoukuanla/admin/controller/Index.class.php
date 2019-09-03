<?php
/*
功能：管理中心首页
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-06-27 18:04
*/
require_once(SKL_CONTROLLER_PATH.'AdminBase.class.php');
class Index extends AdminBase{

function __construct(){  
  
  parent::__construct(); 

}

//顶部菜单
public function index(){  

	$this->get_role_info(); 

  //角色权限转字符串
  require_once(SKL_G_FUNCTIONS_PATH.'skl_node_access_str.php');
  $node_access=skl_node_access_str($this->assign_role_info['admin_role_access']);

  $node_table_name=$this->db->utable($this->cfg_sys_admin_node_table_name);
	$menu_list=$this->db->query("SELECT `admin_node_id`,`admin_node_module_name` FROM `$node_table_name` WHERE `admin_node_auth_type`=0 AND `admin_node_shangji`=0 AND `admin_node_status`=1 AND `admin_node_is_show`=1 AND `admin_node_id` IN($node_access) ORDER BY `admin_node_sort` ASC");	

	//获取远程更新数据，开始
	require_once(SKL_G_CONTROLLER_PATH.'Getupdate.class.php');
	$Getupdate=new Getupdate();
	$update_data=$Getupdate->index();
	//获取远程更新数据，结束

  require(SKL_ROOT_PATH.'version.php');
  require_once(SKL_CONTROLLER_VIEW_PATH.'index.php');
}


//后台首页
public function main(){	 

  $admin_info=$this->db->table("`@#_$this->cfg_sys_admin_table_name`")->field('`admin_pwd`')->where("`admin_id` = '$this->assign_admin_id'")->find();
	//判断默认密码是否修改
	$is_show_alert=$admin_info['admin_pwd'] == md5(md5('123456')) ? true : false;

  require_once(SKL_CONTROLLER_VIEW_PATH.'main.php');
}




}
?>