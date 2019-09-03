<?php 
/*

//提交网站订单到Shoukuanla::dopay()函数处理后跳转到付款页面
define('SKL_MODULE','skl');  
define('SKL_CONTROLLER','Shoukuanla');  
define('SKL_ACTION','external');
require_once(dirname(__FILE__).'/index.php');//引入路径如果不相同需要自己修改

$skl_post           =skl_I();
$paytype            =$skl_post['paytype'];
$price              =$skl_post['price'];
$member_name        =$skl_post['username'];
$is_mobile          =$skl_post['is_mobile'];
$member_uid         =$shoukuanla->_getUid($member_name);//根据用户名获取uid
$out_trade_no       =date('YmdHis').rand(100,999);//生成网站订单号

if($price < 0.01){   skl_error('金额不能低于0.01元');  }


$cfg_order_table_name=$shoukuanla->cfg_order_table_name[1];
if($paytype == SKL_PAYTYPE_ALIPAY){
   $payment_type=1;
}else if($paytype == SKL_PAYTYPE_WXPAY){
   $payment_type=2;
}else if($paytype == SKL_PAYTYPE_TENPAY){
   $payment_type=5;
}else{
   skl_error('支付类型错误！');
}

$now_date=date('Y-m-d H:i:s');
$payment_name=$shoukuanla->cfg_paytype_name[$paytype].'充值(收款啦)';
$weborder_data=array(
	 'bill_no'     =>$out_trade_no,
	 'member_id'   =>$member_uid,
	 'name'        =>$member_name,
	 'money'       =>$price,
	 'payment_type'=>$payment_type,
	 'account'     =>'',
	 'payment_desc'=>$payment_name,
	 'status'      =>1,
	 'hk_at'       =>$now_date,
	 'created_at'  =>$now_date,
);

//网站订单入库
if($shoukuanla->db->table("`@#_$cfg_order_table_name`")->add($weborder_data) < 1){
   skl_error('网站订单入库失败！');
}

$return_url=$is_mobile == '1' ? '/m/recharge' : '/member/userCenter';

$shoukuanla->uid=$member_uid;//会员uid：特别注意:uid必须是唯一不能有重复,不能使用外部传参(例如get、post ...)否则会存在安全隐患
$shoukuanla->publicPost=array(
'guishu'          =>1,//归属某个订单表：如果有多个订单表时用非0数字1、2、3...代表 *
'username'        =>$member_name,//会员用户名：如果会员uid获取不到可以填用户名，特别注意：用户名必须是唯一不能有重复
'paytype'         =>$paytype,//支付类型：alipay=支付宝,wxpay=微信,tenpay=QQ钱包 *
'out_trade_no'    =>$out_trade_no,//网站订单号：如果是已经生成网站订单号必须填上
'price'           =>$price,//订单金额 *  
'return_url'      =>$return_url,//付款成功后返回地址：收款啦管理后台设置的返回地址为空时这里的设置才会生效
'is_mobile'       =>$is_mobile,//客户端：1=手机端用户，0=自动识别
'set_post_alias'  =>array(),//修改POST参数别名：正确格式array('out_trade_no'=>'xxx','price'='xxx')

);
$shoukuanla->dopay();

*/

?>