<?php
/*
功能：后台登录
作者：宇卓
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-07-04 18:04
*/
require_once(SKL_G_CONTROLLER_PATH.'SklPublic.class.php');
class Login extends SklPublic{

function __construct(){  
  
	if(SKL_ACTION != 'index'){
	  $this->_newDb(); 
	}
   

}


public function index(){ 

 require_once(SKL_G_FUNCTIONS_PATH.'skl_startSession.php');
 skl_startSession();

 if(!empty($_SESSION['skl_admin_info'])){
    header('location: '.skl_U('Index/index'));exit;
 }

 require_once(SKL_CONTROLLER_VIEW_PATH.'index.php');

}

public function check_admin(){   	
	 
   $post=skl_I($_POST);
	 $username=& $post['username'];
	 if($username == ''){ exit(json_encode(array('errormsg'=>'管理员账号不能为空！'))); }
	 if($post['password'] == ''){ exit(json_encode(array('errormsg'=>'管理员密码不能为空！'))); }
   if($post['verify'] == ''){ exit(json_encode(array('errormsg'=>'验证码不能为空！'))); }


	 //检测验证码
	 require_once(SKL_ClASS_PATH.'VerifyCode.class.php');
	 $verify_code=new VerifyCode();  
	 if(!$verify_code->check($post['verify'],'admin')){
			exit(json_encode(array('errormsg'=>'验证码错误！')));
	 }

	$this->_newCfg('',SKL_MODULE_NAME_1);//加载模块1配置 
	$this->_newCfg('','','',SKL_ROOT_PATH.'config.php');//加载对接配置
	$this->_newCfg();//加载当前模块配置


	//添加管理员和配置信息表，开始
	require_once(SKL_G_CONTROLLER_PATH.'AddAdminField.class.php');
	$AddAdminField=new AddAdminField($this);
	$admin_field=$AddAdminField->index();
	if($admin_field['status'] != 'success'){ exit(json_encode($admin_field));  }
	//添加管理员和配置信息表，结束

	 

	 $admin_info=$this->db->table("`@#_$this->cfg_sys_admin_table_name`")->field('`admin_id`,`admin_user`,`admin_pwd`,`admin_login_count`,`admin_last_time`')->where("`admin_user`='$username'")->find();

	 if(empty($admin_info)){  exit(json_encode(array('errormsg'=>$username.'该管理员账号不存在！')));  }
	 if($admin_info['admin_pwd'] != md5(md5($post['password']))){  exit(json_encode(array('errormsg'=>'登录密码错误！')));  }

	 //记录登录时间和登录次数 
	 if($this->db->table("`@#_$this->cfg_sys_admin_table_name`")->where('`admin_id`='.$admin_info['admin_id'])->update("`admin_login_count` = `admin_login_count`+1,`admin_last_time` = UNIX_TIMESTAMP()") < 1){
	    exit(json_encode(array('errormsg'=>'登录信息记录失败！'))); 
	 }	 
	 
	 //管理员用户名写入session
   require_once(SKL_G_FUNCTIONS_PATH.'skl_startSession.php');
   skl_startSession();
   $_SESSION['skl_admin_info']=array('id'=>$admin_info['admin_id'],'user'=>$admin_info['admin_user']);


	 //如果update_delete_info.php文件存在就删除指定内容,开始
	 $update_delete_info=SKL_ROOT_PATH.'update_delete_info.php';
	 if(file_exists($update_delete_info)){
			$delete_info=require($update_delete_info);

			//删除旧文件夹
			require_once(SKL_ClASS_PATH.'ShoukuanlaFileUtil.class.php');
			$file_util=new ShoukuanlaFileUtil();
      foreach($delete_info['dirname'] as $del_dirname_v){
			   if($del_dirname_v != ''){
					  if(file_exists($del_dirname_v)){
						   $file_util->unlinkDir($del_dirname_v);
						}				    
				 }
			}
			
			//删除旧表
			foreach($delete_info['tablename'] as $del_table_v){
				 if($del_table_v != ''){
					 $delete_table_union.="`$del_table_v`".',';
				 }			
			}			
			$delete_table_union=rtrim($delete_table_union,',');
			if($delete_table_union != ''){
				 if($this->db->query("DROP TABLE $delete_table_union") === true){
				    unlink($update_delete_info);
				 }
			}
	 
	 }
	 //如果update_delete_info.php文件存在就删除指定内容,结束
   


	 //记录管理员操作日志，开始 
	 require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
	 $AddLog=new AddLog($this);
	 $log_last_login=$admin_info['admin_last_time'] > 0 ? date('Y-m-d H:i:s',$admin_info['admin_last_time']) : '';
	 $log_arr['admin_log_type']    =4;
	 $log_arr['admin_log_user']    =$username;
	 $log_arr['admin_log_explain'] ='管理员登录: 第'.($admin_info['admin_login_count']+1).'次 上次登录时间:'.$log_last_login;
	 $AddLog->index($log_arr);
	 //记录管理员操作日志，结束

	 echo json_encode(array('status'=>'success'));

}


}
?>