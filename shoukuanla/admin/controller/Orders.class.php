<?php
/*
功能：网站订单管理
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2018-12-29 18:04
*/
require_once(SKL_CONTROLLER_PATH.'AdminBase.class.php');
class Orders extends AdminBase{

function __construct(){  
	
  parent::__construct(); 

}

//网站订单列表
public function index(){
  
  require_once(SKL_CONTROLLER_VIEW_PATH.'index.php');
}

//手动补单
public function edit(){
  $this->_newCfg('',SKL_MODULE_NAME_1);//加载模块1配置 
	$this->_newCfg('','','',SKL_ROOT_PATH.'config.php');//加载对接配置 	

	if($_POST){

		$post_budan=skl_I($_POST['budan']);

		$this->_newDbCfg('`cfg_sign`');
    $sign=empty($this->cfg_sign) ? 'www.shoukuanla.net' : $this->cfg_sign;
		echo json_encode(array('status'=>'success','notify_url'=>$this->cfg_notify_url,'budan_info'=>'budan%5Badmin_name%5D='.$this->assign_admin_user.'&is_budan=1&budan%5Bkeymd5%5D='.md5($this->assign_admin_user.$post_budan['skl_money_actual'].$post_budan['skl_sysorder'].$post_budan['skl_weborder'].$post_budan['skl_paytype'].$post_budan['skl_jiaoyi'].$post_budan['skl_pay_time'].$sign)));		

	}else{
		
		$order_table_name=$this->db->utable($this->cfg_sys_order_table_name);

		$id=intval($_GET['id']);	

		//查询id对应的订单信息
		$order_info=$this->db->table("`$order_table_name`")->where("`skl_id`=$id")->find();
		if(empty($order_info)){  skl_error('该订单不存在！');  }
		if($order_info['skl_state'] == 1){  skl_error($order_info['skl_weborder'].' 该网站订单号已被处理过！');  }

		//获取字段备注		
		$comments=$this->db->query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$order_table_name'");
		if($comments){ 
		 while($comment_arr=$comments->fetch_assoc()){
				 $comments_arr[$comment_arr['COLUMN_NAME']]=$comment_arr['COLUMN_COMMENT'];  	   			
		 }
		 $comments->free();	 
		}	

		//不需要显示的字段
		unset($order_info['skl_state']);
		unset($order_info['skl_sklorder']);
		unset($order_info['skl_update_time']);
		unset($order_info['skl_budan']);
		unset($order_info['skl_receive_dir']);

		//显示lable的字段
		$lable_arr=array('skl_guishu','skl_id','skl_uid','skl_username','skl_money','skl_sysorder','skl_weborder','skl_paytype','skl_time');
	
	  require_once(SKL_CONTROLLER_VIEW_PATH.'edit.php');
	}

  
}

//删除数据
public function deldata(){  

	require_once(SKL_G_CONTROLLER_PATH.'Deldata.class.php');
  $Deldata=new Deldata($this);
  $Deldata->index();

}

}
?>