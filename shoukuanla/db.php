<?php 
/*
引入网站数据库配置文件然后填写变量，不推荐写死以免日后网站搬家或修改数据库密码造成麻烦
声明：此文件不能用记事本修改，必须使用UTF-8编码不带BOM否则会出错，请使用Dreamweaver、EditPlus、Notepad++ ...等专业的工具。
修改时间：2019-06-26 17:00
*/
//$db_cfg=require(SKL_ROOT_PATH.'../Application/Common/Conf/config.php');
//if(empty($db_cfg['DB_CHARSET'])){ $db_cfg['DB_CHARSET']='utf8'; }

return array(
//数据库配置信息
'cfg_DB_HOST'        =>'192.168.1.110', //服务器地址*
'cfg_DB_PORT'        =>'3306',//端口号*
'cfg_DB_NAME'        =>'213', // 数据库名*
'cfg_DB_USER'        =>'www', // 用户名*
'cfg_DB_PWD'         =>'123456', // 密码*
'cfg_DB_CHARSET'     =>'utf8',//编码*
'cfg_DB_PREFIX'      =>'', // 数据库表前缀*
);
?>