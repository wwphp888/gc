<?php
/*
功能：删除数据库数据
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-15 15:59

使用方法：
require_once(SKL_G_CONTROLLER_PATH.'Deldata.class.php');
$Deldata=new Deldata($this);
$Deldata->index();
*/
class Deldata{
private $obj;

function __construct(& $obj=null){
  
  //检测需要的变量值
	//if($this->obj->xxx == ''){ skl_error('xxx不能为空'); }
	
	$this->obj=$obj; 
}


//删除数据库指定表数据
public function index(){
  $db=& $this->obj->db; 

  $post=skl_I('',array('trim'));
	$table=$post['table'];
	$where=$post['where'];
	$limit=intval($post['limit']);
	$order=$post['order'];

	$log_name=$post['log_name'];

	//清理数据条件，开始
	$skl_state=(int)$post['skl_state'];
	$time_where_public='UNIX_TIMESTAMP()-(86400*60)';
	if($table == 'cfg_sys_order_table_name' && ($skl_state == 0 || $skl_state == 1)){		 
	   $where="`skl_state` = $skl_state AND `skl_time` < $time_where_public";//订单     

	}elseif($table == 'cfg_sys_admin_log_table_name'){
	   $where="`admin_log_time` < $time_where_public";//日志

	}else{
	   skl_error('命令执行失败！');
	}
	//清理数据条件，结束
	

	if($table == ''){ exit(json_encode(array('errormsg'=>'表名不能为空！'))); }
	if($where == ''){ exit(json_encode(array('errormsg'=>'条件不能为空！'))); }
	if(empty($limit) || $limit > 1000){ $limit=1000; } //每次最多删除x条数据

  //允许删除数据的表
	$in_array_1=skl_C(SKL_MODULE_NAME_1);
	$in_array_2=skl_C(SKL_MODULE_NAME_2);

	if(array_key_exists($table,$in_array_1)){
	   $table_name=$in_array_1[$table];

	}elseif(array_key_exists($table,$in_array_2)){
	   $table_name=$in_array_2[$table]; 

	}else{	 
	   exit(json_encode(array('errormsg'=>$table.'您不能删除该表数据！')));
	}
	
  if($table_name == ''){  exit(json_encode(array('errormsg'=>'表名不能为空2！')));  }

  $rows=$db->table("`@#_$table_name`")->where($where)->order($order)->limit($limit)->delete();

  if($rows > 0){
		 
		 $table_name_prefix=$db->utable($table_name);

		 //删除数据后自动对表进行优化
	   $db->query('OPTIMIZE TABLE `'.$table_name_prefix.'`');
	
		//记录管理员操作日志，开始
		require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
		$AddLog=new AddLog($this->obj);
		$log_arr['admin_log_type']    =2;
		$log_arr['admin_log_explain'] =$log_name.':'.$rows.'条 表名:'.$table_name_prefix.' 表是否优化:yes 条件:'.$where.' 限制条数:'.$limit.' 排序:'.$order;
		$AddLog->index($log_arr);
		//记录管理员操作日志，结束

	}

	echo(json_encode(array('status'=>'success','rows'=>$rows)));

  
}







}
?>