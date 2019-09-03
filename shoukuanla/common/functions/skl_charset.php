<?php 
//编码转换
//修改时间：2019-06-26 18:58
function skl_charset($str=null){

  $db_cfg=skl_C('','',SKL_ROOT_PATH.'db.php');
  $charset=& $db_cfg['cfg_DB_CHARSET'];
  if(stripos('UTF8',$charset) === false){
     return iconv('UTF-8',$charset,$str);	   
  }else{
     return $str;
  }

}

