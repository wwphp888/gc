<?php
/*
功能：实现支付宝、微信扫码自动充值
作者：宇卓
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-07-03 18:08
*/
require_once(SKL_G_CONTROLLER_PATH.'SklPublic.class.php');
class Shoukuanla extends SklPublic{

public $sessionUidKey='skl_uid';

public $uid;
public $separator='-';
public $xiegang='/';
public $publicPost=array();

function __construct(){  

  $this->_newDb();  
	$this->_newCfg();//加载当前模块配置 
	$this->_newCfg('','','',SKL_ROOT_PATH.'config.php');//加载对接配置  
  $this->_newDbCfg();//加载用户配置

  require_once(SKL_SYS_FUNCTIONS_PATH.'skl_authcode.php'); 
}


//提交网站订单处理
public function dopay(){  

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

  $po_encode_uid      =$post[$alias_obj->post_alias['encode_uid']];//加密的用户id
	$po_encode_username =$post[$alias_obj->post_alias['encode_username']];//加密的用户名

	$poPaytype          =$post[$alias_obj->post_alias['paytype']];//支付类型
  $poTitle            =$post[$alias_obj->post_alias['out_trade_no']];//网站订单号
  $poMoney            =$post[$alias_obj->post_alias['price']];//订单金额	
	$poUid              =$po_encode_uid == '' ? $this->uid : skl_authcode($po_encode_uid,'decode',$this->cfg_sign);//用户id,兼容旧版	
	$poUsername         =$po_encode_username == '' ? $post[$alias_obj->post_alias['username']] : skl_authcode($po_encode_username,'decode',$this->cfg_sign);//用户名,兼容旧版
	$paytype            =array_search($poPaytype,$alias_obj->post_alias['paytype_value']);//当前的支付类型
	$poGuishu           =empty($post[$alias_obj->post_alias['guishu']]) ? 1 : intval($post[$alias_obj->post_alias['guishu']]);
  if($poGuishu == 0){ skl_error('归属某个订单表参数格式错误，必须用非0数字1、2、3...代表'); }
	$po_return_url      =htmlspecialchars_decode($post[$alias_obj->post_alias['return_url']]);//付款成功后返回地址
	$po_is_mobile      =$post[$alias_obj->post_alias['is_mobile']];//客户端

	//检测支付类型开关是否开启
	$paytype_start=array_keys($this->cfg_paytype_order,1);
	if(empty($paytype_start)){  skl_error('<p>支付通道（支付开关）已全部关闭！</p><p style="font-size: 14px;"><b>如果需要打开请到收款啦订单管理系统开启</b></p>');  }

  $paytype=empty($paytype) ? $paytype_start[0] : $paytype;	
	//检测支付类型是否存在
  if(!array_key_exists($paytype,$this->cfg_paytype_name)){ skl_error('支付类型错误！'); }	
	$paytype_name=$this->cfg_paytype_name[$paytype];

  if(array_search($paytype,$paytype_start) === false){ skl_error($paytype_name.'通道已关闭');  }


  //根据用户配置获取收款账号和收款码目录，开始
	$qrcode_path_new=$this->cfg_qrcode_path[$paytype];
	//筛选已启用的收款账号
	foreach($qrcode_path_new as $user_k=>$user_v){
		 if($user_v['open'] != 1){ unset($qrcode_path_new[$user_k]); }
	}
  $qrcode_path_count=count($qrcode_path_new);//已启用的收款账号总数
	if($qrcode_path_count == 0){ skl_error($paytype_name.'收款码目录至少要启用1个！'); }

	if($this->cfg_is_open_users == 1){
	   //多账号收款
		 $users_toggle=$this->cfg_users_toggle[$paytype]['toggle'];     
		 
		 if($users_toggle == 1){
		    //轮换
        if($qrcode_path_count >= 2){
           $users_next=$this->cfg_users_toggle_next[$paytype]['next'];
					 if(!array_key_exists($users_next,$qrcode_path_new)){
						  //获已启用的第一个账号
							$user_first=reset($qrcode_path_new);
							$key_in_path=each($qrcode_path_new);
							$key_in_path=$key_in_path['key'];

						  $now_account    =$user_first['user'];//收款账号
					    $now_qrcode_path=$user_first['path'];//收款码路径					     

					 }else{			
						  
							$key_in_path=$users_next;

						  $now_account    =$qrcode_path_new[$users_next]['user'];//收款账号
					    $now_qrcode_path=$qrcode_path_new[$users_next]['path'];//收款码路径		

							//移动指针到指定单元
							while($eachkv= each($qrcode_path_new)){							   
								 if($eachkv['key'] == $users_next){ break; }
							}

					 }

				
				   //获取下一个账号				  
					 $next_key_value=each($qrcode_path_new);

					 //记录下一个收款账号关联的key
					 $update_next=$this->cfg_users_toggle_next;
					 $update_next[$paytype]['next']=empty($next_key_value) ? 0 : $next_key_value['key'];
					 $this->_upDbCfg("`cfg_users_toggle_next`='".json_encode($update_next)."'");				 
					  
					 
				}else{	
					
					 //启用账号只有一个 
					 $array_value=reset($qrcode_path_new);
           $key_in_path=array_keys($qrcode_path_new);
					 $key_in_path=$key_in_path[0];

				   $now_account    =$array_value['user'];//收款账号
					 $now_qrcode_path=$array_value['path'] ;//收款码路径
					 
				}				

		 }elseif($users_toggle == 2){

		    //随机
			 $array_rand=array_rand($qrcode_path_new);
       $key_in_path=$array_rand;

			 $now_account    =$qrcode_path_new[$array_rand]['user'];//收款账号
			 $now_qrcode_path=$qrcode_path_new[$array_rand]['path'] ;//收款码路径


		 }else{
		    skl_error('多账号收款切换方式错误！');
		 }
	}else{

	   //单账号收款
     $array_value_first=reset($qrcode_path_new);
     $key_in_path=array_keys($qrcode_path_new);
		 $key_in_path=$key_in_path[0];

		 $now_account    =$array_value_first['user'];//收款账号
     $now_qrcode_path=$array_value_first['path'];//收款码路径


	}	
	//根据用户配置获取收款账号和收款码目录，结束




  //开启session
	require_once(SKL_G_FUNCTIONS_PATH.'skl_startSession.php');
  skl_startSession();

  $poUid=!empty($_SESSION[$this->sessionUidKey]) && $post['skl_check_code'] == '1' ? $_SESSION[$this->sessionUidKey] : $poUid;
	
  if(empty($poMoney)){ skl_error('金额不能为空！'); } 
	if($poMoney < 0.01){ skl_error('金额不能小于0.01元'); }

  $isEmptyUid=empty($poUid) ? true : false;
	$isEmptyUsername=empty($poUsername) ? true : false;
  if($isEmptyUid && $isEmptyUsername){  skl_error('UID和用户名不能同时为空，必须填一个'); }

  
	//检测用户名或UID是否存在，开始
	if($this->cfg_is_check_user == '1'){

		$memberTableName=$this->cfg_member_table[$poGuishu]['table_name'];
		$memberUidField=$this->cfg_member_table[$poGuishu]['uid_field']; 
		$memberUserField=$this->cfg_member_table[$poGuishu]['user_field'];
		if(empty($memberTableName)){  skl_error($poGuishu.'归属订单的会员表信息不存在！');  }

		//检测uid是否存在
	  if(!$isEmptyUid){
	     if($this->db->table("`@#_$memberTableName`")->where("`$memberUidField` = '$poUid'")->getField("COUNT(`$memberUidField`)") == 0){
	         echo $this->db->getLastSql();exit;
			    skl_error($poUid.'该UID不存在！'); 
			 }
	  
	  }

	  //检测用户名是否存在
	  if(!$isEmptyUsername){
	     if($this->db->table("`@#_$memberTableName`")->where("`$memberUserField` = '$poUsername'")->getField("COUNT(`$memberUserField`)") == 0){
			    skl_error($poUsername.'该用户名不存在！');
			 }	  
	  }
	}
  //检测用户名或UID是否存在，结束


  $dbTimestamp=$this->_nowTime();//获取数据库时间戳

	if(!$isEmptyUid && !$isEmptyUsername){
	  $uidUsernameWhere="`skl_uid`='$poUid' AND `skl_username`='$poUsername'";
	}elseif(!$isEmptyUid){
	  $uidUsernameWhere="`skl_uid`='$poUid'";
	}elseif(!$isEmptyUsername){
	  $uidUsernameWhere="`skl_username`='$poUsername'";
	}else{
	  skl_error('未知错误！');
	}


	//判断是否发生恶意提交行为，开始
  $toTimeValue=$dbTimestamp-86400;
  $checkCountOrder=$this->db->table("`@#_$this->cfg_sys_order_table_name`")->where("$uidUsernameWhere AND `skl_time` >= $toTimeValue")->getField("COUNT(`skl_time`)");
	//如果检查到发生恶意行为，开启验证码保护,默认值50
	if($checkCountOrder > 50){
		
		if($post['skl_check_code'] != '1'){
			if(!empty($poUid)){   $_SESSION[$this->sessionUidKey]=$poUid;		}
			
		  exit('
			<style type="text/css">
			.contens{
			width:280px;
			background-color: #FFF;
			box-shadow: 0px 3px 10px #0070A6;
			margin-right: auto;
			margin-left: auto;
			margin-top: 50px;
			height: auto;
			border-radius: 6px;
			font-family: "微软雅黑";
			margin-bottom: 50px;
			padding-top: 10px;
			padding-right: 20px;
			padding-bottom: 20px;
			padding-left: 20px;
			text-align: center;
			}
			.cursorStyle{ cursor: pointer; }
			.buttonStyle{ width:150px; font-size: 20px; height: 40px;}
			</style>

			<div class="contens">
			<p>订单提交太频繁，请输入验证码。</p>
			<p><img class="cursorStyle" src="'.skl_U('Verify/index').'" title="看不清？点击刷新" onclick="javascript:this.src=\''.skl_U('Verify/index').'&mt=\'+Math.random()"></p>
			<form method="post" action="'.skl_U('dopay',$post).'">
			<p><input class="buttonStyle" name="skl_code_value"></p>
			<input name="skl_check_code" type="hidden" value="1" />
      <input class="buttonStyle cursorStyle" type="submit" value="提交">
			</form>	

			</div>');
		  

		}else{//开始验证
			
			$codeValue=trim($post['skl_code_value']);  
			if(empty($codeValue)){ skl_error('验证码不能为空！',$this->xiegang,10); }

		 //检测验证码
		 require_once(SKL_ClASS_PATH.'VerifyCode.class.php');
		 $VerifyCode=new VerifyCode();  
		 if(!$VerifyCode->check($codeValue)){
				skl_error('验证码错误！',$this->xiegang,10);
		 }

		
		}

	}
	//判断是否发生恶意提交行为，结束


  //检测数据库时间是否有误差，开始
	$isCheckTime=$dbTimestamp > $this->cfg_check_time_next ? true : false;
	if($this->cfg_check_time_count < 3 || $isCheckTime){	

		$apiTimestamp=$this->_getApiTime();//远程获取时间有可能卡顿
     
		$nowTimestamp=$this->_nowTime();
		if($isCheckTime){
		  $checkTimeCount=0;
		}else{
		  $checkTimeCount=$this->cfg_check_time_count+1; 
		}
		
    if(!empty($apiTimestamp)){
			 $deviationValue=$nowTimestamp-$apiTimestamp;
		}else{
			$deviationValue=$this->cfg_deviation_value;
		}

		$checkTimeNext=$nowTimestamp+86400;//一天检测一次	
    $this->_upDbCfg("`cfg_check_time_count`='$checkTimeCount',`cfg_check_time_next`='$checkTimeNext',`cfg_deviation_value`='$deviationValue'");		

	}
  //检测数据库时间是否有误差，结束


	//强制使用填金额备注方式，开始
	$poMoneyFloat=(float)$poMoney;
	if(in_array($paytype,$this->cfg_is_write_note)){
		 $is_write_note=1;
	}else{
	
			//自动判断是否启用输金额备注方式			     
			if(in_array($poMoneyFloat,$this->cfg_money_group)){		 
				 $is_write_note=0;

			}else{
				 $is_write_note=1;
			}
	
	}
  //强制使用填金额备注方式，结束



	//生成订单识别码，开始
	$nowTimestamp=empty($nowTimestamp) ? $dbTimestamp : $nowTimestamp;
  $toTime=$nowTimestamp-$this->cfg_ge_time; 

	$poMoney=sprintf('%.2f',$poMoney);
	$is_float=in_array($paytype,$this->cfg_is_float);

	$publicOrderTable='`'.$this->db->utable($this->cfg_sys_order_table_name).'`';
	$publicField="`skl_sysorder`,`skl_weborder`,`skl_sklorder`,`skl_receive_dir`,`skl_receive_account`,`skl_time`";	
	$publicWhere="`skl_guishu`=$poGuishu AND $uidUsernameWhere AND `skl_money`=$poMoney AND `skl_paytype`='$paytype' AND `skl_time`>$toTime AND `skl_state`!=1";

	//备注识别订单
	require_once(SKL_G_CONTROLLER_PATH.'QrcodeEdit.class.php');
	$QrcodeEdit=new QrcodeEdit($this);

  require_once(SKL_G_FUNCTIONS_PATH.'skl_path_filtration.php');

  $i=0;
	if($is_write_note == 1){   

      //同一个用户提交只能留一张订单
	    if($is_float){
			
				//小数识别订单
        $chaOrder=$this->db->table($publicOrderTable)->field($publicField)->where($publicWhere." AND `skl_sklorder` LIKE '%$poMoneyFloat%'")->find();

			}else{ 
			  
			  if($isEmptyUsername){ skl_error('启用备注识别订单时必须填写用户名'); }

        //用户名或备注识别订单
        $chaOrder=$this->db->table($publicOrderTable)->field($publicField)->where($publicWhere." AND `skl_sklorder` LIKE '%$poUsername%'")->find();
				
			}

			$cha_order_is_empty=empty($chaOrder) ? true : false;

      if($cha_order_is_empty){

				//检测任意金额收款码是否存在,开始
				$any_filename=$paytype.'.jpg';
				$now_qrcode_all_path=skl_path_filtration($now_qrcode_path.$this->xiegang.$any_filename); 
				$dir_end_any=$QrcodeEdit->dir_end_id($now_qrcode_all_path,true); 
				if(empty($dir_end_any)){ skl_error('在'.$paytype_name.'收款码目录('.$now_qrcode_path.')下没有找到任意金额收款码'.$any_filename); }
				//检测任意金额收款码是否存在,结束

			  if($is_float){
						$rand_array=array(0.01,0.02,0.03,0.04,0.05,0.06,0.07,0.08,0.09,0.1,0.11,0.12,0.13,0.14,0.15,0.16,0.17,0.18,0.19,0.2,0.21,0.22,0.23,0.24,0.25,0.26,0.27,0.28,0.29,0.3,0.31,0.32,0.33,0.34,0.35,0.36,0.37,0.38,0.39,0.4,0.41,0.42,0.43,0.44,0.45,0.46,0.47,0.48,0.49,0.5,0.51,0.52,0.53,0.54,0.55,0.56,0.57,0.58,0.59,0.6,0.61,0.62,0.63,0.64,0.65,0.66,0.67,0.68,0.69,0.7,0.71,0.72,0.73,0.74,0.75,0.76,0.77,0.78,0.79,0.8,0.81,0.82,0.83,0.84,0.85,0.86,0.87,0.88,0.89,0.9,0.91,0.92,0.93,0.94,0.95,0.96,0.97,0.98,0.99);          
						
						$poMoneyIsFloat=$this->_strIsFloat($poMoney);	         
						if($poMoneyIsFloat){
							 $readMinimum=0.01; 
							 $isFirst=true;						   
						}else{
							 shuffle($rand_array);//打乱排序
						}

						foreach($rand_array as $rand_v){
							 
							 if($poMoneyIsFloat){					    
									//带小数的金额加0.01
									if($isFirst){
										 $isFirst=false;
										 $readMinimum=0;			
									}else{
										 $readMinimum=$readMinimum+0.01;							
									}
									$rand_v_new=$poMoney+$readMinimum;

							 }else{
									//不带小数的金额使用随机数
									$rand_v_new=$poMoney+$rand_v;
							 }

							 $rand_float=sprintf('%.2f',$rand_v_new);					   
							 $isRepeat=$this->db->table($publicOrderTable)->where("`skl_paytype`='$paytype' AND `skl_state`!=1 AND `skl_sklorder`='$rand_float' AND `skl_receive_account`='$now_account' AND `skl_time`>$toTime")->getField("COUNT(`skl_sklorder`)");					

							 if($isRepeat <= 0){ 
								 $randNum=$rand_float;
								 break;   
							 }
						
						}


			 }else{

					$chaUserNew=$poUsername;
					while($i < 99){ 
					 
						 if($i != 0){	 $chaUserNew=$poUsername.'-'.$i; }
						 
						 $isRepeat=$this->db->table($publicOrderTable)->where("`skl_money`=$poMoney AND `skl_paytype`='$paytype' AND `skl_state`!=1 AND `skl_sklorder`='$chaUserNew' AND `skl_receive_account`='$now_account' AND `skl_time`>$toTime")->getField("COUNT(`skl_sklorder`)");	

						 if($isRepeat <= 0){
							 $randNum=$chaUserNew; 				 
							 break; 
						 }
						 
						 $i++;
					 
					}

				 
			 }
				 
       if(empty($randNum)){ skl_error($poMoneyFloat.'元随机码资源不足！'); } 
			}

	   $now_qrcode_qath_U=skl_U('Showimg/index',array('path'=>$now_qrcode_all_path));

	}else{


    //同一个用户提交只能留一张订单
    if($is_float){
			 $chaOrder=$this->db->table($publicOrderTable)->field($publicField)->where($publicWhere." AND `skl_sklorder` LIKE '%$poMoneyFloat.%'")->find();

		}else{

			 $chaOrder=$this->db->table($publicOrderTable)->field($publicField)->where($publicWhere." AND `skl_sklorder` LIKE '%$poMoneyFloat$this->separator%'")->find();

		}

		$cha_order_is_empty=empty($chaOrder) ? true : false; 

	
   if($cha_order_is_empty){       

      //把已选定的账号信息放到第一位，开始
      if($qrcode_path_count >= 2 && $this->cfg_is_open_users == 1){
				
				 $foreach_qrcode_path=array();
				 foreach($qrcode_path_new as $qk=>$qv){
					  if($qk != $key_in_path){
						   $foreach_qrcode_path[]=$qv;
						} 				    

				 }

				 array_unshift($foreach_qrcode_path,$qrcode_path_new[$key_in_path]);        
			
			}else{			
			   $foreach_qrcode_path[]=reset($qrcode_path_new);
			
			}
			//把已选定的账号信息放到第一位，结束


      foreach($foreach_qrcode_path as $fq_v){

				$now_qrcode_money_path=skl_path_filtration($fq_v['path'].$this->xiegang.$poMoneyFloat); 
				$file_name=array();                                        
				$dir_end=$QrcodeEdit->dir_end_id($now_qrcode_money_path,false);	
				if(!empty($dir_end)){
					 //获取归属目录下的所有收款码
					 $file_name=$QrcodeEdit->get_all_file($dir_end['endid']);					 
				
				}

				$file_count=count($file_name);
				if($file_count >= 99){ skl_error($paytype_name.'收款码目录('.$now_qrcode_money_path.')下的收款码数量不能超过99张！'); }	

				//判断金额组收款码是否存在
				if(empty($file_name)){  skl_error('在'.$paytype_name.'收款码目录('.$now_qrcode_money_path.')下没有找到'.$poMoneyFloat.'元的收款码图片'); }

				if($file_count >= 2){  shuffle($file_name); 	} //打乱排序

				$fileNameArr=array();   
				foreach($file_name as $fileV){		 

					 //过滤无效格式收款码				
					 $expFileName=explode('.',$fileV);
					 $strCount=substr_count($fileV,'.');
					 
					 if($is_float && $strCount == 2 && is_numeric($expFileName[0]) && is_numeric($expFileName[1])){

						 $expFileName2=$expFileName[0].'.'.$expFileName[1];
						 $fileNameArr[$expFileName2]=$fileV;				 

					 }elseif(!$is_float && $strCount == 1 && stripos($expFileName[0],$this->separator) > 0){

						 $fileNameArr[$expFileName[0]]=$fileV;

					 }else{
					 
							$errorQrcodePath=$now_qrcode_money_path.$this->xiegang;
							if($is_float){						
								 skl_error($errorQrcodePath.$fileV."<br>该收款码文件名格式错误，文件名必须和收款码金额同名");
							}else{
								 skl_error($errorQrcodePath.$fileV."<br>该收款码文件名格式错误");
							}
					 
					 }
				}



				foreach($fileNameArr as $k=>$v){ 
					
					 $isRepeat=$this->db->table($publicOrderTable)->where("`skl_paytype`='$paytype' AND `skl_state`!=1 AND `skl_sklorder`='$k' AND `skl_receive_account`='$now_account' AND `skl_time`>$toTime")->getField("COUNT(`skl_sklorder`)");

						if($isRepeat <= 0){
							$IFileName=$v;
							$randNum=$k; 				 
							break; 

						}	
					 
				}

				if(!empty($randNum)){
					 $now_account=$fq_v['user'];//改变收款账号
					 $now_qrcode_all_path=$now_qrcode_money_path.$this->xiegang.$fileNameArr[$randNum];//完整收款码路径
					 $now_qrcode_qath_U=skl_U('Showimg/index',array('path'=>$now_qrcode_all_path));
					 break; 
				}

			}

      if(empty($randNum)){ skl_error($poMoneyFloat.'元收款码图片资源不足，请选择其他金额或联系网站管理员添加收款码'); }

	 }

	}
	//生成订单识别码，结束

 
  if($cha_order_is_empty){
		if(empty($randNum)){ skl_error('系统繁忙请稍后再试！'); }

		if(empty($now_account)){ skl_error($paytype_name.'收款账号不能为空，请在收款啦管理后台填写'); }

		$sys_order='8'.$nowTimestamp.rand(100,999);

	  //订单入库
	  $insertArr=array(
		'skl_guishu'           =>$poGuishu,
		'skl_uid'              =>$poUid,
		'skl_username'         =>$poUsername,
    'skl_money'            =>$poMoney,
	  'skl_sysorder'         =>$sys_order,
	  'skl_weborder'         =>$poTitle,
	  'skl_paytype'          =>$paytype,
    'skl_sklorder'         =>$randNum,
    'skl_receive_account'  =>$now_account,
		'skl_receive_dir'      =>$now_qrcode_all_path,
	  'skl_time'             =>$nowTimestamp,

	  );

		//防止mysql开启严格模式导致无法插入数据
		if(empty($poUid)){  unset($insertArr['skl_uid']); 	}


	  $insertID=$this->db->table($publicOrderTable)->add($insertArr);  
	  if(empty($insertID)){  skl_error('订单入库失败！'); }

		//自动清理没用的订单数据，开始		
		$orderCount=$this->db->table($publicOrderTable)->getField("COUNT(`skl_id`)");   
		if($orderCount > $this->cfg_clear_value){
		    
		   $deleteTime=$nowTimestamp-(86400*60);//最近x天
		   $limitValue=intval($this->cfg_clear_value*0.1); 

       $dltRow=$this->db->table($publicOrderTable)->where("`skl_state`!=1 AND `skl_time` < $deleteTime")->order("`skl_time` ASC")->limit($limitValue)->delete();

		   if($dltRow > 0){		   	
				  $this->_upDbCfg("`cfg_clear_value`=`cfg_clear_value`+$dltRow");

				  //删除未处理订单后自动对表进行优化
	        $this->db->query('OPTIMIZE TABLE '.$publicOrderTable);

					//记录管理员操作日志，开始				
					$this->_newCfg('cfg_sys_admin_log_table_name',SKL_MODULE_NAME_2); //加载模块2配置
					require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
					require_once(SKL_G_FUNCTIONS_PATH.'skl_serverIp.php');
					$AddLog=new AddLog($this);
					$log_arr['admin_log_type']     =2;
					$log_arr['admin_log_user']     ='systen';
					$log_arr['admin_log_ip']       =skl_serverIp();
					$log_arr['admin_log_explain']  ='系统自动删除未处理订单: '.$dltRow.'条 表名:'.$publicOrderTable.' 表是否优化:yes 条件:`skl_time` < '.$deleteTime.' 排序:`skl_time` ASC';
					$AddLog->index($log_arr);
					//记录管理员操作日志，结束

		   }
		}
		//自动清理没用的订单数据，结束


		$nowSysOrder     =& $sys_order;
		$nowShortOrder   =& $randNum;
		$nowWebOrder     =& $poTitle;
		$nowQrcodeQath   =& $now_qrcode_qath_U;
		$nowAccount      =& $now_account;
    $nowInPath       =& $now_qrcode_all_path;

  }else{

	  $nowSysOrder     =& $chaOrder['skl_sysorder'];
		$nowShortOrder   =& $chaOrder['skl_sklorder'];
		$nowWebOrder     =& $chaOrder['skl_weborder'];
		$nowQrcodeQath   =skl_U('Showimg/index',array('path'=>$chaOrder['skl_receive_dir']));
		$nowAccount      =& $chaOrder['skl_receive_account'];
		$nowInPath       =& $chaOrder['skl_receive_dir'];
	}


  //自动识别手机请求	
  if($po_is_mobile != '1'){				
		 require_once(SKL_G_FUNCTIONS_PATH.'skl_isMobile.php');
		 if(skl_isMobile()){
				$now_is_mobile=1;
		 }
	}	


	//返回地址优先级判断，开始
	if($now_is_mobile == 1){
	   $cfg_return_url=& $this->cfg_return_url_m;
	}else{
	   $cfg_return_url=& $this->cfg_return_url;
	}

	$post_url_is_empty=empty($po_return_url) ? true : false;
	$cfg_url_is_empty=empty($cfg_return_url) ? true : false;
	if($post_url_is_empty && $cfg_url_is_empty){
		 $new_return_url=$this->xiegang; 
	}else{
	   $new_return_url=$cfg_url_is_empty ? $po_return_url : $cfg_return_url;	
		 $new_return_url=urldecode($new_return_url);
	}

  $parse_return_url=parse_url($new_return_url,PHP_URL_QUERY);
	if(empty($parse_return_url)){
		 $head_return_url=rtrim($new_return_url,'?');
	}else{

		 $explode_return_url=explode('?',$new_return_url);
		 $head_return_url=$explode_return_url[0];
	}



	$chanshu_arr=array();
  parse_str($parse_return_url,$chanshu_arr);
	$chanshu_arr[$this->cfg_return_url_cs['weborder']]=$nowWebOrder;
	$chanshu_arr['sysorder']=$nowSysOrder;

	$nowReturnUrl=$head_return_url.'?'.http_build_query($chanshu_arr);
	//返回地址优先级判断，结束

  $this->_fileJson(array($paytype=>array('title'=>$nowShortOrder,'rechargeTime'=>$nowTimestamp))); 

	//提交到付款页面,开始
	$arrays=array(
		'paytype'           =>$paytype,
		'email'             =>$nowAccount, 
    'money'             =>$poMoney,
		'short_order'       =>$nowShortOrder,
		'sys_order'         =>$nowSysOrder,
		'web_order'         =>$nowWebOrder,
		'is_write_note'     =>$is_write_note,
		'qrcode_path'       =>$nowQrcodeQath,
		'in_path'           =>$nowInPath,
		//'file_name'         =>$fileNameArr[$nowShortOrder],
		//'url_cfg'           =>$url_cfg[$IFileName],
		'is_mobile'         =>$now_is_mobile,
		'return_url'        =>urlencode($nowReturnUrl),
		'is_float'          =>$is_float ? '1':'0',
	);		


  //是否开启手动转账,如果没开启就不传收款账号
  if(!in_array($paytype,$this->cfg_is_remittance)){ unset($arrays['email']); } 


  //如果是微信浏览器直接用识别收款码支付
	require_once(SKL_G_FUNCTIONS_PATH.'skl_isWeixin.php');
	if($arrays['is_mobile'] == 1 && $paytype == SKL_PAYTYPE_WXPAY && skl_isWeixin()){

		require_once(SKL_G_FUNCTIONS_PATH.'skl_create_wx_url.php');
    header('location: '.skl_create_wx_url($arrays));
		exit;

	}


	$actionUrl=SKL_WEBROOT_PATH.'index.php?c=Scanpay';
  require_once(SKL_CONTROLLER_VIEW_PATH.'goPay.php'); 

	//提交到付款页面,结束


}



//获取远程服务器北京时间
public function _getApiTime($isTimestamp=true){

	require_once(SKL_ClASS_PATH.'ShoukuanlaCurl.class.php');
	$curl=new ShoukuanlaCurl();
	$timeInfo=json_decode($curl->get('http://api.k780.com/?app=life.time&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json'),true); 	  	

	if(empty($timeInfo['result']['timestamp'])){
	   $timeInfo=json_decode($curl->get('http://timeapi.shoukuanla.net/timeapi.php'),true);
	}

  return $isTimestamp ? $timeInfo['result']['timestamp'] : $timeInfo['result']['datetime_1'];

}



//根据uid查出用户名
public function _getUsername($uid=null,$guishu=1){
  
	$memberTableName=$this->cfg_member_table[$guishu]['table_name'];
	$memberUidField=$this->cfg_member_table[$guishu]['uid_field']; 
	$memberUserField=$this->cfg_member_table[$guishu]['user_field'];
	return $this->db->table("`@#_$memberTableName`")->where("`$memberUidField`='$uid'")->getField("`$memberUserField`");
}

//根据用户名查出uid
public function _getUid($username=null,$guishu=1){

	$memberTableName=$this->cfg_member_table[$guishu]['table_name'];
	$memberUidField=$this->cfg_member_table[$guishu]['uid_field']; 
	$memberUserField=$this->cfg_member_table[$guishu]['user_field'];
  return $this->db->table("`@#_$memberTableName`")->where("`$memberUserField`='$username'")->getField("`$memberUidField`");

}




public function  _fileJson($arr=array()){
	
	$arrKey=array_keys($arr);
	$arrKey=$arrKey[0];
	if(empty($arrKey)){ skl_error('Json参数错误') ;  }

	$title       =$arr[$arrKey]['title'];
	$rechargeTime=$arr[$arrKey]['rechargeTime'];

	$isUpOK=$this->db->table("`@#_$this->cfg_sys_last_table_name`")->where("`last_paytype`='$arrKey'")->update("`last_title`='$title',`last_rechargetime`='$rechargeTime'");

	if($isUpOK < 1){ skl_error("记录最后的充值信息表修改失败!") ;	 }


  //写入文件，开始
	$filename = SKL_ROOT_PATH.'json.txt';
	$dataArr=json_decode(file_get_contents($filename),true);
  
	if(empty($dataArr)){
		$dataArr=array(
			 SKL_PAYTYPE_ALIPAY =>array('title'=>'','rechargeTime'=>''),
			 SKL_PAYTYPE_WXPAY  =>array('title'=>'','rechargeTime'=>''),
			 SKL_PAYTYPE_TENPAY =>array('title'=>'','rechargeTime'=>''),
					
		);
	}
  
	$data=json_encode(array_merge($dataArr,$arr)); 
	$isWrite=file_put_contents($filename,$data);
	if(!is_writable($filename) || $isWrite === false) {
		 skl_error(SKL_WEBROOT_PATH."json.txt<br>文件无法保存没有写入权限!") ;	
	}
	//写入文件，结束


}


//判断字符串是不是浮点
public function _strIsFloat($strFloat=null){
   return ((int)$strFloat != $strFloat);
}


public function external(){  }

//获取已登录会员的uid
/*public function _getLoginUid(){

//解析数组
$explodeStr=explode('.',$this->cfg_uid_where);
$xiaoStr=strtolower($explodeStr[0]);
if($xiaoStr == 'session'){

  require_once(SKL_G_FUNCTIONS_PATH.'skl_startSession.php');
  skl_startSession();
	$nowUid=$_SESSION;

}elseif($xiaoStr == 'cookie'){

  $nowUid=$_COOKIE;
}else{ skl_error($this->cfg_uid_where.'不支持该类型数组！'); }

$isClose=false;	
foreach($explodeStr as $ev){

	if($isClose){	
		 $nowUid=$nowUid[$ev];			
	}else{
		$isClose=true;
	}

}

return $nowUid;

}*/


//小型数据库，读写
/*public function _smallDb($type='r',& $arr=array()){
  $file_name='smalldb.php';
  $dbData=skl_C('','',$file_name);

	if(empty($dbData)){
		$dbData=array(
			 'checkTimeCount'  =>0,
			 'checkTimeNext'   =>0,
			 'deviationValue'  =>0,
			 'serverIp'        =>'',
			 'clearValue'      =>'',
	  );
	}

  if($type == 'r'){  
		 return $dbData;
		 
	}elseif($type == 'w'){
     
    $dbName=SKL_ROOT_PATH.$file_name;
		if(empty($arr)){ $arr=array(); }
		$data=array_merge($dbData,$arr);
		$datas='<?php return '.var_export($data,true).'; ?>'; 
		$rStatus=file_put_contents($dbName,$datas);
		if($rStatus === false){  skl_error($dbName.'<br>文件没有写入权限!') ;  }
		return $rStatus;
	}

}*/

}
?>