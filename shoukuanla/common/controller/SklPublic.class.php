<?php
/*
功能：公共类
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2018-10-08 18:59
*/
class SklPublic extends ShoukuanlaBase{

//当前时间，数据库的时间戳
public function _nowTime($is_error=true){

  $dbTimestamp='';
	$rt=$this->db->query('SELECT UNIX_TIMESTAMP() AS dbtime');
	if($rt){
	  $rarr=$rt->fetch_assoc();
	  $rt->free();
		$dbTimestamp=$rarr['dbtime'];	 
	}

  if($is_error){		if(empty($dbTimestamp)){  skl_error('数据库时间戳获取失败！');  }	  }

  return  $dbTimestamp;

}



}
?>