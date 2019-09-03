<?php
/*
功能：收款码编辑
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-06 15:59

使用方法：
require_once(SKL_G_CONTROLLER_PATH.'QrcodeEdit.class.php');
$QrcodeEdit=new QrcodeEdit($this);
$QrcodeEdit->dir_end_id('alipay/user1/alipay.jpg');
*/
class QrcodeEdit{

private $obj; //对象的引用
private $shangji_id=0;


//检测对象必要的值
function __construct(& $obj=null){
  
	$this->obj=$obj;
  if($obj->cfg_sys_dir_table_name == ''){ skl_error('cfg_sys_dir_table_name不能为空'); }

}

/*
获取路径末端目录ID和文件名
参数：$dir_path='alipay/user1/alipay.jpg'，$is_basename=末端是否有文件名
返回值：array('endid'=>'末端ID','endfile'=>'末端文件名')) 如果路径不存在返回空数组
*/
public function dir_end_id($dir_path=null,$is_basename=false){
	
	$retrun_arr=array();  
  if((string)$dir_path == ''){ return $retrun_arr; }
	
	$pathinfo=pathinfo($dir_path);
	$dirname    =& $pathinfo['dirname'];
	$basename   =(string)$pathinfo['basename'];   
  
	//如果没传目录只有文件名就从根目录获取 
	$dian='.';  $fanxie='\\';
	if($dirname == $dian || $dirname == $fanxie || $dirname == '/'){ $endid=0; }

	//只传根目录情况
	if($endid === 0 && $basename == ''){ return $retrun_arr; }
 
  //根目录下的文件
	if($endid === 0 && $is_basename){	   

		 //查询文件名是否存在
		 $basename_exists=$this->get_all_file($endid,$basename);
		 if(empty($basename_exists)){
				return $retrun_arr; 

		 }else{
				$retrun_arr['endid']    =$endid;
				$retrun_arr['endfile']  =$basename;
				return $retrun_arr;  

		 }		 

	}
		
  $fengefu='/';
  if($endid === 0){ $dirname=str_replace(array($dian,$fanxie),array($fengefu,$fengefu),$dirname); }
  $path_arr=explode('/',$dirname); 
	
  //如果末端没有文件名
  if(!$is_basename){  array_push($path_arr,$basename);	}  
  
	$path_count=count($path_arr); 
	$i=0; $is_first=true;	 $this->shangji_id=0; //初始化
	foreach($path_arr as $path_v){ 
	 $i++;
	 $path_v=trim($path_v);
	 if($path_v != ''){					
			
			if($is_first){
				 $sid=0;
				 $is_first=false;
			}else{
				 $sid=$this->shangji_id;
			}

			$cha_xiaji_ok=$this->_cha_xiaji_name($sid,$path_v);
			if(!$cha_xiaji_ok && $i != $path_count){

				 //查到末端都不存在
				 return $retrun_arr;

			}elseif(!$cha_xiaji_ok){
			
				 //查到途中不存在
				 unset($endid_dir);
			}else{          
				 
				 //都存在
				 $endid_dir=(int)$this->shangji_id;			 
				 
			}
	 }

	}


	//查询文件名是否存在
	if($endid_dir > 0){		 
		 $retrun_arr['endid']=$endid_dir;//目录存在

     if($is_basename){
				$file_exists=$this->get_all_file($endid_dir,$basename);
				if(empty($file_exists)){
					 
					 unset($retrun_arr['endid']);
					 return $retrun_arr;  
				}else{
					 
					 $retrun_arr['endfile']  =$basename;
				}
			}

	} 

	return $retrun_arr;
  
	
}


/*
根据上级目录名称查下级目录名称是否存在
返回值：存在返回true  不存在返回false
注意：要初始化$this->shangji_id=0;
*/
public function _cha_xiaji_name($shangji_id=null,$shangji_name=null){ 
  
	$shangji_id=(int)$shangji_id;
  if($shangji_name == ''){ return false;  }	

	$xiaji_info=$this->obj->db->query("SELECT `dir_id` FROM `".$this->obj->db->utable($this->obj->cfg_sys_dir_table_name)."` WHERE `dir_shangji`=$shangji_id AND `dir_name`='$shangji_name' LIMIT 1"); 
	if($xiaji_info){
		$xiaji_arr=$xiaji_info->fetch_assoc(); 
		$xiaji_info->free();

		if($xiaji_arr['dir_id'] > 0){  
		   $this->shangji_id=$xiaji_arr['dir_id'];
       return true;
		}else{
		   return false;
		}
		 
		
	}

}


/*
获取归属目录下所有的文件，或指定文件
参数：$dir_id=目录id,$filename=指定的文件名
返回值：array('1.01.jpg','1.02.jpg')， 如果没查到返回空数组
*/
public function get_all_file($dir_id=0,$filename=null){

   $file_name_where=(string)$filename == '' ? '': " AND `qrcode_name` = '$filename'";
   $dir_id=(int)$dir_id;
   $qrcode_info=$this->obj->db->query("SELECT `qrcode_id`,`qrcode_name` FROM `".$this->obj->db->utable($this->obj->cfg_sys_qrcode_table_name)."` WHERE `qrcode_dir`=$dir_id".$file_name_where);   
   
	 $return_qrcode_arr=array();
   if($qrcode_info){
	  while($qrcode_arr=$qrcode_info->fetch_assoc()){ 			 
			 $return_qrcode_arr[]=$qrcode_arr['qrcode_name'];
	  }
	  $qrcode_info->free();
	   
   }

	 return $return_qrcode_arr;

}


}
?>