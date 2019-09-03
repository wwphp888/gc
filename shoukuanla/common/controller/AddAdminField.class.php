<?php 
/*
//添加管理员和配置信息表
//修改时间：2019-01-22 18:58

使用方法：
//添加管理员和配置信息表，开始
require_once(SKL_G_CONTROLLER_PATH.'AddAdminField.class.php');
$AddAdminField=new AddAdminField($this);
$AddAdminField->index();
//添加管理员和配置信息表，结束
*/
class AddAdminField{
private $obj;

function __construct(& $obj=null){
    
	//检测需要的变量值
	if($obj->cfg_sys_admin_table_name == ''){  skl_error('cfg_sys_admin_table_name不能为空');   }	
	if($obj->cfg_sys_admin_log_table_name == ''){  skl_error('cfg_sys_admin_log_table_name不能为空');   }	
	if($obj->cfg_sys_admin_role_table_name == ''){  skl_error('cfg_sys_admin_role_table_name不能为空');   }	
	if($obj->cfg_sys_admin_node_table_name == ''){  skl_error('cfg_sys_admin_node_table_name不能为空');   }	
   
	$this->obj=$obj;
}

//返回值：成功返回array('status'=>'success')  失败返回array('errormsg'=>'错误信息')
function index(){
  $db=& $this->obj->db; 

  require_once(SKL_G_FUNCTIONS_PATH.'skl_add_skl_field.php');
  $return_value=skl_add_skl_field($this->obj);//添加配置表、字段
	if($return_value['status'] != 'success'){  return $return_value; }


	$admin_table=$db->utable($this->obj->cfg_sys_admin_table_name);  
	$admin_log_table=$db->utable($this->obj->cfg_sys_admin_log_table_name); 
	$admin_role_table=$db->utable($this->obj->cfg_sys_admin_role_table_name); 
	$admin_node_table=$db->utable($this->obj->cfg_sys_admin_node_table_name); 

	$table_names=$db->query("SELECT `table_name` FROM information_schema.tables WHERE `table_name` = '$admin_table' OR `table_name` = '$admin_log_table' OR `table_name` = '$admin_role_table' OR `table_name` = '$admin_node_table'");
	
	if($table_names){

    while($table_name_arr=$table_names->fetch_assoc()){	
	       if(!$admin_exists && $table_name_arr['table_name'] == $admin_table ){  $admin_exists=true ;} 
         if(!$admin_log_exists && $table_name_arr['table_name'] == $admin_log_table ){  $admin_log_exists=true ;} 
				 if(!$admin_role_exists && $table_name_arr['table_name'] == $admin_role_table ){  $admin_role_exists=true ;}
				 if(!$admin_node_exists && $table_name_arr['table_name'] == $admin_node_table ){  $admin_node_exists=true ;}

	  }
	  $table_names->free();
  }


	//添加管理员用户表
	if(!$admin_exists){
	
			$create_admin=$db->query("CREATE TABLE IF NOT EXISTS `$admin_table` (
			`admin_id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'ID',
			`admin_user` char(50) NOT NULL DEFAULT 'admin' UNIQUE COMMENT '管理员账号',
			`admin_pwd` char(32) NOT NULL DEFAULT '14e1b600b1fd579f47433b88e8d85291' COMMENT '管理员密码',
			`admin_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
			`admin_role_id` int(10) NOT NULL DEFAULT '0' COMMENT '角色ID',
			`admin_login_count` int(10) NOT NULL DEFAULT '0' COMMENT '登录次数',			
			`admin_last_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
			`admin_create_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '创建时间'
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员信息';");
		
		if(!$create_admin){  return array('errormsg'=>'管理员信息单表创建失败！'); }

		//默认值
		$admin_data=array(
			'admin_id'                 =>1,
			'admin_status'             =>1,
			'admin_role_id'            =>1,
			'admin_create_time'        =>'UNIX_TIMESTAMP()',
		);

		$insert_admin=$db->table("`$admin_table`")->add($admin_data,false);
    if(empty($insert_admin)){
			$db->query("DROP TABLE `$admin_table`");//如果插入数据失败删除该表
			return array('errormsg'=>'管理员表插入数据失败！');
		}
	
	}



	//添加管理员操作日志表
	if(!$admin_log_exists){
	
			$create_admin_log=$db->query("CREATE TABLE IF NOT EXISTS `$admin_log_table` (
			`admin_log_id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'ID',
			`admin_log_user` char(50) NOT NULL DEFAULT '' COMMENT '操作账号',
			`admin_log_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '操作类型 1=增 2=删 3=改 4=查',
			`admin_log_ip` varchar(40) NOT NULL DEFAULT '' COMMENT '客户端IP',
			`admin_log_explain` text NOT NULL COMMENT '操作内容说明',
			`admin_log_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '操作时间'
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员操作日志信息';");
		
		if(!$create_admin_log){  return array('errormsg'=>'管理员操作日志单表创建失败！'); }
	
	}



	//添加角色表
  if(!$admin_role_exists){
	
		 $create_admin_role=$db->query("CREATE TABLE IF NOT EXISTS `$admin_role_table` (
			`admin_role_id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'ID',
			`admin_role_name` varchar(20) NOT NULL DEFAULT '' UNIQUE COMMENT '角色名称',
			`admin_role_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '角色状态',
			`admin_role_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述',
			`admin_role_access` text NOT NULL COMMENT '角色权限',
			`admin_role_create_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '角色创建时间',
			`admin_role_update_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '角色修改时间'
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色信息';");
		
		if(!$create_admin_role){  return array('errormsg'=>'角色信息单表创建失败！'); }


		//默认值
		$admin_role_data=array(
			'admin_role_id'                 =>1,
			'admin_role_name'               =>"'超级管理员'",
			'admin_role_status'             =>1,
			'admin_role_remark'             =>"'超级管理员'",
			'admin_role_access'             =>"'".json_encode(array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33))."'",
			'admin_role_create_time'        =>'UNIX_TIMESTAMP()',
		);

		$insert_admin_role=$db->table("`$admin_role_table`")->add($admin_role_data,false);
    if(empty($insert_admin_role)){
			$db->query("DROP TABLE `$admin_role_table`");//如果插入数据失败删除该表
			return array('errormsg'=>'角色表插入数据失败！');
		}

	}


	//添加节点表
	if(!$admin_node_exists){
	
		 $create_admin_node=$db->query("CREATE TABLE IF NOT EXISTS `$admin_node_table` (
  `admin_node_id` smallint(6) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'ID',
  `admin_node_module` char(50) NOT NULL DEFAULT '' COMMENT '模型',
  `admin_node_module_name` varchar(50) NOT NULL DEFAULT '' COMMENT '模型名称',
  `admin_node_action` char(50) NOT NULL DEFAULT '' COMMENT '操作',
  `admin_node_action_name` varchar(50) NOT NULL DEFAULT '' COMMENT '操作名称',
  `admin_node_data` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `admin_node_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `admin_node_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `admin_node_sort` smallint(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `admin_node_auth_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '节点类型,0=顶部菜单 1=一级菜单 99=操作',
  `admin_node_shangji` smallint(6) NOT NULL COMMENT '上级菜单ID',
  `admin_node_often` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-不常用 1-常用',
  `admin_node_is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是在菜单中显示'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='节点信息'");
		
		if(!$create_admin_node){  return array('errormsg'=>'节点信息单表创建失败！'); }

		//默认值
		$insert_admin_node=$db->table("`$admin_node_table`")->add("(`admin_node_id`, `admin_node_module`, `admin_node_module_name`, `admin_node_action`, `admin_node_action_name`, `admin_node_data`, `admin_node_status`, `admin_node_remark`, `admin_node_sort`, `admin_node_auth_type`, `admin_node_shangji`, `admin_node_often`, `admin_node_is_show`) VALUES
		(1, 'Index', '', 'index', '管理中心首页', '', 1, '', 0, 99, 0, 0, 0),
		(2, '', '网站设置', '', '', '', 1, '', 1, 0, 0, 0, 1),
		(3, '', '基本设置', '', '', '', 1, '', 0, 1, 2, 0, 1),
		(4, 'Index', '', 'main', '后台首页', '', 1, '', 0, 99, 0, 0, 0),
		(5, 'Webset', '', 'pass', '修改密码', '', 1, '', 0, 99, 3, 0, 1),
		(6, 'Webset', '', 'config', '接口配置', '', 1, '', 1, 99, 3, 0, 1),
		(7, '', '系统设置', '', '', '', 1, '', 2, 0, 0, 0, 1),
		(8, '', '管理员管理', '', '', '', 1, '', 1, 1, 7, 0, 0),
		(9, 'User', '', 'index', '管理员列表', '', 1, '', 0, 99, 8, 0, 1),
		(10, 'User', '', 'add', '添加管理员', '', 1, '', 0, 99, 8, 0, 0),
		(11, 'User', '', 'edit', '编辑管理员', '', 1, '', 0, 99, 8, 0, 0),
		(12, 'User', '', 'delete', '删除管理员', '', 1, '', 0, 99, 8, 0, 0),
		(13, '', '角色管理', '', '', '', 1, '', 1, 1, 7, 0, 0),
		(14, 'Role', '', 'index', '角色列表', '', 1, '', 0, 99, 13, 0, 1),
		(15, 'Role', '', 'add', '添加角色', '', 1, '', 0, 99, 13, 0, 0),
		(16, 'Role', '', 'edit', '编辑角色', '', 1, '', 0, 99, 13, 0, 0),
		(17, 'Role', '', 'delete', '删除角色', '', 1, '', 0, 99, 13, 0, 0),
		(18, 'Role', '', 'auth', '角色授权', '', 1, '', 0, 99, 13, 0, 0),
		(19, '', '上传管理', '', '', '', 1, '', 1, 1, 2, 0, 0),
		(20, 'Upload', '', 'index', '收款码管理', '', 1, '', 0, 99, 19, 0, 0),
		(21, 'Upload', '', 'qrcode', '上传收款码', '', 1, '', 0, 99, 19, 0, 0),
		(22, 'Upload', '', 'delete', '删除收款码', '', 1, '', 0, 99, 19, 0, 0),
		(23, 'Upload', '', 'newdir', '新建收款码目录', '', 1, '', 0, 99, 19, 0, 0),
		(24, '', '订单管理', '', '', '', 1, '', 0, 0, 0, 0, 1),
		(25, '', '网站订单', '', '', '', 1, '', 0, 1, 24, 0, 1),
		(26, 'Orders', '', 'index', '订单列表', '', 1, '', 0, 99, 25, 0, 1),
		(27, 'Orders', '', 'edit', '手动补单', '', 1, '', 0, 99, 25, 0, 0),
		(28, 'Orders', '', 'deldata', '清理订单', '', 1, '', 0, 99, 25, 0, 0),
		(29, '', '日志管理', '', '', '', 1, '', 1, 1, 7, 0, 1),
		(30, 'Adminlog', '', 'index', '日志列表', '', 1, '', 0, 99, 29, 0, 1),
		(31, 'Adminlog', '', 'deldata', '清理日志', '', 1, '', 0, 99, 29, 0, 0),
		(32, '', '更新管理', '', '', '', 1, '', 1, 1, 7, 0, 0),
		(33, 'Onlineupdate', '', 'index', '系统更新', '', 1, '', 0, 99, 32, 0, 0)
		");
    if(empty($insert_admin_node)){      
      $db->query("DROP TABLE `$admin_node_table`");//如果插入数据失败删除该表
			return array('errormsg'=>'节点表插入数据失败！');
			
		}

		
	
	}



  return array('status'=>'success');

}
}

?>