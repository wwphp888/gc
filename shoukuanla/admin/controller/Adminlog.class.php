<?php
/*
功能：管理员操作日志
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2018-12-28 18:04
*/
require_once(SKL_CONTROLLER_PATH.'AdminBase.class.php');
class Adminlog extends AdminBase{

function __construct(){  
  
  parent::__construct(); 

}

//顶部菜单
public function index(){    

  require_once(SKL_CONTROLLER_VIEW_PATH.'index.php');
}

//删除数据
public function deldata(){  

	require_once(SKL_G_CONTROLLER_PATH.'Deldata.class.php');
  $Deldata=new Deldata($this);
  $Deldata->index();

}

}
?>