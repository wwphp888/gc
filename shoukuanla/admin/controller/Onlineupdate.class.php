<?php
/*
功能：在线更新
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-24 18:04
*/
require_once(SKL_CONTROLLER_PATH.'AdminBase.class.php');
class Onlineupdate extends AdminBase{

function __construct(){  
  
  parent::__construct(); 

}

//在线更新
public function index(){ 

if($_POST){
	require_once(SKL_G_FUNCTIONS_PATH.'skl_startSession.php');
  skl_startSession(); 
	require(SKL_ROOT_PATH.'version.php');

	$_SESSION['skl_cache_path']=SKL_ROOT_PATH.'shoukuanla_cache_rand';
	$cache_path=$_SESSION['skl_cache_path'];//缓存目录路径

	require_once(SKL_ClASS_PATH.'ShoukuanlaCurl.class.php');
	$curl=new ShoukuanlaCurl();

	//获取远程更新数据，开始
	require_once(SKL_G_CONTROLLER_PATH.'Getupdate.class.php');
  $object='';
	$Getupdate=new Getupdate($object,$curl);
	$update_data=$Getupdate->index('version='.$skl_main_version);
	if(empty($update_data)){ skl_error('远程获取更新信息失败！'); }
	//获取远程更新数据，结束

	
  if($update_data['version'] == $skl_main_version){ skl_error('当前程序已经是最新版无需更新'); }

	//删除缓存目录
	require_once(SKL_ClASS_PATH.'ShoukuanlaFileUtil.class.php');
	$FileUtil=new ShoukuanlaFileUtil();
	if(!$FileUtil->unlinkDir($cache_path)){ skl_error($cache_path.' 该缓存目录删除失败！');  } 

	//创建缓存目录
	if(!$FileUtil->createDir($cache_path)){ skl_error($cache_path.' 该缓存目录创建失败，或是没有写入权限！'); }
	if(!is_writable($cache_path)){ skl_error($cache_path.' 该缓存目录没有写入权限！');  }

	//开始下载
	if($curl->iscurl()){ curl_setopt($curl->ch, CURLOPT_TIMEOUT,0); }//等待时间需要设置最大
	$filename=$curl->download($update_data['download_url'],$cache_path);
	if($filename == false){ skl_error('更新包下载失败！'); }

	//开始解压
	require_once(SKL_ClASS_PATH.'ShoukuanlaUnZip.class.php');  
	$zip=new ShoukuanlaUnZip();
  $count_unzip=$zip->unzip($cache_path.'/'.$filename,SKL_ROOT_PATH);
	if($count_unzip < 1){ skl_error('更新包'.$cache_path.'/'.$filename.'解压失败！');  }

	$_SESSION['skl_action_update']=true;
  
  skl_ajax(array('status'=>'success','url'=>SKL_WEBROOT_PATH.pathinfo($filename,PATHINFO_FILENAME).'.php'));


	
}

}

}
?>