<?php 
//检测数据格式
//修改时间：2018-11-14 18:58
function skl_check_data($data=null,$type='int',$is_null=true){

  if($data == '' && $is_null){  return true;	}
 
  $type_arr=array(
		'int'        =>'/^(\d)+$/', //正整数
	  'integer'    =>'/^([1-9](\d*))+$/', //非0开头正整数
	  'float'      =>'/^(\d)+$|^(\d)+\.([0-9])+$/',	//浮点正整数
	);
	if(empty($type_arr[$type])){ skl_error($type.'该数据类型不支持！'); }
	if(preg_match($type_arr[$type],$data) > 0){
	   return true;
	}else{
	   return false;
	}


}

?>