<?php
/*
功能：网站设置
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-06-07 18:04
*/
require_once(SKL_CONTROLLER_PATH.'AdminBase.class.php');
class Webset extends AdminBase{

function __construct(){  
  
  parent::__construct(); 

}

public function pass(){

	if($_POST){

    $old_password=trim($_REQUEST['oldpassword']);
		$password=trim($_REQUEST['password']);
		$repassword=trim($_REQUEST['repassword']);
    if($old_password == ''){ exit(json_encode(array('errormsg'=>'旧密码不能为空')));  }
		
	  if(!$this->compare_pass(md5(md5($old_password)))){  exit(json_encode(array('errormsg'=>'旧密码错误！'))); 	}

		//旧密码不能和新密码相同
		if($old_password == $password){ exit(json_encode(array('errormsg'=>'旧密码不能和新密码相同！'))); }

		//验证重复密码
    if($password != $repassword){  exit(json_encode(array('errormsg'=>'两次输入密码不一致！'))); }

		//修改密码
    if($this->db->table("@#_$this->cfg_sys_admin_table_name")->where("`admin_id`=$this->assign_admin_id")->update("`admin_pwd`='".md5(md5($password))."'") < 1){
			exit(json_encode(array('errormsg'=>'密码修改失败！'))); 
		 }


		//记录管理员操作日志，开始
		require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
		$AddLog=new AddLog($this);
		$log_arr['admin_log_type']    =3;
		$log_arr['admin_log_explain'] ='修改密码';
		$AddLog->index($log_arr);
		//记录管理员操作日志，结束


     echo json_encode(array('status'=>'success')); 

	}else{	
	  require_once(SKL_CONTROLLER_VIEW_PATH.'pass.php');
	} 

  
}

//比较密码(公用)
private function compare_pass($oldpassword=null){

	$admin_pass=$this->db->table("@#_$this->cfg_sys_admin_table_name")->where("`admin_id`=$this->assign_admin_id")->getField("`admin_pwd`");

	return $admin_pass == $oldpassword ? true : false;
}


//ajax验证当前管理密码
public function check_old_pass(){

 $check_password=md5(md5(trim($_REQUEST['oldpassword'])));

 if($this->compare_pass($check_password)){
    echo 1;
 }else{
    echo 0;
 } 
  

}


//接口配置
public function config(){

	$this->_newCfg('',SKL_MODULE_NAME_1);//加载模块1配置 
	$this->_newCfg('','','',SKL_ROOT_PATH.'config.php');//加载对接配置  
	$this->_newDbCfg();//获取数据库配置信息
  
	if($_POST){
     $psot=skl_I($_POST['site']);

     //检测间隔时间，开始
     $psot['cfg_ge_time']=intval($psot['cfg_ge_time']);
		 if($psot['cfg_ge_time'] < 2){  exit(json_encode(array('errormsg'=>'订单有效期不能小于2分钟！')));  }
		 if($psot['cfg_ge_time'] > 10){  exit(json_encode(array('errormsg'=>'订单有效期不能大于10分钟！')));  }
		 $psot['cfg_ge_time']=$psot['cfg_ge_time']*60;

		 //最近x分钟不能有未处理订单
		 if($psot['cfg_ge_time'] != $this->cfg_ge_time){
				$time_num=30*60;
				$recently_order_count=$this->db->table("`@#_$this->cfg_sys_order_table_name`")->where("`skl_state`!=1 AND `skl_time`>UNIX_TIMESTAMP()-$time_num")->getField("COUNT(`skl_time`)");
				if($recently_order_count > 0){  
					exit(json_encode(array('errormsg'=>'最近'.($time_num/60).'分钟还有('.$recently_order_count.')条未处理订单，暂时不能修改间订单有效期'))); 
				}
		 }
		 //检测间隔时间，结束

		 //检测指定金额组
     $psot['cfg_money_group']=explode('-',$psot['cfg_money_group']);
     foreach($psot['cfg_money_group'] as $money_v){		 
		    if(preg_match("/^([0-9]){1,10}$/",$money_v) < 1){ exit(json_encode(array('errormsg'=>'指定金额组格式错误！')));  }
		 }

		 //如果 是否开启手动转账 为空则为空
		 if(empty($psot['cfg_is_remittance'])){ $psot['cfg_is_remittance']=''; }

		 //如果 强制使用任意金额支付类型 为空则为空
		 if(empty($psot['cfg_is_write_note'])){ $psot['cfg_is_write_note']=''; }

		 //如果 支付开关和排序 为空则为空
		 foreach($this->cfg_paytype_name as $cpo_k=>$cpo_v){	   
			 if(empty($psot['cfg_paytype_order'][$cpo_k])){  $psot['cfg_paytype_order'][$cpo_k]=0;	 }
		 }

     //检测收款码目录数量
		 foreach($this->cfg_paytype_name as $cpo2_k=>$cpo2_v){	
			    
			 if(count($psot['cfg_qrcode_path'][$cpo2_k]) > 10){   exit(json_encode(array('errormsg'=>$cpo2_v.'收款码目录数量已超出限制！')));	 }

			 //检测目录格式
			 $is_open=false;
       foreach($psot['cfg_qrcode_path'][$cpo2_k] as $path_k=>$path_v){

          $is_open_path  =$path_v['open'] == 1 ? true : false ;
					$is_empty_path =$path_v['path'] == '' ? true : false ;

		      //多账号收款关闭情况下必须启用目录1
		      if($psot['cfg_is_open_users'] != 1 && $path_k == 1 && !$is_open_path){  
						exit(json_encode(array('errormsg'=>'多账号收款关闭情况下必须启用，'.$cpo2_v.'收款码目录'.$path_k)));  
					}


					//检测收款码目录格式
					if(!$is_empty_path){

						$path_arr=explode('/',$path_v['path']);
						foreach($path_arr as $epath_v){
							 $epath_v=trim($epath_v);
							 if($epath_v !== ''){
								 if(preg_match("/^(\w){1,50}$/",$epath_v) < 1){  exit(json_encode(array('errormsg' =>$cpo2_v.'收款码目录'.$path_k.'格式错误，只能用数字或字母组合！')));  }					 
							 }
							 
						}					
					}

				 if($is_open_path){

						if($is_empty_path){  exit(json_encode(array('errormsg'=>$cpo2_v.'收款码目录'.$path_k.'不能为空必须填写'))); }
						if($path_v['user'] == ''){  exit(json_encode(array('errormsg'=>$cpo2_v.'收款账号'.$path_k.'不能为空必须填写')));  }
					  if(!$is_open){ $is_open=true;}

			  }else{
			    
					//不启用、不等于第一个目录、目录为空、收款账号也为空、则删除
			 		if($path_k != 1 && $path_v['user'] == '' && $is_empty_path){ 
						 unset($psot['cfg_qrcode_path'][$cpo2_k][$path_k]);
					}	
			 }

		 }

			if(!$is_open){  exit(json_encode(array('errormsg'=>$cpo2_v.'收款码目录至少要启用1个')));  }	

		 }

		 foreach($psot as $cfg_k=>$cfg_v){	 

			 if(is_array($cfg_v)){ $cfg_v=json_encode($cfg_v); }
		   $update_field.="`$cfg_k`='$cfg_v',";
		 }

		 $update_field.='`cfg_time`=UNIX_TIMESTAMP()';
		 
		if($this->_upDbCfg($update_field) < 1){   exit(json_encode(array('errormsg'=>'保存配置失败！')));		}

		//记录管理员操作日志，开始
		require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
		$AddLog=new AddLog($this);
		$log_arr['admin_log_type']    =3;
		$log_arr['admin_log_explain'] ='修改接口配置: 1次 修改内容:'.addslashes($update_field);
		$AddLog->index($log_arr);
		//记录管理员操作日志，结束

		echo json_encode(array('status'=>'success'));
	
	}else{

		 //金额组数据转换
		 foreach($this->cfg_money_group as $money_v){
		    $money_group.=$money_v.'-';
		 }
		 $money_group=rtrim($money_group,'-');

	   require_once(SKL_CONTROLLER_VIEW_PATH.'config.php');
	}

}



}
?>