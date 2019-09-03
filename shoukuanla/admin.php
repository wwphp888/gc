<?php 
/*
功能：后台模块入口,如果需要修改后台登录地址可以修改该文件名
作者：宇卓
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-07-04 18:58
*/
define('SKL_IS_ADMIN',true);
if(empty($_GET['m'])){ define('SKL_MODULE','admin'); }
if(empty($_GET['c'])){ define('SKL_CONTROLLER','Login'); }

require_once(dirname(__FILE__).'/index.php');

?>