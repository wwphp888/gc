<?php 
//获取远程更新数据
//修改时间：2019-01-18 18:58

/*
使用方法：
//获取远程更新数据，开始
require_once(SKL_G_CONTROLLER_PATH.'Getupdate.class.php');
$Getupdate=new Getupdate($object='');
$Getupdate->index();
//获取远程更新数据，结束
*/
class Getupdate{
private $obj;
private $curl;
private $api_url=array(
	  'http://api.shoukuanla.net/index.php?m=PayApi&c=Apipublic&a=getzip',
	  'http://api.chonty.com/index.php?m=PayApi&c=Apipublic&a=getzip',
);

function __construct(& $obj=null,& $curl=null){
  
	//检测需要的变量值
	//if($obj->cfg_xxx == ''){  skl_error('');   }	

	if(is_object($curl)){
     $this->curl=$curl;
	}else{
		 require_once(SKL_ClASS_PATH.'ShoukuanlaCurl.class.php');
	   $this->curl=new ShoukuanlaCurl(); 
	}

	$this->obj=$obj;
	
}

//获取版本号、下载信息...
public function index($param=null){
  if($param != ''){ $param='&'.$param; }

	foreach($this->api_url as $url){
		 $curl_data=json_decode($this->curl->get($url.$param),true);
		 if(!empty($curl_data)){ return $curl_data; }
	}	
	
  return array();

}


}

?>