<?php
/*
功能：微信付款链接识别二维支付。
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-13 19:00
*/
require_once(SKL_G_CONTROLLER_PATH.'SklPublic.class.php');	
class Identify extends SklPublic{

function __construct(){  
  $this->_newDb();
}

public function index(){
  $post=skl_I($_GET,array('trim','addslashes'));
	if(empty($post['short_order']) && $post['is_write_note'] == '1'){  skl_error('参数不全！'); }

  $this->_newCfg('cfg_sys_order_table_name');//加载当前模块配置

	$sys_order=$post['sys_order'];
  //先查出订单有效时间
  $chaOrderInfo=$this->db->table("`@#_$this->cfg_sys_order_table_name`")->field("`skl_time`")->where("`skl_sysorder`='$sys_order' AND `skl_state`!=1")->find();

	if(empty($chaOrderInfo)){ skl_error('该付款链接已失效'); }

  $dbTimestamp=$this->_nowTime();
	$this->_newDbCfg('`cfg_ge_time`');//加载用户配置
	$post['ge_time']=$this->cfg_ge_time-($dbTimestamp-$chaOrderInfo['skl_time']);	

  $qrcodePath=$post['qrcode_path']."&cacheTime=$dbTimestamp";

  require_once(SKL_CONTROLLER_VIEW_PATH.'index.php');
 
}


}
?>