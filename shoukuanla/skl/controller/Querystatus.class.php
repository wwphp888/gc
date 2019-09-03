<?php
/*
功能：ajax查询订单状态
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2018-12-13 19:00
*/
require_once(SKL_G_CONTROLLER_PATH.'SklPublic.class.php');
class Querystatus extends SklPublic{

function __construct(){  
  
	$this->_newDb();

}

//ajax查询订单状态
public function index(){

	$get=skl_I($_GET); 
	$sys_order=$get['sys_order'];  
	
	$this->_newCfg('cfg_sys_order_table_name');//加载当前模块配置
	
	$chaStatus=$this->db->table("`@#_$this->cfg_sys_order_table_name`")->field("`skl_state`,`skl_time`")->where("`skl_sysorder`='$sys_order'")->find();
	
	$jsonInfo=array();
	if(!empty($chaStatus)){ 
	  $jsonInfo['skl_state']=$chaStatus['skl_state'];

		//判断订单是否过期
		$this->_newDbCfg('`cfg_ge_time`');//加载用户配置
		$orderCreateTime=$chaStatus['skl_time'];
		$dbTimestamp=$this->_nowTime();
		if(($this->cfg_ge_time-($dbTimestamp-$orderCreateTime)) < 0){
		   $jsonInfo['isTimeout']='1';
		}
	
	}else{
	  $jsonInfo['isEmpty']='1';
	}

  echo json_encode($jsonInfo);
 
}


}
?>