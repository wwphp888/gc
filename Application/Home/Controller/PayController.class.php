<?php
namespace Home\Controller;
use Think\Controller;
/**
* 第三方支付集合
*/
class PayController extends HomeController {
	public function _initialize(){
		parent::_initialize();
		if(CONTROLLER_NAME == 'Pay'){
			$param = array(
				'out_trade_no' => $_GET['out_trade_no'],
				'money' => $_GET['money'],
				'name' => $_GET['name'],
				'callback' => $_GET['callback'],
				'sign' => $_GET['sign']
			);
			$check_sign = check_url_sign($param);
			if(!$check_sign){
				die('支付地址无效');
			}
		}
		$this -> out_trade_no = $_GET['out_trade_no'];
		$this -> money = $_GET['money'];
		$this -> name = empty($_GET['name']) ? $this -> _site['name'].'在线支付' : $_GET['name'];
	}
	
	// 微信支付
	function wxpay(){
		
		$jsapi = new \Common\Util\wxjspay;
		$param = $this -> _mp;
		$param['key'] = $this -> _mp['key'];
		
		$param['openid'] = $this -> user['openid'];
		$param['body'] = $this -> name;
		$param['out_trade_no'] = $this -> out_trade_no;
		$param['total_fee'] = $this -> money * 100;
		$param['notify_url'] = "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/wx_notify.php';
		$jsapi -> set_param($param);
		$uo = $jsapi -> unifiedOrder(IS_WECHAT ? 'JSAPI' : 'NATIVE');
		
		// 发生错误则提示
		if(!$uo){
			$this -> error($jsapi -> errmsg);
		}
		
		// 微信中发起支付
		$prepay_id = $uo['prepay_id'];
		$jsapi_params = $jsapi -> get_jsApi_parameters();
		$this -> assign('jsApiParameters', $jsapi_params);
		
		// 支付成功之后跳转地址
		$this -> assign('callback',$_GET['callback']);
		
		$this -> display();
	}
	
	// 支付宝支付
	public function alipay(){
		
		if(IS_WECHAT && empty($_GET['iframe'])){
			$_GET['iframe'] = 1;
			$url = U(null,$_GET);
			$this -> assign('url',$url);
			$this -> display();
			exit;
		}
		
		$alipay_config = $this -> _alipay;
		
		$parameter = array(
			"service"       => IS_MOBILE ? 'alipay.wap.create.direct.pay.by.user' : 'create_direct_pay_by_user',
			"partner"       => $this -> _alipay['pid'],
			"seller_id"  	=> $this -> _alipay['pid'],
			"payment_type"	=> 1,
			"notify_url"	=> complete_url('/alipay_notify.php'),
			"return_url"	=> complete_url($_GET['callback']),
			"anti_phishing_key"=> '',
			"exter_invoke_ip"=> '',
			"out_trade_no"	=> $this -> out_trade_no,
			"subject"	=> $this -> name,
			"total_fee"	=> $this -> money,
			"body"	=> '',
			"_input_charset"	=> strtolower('utf-8')	
		);
		
		$alipay_config['sign_type'] = strtoupper('MD5');
		
		$alipay = new \Common\Util\Alipay\AlipaySubmit($alipay_config);
		$html_text = $alipay->buildRequestForm($parameter,"get", "确认");
		echo $html_text;
	}
	
}?>