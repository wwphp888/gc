<?php 
/*
使用递归方式替换数组，后边替换前边的
参数：$array1,$array2=数组
修改时间：2019-01-13 18:58
*/
function skl_array_replace_recursive($array1=array(),$array2=array()){
 
	foreach($array2 as $k=>$v){ 	 
		 if(is_array($v)){
			  if($array1[$k] !== $v){ $array1[$k]=skl_array_replace_recursive($array1[$k],$v); }
		    
		 }else{
		    if($array1[$k] !== $v){ $array1[$k]=$v; }		    
		 }
	}
	return $array1;

}

?>