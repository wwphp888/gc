<?php 
/*
功能：接收软件提交的订单并处理
作者：宇卓
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-07-05 18:58
*/
define('SKL_MODULE','skl');  
define('SKL_CONTROLLER','Orderinfo');  
define('SKL_ACTION','external');
require_once(dirname(__FILE__).'/index.php');

$skl_post=skl_I();
$skl_is_budan=$skl_post['is_budan'] == 1 ? true : false;
if($skl_is_budan){
	exit('<errormsg>手动补单未对接成功！</errormsg>');//接口正在对接或对接成功后去掉这行代码 <改> 
  
	//接收用户提交的补单信息，开始
	$skl_post_budan=$skl_post['budan'];
	$skl_guishu           =& $skl_post_budan['skl_guishu'];//归属某个订单表：非0数字1、2、3...代表
	$skl_money            =& $skl_post_budan['skl_money'];//订单金额
	$skl_money_actual     =& $skl_post_budan['skl_money_actual'];//实际收款金额(用户填写的)
	$skl_sysorder         =& $skl_post_budan['skl_sysorder'];//系统订单号
	$skl_weborder         =& $skl_post_budan['skl_weborder'];//网站订单号
	$skl_paytype          =& $skl_post_budan['skl_paytype'];//支付类型：alipay=支付宝,wxpay=微信,tenpay=QQ钱包
	$skl_receive_account  =& $skl_post_budan['skl_receive_account'];//收款账号(用户填写的)
	$skl_jiaoyi           =& $skl_post_budan['skl_jiaoyi'];//交易号(用户填写的)
	$skl_user             =& $skl_post_budan['skl_user'];//付款人姓名(用户填写的)
	$skl_beizhu           =& $skl_post_budan['skl_beizhu'];//订单备注(用户填写的)
	$skl_pay_time         =& $skl_post_budan['skl_pay_time'];//订单付款时间(用户填写的)
	$skl_time             =& $skl_post_budan['skl_time'];//订单创建时间(时间戳格式)
	$skl_keymd5           =& $skl_post_budan['keymd5'];//md5加密的结果 md5($skl_post_budan['skl_money_actual'].$skl_post_budan['skl_sysorder'].$skl_post_budan['skl_weborder'].$skl_post_budan['skl_paytype'].$skl_post_budan['skl_jiaoyi'].$skl_post_budan['skl_pay_time'].静态秘钥)
  //接收用户提交的补单信息，结束


}else{

	//收款啦软件提交过来的订单信息，开始
	$skl_title           =& $skl_post['title'];//支付宝订单备注信息
	$skl_jiaoyi          =& $skl_post['order'];//交易号
	$skl_money_actual    =& $skl_post['money'];//实际收款金额
	$skl_paytype         =& $skl_post['type'];//支付类型：alipay=支付宝,wxpay=微信,tenpay=QQ钱包
	$skl_receive_account =& $skl_post['account'];//收款账号
	$skl_user            =& $skl_post['user'];//付款人姓名
	$skl_pay_time        =& $skl_post['time'];//付款时间
	$skl_keymd5          =& $skl_post['keymd5'];//动态秘钥(备注订单号+交易号+付款金额+付款时间+支付类型+收款账号+静态秘钥合并后md5加密的结果)
	//收款啦软件提交过来的订单信息，结束

}


//验证签名，收款啦软件填写的通讯秘钥
if(!$shoukuanla->_checkKey($shoukuanla->cfg_sign,$skl_post)){ exit('<errortype>signError</errortype><errormsg>秘钥错误</errormsg>'); }

//获取对应的系统订单号、网站订单号、uid、用户名...
$skl_weborder_info=$shoukuanla->_getWebOrder($skl_post);    

if(empty($skl_weborder_info)){ exit('<errortype>orderEmpty</errortype><errormsg>未找到对应的订单</errormsg>'); }

$skl_guishu     =$skl_weborder_info['skl_guishu'];//归属某个订单表，非0数字1、2、3...代表
$skl_sysorder   =$skl_weborder_info['skl_sysorder']; //系统订单号
$skl_weborder   =$skl_weborder_info['skl_weborder']; //网站订单号，特别注意：如果提交订单时没有填写网站订单号则返回空
$skl_uid        =$skl_weborder_info['skl_uid'];//uid，特别注意：如果提交订单时没有填写uid则返回空
$skl_username   =$skl_weborder_info['skl_username'];//用户名，特别注意：如果提交订单时没有填写用户名则返回空


//获取网站订单号后自己写处理代码，开始

/*.......................................................................
.........................................................................
.................网站订单处理代码请写到这个区域..........................
.........................................................................
.........................................................................
*/

$skl_notify_status=false;//如果订单处理成功，将该变量值改为true，失败改为false; <改>
//获取网站订单号后自己写处理代码，结束




//网站订单处理成功后才执行的代码，开始
if($skl_notify_status){
	 
	 //修改系统订单状态
   if($shoukuanla->_upOrder($skl_sysorder,$skl_money_actual,$skl_jiaoyi,$skl_user,$skl_beizhu,$skl_pay_time,$skl_post['is_budan'],$skl_receive_account) < 1){  
		 exit('<errortype>upSysOrderError</errortype><errormsg>系统订单状态修改失败</errormsg>'); 
	 }
   $shoukuanla->_echoInfo('success',$skl_uid,$skl_username,$skl_weborder,$skl_sysorder,$skl_guishu);//输出订单信息给收款啦软件

	 if($skl_is_budan){		 

		//记录管理员操作日志，开始
    $shoukuanla->_newCfg('cfg_sys_admin_log_table_name',SKL_MODULE_NAME_2); //加载模块2配置
		require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
		$skl_add_log=new AddLog($shoukuanla);		
		$skl_log_arr['admin_log_user']    =$skl_post_budan['admin_name'];
		$skl_log_arr['admin_log_type']    =3;
		$skl_log_arr['admin_log_explain'] ='手动补单: 1条 UID:'.$skl_uid.' 用户名:'.$skl_username.' 系统订单号:'.$skl_sysorder.' 网站订单号:'.$skl_weborder.' 实际收款金额:'.$skl_money_actual.' 交易号:'.$skl_jiaoyi;
		$skl_add_log->index($skl_log_arr);
		//记录管理员操作日志，结束

	 
	 }
}
//网站订单处理成功后才执行的代码，结束


/*
//如果需要通知其他服务器可以启用下边的代码
require_once(SKL_ClASS_PATH.'ShoukuanlaCurl.class.php');
$skl_curl=new ShoukuanlaCurl();
$skl_curl->post('请求地址',post参数(a=1&b=2或array('a'=>1,'b'=>2)));
*/
?>