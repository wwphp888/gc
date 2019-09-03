<?php
//修改时间：2019-07-18 17:00

/*
//收款啦配置，开始，官网www.shoukuanla.net
//提交网站订单到Shoukuanla::dopay()函数处理后跳转到付款页面
define('SKL_MODULE','skl');  
define('SKL_CONTROLLER','Shoukuanla');  
define('SKL_ACTION','external');
require_once(dirname(__FILE__).'/../shoukuanla/index.php');//引入路径如果不相同需要自己修改
$skl_uid='';//会员uid：特别注意:uid必须是唯一不能有重复,不能接收外部传参(例如get、post ...)否则会存在安全隐患
$skl_username='';//会员用户名：如果会员uid获取不到可以填用户名，特别注意：用户名必须是唯一不能有重复

$shoukuanla->publicPost=array(
'guishu'          =>1,//归属某个订单表：如果有多个订单表时用非0数字1、2、3...代表 *
'encode_uid'      =>skl_authcode($skl_uid,'encode',$shoukuanla->cfg_sign),
'encode_username' =>skl_authcode($skl_username,'encode',$shoukuanla->cfg_sign),
'paytype'         =>'alipay',//支付类型：alipay=支付宝,wxpay=微信,tenpay=QQ钱包 *
'out_trade_no'    =>'',//网站订单号：如果是已经生成网站订单号必须填上
'price'           =>'30.00',//订单金额 *  
'return_url'      =>urlencode('http://'.$_SERVER['HTTP_HOST']),//付款成功后返回地址：收款啦订单管理系统设置的返回地址为空时这里的设置才会生效
'is_mobile'       =>'0',//客户端：1=手机端用户，0=自动识别
//'set_post_alias'  =>array(),//修改POST参数别名：正确格式array('out_trade_no'=>'xxx','price'='xxx')

);
$shoukuanla->dopay();exit;
//收款啦配置，结束
*/





/*
//收款啦配置，开始，官网www.shoukuanla.net
//提交网站订单到dopay()函数处理后跳转到付款页面(外部传参方式，例如get、post ...)
define('SKL_MODULE','skl');  
define('SKL_CONTROLLER','External');  
define('SKL_ACTION','index');
require_once(dirname(__FILE__).'/../shoukuanla/index.php');//引入路径如果不相同需要自己修改
require_once(SKL_SYS_FUNCTIONS_PATH.'skl_authcode.php');
$shoukuanla->_newDbCfg('`cfg_sign`');

$skl_uid         ='';//会员uid：特别注意:uid必须是唯一不能有重复,不能接收外部传参(例如get、post ...)否则会存在安全隐患
$skl_username    ='';//会员用户名：如果会员uid获取不到可以填用户名，特别注意：用户名必须是唯一不能有重复
$guishu          =1;//归属某个订单表：如果有多个订单表时用非0数字1、2、3...代表 *
$encode_uid      =skl_authcode($skl_uid,'encode',$shoukuanla->cfg_sign);
$encode_username =skl_authcode($skl_username,'encode',$shoukuanla->cfg_sign);
$paytype         ='alipay';//支付类型：alipay=支付宝,wxpay=微信,tenpay=QQ钱包 *
$out_trade_no    ='';//网站订单号：如果是已经生成网站订单号必须填上
$price           ='30.00';//订单金额 *  
$return_url      =urlencode('http://'.$_SERVER['HTTP_HOST']);//付款成功后返回地址,收款啦订单管理系统设置的返回地址为空时这里的设置才会生效
$is_mobile       ='0';//客户端：1=手机端用户，0=自动识别
//$set_post_alias  ='';//修改POST参数别名：正确格式set_post_alias[out_trade_no]=xxx&set_post_alias[price]=xxx
header("location: /shoukuanla/index.php?c=shoukuanla&a=dopay&guishu=$guishu&encode_uid=$encode_uid&encode_username=$encode_username&paytype=$paytype&out_trade_no=$out_trade_no&price=$price&return_url=$return_url&is_mobile=$is_mobile&set_post_alias=$set_post_alias");
exit;
//收款啦配置，结束
*/



/*
//修改网站订单状态，给会员加钱
$cfg_order_table_name=$shoukuanla->cfg_order_table_name[1];

$cfg_member_table_info=$shoukuanla->cfg_member_table[1];
$cfg_member_table_name=$cfg_member_table_info['table_name'];
$cfg_member_uid_field=$cfg_member_table_info['uid_field'];
$cfg_member_user_field=$cfg_member_table_info['user_field'];

//查询网站订单是否存在
$weborder_exists=$shoukuanla->db->table("`@#_$cfg_order_table_name`")->field('`bill_no`')->where("`bill_no` = '$skl_weborder' AND `status` != 1")->find();
if(empty($weborder_exists)){
   exit('<errormsg>网站订单不存在</errormsg>'); 
}

//修改未付款网站订单状态
if($shoukuanla->db->table("`@#_$cfg_order_table_name`")->where("`bill_no` = '$skl_weborder' AND `status` = 0")->limit(1)->update("`status` = 1,`confirm_at` = '".date('Y-m-d H:i:s')."'") < 1){
   exit('<errormsg>网站订单状态修改失败</errormsg>'); 
}

//给会员加钱
$skl_money_actual=(float)$skl_money_actual;
if($shoukuanla->db->table("`@#_$cfg_member_table_name`")->where("`$cfg_member_user_field` = '$skl_username'")->limit(1)->update("`money` = `money`+$skl_money_actual") < 1){
   exit('<errormsg>会员加钱失败</errormsg>'); 
}
*/




/*
<!--收款啦配置，开始，官网www.shoukuanla.net--> 
<?php 
//输出选择金额样式
define('SKL_MODULE','skl');  
define('SKL_CONTROLLER','Selectmoney');  
define('SKL_ACTION','external');
require_once(dirname(__FILE__).'/../../../shoukuanla/index.php');
$shoukuanla->showmoney(string=表单中的金额字段名称(name),string=li标签样式表);


//输出选择支付类型组样式
$shoukuanla->showpaytype(array('alipay'=>'在表单中用什么值代表支付宝','wxpay'=>'在表单中用什么值代表微信','tenpay'=>'在表单中用什么值代表QQ钱包'),string=支付类型在表单中的字段名称(name),string=img标签样式表);
?>
<!--收款啦配置，结束-->
*/





/*
<!--收款啦配置，开始，官网www.shoukuanla.net--> 
<?php
//获取收款啦管理后台设置的指定金额组，返回数组遍历输出
define('SKL_MODULE','skl');  
define('SKL_CONTROLLER','Selectmoney');  
define('SKL_ACTION','external');
require_once(dirname(__FILE__).'/../../../shoukuanla/index.php');
$skl_moneyname=$shoukuanla->moneyname();

foreach($skl_moneyname as $skl_v){	
  echo $skl_v;		
}
?>
<!--收款啦配置，结束-->
*/




/*
<!--收款啦配置，开始，官网www.shoukuanla.net  style="display:none;"-->
<!--在网页中嵌入框架(支付页面)-->
<iframe src="/shoukuanla/index.php?width=900" width="900px" height="700px"  frameborder=0  />
<!--收款啦配置，结束-->
*/



//测试接收软件提交数据写入文件
//file_put_contents($_POST['type'].'-'.$_POST['order'].'.txt',var_export($_POST,true));

?>