<?php
/*
功能：显示数据库中的图片
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-05 15:56
*/
class Showimg extends ShoukuanlaBase{

private $assign_is_first=true;
private $assign_shangji_id=0;

function __construct(){ 

  $this->_newDb();
	$this->_newCfg('cfg_sys_qrcode_table_name,cfg_sys_dir_table_name');//加载当前模块配置
}


/*
使用方法1：/shoukuanla/index.php?m=skl&c=Showimg&a=index&dir=4&name=alipay.jpg
dir=目录id，name=文件名称
方法2：/shoukuanla/index.php?m=skl&c=Showimg&a=index&path=alipay/user1/alipay.jpg
path=图片路径
*/
public function index(){  

  $get=skl_I($_GET);
	$img_dir=intval($get['dir']);
	$img_name=$get['name'];
	$img_path=$get['path'];
   
  //路径方式查询
  if($img_path != ''){
		require_once(SKL_G_CONTROLLER_PATH.'QrcodeEdit.class.php');
		$QrcodeEdit=new QrcodeEdit($this);
		$dir_end=$QrcodeEdit->dir_end_id($img_path,true);
		$img_name =$dir_end['endfile'];
		$img_dir  =$dir_end['endid'];
	}

  if($img_dir >= 0 && $img_name != ''){
		
		$img=$this->db->table("`@#_$this->cfg_sys_qrcode_table_name`")->field("`qrcode_type`,`qrcode_data`")->where("`qrcode_dir`=$img_dir AND `qrcode_name`='$img_name'")->find(); 

		if($img){	
			header('content-type: '.$img['type']);
			echo $img['qrcode_data'];
		}
	
	}



}


}
?>