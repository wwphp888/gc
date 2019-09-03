<?php
/*
功能：获取网站订单信息、处理订单
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-13 18:08

*/
class Orderinfo extends ShoukuanlaBase{

function __construct(){  

  $this->_newDb();  
	$this->_newCfg();//加载当前模块配置
	$this->_newCfg('','','',SKL_ROOT_PATH.'config.php');//加载对接配置  
  $this->_newDbCfg();//加载用户配置

}


//获取对应的系统订单号、网站订单号、uid、用户名...
public function _getWebOrder(& $po=array()){
if(empty($po)){
  $post=skl_I($_POST);
}else{
  $post=$po;
}

//判断手动补单
$is_budan=$post['is_budan'] == 1 ? true : false;
if($is_budan){
	 $post_budan      =$post['budan'];
   $pay_time        =& $post_budan['skl_pay_time'];
	 $paytype         =& $post_budan['skl_paytype'];
	 $jiaoyi          =& $post_budan['skl_jiaoyi'];
	 $money_actual    =& $post_budan['skl_money_actual'];
	 $receive_account =& $post_budan['skl_receive_account'];
	 $sysorder        =& $post_budan['skl_sysorder'];

}else{

   $pay_time        =& $post['time'];
	 $paytype         =& $post['type'];
	 $jiaoyi          =& $post['order'];
	 $money_actual    =& $post['money'];
	 $receive_account =& $post['account'];
}

require_once(SKL_G_FUNCTIONS_PATH.'skl_check_data.php');

//实际收款金额必须大于0.01	 
if($money_actual == ''){ exit('<errormsg>实际收款金额不能为空</errormsg>');  }
if(!skl_check_data($money_actual,'float',false)){ exit('<errormsg>实际收款金额格式错误！</errormsg>'); }
if($money_actual < 0.01){ exit('<errormsg>实际收款金额必须大于0.01！</errormsg>'); }

//检测交易号格式
if($jiaoyi == ''){ exit('<errormsg>交易号不能为空！</errormsg>'); }
if(!skl_check_data($jiaoyi,'integer',false)){ exit('<errormsg>交易号格式错误！</errormsg>'); }

//检测支付类型
if(!array_key_exists($paytype,$this->cfg_paytype_name)){  exit('<errormsg>支付类型错误！</errormsg>'); } 

//检测付款时间格式
if($pay_time == ''){ exit('<errormsg>付款时间不能为空！</errormsg>'); }
$payTime=strtotime($pay_time);//付款时间转时间戳
if($payTime == false){  exit('<errormsg>付款时间格式错误！</errormsg>');  }

//防止订单重复处理
$orderExists=$this->db->table("`@#_$this->cfg_sys_order_table_name`")->field("`skl_guishu`,`skl_uid`,`skl_username`,`skl_weborder`,`skl_sysorder`")->where("`skl_paytype`='$paytype' AND `skl_state`=1 AND `skl_jiaoyi`='$jiaoyi'")->find();

if(!empty($orderExists)){
	
	if($is_budan){
    exit('<errormsg>'.$jiaoyi.' 该交易号已处理过，请重新填写！</errormsg>');
	}else{
	
		echo '该订单已处理过:';
		$this->_echoInfo('success',$orderExists['skl_uid'],$orderExists['skl_username'],$orderExists['skl_weborder'],$orderExists['skl_sysorder'],$orderExists['skl_guishu']);
		exit;
	}

}

if($is_budan){

	 //检测系统订单号是否被处理过
   if($this->db->table("`@#_$this->cfg_sys_order_table_name`")->where("`skl_state`=1 AND `skl_sysorder`='$sysorder'")->getField('COUNT(`skl_sysorder`)') > 0){
	     exit('<errormsg>'.$sysorder.' 该统订单号已处理过！</errormsg>');
	 }

   $findOrder=& $post['budan'];

}else{

	//时间偏差
	if(!empty($this->cfg_deviation_value)){
		 $payTime+=$this->cfg_deviation_value;
	}

	$payTime+=10;//付款时间多加10秒防止服务器时间不准造成错误
	$smallTime=$payTime-$this->cfg_ge_time;

	$out_trade_no=$post['title'];
	$money=sprintf('%.2f',$post['money']); 


	//小数识别订单
	if(in_array($paytype,$this->cfg_is_float)){
		 
		 $findOrder=$this->db->table("`@#_$this->cfg_sys_order_table_name`")->field('*')->where("`skl_sklorder`='$money' AND `skl_paytype`='$paytype' AND `skl_state`!=1 AND `skl_receive_account`='$receive_account' AND `skl_time` BETWEEN $smallTime AND $payTime")->order("`skl_time` ASC")->find();

	}else{
		 //备注识别订单
		 $findOrder=$this->db->table("`@#_$this->cfg_sys_order_table_name`")->field('*')->where("`skl_sklorder`='$out_trade_no' AND `skl_paytype`='$paytype' AND `skl_money`=$money AND `skl_state`!=1 AND `skl_receive_account`='$receive_account' AND `skl_time` BETWEEN $smallTime AND $payTime")->order("`skl_time` ASC")->find();
	}	 


}
return $findOrder;

}



//修改订单状态公共方法
public function _upOrder($sys_order=null,$skl_money_actual=null,$skl_jiaoyi=null,$skl_user=null,$skl_beizhu=null,$skl_pay_time=null,$is_budan=false,$skl_receive_account=null){

  $beizhu_field=$skl_beizhu == '' ? '' : ",`skl_beizhu`='$skl_beizhu'" ; 
  $is_budan_field=$is_budan == 1 ? ",`skl_budan`=1" : ''; 
	$receive_account=$skl_receive_account == '' ? '' : ",`skl_receive_account`='$skl_receive_account'" ;
	$skl_pay_time=strtotime($skl_pay_time);
  
  return $this->db->table("`@#_$this->cfg_sys_order_table_name`")->where("`skl_sysorder`='$sys_order' AND `skl_state`!=1")->limit(1)->update("`skl_state`=1".$is_budan_field.",`skl_money_actual`='$skl_money_actual'".$receive_account.",`skl_jiaoyi`='$skl_jiaoyi',`skl_user`='$skl_user'".$beizhu_field.",`skl_pay_time`=$skl_pay_time,`skl_update_time`=UNIX_TIMESTAMP()");  


}


//输出订单信息给软件
public function _echoInfo($status='success',$uid=null,$username=null,$web_order=null,$sys_order=null,$guishu=1){
   
  echo "<status>$status</status><userid>$uid</userid><username>$username</username><weborder>$web_order</weborder><sysorder>$sys_order</sysorder><guishu>$guishu</guishu>";
}

//验证秘钥
public function _checkKey($sign=null,& $post=array()){

  //验证签名，收款啦软件填写的通讯秘钥
  if(empty($sign)){ $sign='www.shoukuanla.net'; }

  if($post['is_budan'] == 1){
		 //手动补单md5加密规则
		 $post_budan=& $post['budan'];
		 $md5Str=md5($post_budan['admin_name'].$post_budan['skl_money_actual'].$post_budan['skl_sysorder'].$post_budan['skl_weborder'].$post_budan['skl_paytype'].$post_budan['skl_jiaoyi'].$post_budan['skl_pay_time'].$sign);

		 $post_keymd5=$post_budan['keymd5'];
	
	}else{	
		 //md5加密规则
	   $md5Str=md5($post['title'].$post['order'].$post['money'].$post['time'].$post['type'].$post['account'].$sign);
     $post_keymd5=$post['keymd5'];
	}  

  if($post_keymd5 == $md5Str){  
		return true;  
	}else{ return false; }


}


public function external(){  }


}
?>