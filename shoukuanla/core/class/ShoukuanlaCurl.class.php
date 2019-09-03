<?php
/*
功能:curl获取远程数据、下载文件
使用方法:	
require_once(SKL_ClASS_PATH.'ShoukuanlaCurl.class.php');
$curl=new ShoukuanlaCurl();
if($curl->iscurl()){
	//需要设置的项目
	curl_setopt($curl->ch, CURLOPT_TIMEOUT,8);
}else{
	$options=array(
	  'http' =>array(
		   'timeout'        =>8, // 超时时间(秒)
	  )
  );
  $curl->set_context($options);
}
$curl->post('http://www.shoukuanla.net','a=1&b=2');

修改时间：2019-01-21 18:04
*/
class ShoukuanlaCurl{
public $ch;
private $context=array();
private $FileUtil;
private $is_curl;
private $url;
private $timeout=10;

function __construct($url=null){  
  
	$this->url=$url;
  $this->is_curl=extension_loaded('curl');
	//判断curl扩展是否开启
	if($this->is_curl){
	
		$this->ch=curl_init(); //初始化一个 cURL 对象
		if($this->url != ''){  curl_setopt($this->ch, CURLOPT_URL,$this->url);	}//设置抓取的URL
		curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,true);  //请求结果保存到字符串中还是输出到屏幕上(false=输出 true=不输出)
		curl_setopt($this->ch,CURLOPT_TIMEOUT,$this->timeout);  //默认的响应允许执行的最长时间(秒)
		curl_setopt($this->ch,CURLOPT_FOLLOWLOCATION,true); //跟随“Location: " 重定向
		//curl_setopt($this->ch,CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'); 
		//curl_setopt($this->ch,CURLOPT_HEADER,true);//输出头部信息

	}else{
	   require_once(SKL_SYS_FUNCTIONS_PATH.'skl_array_replace_recursive.php');
	}

}

public function iscurl(){  return $this->is_curl; }

//设置context
public function set_context(& $arr=array()){  
   $this->context=array();
	 $this->context=$arr;
}

/*
get获取远程数据
参数：$url_get=请求地址
返回值(string)：字符串数据
*/
public function get($url_get=null){

  if($url_get == ''){ $url_get=& $this->url; }
  if($this->is_curl){
		curl_setopt($this->ch,CURLOPT_URL,$url_get);//设置抓取的URL	
    return curl_exec($this->ch);

	}else{  
	  
		$options=array(
			'http' =>array(
				'method'            =>'GET',
				'timeout'           =>$this->timeout, // 超时时间（单位:s）
			)
		);
    if(!empty($this->context)){ $options=skl_array_replace_recursive($options,$this->context); }

		return file_get_contents($url_get,false,stream_context_create($options));
	}


}

/*
post获取远程数据
参数：$url_post=请求地址,$postfields=参数(a=1&b=2)
返回值(string)：字符串数据
*/
public function post($url_post=null,$postfields=null){
  
  if($url_post == ''){ $url_post=& $this->url; } 
	if($this->is_curl){
	   curl_setopt($this->ch, CURLOPT_URL,$url_post);//设置抓取的URL	
	   curl_setopt($this->ch, CURLOPT_POST,true);
	   curl_setopt($this->ch, CURLOPT_POSTFIELDS,$postfields); 

	   return curl_exec($this->ch);
	}else{

		 $options=array(
			'http' =>array(
				'method'            =>'POST',
				'timeout'           =>$this->timeout, // 超时时间（单位:s）
		    'header'            =>'Content-type: application/x-www-form-urlencoded',
        'content'           =>is_array($postfields) ? http_build_query($postfields) : $postfields,
			)
		 );
     if(!empty($this->context)){ $options=skl_array_replace_recursive($options,$this->context); }

     return file_get_contents($url_post,false,stream_context_create($options));
	}

}

/*
下载文件并保存
参数：$url_download=请求地址,$save_path=保存路径
返回值(bool)：成功返回保存的文件名 false=失败
注意：if($curl->isurl()){ curl_setopt($curl->ch, CURLOPT_TIMEOUT,0); }//等待时间需要设置最大
*/
public function download($url_download=null,$save_path=null){

	if($save_path == ''){ $save_path='./'; }

  if(!is_object($this->FileUtil)){
	   require_once(SKL_ClASS_PATH.'ShoukuanlaFileUtil.class.php');
	   $this->FileUtil=new ShoukuanlaFileUtil();	
	}
  
	if(!$this->FileUtil->createDir($save_path)){ skl_error($save_path.' 该目录创建失败，或是没有写入权限！'); }
	if(!is_writable($save_path)){ skl_error($save_path.' 该目录没有写入权限！');  }

  if($url_download == ''){ $url_download=& $this->url; } 
	if($this->is_curl){		
		 curl_setopt($this->ch, CURLOPT_URL,$url_download);//设置抓取的URL
		 $return_data=curl_exec($this->ch);	
     $return_header=curl_getinfo($this->ch,CURLINFO_CONTENT_TYPE);

	}else{

		 $options=array(
				'http' =>array(
					'method'            =>'GET',
				)
			);

		 if(!empty($this->context)){ $options=skl_array_replace_recursive($options,$this->context); }	   
	   $return_data=file_get_contents($url_download,false,stream_context_create($options));

     //查找出指定内容，开始
		 foreach($http_response_header as $val){
        $val=trim($val);
		    if(stripos($val,'Content-Type') === 0){
					$return_header=explode(':',$val);
					$return_header=$return_header[1];  
					break;
				}
		 }		 
     //查找出指定内容，结束
	}
 
  if($return_data === false){ return false; }
  
	$content_type_arr=explode(';',$return_header);
	$content_type=explode('/',trim($content_type_arr[0]));
  if(strtolower($content_type[0]) == 'application'){
    
		$save_filename=pathinfo($url_download,PATHINFO_FILENAME).'.'.$content_type[1];
		$fp=fopen($save_path.'/'.$save_filename,'w');
	  $fwrite=fwrite($fp,$return_data);
	  fclose($fp);
		
		return $fwrite === false ? $fwrite : $save_filename;
	}

	return false;

}


//析构函数
function __destruct(){
	if($this->is_curl){  curl_close($this->ch);  }//关闭URL请求	
}

}
?>