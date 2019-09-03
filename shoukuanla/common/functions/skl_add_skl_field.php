<?php 
//添加表、字段
//返回值：数组
//成功 array('status'=>'success')   失败：array('errormsg'=>'错误信息！')
//修改时间：2019-07-17 21:07
function skl_add_skl_field(& $obj=null){
	//检测需要的变量值
  if($obj->cfg_sys_order_table_name == ''){ skl_error('cfg_sys_order_table_name不能为空'); }
	if($obj->cfg_sys_last_table_name == ''){ skl_error('cfg_sys_last_table_name不能为空'); }
	if($obj->cfg_sys_cfg_table_name == ''){ skl_error('cfg_sys_cfg_table_name不能为空'); }
	if($obj->cfg_sys_qrcode_table_name == ''){ skl_error('cfg_sys_qrcode_table_name不能为空'); }
	if($obj->cfg_sys_dir_table_name == ''){ skl_error('cfg_sys_dir_table_name不能为空'); }

  $sklOrderTable=$obj->db->utable($obj->cfg_sys_order_table_name);
  $sklLastTable=$obj->db->utable($obj->cfg_sys_last_table_name);
	$sklCfgTable=$obj->db->utable($obj->cfg_sys_cfg_table_name);
	$sklQrcodeTable=$obj->db->utable($obj->cfg_sys_qrcode_table_name);
	$sklDirTable=$obj->db->utable($obj->cfg_sys_dir_table_name);

  $tableNames=$obj->db->query("SELECT `table_name` FROM information_schema.tables WHERE `table_name` = '$sklOrderTable' OR `table_name` = '$sklLastTable' OR `table_name` = '$sklCfgTable' OR `table_name` = '$sklQrcodeTable' OR `table_name` = '$sklDirTable'");  

  if($tableNames){

    while($tableNameArr=$tableNames->fetch_assoc()){	
	       if(!$sklOrderExists && $tableNameArr['table_name'] == $sklOrderTable ){  $sklOrderExists=true ;} 
         if(!$sklLastExists && $tableNameArr['table_name'] == $sklLastTable ){  $sklLastExists=true ;} 
				 if(!$sklCfgExists && $tableNameArr['table_name'] == $sklCfgTable ){  $sklCfgExists=true ;}
				 if(!$sklQrcodeExists && $tableNameArr['table_name'] == $sklQrcodeTable ){  $sklQrcodeExists=true ;}
				 if(!$sklDirExists && $tableNameArr['table_name'] == $sklDirTable ){  $sklDirExists=true ;}

	  }
	  $tableNames->free();
  }

	
  //添加shoukuanla_cfg订单表
	if(!$sklCfgExists){

    //字段默认值，开始
		$is_float_default='["'.SKL_PAYTYPE_ALIPAY.'","'.SKL_PAYTYPE_WXPAY.'","'.SKL_PAYTYPE_TENPAY.'"]';
		$paytype_order_default=json_encode(array(SKL_PAYTYPE_ALIPAY=>1,SKL_PAYTYPE_WXPAY=>1,SKL_PAYTYPE_TENPAY=>1));
		$users_toggle_default=json_encode(array(SKL_PAYTYPE_ALIPAY=>array('toggle'=>1),SKL_PAYTYPE_WXPAY=>array('toggle'=>1),SKL_PAYTYPE_TENPAY=>array('toggle'=>1)));
		$qrcode_path_default=json_encode(array(
			SKL_PAYTYPE_ALIPAY=>array(1=>array('open'=>1,'user'=>'','path'=>SKL_PAYTYPE_ALIPAY.'/user1','user'=>'yueruiwupay@163.com')),
			SKL_PAYTYPE_WXPAY=>array(1=>array('open'=>1,'user'=>'','path'=>SKL_PAYTYPE_WXPAY.'/user1','user'=>'wx659915080')),
			SKL_PAYTYPE_TENPAY=>array(1=>array('open'=>1,'user'=>'','path'=>SKL_PAYTYPE_TENPAY.'/user1','user'=>'2323027019')),
		));
		$users_toggle_next_default=json_encode(array(
			SKL_PAYTYPE_ALIPAY  =>array('next'=>0),
			SKL_PAYTYPE_WXPAY   =>array('next'=>0),
			SKL_PAYTYPE_TENPAY  =>array('next'=>0),
		));
		$is_remittance_default=json_encode(array(SKL_PAYTYPE_ALIPAY,SKL_PAYTYPE_TENPAY));
		$is_hide_search_default=json_encode(array('Orders'=>0,'Adminlog'=>0));
		//字段默认值，结束

		$createSklCfg=$obj->db->query("CREATE TABLE IF NOT EXISTS `$sklCfgTable` (
		`cfg_id` tinyint(2) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'ID',
		`cfg_check_time_count` tinyint(2) NOT NULL DEFAULT '0' COMMENT '检查时间总数',
    `cfg_check_time_next` bigint(16) NOT NULL DEFAULT '0' COMMENT '下次检查时间',
		`cfg_deviation_value` int(10) NOT NULL DEFAULT '0' COMMENT '时间偏差值',
		`cfg_clear_value` int(10) NOT NULL DEFAULT '10000' COMMENT '自动清理数据上限值',
		`cfg_is_hide_search` varchar(200) NOT NULL DEFAULT '$is_hide_search_default' COMMENT '是否隐藏搜索',
		`cfg_sign` varchar(200) NOT NULL DEFAULT '' COMMENT '静态秘钥',
		`cfg_ge_time` int(10) NOT NULL DEFAULT '480' COMMENT '间隔时间秒',
		`cfg_money_group` varchar(100) NOT NULL DEFAULT '[50,150,300,500,1000]' COMMENT '指定金额组',
		`cfg_return_url` varchar(300) NOT NULL DEFAULT '/' COMMENT '付款成功后返回地址',
		`cfg_return_url_m` varchar(300) NOT NULL DEFAULT '/' COMMENT '付款成功后返回地址(手机端)',
		`cfg_is_open_pay` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否启用自带的支付页面',
		`cfg_is_other_money` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否开启其他金额充值',
		`cfg_is_float` varchar(50) NOT NULL DEFAULT '$is_float_default' COMMENT '使用小数识别订单的支付类型',
		`cfg_is_remittance` varchar(50) NOT NULL DEFAULT '$is_remittance_default' COMMENT '是否开启手动转账',
		`cfg_is_write_note` varchar(50) NOT NULL DEFAULT '' COMMENT '强制使用任意金额输入金额或备注',
		`cfg_paytype_order` varchar(50) NOT NULL DEFAULT '$paytype_order_default' COMMENT '支付开关和排序',
		`cfg_is_open_users` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否启用多账号收款',
		`cfg_users_toggle` varchar(100) NOT NULL DEFAULT '$users_toggle_default' COMMENT '多账号收款切换方式 1=轮换 2=随机',
		`cfg_users_toggle_next` varchar(100) NOT NULL DEFAULT '$users_toggle_next_default' COMMENT '下一个收款账号',
		`cfg_qrcode_path` text NOT NULL COMMENT '收款码目录',
    `cfg_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '修改时间'
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配置表';");
		
		if(!$createSklCfg){  return array('errormsg'=>'配置表创建失败！');  }
    
		//插入默认值
		$cfgData=array(
			'cfg_id'                 =>1,
			'cfg_time'               =>'UNIX_TIMESTAMP()',
			'cfg_qrcode_path'        =>"'$qrcode_path_default'",
		);

		$insertSklCfg=$obj->db->table("`$sklCfgTable`")->add($cfgData,false);
    if(empty($insertSklCfg)){ 
			$obj->db->query("DROP TABLE `$sklCfgTable`");//如果插入数据失败删除该表
			return array('errormsg'=>'配置表插入数据失败！'); 
		}
		
	}



  //添加shoukuanla_order订单表
	if(!$sklOrderExists){
		$createSklOrder=$obj->db->query("CREATE TABLE IF NOT EXISTS `$sklOrderTable` (
		`skl_id` int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'ID',
		`skl_guishu` tinyint(2) NOT NULL DEFAULT '1' COMMENT '归属某个订单表',
		`skl_uid` int(10) NOT NULL DEFAULT '0' COMMENT 'UID',
		`skl_username` char(50) NOT NULL DEFAULT '' COMMENT '用户名',INDEX(`skl_username`),
		`skl_money` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
		`skl_money_actual` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际收款金额',
		`skl_sysorder` char(40) UNIQUE NOT NULL DEFAULT '' COMMENT '系统订单号',
		`skl_weborder` char(50) NOT NULL DEFAULT '' COMMENT '网站订单号',INDEX(`skl_weborder`),
		`skl_paytype` char(10) NOT NULL DEFAULT '' COMMENT '支付类型',		
		`skl_state` tinyint(2) NOT NULL DEFAULT '0' COMMENT '订单状态',
		`skl_budan` tinyint(2) NOT NULL DEFAULT '0' COMMENT '手动补单',
		`skl_sklorder` char(50) NOT NULL DEFAULT '' COMMENT '订单识别码',INDEX(`skl_sklorder`),
		`skl_receive_account` char(50) NOT NULL DEFAULT '' COMMENT '收款账号',INDEX(`skl_receive_account`),
		`skl_receive_dir` varchar(200) NOT NULL DEFAULT '' COMMENT '收款码目录',
		`skl_jiaoyi` char(50) NOT NULL DEFAULT '' COMMENT '交易号',INDEX(`skl_jiaoyi`),
		`skl_user` char(50) NOT NULL DEFAULT '' COMMENT '付款人姓名',		
		`skl_beizhu` varchar(255) NOT NULL DEFAULT '' COMMENT '订单备注',
		`skl_pay_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '订单付款时间',
		`skl_update_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '订单处理时间',
		`skl_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '订单创建时间'
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='充值记录';");
		
		if(!$createSklOrder){  return array('errormsg'=>'充值订单表创建失败！');  }
		
	}

	

  //查询shoukuanla_last表是否存在
	if(!$sklLastExists){
		 //添加shoukuanla_last表
		$createSklLast=$obj->db->query("CREATE TABLE IF NOT EXISTS `$sklLastTable` (
	`last_id` tinyint(3) unsigned PRIMARY KEY AUTO_INCREMENT NOT NULL COMMENT 'ID',
	`last_paytype` char(10) NOT NULL DEFAULT '' COMMENT '支付类型',
	`last_title` char(30) NOT NULL DEFAULT '' COMMENT '备注',
	`last_rechargetime` bigint(16) NOT NULL DEFAULT '0' COMMENT '充值时间'  
) ENGINE=MyISAM COMMENT='充值记录';
	 ");
		if(!$createSklLast){  return array('errormsg'=>'充值记录表创建失败！'); }

		$insertIsOk=$obj->db->table("`$sklLastTable`")->add("(`last_id`, `last_paytype`, `last_title`, `last_rechargetime`) VALUES (NULL, '".SKL_PAYTYPE_ALIPAY."', '10.01', UNIX_TIMESTAMP()),(NULL, '".SKL_PAYTYPE_WXPAY."', '10.01', UNIX_TIMESTAMP()),(NULL, '".SKL_PAYTYPE_TENPAY."', '10.01', UNIX_TIMESTAMP())");

		if(empty($insertIsOk)){
			$obj->db->query("DROP TABLE `$sklLastTable`");//如果插入数据失败删除该表
			return array('errormsg'=>'充值记录表插入数据失败！'); 
		}
		
	}



	 //添加shoukuanla_dir收款码目录表
	if(!$sklDirExists){
		$createSklDir=$obj->db->query("CREATE TABLE IF NOT EXISTS `$sklDirTable` (
		`dir_id` smallint(6) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'ID',
		`dir_name` char(50) NOT NULL DEFAULT '' COMMENT '目录名称',
    `dir_shangji` smallint(6) NOT NULL DEFAULT '0' COMMENT '上级目录',
		`dir_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '最后修改时间'
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收款码目录';");
		
		if(!$createSklDir){  return array('errormsg'=>'收款码目录表创建失败！');  }

		//插入默认值
		$insertSklDir=$obj->db->table("`$sklDirTable`")->add("(`dir_id`,`dir_name`,`dir_shangji`,`dir_time`) VALUES 
		(1, '".SKL_PAYTYPE_ALIPAY."', 0, UNIX_TIMESTAMP()),
		(2, '".SKL_PAYTYPE_WXPAY."', 0, UNIX_TIMESTAMP()),
		(3, '".SKL_PAYTYPE_TENPAY."', 0, UNIX_TIMESTAMP()),
		(4, 'user1', 1, UNIX_TIMESTAMP()),
		(5, 'user1', 2, UNIX_TIMESTAMP()),
		(6, 'user1', 3, UNIX_TIMESTAMP()),
		(7, '50', 4, UNIX_TIMESTAMP()),
		(8, '150', 4, UNIX_TIMESTAMP()),
		(9, '300', 4, UNIX_TIMESTAMP()),
		(10, '500', 4, UNIX_TIMESTAMP()),
		(11, '1000', 4, UNIX_TIMESTAMP()),
		(12, '50', 5, UNIX_TIMESTAMP()),
		(13, '150', 5, UNIX_TIMESTAMP()),
		(14, '300', 5, UNIX_TIMESTAMP()),
		(15, '500', 5, UNIX_TIMESTAMP()),
		(16, '1000', 5, UNIX_TIMESTAMP()),
		(17, '50', 6, UNIX_TIMESTAMP()),
		(18, '150', 6, UNIX_TIMESTAMP()),
		(19, '300', 6, UNIX_TIMESTAMP()),
		(20, '500', 6, UNIX_TIMESTAMP()),
		(21, '1000', 6, UNIX_TIMESTAMP())");
    if(empty($insertSklDir)){
			$obj->db->query("DROP TABLE `$sklDirTable`");//如果插入数据失败删除该表
			return array('errormsg'=>'收款码目录表插入数据失败！');  
		}
		
	}


	
  //添加shoukuanla_qrcode收款码表
	if(!$sklQrcodeExists){
		$createSklQrcode=$obj->db->query("CREATE TABLE IF NOT EXISTS `$sklQrcodeTable` (
		`qrcode_id` smallint(6) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'ID',
		`qrcode_dir` smallint(6) NOT NULL DEFAULT '0' COMMENT '归属目录ID',
		`qrcode_name` char(50) NOT NULL DEFAULT '' COMMENT '文件名称',
		`qrcode_type` char(30) NOT NULL DEFAULT '' COMMENT '文件类型',
    `qrcode_data` mediumblob NOT NULL COMMENT '二进制数据',
		`qrcode_time` bigint(16) NOT NULL DEFAULT '0' COMMENT '最后修改时间'
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收款码';");
		
		if(!$createSklQrcode){  return array('errormsg'=>'收款码表创建失败！');  }

		//插入默认值
		$insertSklQrcodeAli=$obj->db->table("`$sklQrcodeTable`")->add("(`qrcode_dir`,`qrcode_name`,`qrcode_type`,`qrcode_data`,`qrcode_time`) VALUES (7,'50.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/alipay/50.01.jpg'))."',UNIX_TIMESTAMP()),
		(8,'150.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/alipay/150.01.jpg'))."',UNIX_TIMESTAMP()),
		(9,'300.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/alipay/300.01.jpg'))."',UNIX_TIMESTAMP()),
		(10,'500.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/alipay/500.01.jpg'))."',UNIX_TIMESTAMP()),
		(11,'1000.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/alipay/1000.01.jpg'))."',UNIX_TIMESTAMP()),
		(4,'alipay.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/alipay/alipay.jpg'))."',UNIX_TIMESTAMP())");

		$insertSklQrcodeWx=$obj->db->table("`$sklQrcodeTable`")->add("(`qrcode_dir`,`qrcode_name`,`qrcode_type`,`qrcode_data`,`qrcode_time`) VALUES (12,'50.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/wxpay/50.01.jpg'))."',UNIX_TIMESTAMP()),
		(13,'150.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/wxpay/150.01.jpg'))."',UNIX_TIMESTAMP()),
		(14,'300.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/wxpay/300.01.jpg'))."',UNIX_TIMESTAMP()),
		(15,'500.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/wxpay/500.01.jpg'))."',UNIX_TIMESTAMP()),
		(16,'1000.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/wxpay/1000.01.jpg'))."',UNIX_TIMESTAMP()),
		(5,'wxpay.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/wxpay/wxpay.jpg'))."',UNIX_TIMESTAMP())");

		$insertSklQrcodeTen=$obj->db->table("`$sklQrcodeTable`")->add("(`qrcode_dir`,`qrcode_name`,`qrcode_type`,`qrcode_data`,`qrcode_time`) VALUES (17,'50.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/tenpay/50.01.jpg'))."',UNIX_TIMESTAMP()),
    (18,'150.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/tenpay/150.01.jpg'))."',UNIX_TIMESTAMP()),
    (19,'300.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/tenpay/300.01.jpg'))."',UNIX_TIMESTAMP()),
    (20,'500.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/tenpay/500.01.jpg'))."',UNIX_TIMESTAMP()),
    (21,'1000.01.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/tenpay/1000.01.jpg'))."',UNIX_TIMESTAMP()),
    (6,'tenpay.jpg','image/jpeg','".addslashes(file_get_contents(SKL_ROOT_PATH.'qrcode/tenpay/tenpay.jpg'))."',UNIX_TIMESTAMP())");

    if(empty($insertSklQrcodeAli) || empty($insertSklQrcodeWx) || empty($insertSklQrcodeTen)){
			$obj->db->query("DROP TABLE `$sklQrcodeTable`");//如果插入数据失败删除该表
			return array('errormsg'=>'收款码表插入数据失败！');  
		}
		
	}


	return array('status'=>'success');

}

?>