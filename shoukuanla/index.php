<?php 
/*
功能：收款啦PHP框架入口文件,兼容php5.2以上版本
作者：宇卓
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-06-30 18:33
*/
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);//显示除去E_WARNING E_NOTICE 之外的所有错误信息
header('Content-Type:text/html; charset=UTF-8');
ini_set('date.timezone','Asia/Shanghai');

define('SKL_MODULE_NAME_1','skl');//模块1名称
define('SKL_MODULE_NAME_2','admin');//模块2名称

$skl_defined_module     =defined('SKL_MODULE');
$skl_defined_controller =defined('SKL_CONTROLLER');
$skl_defined_action     =defined('SKL_ACTION');
$skl_m=$skl_defined_module ? SKL_MODULE : strtolower(trim($_GET['m']));
$skl_c=$skl_defined_controller ? SKL_CONTROLLER : ucfirst(trim($_GET['c']));
$skl_a=$skl_defined_action ? SKL_ACTION : strtolower(trim($_GET['a']));
if(empty($skl_m)){ $skl_m=SKL_MODULE_NAME_1; }
if(empty($skl_c)){ $skl_c='Index'; }
if(empty($skl_a)){ $skl_a='index'; } 
if(!$skl_defined_module){ define('SKL_MODULE',$skl_m); }
if(!$skl_defined_controller){ define('SKL_CONTROLLER',$skl_c); } 
if(!$skl_defined_action){ define('SKL_ACTION',$skl_a); } 
 

//定义支付类型
define('SKL_PAYTYPE_ALIPAY','alipay');
define('SKL_PAYTYPE_WXPAY','wxpay');
define('SKL_PAYTYPE_TENPAY','tenpay');

if(stripos(SKL_ACTION, '_') === 0){  exit;  }//禁止浏览器访问下划线开头的成员函数

define('SKL_WEBROOT_PATH','/shoukuanla/');  //收款啦文件夹路径(相对于网站根目录)
define('SKL_ROOT_PATH',dirname(__FILE__).'/');//入口文件路径
define('SKL_ClASS_PATH',SKL_ROOT_PATH.'core/class/'); //核心类库路径 
define('SKL_SYS_FUNCTIONS_PATH',SKL_ROOT_PATH.'core/functions/'); //系统函数路径
define('SKL_CONTROLLER_PATH',SKL_ROOT_PATH.SKL_MODULE.'/controller/'); //当前模块控制器路径
define('SKL_VIEW_PATH',SKL_ROOT_PATH.SKL_MODULE.'/view/'); //当前模块视图路径
define('SKL_CONTROLLER_VIEW_PATH',SKL_VIEW_PATH.SKL_CONTROLLER.'/'); //当前控制器视图路径
//define('SKL_CONFIG_PATH',SKL_ROOT_PATH.SKL_MODULE.'/conf/'); //当前模块配置文件路径
define('SKL_G_CONTROLLER_PATH',SKL_ROOT_PATH.'common/controller/'); //全局控制器路径
define('SKL_G_FUNCTIONS_PATH',SKL_ROOT_PATH.'common/functions/'); //全局函数路径
define('SKL_G_CONFIG_PATH',SKL_ROOT_PATH.'common/conf/'); //全局配置文件夹路径
define('SKL_G_VIEW_PATH',SKL_ROOT_PATH.'common/view/'); //全局视图文件夹路径
define('SKL_STATIC_PATH',SKL_WEBROOT_PATH.'static/'.SKL_MODULE.'/'); //静态文件夹路径(包含当前模块路径)
define('SKL_PUBLIC_PATH',SKL_WEBROOT_PATH.'static/public/'); //静态文件公用文件夹

require_once(SKL_G_FUNCTIONS_PATH.'functions.php');//加载全局公用函数
$cFileName=SKL_CONTROLLER_PATH.SKL_CONTROLLER.'.class.php';

//报错,开始
if(version_compare(PHP_VERSION,'5.2.0','<')){  skl_error('PHP版本不能低于5.2 !'); }//检测PHP版本
if(SKL_WEBROOT_PATH == '/'){ skl_error('收款啦PHP框架入口文件('.basename($_SERVER['PHP_SELF']).')不能放在网站根目录下');  }
if(!defined('SKL_IS_ADMIN')){	if(SKL_MODULE == SKL_MODULE_NAME_2){ skl_error('只允许授权的文件访问后台'); }}
//报错,结束

if(file_exists($cFileName)){ 
	require_once(SKL_ClASS_PATH.'ShoukuanlaBase.class.php');
	require_once($cFileName);

	$shoukuanla=new $skl_c();
	if(method_exists($shoukuanla,SKL_ACTION)){
		$shoukuanla->$skl_a(); 
	}else{
		skl_error(SKL_ACTION.'该成员函数不存在！');
	}
}


?>