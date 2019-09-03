<?php 
/*
功能：全局公用函数
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-07-06 18:59
*/

//递归
function skl_recursive($filter,$data){

	$result = array();
	foreach ($data as $key => $val) {
		$result[$key] = is_array($val)? skl_recursive($filter, $val) : call_user_func($filter, $val);
	}
	return $result;

}

/*
过滤POST数据
参数：$datas=数组($_GET、$_POST...),$funArr=过滤函数
返回值：过滤之后的数据
*/
function skl_I($datas=null,$funArr=array()){
  
  if(is_array($datas)){
	   $post=& $datas;
	}else{

		 if($datas == ''){ 
			  $post=& $_REQUEST;	//默认值
		 }else{
		    skl_error('skl_I()函数参数错误！');
		 }
	
	}

	if(empty($post)){	   return $post; 	}

  //默认的过滤函数
  if(empty($funArr)){	  $funArr=array('trim','htmlspecialchars','addslashes'); }  //strip_tags	
  
	//如果系统已经转义但用户又不需要转义就添加stripslashes
  $magic_quotes_gpc=get_magic_quotes_gpc(); 
	$search_key=array_search('addslashes',$funArr);
	if($magic_quotes_gpc && $search_key === false){	 
     if(array_search('stripslashes',$funArr) === false){ array_unshift($funArr,'stripslashes'); }
	}
	
  //如果系统已经转义但用户也需要转义就删除addslashes
	if($magic_quotes_gpc && $search_key !== false){
	   unset($funArr[$search_key]);
	}

  foreach($funArr as $v){
	  $post=is_array($post) ? skl_recursive($v,$post) : $v($post);  
	}
 
	return $post;

}


/*
读取配置文件
参数：$module_name=模块名称,$file_name=文件名，$file_path=文件路径 
注意：$file_path有值情况下$module_name，$file_name参数会失效
返回值：array
*/
function skl_C($module_name=null,$file_name=null,$file_path=null){

	if($file_path == ''){ 
		 if($file_name == ''){ $file_name='config.php'; }  //默认文件名
	   if($module_name == ''){ $module_name=SKL_MODULE; } //默认使用当前模块
	   $cfg_path=SKL_ROOT_PATH.$module_name.'/conf/'.$file_name;//其他模块默认的配置文件路径

	}else{
	   $cfg_path=& $file_path;//模块1的配置文件路径
	}
  
	return file_exists($cfg_path) ? require($cfg_path) : array();
  
}

//返回ajax数据
function skl_ajax($arr=array()){
  /*
	if($type == ''){ $type='json'; }//默认值
  if($type == 'json'){ echo json_encode($arr); }	
	*/
  echo json_encode($arr);
}


//输出错误信息,判断是否返回json格式
function skl_error($title=null,$returnPath=null,$returnTime=300){
 
	 if($_REQUEST['is_ajax'] == '1'){
      
			if(is_array($title)){
			   exit(json_encode($title));
			}else{
			   exit(json_encode(array('errormsg'=>$title)));
			}		

	 }else{

		 require_once(SKL_G_FUNCTIONS_PATH.'skl_isMobile.php');
		 if(skl_isMobile()){
				$errorPath=SKL_G_VIEW_PATH.'error_mobile.php';
		 }else{
				$errorPath=SKL_G_VIEW_PATH.'error.php';
		 }

		 require_once($errorPath);
     exit;
	 }
	 
}


//生成url地址,使用方法：skl_U('Shoukuanla/index',array('参数名称'=>'参数值'))
function skl_U($mca_name=null,$parameter=array()){

	$arr_mca_name=explode('/',$mca_name);
	$count_mca_name=count($arr_mca_name);
	$new_mca_name=array();
  if($count_mca_name == 2){
		 $new_mca_name['m']=SKL_MODULE;
	   $new_mca_name['c']=$arr_mca_name[0];
		 $new_mca_name['a']=$arr_mca_name[1];

	}elseif($count_mca_name == 3){
	   $new_mca_name['m']=$arr_mca_name[0];
		 $new_mca_name['c']=$arr_mca_name[1];
		 $new_mca_name['a']=$arr_mca_name[2];
		 
	}else{
		 $new_mca_name['m']=SKL_MODULE; 
		 $new_mca_name['c']=SKL_CONTROLLER; 
	   $new_mca_name['a']=$mca_name;
	}

  $server_self=$_SERVER['PHP_SELF'];
	if($server_self == ''){
	   $server_self=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
	}

  $top=$new_mca_name['m'] == SKL_MODULE_NAME_2 ? $server_self : SKL_WEBROOT_PATH.'index.php';

  if(empty($parameter)){
	   return $top.'?'.http_build_query($new_mca_name);
	}else{
	   return $top.'?'.http_build_query(array_merge($new_mca_name,$parameter));
	}

}

?>