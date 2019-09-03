<?php 
/*
声明：cfg_sys_开头的系统配置不能修改否则会出现意想不到的错误
修改时间：2019-01-24 17:00
*/
require(SKL_ROOT_PATH.'version.php');
return array(

'cfg_sys_order_table_name'  =>'shoukuanla_order_'.$skl_db_version,//订单表名(不加表前缀)*
'cfg_sys_cfg_table_name'    =>'shoukuanla_cfg_'.$skl_db_version,//配置信息表(不加表前缀)*
'cfg_sys_last_table_name'   =>'shoukuanla_last_'.$skl_db_version,//记录最后的充值信息表(不加表前缀)*
'cfg_sys_qrcode_table_name' =>'shoukuanla_qrcode_'.$skl_db_version,//收款码表(不加表前缀)*
'cfg_sys_dir_table_name'    =>'shoukuanla_dir_'.$skl_db_version,//收款码目录表(不加表前缀)*

);

?>