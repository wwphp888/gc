<?php 
/*
生成微信付款链接
修改时间：2019-01-18 18:58
*/
function skl_create_wx_url(& $post=array()){
   
	require_once(SKL_SYS_FUNCTIONS_PATH.'skl_is_https.php');
	$payUrlStr='http';
	$skl_is_https=skl_is_https();
	if($skl_is_https){  $payUrlStr.='s'; }

	$serverPort=$_SERVER['SERVER_PORT'];
	if((!$skl_is_https && $serverPort == 80) || ($skl_is_https && $serverPort == 443)){
	  $payUrlStr.='://'.$_SERVER['SERVER_NAME'];
	}else{
	  $payUrlStr.='://'.$_SERVER['SERVER_NAME'].':'.$serverPort;
	}

  return $payUrlStr.SKL_WEBROOT_PATH.'index.php?c=Identify&sys_order='.$post['sys_order'].'&web_order='.$post['web_order'].'&short_order='.$post['short_order'].'&is_write_note='.$post['is_write_note'].'&money='.$post['money'].'&is_float='.$post['is_float'].'&in_path='.$post['in_path'].'&qrcode_path='.urlencode($post['qrcode_path']).'&return_url='.urlencode($post['return_url']);     

}

