<?php 
/*
声明：此文件不能用记事本修改，必须使用UTF-8编码不带BOM否则会出错，请使用Dreamweaver、EditPlus、Notepad++ ...等专业的工具。
修改时间：2019-06-07 17:00
*/
return array(
'cfg_order_table_name'      =>array(1=>'dd_wxpay_log'),//订单表名(不加表前缀),如果有多个订单表在数组里添加对应的键值array(1=>'表名1',2=>'表名2'，...)*

'cfg_member_table'          =>array(
		1=>array(
			'table_name'          =>'dd_user',//会员表名(不加表前缀)*
			'uid_field'           =>'id',//会员UID字段(该字段不能有空值并且不能有重复)*
      'user_field'          =>'login_name',//会员用户名字段(该字段不能有空值并且不能有重复)*
		),
    //如果有多个会员表在数组里添加对应的键值
		/*2=>array(
			'table_name'          =>'',
			'uid_field'           =>'',
      'user_field'          =>'',
		),//...	*/	
),

'cfg_is_check_user'         =>'1',//是否检测uid或用户名是否存在，开启检测可以防止用户输入错误的用户名导致充值失败，0=关闭 1=开启
'cfg_paytype_name'          =>array(SKL_PAYTYPE_ALIPAY=>'支付宝',SKL_PAYTYPE_WXPAY=>'微信',SKL_PAYTYPE_TENPAY=>'QQ钱包'),
'cfg_notify_url'            =>'/shoukuanla/notify.php',//订单通知地址，推荐使用相对路径,例如/shoukuanla/notify.php *
'cfg_return_url_cs'         =>array('weborder'=>'order'),//付款成功后返回地址后边带的GET参数别名，/shoukuanla/return.php?order=网站订单号，注意：只能改数组的值，键名不能改

);

?>