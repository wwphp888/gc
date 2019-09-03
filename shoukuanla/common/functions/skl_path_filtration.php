<?php 
/*
路径过滤
参数：$path=string
修改时间：2019-01-06 18:58
*/
function skl_path_filtration($path=null){

  $xiegang='/';
	if($path == $xiegang || $path == ''){ return $path; } 

  $dirname=dirname($path);
	$is_root=false;
  //根目录
	if($dirname == $xiegang || $dirname == '\\' || substr($dirname,0,1) == $xiegang){		$is_root=true; 	}
	
	$path_explode=explode($xiegang,$path);
  foreach($path_explode as $path_v){
	   $path_v=trim($path_v);
		 if($path_v != ''){
		    $path_new.=$path_v.$xiegang;
		 }
	}

	if($is_root){ $path_new=$xiegang.$path_new; }

	return rtrim($path_new,$xiegang);


}

?>