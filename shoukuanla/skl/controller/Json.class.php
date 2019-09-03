<?php
/*
功能：查询网站是否有人充值。
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-13 23:35
*/
class Json extends ShoukuanlaBase{


//浏览器访问 http://域名/shoukuanla/index.php?c=Json
public function index(){

	$this->_newDb();
	$this->_newCfg('cfg_sys_last_table_name');
	$last_list=$this->db->query("SELECT `last_paytype`,`last_title`,`last_rechargetime` FROM `".$this->db->utable($this->cfg_sys_last_table_name)."`");

	$echo_arr=array();
	if($last_list){
		while($last_arr=$last_list->fetch_assoc()){
			 $echo_arr[$last_arr['last_paytype']]=array('title'=>$last_arr['last_title'],'rechargeTime'=>$last_arr['last_rechargetime']);
		}
		$last_list->free();	
	}

	skl_ajax($echo_arr);

}


}
?>