<?php 
/*
功能：显示扫码支付页面
作者：宇卓
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-07-03 19:00

//升级修改
*/
require_once(SKL_G_CONTROLLER_PATH.'SklPublic.class.php');	
class Scanpay extends SklPublic{

function __construct(){  

  $this->_newDb();
}

//支付宝
public function index(){

  $post=skl_I($_POST);  
	$post['return_url']=urldecode($_REQUEST['return_url']);

  $sys_order=$post['sys_order'];
  if(empty($sys_order)){  skl_error('系统订单号不能为空！'); }
  if(empty($post['short_order']) && $post['is_write_note'] == '1'){  skl_error('参数不全！'); }	

  $this->_newCfg('cfg_sys_order_table_name');//加载当前模块配置
	$this->_newCfg('cfg_paytype_name','','',SKL_ROOT_PATH.'config.php');//加载对接配置  
	
  //先查出订单有效时间 
	$chaOrderInfo=$this->db->table("`@#_$this->cfg_sys_order_table_name`")->field("`skl_time`")->where("`skl_sysorder`='$sys_order'")->find(); 

	if(empty($chaOrderInfo)){ skl_error($sys_order.' 该订单已失效'); }

	$dbTimestamp=$this->_nowTime();
	
	$this->_newDbCfg('`cfg_ge_time`,`cfg_is_remittance`');//加载用户配置
	$post['ge_time']=$this->cfg_ge_time-($dbTimestamp-$chaOrderInfo['skl_time']);

  $qrcodePath=$post['qrcode_path']."&cacheTime=$dbTimestamp";

	//检测支付类型
	if(!array_key_exists($post['paytype'],$this->cfg_paytype_name)){   skl_error('支付类型错误！'); 	}
	
	//是否开启手动转账
  $is_remittance=in_array($post['paytype'],$this->cfg_is_remittance) ? true : false; 

	if($post['is_mobile'] == '1'){
     $template=$post['paytype'].'_mobile.php';

	}else{
	   $template=$post['paytype'].'.php';
	}

  require_once(SKL_CONTROLLER_VIEW_PATH.$template);

}


}


?>