<?php 
//启动session
//修改时间：2018-02-19 18:58
function skl_startSession(){

  //启动session
	if(version_compare(phpversion(), '5.4.0', '>=')){
		if(session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

	}else{
	  session_start();
	}


}

