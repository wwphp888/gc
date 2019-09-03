<?php
/*
功能：POST参数别名定义和修改
作者：宇卓
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-07-03 18:08
*/
class PostAlias{

public $set_post_alias='set_post_alias';

//post参数别名
public $post_alias=array(
'guishu'          =>'guishu',
'encode_uid'      =>'encode_uid',
'encode_username' =>'encode_username',
'username'        =>'username',
'paytype'         =>'paytype',
'paytype_value'   =>array(SKL_PAYTYPE_ALIPAY=>SKL_PAYTYPE_ALIPAY,SKL_PAYTYPE_WXPAY=>SKL_PAYTYPE_WXPAY,SKL_PAYTYPE_TENPAY=>SKL_PAYTYPE_TENPAY),
'out_trade_no'    =>'out_trade_no',
'price'           =>'price',
'return_url'      =>'return_url',
'is_mobile'       =>'is_mobile',
);

//修改post参数别名
public function _setPostAlias($arr=array()){

  foreach($arr as $k=>$v){
	   if(array_key_exists($k,$this->post_alias) && $v != ''){
			  if(is_array($v)){
				   $this->post_alias[$k]=$this->_updateArr($this->post_alias[$k],$v);
				}else{
				   $this->post_alias[$k]=$v;
				}
		    
		 }
	}

}

//修改数组对应键值
public function _updateArr($arr1=array(),$arr2=array()){

  foreach($arr2 as $k=>$v){
	  if(array_key_exists($k,$arr1)){
			 if(is_array($v)){
			   $arr1[$k]=$this->_updateArr($arr1[$k],$v);
			 }else{
			   $arr1[$k]=$v;
			 }
		   
		}
	}

	return $arr1;

}


}
?>