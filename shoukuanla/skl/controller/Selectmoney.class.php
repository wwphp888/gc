<?php
/*
功能：输出选择金额样式
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-13 18:56
*/
class Selectmoney extends ShoukuanlaBase{
private $is_include_style=false;

function __construct(){  
  //加载用户配置
	$this->_newDbCfg('`cfg_money_group`,`cfg_paytype_order`,`cfg_is_other_money`');
 
}

/*调用方法：
<?php 
$_GET['m']='skl';
$_GET['c']='Selectmoney';
$_GET['a']='external';
require_once(dirname(__FILE__).'/../../../shoukuanla/index.php');
$skl_moneyname=$shoukuanla->moneyname();

foreach($skl_moneyname as $skl_v){	
  echo $skl_v;		
}
?>*/
public function moneyname(){
  return $this->cfg_money_group;
}



//输出选择金额组样式
/*调用方法：      
<?php 
$_GET['m']='skl';
$_GET['c']='Selectmoney';
$_GET['a']='external';
require_once(dirname(__FILE__).'/../shoukuanla/index.php');
$shoukuanla->showmoney(string=表单中的金额字段名称(name),string=li标签样式表);
?>
*/
public function showmoney($skl_moneyName='money',$stylesheet=''){

  if($stylesheet != ''){ $stylesheet='style="'.$stylesheet.'"';  }

  require(SKL_CONTROLLER_VIEW_PATH.'showmoney.php');

}


//引入jQuery
public function include_jQuery(){

return '<script type="text/javascript">
if(typeof jQuery == "undefined"){
	document.write("<script src=\''.SKL_PUBLIC_PATH.'js/jquery.min.js\'><\/script>");
}
</script>';

}


//公共样式
public function public_style(){

if(!$this->is_include_style){
	$this->is_include_style=true; 

	return '
li{ list-style:none; }  
.skl_selectli{
background-image: url('.SKL_STATIC_PATH.'images/select.png);
background-repeat: no-repeat;
background-position: right bottom;
background-color: #E1F5FF;
}
		';
}


}


//输出支付方式样式
/*调用方法：      
<?php 
$_GET['m']='skl';
$_GET['c']='Selectmoney';
$_GET['a']='external';
require_once(dirname(__FILE__).'/../shoukuanla/index.php');
$shoukuanla->showpaytype(array('alipay'=>'在表单中用什么值代表支付宝','wxpay'=>'在表单中用什么值代表微信','tenpay'=>'在表单中用什么值代表QQ钱包'),string=支付类型在表单中的字段名称(name),string=img标签样式表);
?>
*/
public function showpaytype($paytype=array(),$inputName='paytype',$stylesheet='height:30px'){

  $paytype_start=array_keys($this->cfg_paytype_order,1);
  if(!empty($paytype_start)){
		$this->_newCfg('cfg_paytype_name','','',SKL_ROOT_PATH.'config.php');//加载对接配置  

		//默认值
		foreach($this->cfg_paytype_name as $type_k=>$type_v){	
			$paytype_default[$type_k]=$type_k;

		}
	 
		if(empty($paytype)){ $paytype=array(); }
		$paytype_new=array_merge($paytype_default,$paytype);

		if($stylesheet != ''){ $stylesheet='style="'.$stylesheet.'"';  }

		require(SKL_CONTROLLER_VIEW_PATH.'showpaytype.php'); 
  } 

}

public function external(){ }


}
?>