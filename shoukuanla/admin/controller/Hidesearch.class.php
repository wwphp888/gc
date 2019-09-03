<?php
/*
功能：隐藏或显示搜索
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2018-12-28 15:59
*/
require_once(SKL_CONTROLLER_PATH.'AdminBase.class.php');
class Hidesearch extends AdminBase{

function __construct(){
  
	parent::__construct(); 

}

/*
修改用户配置,隐藏或显示搜索
*/
public function index(){

  $datai=skl_I($_GET);
	$kname=$datai['kname'];

	if($datai['is_hide'] == 1){
	   $upfield=1;
	}else{
	   $upfield=0;
	}
  
	if(empty($this->cfg_is_hide_search)){   $this->_newDbCfg('`cfg_is_hide_search`');	}
  $is_hide_search_arr=$this->cfg_is_hide_search;
	
	$is_hide_search_arr[$kname]=$upfield;

  $this->_upDbCfg("`cfg_is_hide_search`='".json_encode($is_hide_search_arr)."'");

	echo json_encode(array('status'=>'success'));

}







}
?>