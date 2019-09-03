<?php
/*
功能：后台基础类
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-16 23:04
*/
require_once(SKL_G_CONTROLLER_PATH.'SklPublic.class.php');
class AdminBase extends SklPublic{

public $assign_admin_id;//管理员id
public $assign_admin_user;//管理员账号
public $assign_admin_role_id;//管理员角色id
public $assign_role_info;//角色信息(数组)
//protected $assign_node_id;//当前节点id
private $is_construct=false;

//检测管理员权限
function __construct(){
   
  $this->_newDb();  
  $this->_newCfg();//加载当前模块配置

	require_once(SKL_G_FUNCTIONS_PATH.'skl_startSession.php');
  skl_startSession();
	$userid=$_SESSION['skl_admin_info']['id'];
	$username=$_SESSION['skl_admin_info']['user'];

	$public_url=skl_U('Login/index');
  if($userid == '' || $username == ''){ 	skl_error('请登录后再来！',$public_url,3); 	}

	$admin_info=$this->db->table("`@#_$this->cfg_sys_admin_table_name`")->field('`admin_id`,`admin_user`,`admin_status`,`admin_role_id`')->where("`admin_id`='$userid'")->find();
  if(empty($admin_info)){
		unset($_SESSION['skl_admin_info']);
		skl_error($username.'该管理员账号不存在！',$public_url,3);	
	}

	//检测用户状态
	if($admin_info['admin_status'] != 1){  skl_error($username.'该管理员账号已被禁用！');		}

  //赋值给模板 
	$this->assign_admin_id=$admin_info['admin_id'];
	$this->assign_admin_user=$admin_info['admin_user'];
	$this->assign_admin_role_id=$admin_info['admin_role_id'];

	$this->is_construct=true;

  
  //检查权限,开始
	$skl_controller=SKL_CONTROLLER;   $skl_action=SKL_ACTION; 
	$index='index';  

	//不需要检查权限的项目
	$in_arrays=array(
		'Lmenu'       =>array($index),
		'Webset'      =>array('check_old_pass'),
		'Getdbtime'   =>array($index),
		'Hidesearch'  =>array($index),
	);

  if($this->assign_admin_id != 1 && !in_array($skl_action,$in_arrays[$skl_controller])){//非超级管理员都要检查

			//读取当前节点id			
			$node_id=$this->db->table("`@#_$this->cfg_sys_admin_node_table_name`")->where("`admin_node_module`='$skl_controller' AND `admin_node_action`='$skl_action'")->getField('`admin_node_id`');

      $this->get_role_info(); 
		  if(!in_array($node_id,$this->assign_role_info['admin_role_access'])){   skl_error('您没有操作权限！');		}	
	
	}
  //检查权限，结束	

}


//获取角色信息,并且赋值给成员变量$this->assign_role_info
protected function get_role_info(){ 

	if(!$this->is_construct){ skl_error('调用'.__METHOD__.'函数之前必须先执行构造函数！');	 }

	if(empty($this->assign_role_info)){

     $this->assign_role_info=$this->db->table("`@#_$this->cfg_sys_admin_role_table_name`")->field('`admin_role_name`,`admin_role_access`')->where("`admin_role_id`=$this->assign_admin_role_id")->find();
		 $this->assign_role_info['admin_role_access']=json_decode($this->assign_role_info['admin_role_access'],true);

	}

}


}
?>