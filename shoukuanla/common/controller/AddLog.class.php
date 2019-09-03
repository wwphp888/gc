<?php 
//添加管理操作日志
//修改时间：2019-01-22 18:58

/*
使用方法：
//记录管理员操作日志，开始
require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
$AddLog=new AddLog($this);
$log_arr['admin_log_type']         =x;
$log_arr['admin_log_user']         =x;
$log_arr['admin_log_ip']           =x;
$log_arr['admin_log_explain']      =x;
$AddLog->index($log_arr);
//记录管理员操作日志，结束
$log_arr['admin_log_explain']格式： 事件标题: x条 细节描述1:x 细节描述2:x
*/
class AddLog{
private $obj;

function __construct(& $obj=null){
  
	//检测需要的变量值
	if($obj->cfg_sys_admin_log_table_name == ''){  skl_error('管理员操作日志表不能为空');   }	

	$this->obj=$obj;
}

//添加一条日志
function index(& $add_field=array()){
  $db=& $this->obj->db; 

  $admin_log_table_name=$db->utable($this->obj->cfg_sys_admin_log_table_name);

  $admin_log_user   =$add_field['admin_log_user'];
	$admin_log_type   =$add_field['admin_log_type'];
	$admin_log_ip     =$add_field['admin_log_ip'];
  $admin_log_explain=$add_field['admin_log_explain'];

	//如果管理员用户名为空则自动获取
	if(empty($admin_log_user)){ $admin_log_user=$this->obj->assign_admin_user; }
	if(empty($admin_log_user)){  skl_error('管理员用户名不能为空');   }	
 
  //如果客户端ip为空则自动获取
	if(empty($admin_log_ip)){ 
		 require_once(SKL_G_FUNCTIONS_PATH.'skl_get_client_ip.php');
		 $admin_log_ip=skl_get_client_ip();
	}


  if($db->table("`$admin_log_table_name`")->add("(`admin_log_user`,`admin_log_type`,`admin_log_ip`,`admin_log_explain`,`admin_log_time`) VALUES ('$admin_log_user','$admin_log_type','$admin_log_ip','$admin_log_explain',UNIX_TIMESTAMP())") < 1){
	   skl_error('添加管理操作日志失败！');
  }

	//日志达到一定数量自动清理
	$log_count=$db->table("`$admin_log_table_name`")->getField('COUNT(`admin_log_id`)');	
  if($log_count > 10000){
		 $no_del_time='UNIX_TIMESTAMP()-(86400*60)';//最近x天不能删除
	   $log_rows=$db->table("`$admin_log_table_name`")->where("`admin_log_time` < $no_del_time")->order('`admin_log_time` ASC')->limit(500)->delete();

     if($log_rows > 0){

		   //删除日志后自动对表进行优化
	     $db->query('OPTIMIZE TABLE `'.$admin_log_table_name.'`');

			 //记录删除条数
			 require_once(SKL_G_FUNCTIONS_PATH.'skl_serverIp.php');			 
			 $server_ip=skl_serverIp();
       $db->table("`$admin_log_table_name`")->add(array('admin_log_user'=>"'systen'",'admin_log_type'=>2,'admin_log_ip'=>"'$server_ip'",'admin_log_explain'=>"'系统自动删除日志: ".$log_rows."条 表名:".$admin_log_table_name." 表是否优化:yes 条件:`admin_log_time` < ".$no_del_time." 排序:`admin_log_time` ASC'",'admin_log_time'=>'UNIX_TIMESTAMP()'),false);
		 
		 }
	}

}
}

?>