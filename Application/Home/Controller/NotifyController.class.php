<?php

namespace Home\Controller;

use Think\Controller;

class NotifyController extends Controller {

	

	public function _initialize(){

		// 加载配置

		$config = M('config') -> select();

		if(!is_array($config)){

			die('请先在后台设置好各参数');

		}

		foreach($config as $v){

			$key = '_'.$v['name'];

			$this -> $key = unserialize($v['value']);

			$_CFG[$v['name']] = $this -> $key;

		}

		$GLOBALS['_CFG'] = $_CFG;

	}

    

	// 微信支付通知异步页面

	public function index(){



		$jsapi = new \Common\Util\wxjspay;

		$jsapi -> set_param('key', $this -> _mp['key']);

		

		// 验证签名之前必须调用get_notify_data方法获取数据

		$data = $jsapi -> get_notify_data();

		

		file_put_contents('wxpay.log',json_encode($data)."\r\n",FILE_APPEND);

		if(!$jsapi->check_sign()){

			file_put_contents('wxpay.log',"\r\nCHECK SIGN FAIL\r\n",FILE_APPEND);

			// 签名验证失败

			die('FAIL');

		}

		if($data['return_code'] != 'SUCCESS' || $data['result_code'] != 'SUCCESS'){

			file_put_contents('wxpay.log',"\r\RETURN CODE FAIL\r\n",FILE_APPEND);

			die('FAIL');

		}

		$sn = $data['out_trade_no'];

		$money = $data['total_fee']/100;

		$payway = 'wxpay';		

		$type = substr($sn,0,1);
		

		$data['log_time'] = NOW_TIME;
        $is_pay = M('wxpay_log')->where(array('out_trade_no'=>$data['out_trade_no']))->find();
		if($is_pay){

			die;

		}		
        // 记录支付日志

		$data['log_time'] = NOW_TIME;

		$data['type'] = $type;

		if($data){

		 M('wxpay_log') -> add($data);

		} 
		$user_info = M('user') ->where(array('openid'=>$data['openid']))->find();
		if($data['out_trade_no']&&$type==2){

		   $plant = M('user_plant')->where(array('codeid'=>$data['out_trade_no']))->find();

		   if($plant && $plant['status']==0){

		   	 M('user_plant')->where(array('codeid'=>$data['out_trade_no']))->save(array('status'=>1));
             tuidan($data['openid'], $plant['name'], $plant['price']); 
		   }



		}
        if($data['out_trade_no']&&$type==3){

		   $fertilizer = M('user_fertilizer')->where(array('codeid'=>$data['out_trade_no']))->find();

		   if($fertilizer && $fertilizer['status']==0){

		   	 M('user_fertilizer')->where(array('codeid'=>$data['out_trade_no']))->save(array('status'=>1));

             tuidan($data['openid'], $fertilizer['name'], $fertilizer['price']); 

		   }



		}
		if($data){

		 if($type!=8||$type!=4){

		 expense($user_info,$money,$type);

		 }

	    }				

		
		$tables = array(

			1 => 'land',

			3 => 'user_fertilizer',

			4 => 'charge',

			8 => 'chou'

		);

		if($type!=9&&$type!=''){
		
		$table = $tables[$type];
		
		if(empty($table)){

			//elog('wxpay out_trade_no type error');

			//die('FAIL');

		}

		$id = substr($sn,1);

		$info = M($table) -> find($id);
		//$info = M($table) -> where('out_no ='.$id)-> find();

		M($table) -> save(array(

			'id' => $id,

			'status' => 1,

			'pay_time' => NOW_TIME,

			'payway' => 'wxpay',

			'paid' => $money

		));
//file_put_contents('wxpay3.log',"bbb".$user_info['id']."aaa\r\n",FILE_APPEND);
		}

		// 如果是扩建，则需要增加用户的土地

		
		
		
		if($type == 1){
             
			M('user') -> where('id='.$info['user_id']) -> setInc('lands');

		}

		// 如果是充值

		elseif($type == 4){

			M('user') -> where('id='.$user_info['id']) -> save(array(

				'money' => array('exp','money+'.$money)
				//'money' => array('exp','money+'.$money)

			));;

		}

		

		

		

		if($data['out_trade_no']){

              $wei = strpos($data['out_trade_no'],'1481');

		      $num = substr($data['out_trade_no'], 1,$wei-1);

		      $num = $num?$num:1;

		      for($i=1;$i<=$num;$i++){

			  // paiwei($user_info,1);//执行排位

		      }

			 M('wxpay_log')->where(array('out_trade_no'=>$data['out_trade_no']))->save(array('is_gong'=>1)); 

		}

      

		

		// 支付日志

		if(M('wxpay_log') -> where(array('transaction_id' => $data['transaction_id'])) -> find()){

			die('SUCCESS');

		}

		die('SUCCESS');

		

	}// index

	

