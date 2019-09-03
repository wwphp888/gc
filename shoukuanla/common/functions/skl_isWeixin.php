<?php 
//判断是不是微信浏览器请求
//修改时间：2018-02-19 18:58
function skl_isWeixin(){

  if(stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false){ 
     return false; 
  }else{ return true; }

}