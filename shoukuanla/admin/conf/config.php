<?php 
//声明：cfg_sys_开头的系统配置不能修改否则会出现意想不到的错误
//修改时间：2019-01-21 17:00
require(SKL_ROOT_PATH.'version.php');
return array(

'cfg_sys_admin_table_name'                 =>'shoukuanla_admin_'.$skl_db_version,//管理员用户信息表(不加表前缀)*
'cfg_sys_admin_log_table_name'             =>'shoukuanla_admin_log_'.$skl_db_version,//管理员操作日志表(不加表前缀)*
'cfg_sys_admin_role_table_name'            =>'shoukuanla_admin_role_'.$skl_db_version,//角色表(不加表前缀)*
'cfg_sys_admin_node_table_name'            =>'shoukuanla_admin_node_'.$skl_db_version,//节点表(不加表前缀)*

);

?>