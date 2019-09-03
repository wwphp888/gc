<?php
/*
功能：获取数据库时间
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2018-12-28 15:59
*/
require_once(SKL_CONTROLLER_PATH.'AdminBase.class.php');
class Getdbtime extends AdminBase{

function __construct(){
  
	parent::__construct(); 

}

/*
输出数据库时间
参数：            返回值：
$_GET['datetime']=2018-08-08 08:08:08 
$_GET['between_time']=array('begin'=>'2018-08-08 00:00:00','end'=>'2018-08-08 23:59:59')
*/
public function index(){ 
 
  $timetype=trim($_GET['timetype']);
	$in_array_timetype=array('datetime'=>'CURRENT_TIMESTAMP()','between_time'=>'CURDATE()');

	if(!array_key_exists($timetype,$in_array_timetype)){  exit(json_encode(array('errormsg'=>'时间指令错误！')));  }

  $curdate=$this->db->getField($in_array_timetype[$timetype]);
	if(empty($curdate)){  exit(json_encode(array('errormsg'=>'时间获取失败！'))); }
	$return_data['status']='success';

	if($timetype == 'between_time'){
	   $return_data[$timetype]=array('begin'=>$curdate.' 00:00:00','end'=>$curdate.' 23:59:59');
	}else{
	   $return_data[$timetype]=$curdate;
	}

  echo json_encode($return_data);
}







}
?>