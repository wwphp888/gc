<?php 
/*
//替换指定字符后解码base64：'-'换'+'  '_'换'/'
返回值(string)：
修改时间：2019-01-20 18:58
*/
function skl_from_url_base64(& $base64Str=null){

  $replaceStr=str_replace(array('-','_'),array('+','/'),$base64Str);
  return base64_decode($replaceStr);

}

?>