	// 支付宝同步/异步通知

	public function alipay(){

		$alipay_config['partner'] 		= $GLOBALS['_CFG']['alipay']['pid'];

		$alipay_config['transport']		= 'http';

		$alipay_config['sign_type']		= strtoupper('MD5');

		$alipay_config['key']			= $GLOBALS['_CFG']['alipay']['key'];



		$alipayNotify = new \Common\Util\Alipay\AlipayNotify($alipay_config);

		

		if(IS_POST){

			$verify_result = $alipayNotify->verifyNotify();

		}else{

			$verify_result = $alipayNotify->verifyReturn();

		}

		

		if($verify_result){//验证成功

			

			//商户订单号

			$out_trade_no = $_REQUEST['out_trade_no'];



			//支付宝交易号

			$trade_no = $_REQUEST['trade_no'];



			//交易状态

			$trade_status = $_REQUEST['trade_status'];



			if($_REQUEST['trade_status'] == 'TRADE_FINISHED' || $_REQUEST['trade_status'] == 'TRADE_SUCCESS') {

				// 先判断是否处理

				$has_done = M('alipay_log') -> where(array(

					'out_trade_no' => $out_trade_no,

					'trade_no' => $trade_no

				)) -> find();

				

				// 没有处理过才处理

				if(!$has_done){					

					$money = $_REQUEST['total_fee'];

					$payway = 'alipay';

					

					$type = substr($out_trade_no,0,1);

					

					$tables = array(

						1 => 'land',

						2 => 'user_plant',

						3 => 'user_fertilizer',

						4 => 'charge',

						8 => 'chou'

					);

					$table = $tables[$type];

					if(empty($table)){

						elog('alipay out_trade_no type error');

						die('FAIL');

					}

					$id = substr($out_trade_no,1);

					$info = M($table) -> find($id);



					M($table) -> save(array(

						'id' => $id,

						'status' => 1,

						'pay_time' => NOW_TIME,

						'payway' => 'wxpay',

						'paid' => $money

					));



					// 如果是扩建，则需要增加用户的土地

					if($type == 1){

						M('user') -> where('id='.$info['user_id']) -> setInc('lands');

					}

					// 如果是充值

					elseif($type == 4){

						M('user') -> where('id='.$info['user_id']) -> save(array(

							'money' => array('exp','money+'.$info['money'])

						));;

					}

					

					

					M('alipay_log') -> add(array(

						'out_trade_no' => $out_trade_no,

						'trade_no' => $trade_no,

						'total_fee' => $_REQUEST['total_fee'],

						'create_time' => NOW_TIME,

						'seller_email' => $REQUEST['seller_email'],

						'buyer_email' => $REQUEST['buyer_email'],

						'seller_id' => $REQUEST['seller_id'],

						'buyer_id' => $REQUEST['buyer_id'],

					));

				}

			}else die('error');

			

			if(IS_POST){

				echo "success";		//请不要修改或删除

			}

			else{

				redirect('index.php?a=ucenter');

			}

			

		}

		else {

			//验证失败

			echo "fail";

		}

	}

	

	// 抽抽乐

	private function _chou(){

		

	}

}?>