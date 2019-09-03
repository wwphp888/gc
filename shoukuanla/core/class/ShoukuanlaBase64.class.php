<?php
/*
功能:加密解密数据,可逆的加密算法
使用方法:	
require_once(SKL_ClASS_PATH.'ShoukuanlaBase64.class.php');
$ShoukuanlaBase64=new ShoukuanlaBase64();
$str='abc';
$ShoukuanlaBase64->encrypt($str);
修改时间：2019-01-25 18:04
*/
class ShoukuanlaBase64{
private $password=array();
//private $abc_arr1=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
//private $abc_arr2=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
private $url_replace1=array('+','/','=');
private $url_replace2=array('-','_','!');
private $decrypt_arr=array(
   'a'=>'Q','A'=>'m',
	 'b'=>'W','B'=>'n',
	 'c'=>'E','C'=>'b',
	 'd'=>'R','D'=>'v',
	 'e'=>'T','E'=>'c',
	 'f'=>'Y','F'=>'x',
	 'g'=>'U','G'=>'z',
	 'h'=>'I','H'=>'l',
	 'i'=>'O','I'=>'k',
	 'j'=>'P','J'=>'j',
	 'k'=>'A','K'=>'h',
	 'l'=>'S','L'=>'g',
	 'm'=>'D','M'=>'f',
	 'n'=>'F','N'=>'d',
	 'o'=>'G','O'=>'s',
	 'p'=>'H','P'=>'a',
	 'q'=>'J','Q'=>'p',
	 'r'=>'K','R'=>'o',
	 's'=>'L','S'=>'i',
	 't'=>'Z','T'=>'u',
	 'u'=>'X','U'=>'y',
	 'v'=>'C','V'=>'t',
	 'w'=>'V','W'=>'r',
	 'x'=>'B','X'=>'e',
	 'y'=>'N','Y'=>'w',
	 'z'=>'M','Z'=>'q',
	 '0'=>'9',
	 '1'=>'8',
	 '2'=>'7',
	 '3'=>'6',
	 '4'=>'5',
	 '5'=>'4',
	 '6'=>'3',
	 '7'=>'2',
	 '8'=>'1',
	 '9'=>'0',
   '+'=>'+',
   '/'=>'/',
   '='=>'=',
);

function __construct($pass=null){ 

  $this->password=& $this->decrypt_arr;
	if($pass != ''){ $this->set_password($pass); }
}

//设置密码，密码只允许字母和数字组合
public function set_password($pass=null){

	if($pass != ''){
		 
     $md5_pass=md5($pass);

     //筛选出字母
		 $unique_array=array_unique(str_split($md5_pass),SORT_STRING);
		 $unique_new_array=array();
		 foreach($unique_array as $val){
		    
				if(in_array($val,$this->abc_arr1,true)){
				   $unique_new_array[]=$val;
				}

		 }

	   //未完成............
	}
  
}

//base64加密用于url传输
public function encrypt_replace_url(& $str=null){

  return str_replace($this->url_replace1,$this->url_replace2,base64_encode($str));
}

//base64解密url传输数据
public function decrypt_replace_url(& $str=null){

  return str_replace($this->url_replace2,$this->url_replace1,base64_decode(trim($str)));
}

//加密用于url传输(自定义算法)
public function encrypt_url(& $str=null,$password=null){

  return str_replace($this->url_replace1,$this->url_replace2,$this->encrypt($str,$password));
}

//加密(自定义算法)
public function encrypt(& $str=null,$password=null){
  
  if($str == ''){ return ''; }
 
	$byteArray=str_split(base64_encode($str));
	foreach($byteArray as $bv){			 
		 $byteArrayNew.=$this->password[$bv];
	}

	return $byteArrayNew;   
 
}

//解密url传输数据(自定义算法)
public function decrypt_url(& $str=null,$password=null){
  
  $url_str=str_replace($this->url_replace2,$this->url_replace1,$str);
	return $this->decrypt($url_str,$password); 

}


//解密(自定义算法)
public function decrypt(& $str=null,$password=null){

  if($str == ''){  return ''; }
 
	$byteArray=str_split(trim($str));
	foreach($byteArray as $bv){
		 $byteArrayNew.=array_search($bv,$this->password,true);
	}

	return base64_decode($byteArrayNew);   


}


}

?>