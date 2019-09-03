<?php
/*
功能：支付页面，选择金额支付类型
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-06-28 18:08
*/
class Index extends ShoukuanlaBase{

function __construct(){  

	$this->_newCfg('cfg_paytype_name','','',SKL_ROOT_PATH.'config.php');//加载对接配置  
  $this->_newDbCfg('`cfg_is_open_pay`,`cfg_paytype_order`,`cfg_money_group`,`cfg_is_other_money`');//加载用户配置
}

//支付页面,只能传用户名不能传uid
public function index(){ 

	if($this->cfg_is_open_pay != '1'){ skl_error('支付页面已关闭，如果需要启用请到后台开启'); }

	if(empty($this->publicPost)){ 
		$post=skl_I();
	}else{
		$post=& $this->publicPost;
	}

	require_once(SKL_G_CONTROLLER_PATH.'PostAlias.class.php');
  $alias_obj=new PostAlias();

	//有命令才修改post参数别名
	$set_post_alias=& $post[$alias_obj->set_post_alias];
	if(!empty($set_post_alias)){
		$alias_obj->_setPostAlias($set_post_alias);  
	}

	$poPaytype   =$post[$alias_obj->post_alias['paytype']];//支付类型
  //$poTitle     =$post[$alias_obj->post_alias['out_trade_no']];//网站订单号
  $poMoney     =$post[$alias_obj->post_alias['price']];//订单金额
	$poUsername  =$post[$alias_obj->post_alias['username']];//用户名
	//$poGuishu    =$post[$alias_obj->post_alias['guishu']];//归属某个订单表
	//$poReturnUrl =$post[$alias_obj->post_alias['return_url']];//付款成功后返回地址
	$poIsMobile  =$post[$alias_obj->post_alias['is_mobile']];//是否手机请求
	$skl_action_url  =urldecode($post['skl_action_url']);//订单提交地址
	if($skl_action_url == ''){ $skl_action_url=skl_U('Shoukuanla/dopay'); }


  //是否存在指定金额组中
	$money_float=(float)$poMoney;
	if($money_float > 0){
	   $in_money_group=in_array($money_float,$this->cfg_money_group) ? $money_float : 'other';
	}else{
	   $in_money_group=& $this->cfg_money_group[0];
	}


	$paytype      =array_search($poPaytype,$alias_obj->post_alias['paytype_value']);//当前的支付类型	
	$paytype_start=array_keys($this->cfg_paytype_order,1);//检测支付类型开关是否开启
	$paytype_start_empty=empty($paytype_start)? true : false;
	if(!empty($paytype)){
	   $in_paytype_start=in_array($paytype,$paytype_start) ? $paytype : $paytype_start[0] ;
	}else{
	   $in_paytype_start=& $paytype_start[0];
	}

  $alias_not=array('username','paytype_value');

	//强行显示手机页面
	$mobile_view_path=SKL_CONTROLLER_VIEW_PATH.'index_mobile.php';
	if($poIsMobile == '1'){
		 $viewPath=& $mobile_view_path;
	}else{

		//自动识别手机请求
		require_once(SKL_G_FUNCTIONS_PATH.'skl_isMobile.php');
		if(skl_isMobile()){
			 $viewPath=& $mobile_view_path;
		}else{
			 $viewPath=SKL_CONTROLLER_VIEW_PATH.'index.php';
		}
	}
	require_once($viewPath);
 

}


}
?>