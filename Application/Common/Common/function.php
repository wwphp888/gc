<?php



function erweima($uid){
	    include 'Public/phpqrcode/phpqrcode.php';    
		$value = 'http://houjidao.cn/tg.php?mid='.$uid; //二维码内容  
		$errorCorrectionLevel = 'L';//容错级别   
		$matrixPointSize =15;//生成图片大小   
		//生成二维码图片
		$imgDir = './Public/code/';  
        $filename=$uid.".png";///要生成的图片名字    
		QRcode::png($value,$imgDir.$filename, $errorCorrectionLevel, $matrixPointSize, 2);
		// $url = $imgDir.$filename;
		// $imgDir = './Public/code/';  
  //       $filename=$uid.".jpg";///要生成的图片名字                  
  //       $file = copy($url,($imgDir.$filename));//打开文件准备写入     
        return $imgDir.$filename;
}

// 对字符串进行加盐散列加密


function xmd5($str){


	return md5(md5($str).C('SAFE_SALT'));


}



// 返回最新比特币成交价格

function get_newbtc(){
	$info = M('btclist')->order('id desc')->find();
	return $info['last'];
}

// 获得当前的url


function get_current_url(){


	$url = "http://" . $_SERVER['SERVER_NAME'];


	$url .= $_SERVER['REQUEST_URI'];


	return $url;


}
function get_btc(){
     $url = 'https://www.okcoin.cn/api/v1/ticker.do?symbol=btc_cny';
     $info  = send_get($url);
     $list = json_decode($info,true);
     return $list;
}
function get_btc_list(){
	 $url = 'https://www.okcoin.cn/api/v1/trades.do?symbol=btc_cny&since=600';
     $info  = send_get($url);
     $list = json_decode($info,true);
     return $list;
}
function get_btc_new(){
	 $url = 'https://www.okcoin.cn/api/v1/kline.do?symbol=btc_cny&type=1min';
     $info  = send_get($url);
     $list = json_decode($info,true);
     return $list;
}
function send_get($url){
	$ch = curl_init($url) ;  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 
    $output = curl_exec($ch) ; 
    return $output;
}
function send_post($url,$data=null){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $info = curl_exec($ch);
    curl_close($ch);
    return $info;
}
/**
 * Curl请求
 * @param type $url
 * @param type $data
 * @param type $method
 * @return type
 */
function CurlUse($url, $data = '', $method = 'GET') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); //要访问的地址
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //对认证证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器  
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转  
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer 
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求  
        if ($data != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Post提交的数据包  
        }
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环  
    curl_setopt($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回  
    $tmpInfo = curl_exec($ch); // 执行操作  
    if (curl_errno($ch)) {
        echo 'Errno' . curl_error($ch) . '<br>'; //捕抓异常
        echo curl_strerror(curl_errno($ch));
    }
    curl_close($ch); // 关闭CURL会话  
    return $tmpInfo; // 返回数据 
}

function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
//返回当前的毫秒时间戳
function msectime() {
       list($tmp1, $tmp2) = explode(' ', microtime());
       return (float)sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
}
 

/** 格式化时间戳，精确到毫秒，x代表毫秒 */

function microtime_format($format = 'x', $utimestamp = null)
{
    if (is_null($utimestamp))
           $utimestamp = microtime(true);
 
       $timestamp = floor($utimestamp);
       $milliseconds = round(($utimestamp - $timestamp) * 1000000);
 
       return date(preg_replace('`(?<!\\\\)x`', $milliseconds, $format), $timestamp);
}
//发送微信消息


function sendwen($openid,$msg){


    $token = wx_token();


    $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}";


            $msg = str_replace('"', '\\"', $msg);


            $data = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $msg . '"}}';


    $ch = curl_init();


    curl_setopt($ch, CURLOPT_URL, $url);


    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);


    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);


    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');


    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);


    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);


    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    $info = curl_exec($ch);


    curl_close($ch);


    return $info;


}


function wxuser($openid){


  $token = wx_token();  


  $info = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN');


  return json_decode($info,true);


}


//获取token


function wx_token(){


    $appid = $GLOBALS['_CFG']['mp']['appid']; 


    $appsecret = $GLOBALS['_CFG']['mp']['appsecret']; 


    $token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;


    $token_content = file_get_contents($token_url);


    $token = @json_decode($token_content, true);


    $token = $token['access_token'];


    return $token;


}


// 补全url


function complete_url($url){


	$host = $GLOBALS['_CFG']['site']['url'];


	if(substr($url,0,1) == '.'){


		return $host.__ROOT__.substr($url,1);


	}


	elseif(substr($url,0,7) != 'http://' && substr($url,0,8) != 'https://'){


		return $host.$url;


	}


	else{


		return $url;


	}


}








// 根据GET参数生成一个支付URL签名


function make_url_sign($get){


	unset($get['sign']);


	ksort($get, SORT_STRING);


	$string =  urldecode(http_build_query($get));


	$string = xmd5($string);


	return substr($string,16,16);


}





// 根据GET参数检查URL签名


function check_url_sign($get){


	$sign = $get['sign'];


	if(empty($sign))return false;


	unset($get['sign']);


	$new_sign = make_url_sign($get);


	return $new_sign == $sign ? true : false;


}








// 根据自定义菜单类型返回名称


function get_selfmenu_type($type){


	$type_name = '';


	switch($type){


		case 'click':


			$type_name = '点击推事件';


			break;


		case 'view':


			$type_name = '跳转URL';


			break;


		case 'scancode_push':


			$type_name = '扫码推事件';


			break;


		case 'scancode_waitmsg':


			$type_name = '扫码推事件且弹出“消息接收中”提示框';


			break;


		case 'pic_sysphoto':


			$type_name = '弹出系统拍照发图';


			break;


		case 'pic_photo_or_album':


			$type_name = '弹出拍照或者相册发图';


			break;


		case 'pic_weixin':


			$type_name = '弹出微信相册发图器';


			break;


		case 'location_select':


			$type_name = '弹出地理位置选择器';


			break;


		default : $type_name = '不支持的类型';


	}


	return $type_name;


}





// 获取列表模板列表


function get_list_tpl(){


	$tpl_path = C('list_tpl_path');


	$dir_list = scandir($tpl_path);


	foreach($dir_list as $k => $v){


		if(in_array($v,array('.','..'))  || !is_dir($tpl_path.'/'.$v)){


			unset($dir_list[$k]);


		}


	}


	return $dir_list;


}





// 返回订单的状态


function get_order_status($status){


	$arr = array(


		-1 => '已取消',


		0 => '待审核',


		1 => '待服务',


		2 => '已完成'


	);


	if(!empty($arr[$status]))return $arr[$status];


	else return '未知';


}





// 发送短信


function send_sms($mobile,$msg,$sign = null, $orig = false){


	if(empty($sign))$sign = C('SMS_SIGN');


	$msg = "【".$sign."】".$msg;


	$url = "http://api.smsbao.com/sms?u=".C('SMS_USER')."&p=".md5(C('SMS_PASS'))."&m={$mobile}&c=".urlencode($msg);


	$rt =  file_get_contents($url);


	M('sms_log') -> add(array(


		'mobile' => $mobile,


		'msg' => $msg,


		'result' => $rt,


		'create_time' => NOW_TIME


	));


	if($orig)return $rt;


	return $rt == '0' ? true : false;


}





// 返回短信状态


function get_sms_result($rt){


	$code = array(


		0  => '发送成功',


		30 => '密码错误',


		40 => '账号不存在',


		41 => '余额不足',


		42 => '帐号过期',


		43 => 'IP地址限制',


		50 => '内容含有敏感词',


		51 => '手机号码不正确'


	);


	$return = !empty($code[$rt]) ? $code[$rt] : '其他错误';


	return  $return;


}





// 记录错误日志


function elog($msg){


	$log_file = "./log/error/".date('Ym/d').".log";


	if(!is_dir(dirname($log_file))){


		mkdir(dirname($log_file),0777,1);


	}


	$log_arr = array(


		date('H:i:s'),


		$msg,


		get_current_url(),


		$_SERVER['HTTP_USER_AGENT']


	);


	file_put_contents($log_file,implode("\t",$log_arr)."\n",FILE_APPEND);


}








// 发送客服提示消息


function send_msg($openid,$str){


	$dd = new \Common\Util\ddwechat($GLOBALS['_CFG']['mp']);


	$accesstoken = $dd -> getaccesstoken();


	$msg = array(


		'touser' => $openid,


		'msgtype' => 'text',


		'text' => array(


			'content' => $str


		)


	);


	$dd -> custommsg($msg);


}





// 获得用户信息,缓存（迟钝）


function get_user_info($user_id){


	$user_info = M('user') -> find($user_id);


	return $user_info;


	/*


	$key = S('user_info_'.$user_id);


	if(S($key)){


		return S($key);


	} else{


		$user_info = M('user') -> find($user_id);


		S($key,$user_info);


		return $user_info;


	}


	*/


}





// 微信企业转帐


function mch_wxpay($sn,$openid,$money,$remark = null){


	$param = array(


		'mch_appid' => $GLOBALS['_CFG']['mp']['appid'],


		'mchid' => $GLOBALS['_CFG']['mp']['mch_id'],


		'partner_trade_no' => $sn,


		'openid' => $openid,


		'check_name' => 'NO_CHECK', // 不验证名字


		'amount' => intval($money*100), // 金额，分


		'desc' => empty($remak) ? '系统转帐' : $remak,


	);


	


	$dd = new \Common\Util\ddwechat;


	$dd -> setParam($GLOBALS['_CFG']['mp']);
    if(substr($GLOBALS['_CFG']['mp']['cert'], 0,1)=='.'){
    	$GLOBALS['_CFG']['mp']['cert'] = substr($GLOBALS['_CFG']['mp']['cert'], 1);
    }

	$ssl = array(


		'sslcert' => getcwd() . $GLOBALS['_CFG']['mp']['cert'].'apiclient_cert.pem',


		'sslkey'  => getcwd() . $GLOBALS['_CFG']['mp']['cert'].'apiclient_key.pem',


	);


	$rt = $dd -> mch_pay($param, $ssl);


	if($rt['return_code'] == 'SUCCESS' && $rt['result_code'] == 'SUCCESS'){


		$status = 1;


	}


	else{


		$status = 0;


	}


	


	return array(


		'status' => $status,


		'return_code' => $rt['return_code'],


		'result_code' => $rt['result_code'],


		'return_msg'  => $rt['return_msg'],


		'err_code_des'  => $rt['err_code_des'],


		'err_code'  => $rt['err_code'],


		'payment_no'  => $rt['payment_no'], // 微信订单号


	);


}





// 记录财务日志


function flog($user_id,$type,$money,$action,$remark = null){


	if(CLI === true){


		$time = time();


	}else{


		$time = NOW_TIME;


	}


	M('finance_log') -> add(array(


		'user_id' => $user_id,


		'type' => $type,


		'money' => $money,


		'action' => $action,


		'create_time' => $time,


		'remark' => $remak


	));


}





// 取得财务动作名称


function get_flog_name($action){


	$arr = array(


		1 => '扩建土地',


		2 => '购买植物',


		3 => '购买肥料',


		4 => '采摘收入',


		5 => '提现',


		6 => '参与抽抽乐',


		7 => '抽瞅乐奖金',


		8 => '抽瞅乐推荐奖',


		9 => '偷菜',


		10 => '公排占点',


		11 => '施肥奖励',


		100 => '懒人奖',


		101 => '扩建分成',


		102 => '植物分成',


		103 => '肥料分成',


	);


	return $arr[$action];


}


// 取得微信支付参数


function get_wxpay_parameters($sn,$total,$openid,$remark = null){


	if(S('wxpay_'.$sn))return S('wxpay_'.$sn);


	// 微信支付


	$jsapi = new \Common\Util\wxjspay;


	$param = $GLOBALS['_CFG']['mp'];


	$param['key'] = $GLOBALS['_CFG']['mp']['key'];


	


	$param['openid'] = $openid;


	$param['body'] = empty($remark) ? '在线支付' : $remark;


	$param['out_trade_no'] = $sn;


	$param['total_fee'] = $total * 100;


	$param['notify_url'] = $GLOBALS['_CFG']['site']['url'].'/wx_notify.php';


	$jsapi -> set_param($param);


	$uo = $jsapi -> unifiedOrder('JSAPI');


	


	// 发生错误则提示


	if(!$uo){


		elog('[wxpay]'.$jsapi -> errmsg);


		return false ;


	}


	


	$jsapi_params = $jsapi -> get_jsApi_parameters();


	if($jsapi_params){


		S('wxpay_'.$sn,$jsapi_params);


	}


	return $jsapi_params;


}


//根据id获取用户信息


function get_user($id){


   $user_info = M('user')->where(array('id'=>$id))->find();


   return $user_info;


}


// 分成 type => 1表示扩建，2表示购买植物，3表示购买肥料



// 分成 type => 1表示公排直推，2表示静态100，3表示静态500 4代表静态1500

function expense($user_info,$money,$type){

    //$config = explode(';',$GLOBALS['_CFG']['site']['expense']);
	
    $config = M('cash')->find();
	$config[0] = $config['parent1'];
	$config[1] = $config['parent2'];
	$config[2] = $config['parent3'];
	
    //1
    if($user_info['parent1']){
    $parent_info = M('user') ->where(array('id'=>$user_info['parent1']))->find();
    $expense = $config[0]*$money/100;
	
    if($parent_info){

        $expense= array(

            'user_id' => $parent_info['id'],

            'buyer_id' => $user_info['id'],

            'moneys' => $expense,

            'level' => 1,

            'create_time' => time(),

            'type' => $type

        );
        $save = array('expense'=>$parent_info['expense']+$expense['moneys']);
        M('user')->where(array('id'=>$parent_info['id']))->save($save);
        $info = M('expense')->add($expense);
        if($info){
            $msg = "你的下级 ".$user_info['nickname']." 成功下单 恭喜您获到佣金奖".$expense['money']."元！\n购买时间:".date('Y-m-d H:i:s');
            sendwen($parent_info['openid'],$msg);
        }

     } 
    }
   //2
    if($user_info['parent2']){
    $parent_info = M('user') ->where(array('id'=>$user_info['parent2']))->find();
    $expense = $config[1]*$money/100;
    if($parent_info){

        $expense= array(

            'user_id' => $parent_info['id'],

            'buyer_id' => $user_info['id'],

            'moneys' => $expense,

            'level' => 2,

            'create_time' => time(),

            'type' => $type

        );
        M('user')->where(array('id'=>$parent_info['id']))->save(array('expense'=>$parent_info['expense']+$expense['moneys']));
        $info = M('expense')->add($expense);
        if($info){
            $msg = "你的下级 ".$user_info['nickname']." 成功下单 恭喜您获到佣金奖".$expense['money']."元！\n购买时间:".date('Y-m-d H:i:s');
            sendwen($parent_info['openid'],$msg);
        }

     } 
    }
    //3
    if($user_info['parent3']){
    $parent_info = M('user') ->where(array('id'=>$user_info['parent3']))->find();
    $expense = $config[2]*$money/100;
    if($parent_info){

        $expense= array(

            'user_id' => $parent_info['id'],

            'buyer_id' => $user_info['id'],

            'moneys' => $expense,

            'level' => 3,

            'create_time' => time(),

            'type' => $type

        );
        M('user')->where(array('id'=>$parent_info['id']))->save(array('expense'=>$parent_info['expense']+$expense['moneys']));
        $info = M('expense')->add($expense);
        if($info){
            $msg = "你的下级 ".$user_info['nickname']." 成功下单 恭喜您获到佣金奖".$expense['money']."元！\n购买时间:".date('Y-m-d H:i:s');
            sendwen($parent_info['openid'],$msg);
        }

     } 
    }
   
}





// 根据用户信息取得推广二维码路径信息


function get_qrcode_path($user){


	if(!is_array($user)){


		$user = M('user') -> find($user);


	}


	


	$path = './Public/qrcode/'.date('ym/d/',$user['sub_time']);


	return array(


			'path'		=> $path,


			'new'		=> $path.$user['id'].'_dragondean.jpg',


			'head' 		=> $path.$user['id'].'_head.jpg',


			'qrcode'	=> $path.$user['id'].'_qrcode.jpg',


			'full_path' => $_SERVER['DOCUMENT_ROOT'] . __ROOT__ . substr($path,1)


		);


}


function cget($url){


  $ch=curl_init();


  curl_setopt($ch,CURLOPT_URL,$url);


  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);


  curl_setopt($ch,CURLOPT_HEADER,0);


  $output = curl_exec($ch);


  curl_close($ch);


  return $output;


}


function cpost($url,$data=''){


    $ch = curl_init();


    curl_setopt($ch, CURLOPT_URL, $url);


    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);


    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);


    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');


    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);


    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);


    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    $info = curl_exec($ch);


    curl_close($ch);


    return $info;


}


//获取参数二维码


function cancode($uid){


    $token = wx_token();    


    $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;


    $data = array('action_name' => 'QR_LIMIT_STR_SCENE', 'action_info' => array('scene' => array('scene_str' => 'user_'.$uid)));


    $ret1 = cpost($url, json_encode($data));


    $content = @json_decode($ret1, true);


    $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($content['ticket']);


    $imgDir = './Public/code/';  


    $filename=time().rand(1,10000).".jpg";///要生成的图片名字          


    $jpg = $url;


    $file = copy($url,($imgDir.$filename));//打开文件准备写入     


    return $imgDir.$filename;


}

function tuidan($openid,$name,$price){
   $msg = '恭喜你购买'.$name."成功!\n金额:".$price."元\n时间:".date('Y-m-d H:i');
   sendwen($openid,$msg);
}
//自动生成二维码


function newqrcode($uid,$return_false = false){


	// 忽略用户取消，限制执行时间为90s

   
	ignore_user_abort();


	set_time_limit(90);


	


    $path = './Public/code/';


	$path_info =  array(


			'path'		=> $path,


			'new'		=> $path.$uid.'_dragondean.jpg',


			'head' 		=> $path.$uid.'_head.jpg',


			'qrcode'	=> $path.$uid.'_qrcode.jpg',


			'full_path' => $_SERVER['DOCUMENT_ROOT'] . __ROOT__ . substr($path,1)


		);

	


	// 已生成则直接返回


	if(is_file($path_info['new'])){


		return $path_info['new'];


	}


	


	// 目录不存在则创建
   if(!is_dir($path_info['path'])){


		mkdir($path_info['path'], 0777,1);


	}


	$dd = new \Common\Util\ddwechat($GLOBALS['_CFG']['mp']);


	


	if(!is_file($path_info['qrcode'])){


		$accesstoken = $dd -> getaccesstoken();


		$path_info['qrcode'] = cancode($uid);


	}


	// 合成


	$im_dst = imagecreatefromjpeg("./Public/images/newcode.jpg");


	$im_src = imagecreatefromjpeg($path_info['qrcode']);


	


	// 合成二维码（二维码大小282*282)


	imagecopyresized( $im_dst, $im_src,170, 520, 0, 0, 300, 300, 430, 430);



	




	$format_arr = array(


		1 => 'gif',


		2 => 'jpeg',


		3 => 'png'


	);


	


	// 保存


	imagejpeg($im_dst, $path_info['new']);


	// 销毁


	imagedestroy($im_src);


	imagedestroy($im_dst);


	return $path_info['new'];


}
// 生成二维码图片,return_false默认直接提示错误，当为true的时候返回false



function create_qrcode($user_info,$return_false = false){


	// 忽略用户取消，限制执行时间为90s
    if(is_array($user_info)){
    	$uid = $user_info['id'];
    }else{
    	$uid = $user_info;
    }
   
	ignore_user_abort();


	set_time_limit(90);


	


	$path_info = get_qrcode_path($user_info);


	


	// 已生成则直接返回


	if(is_file($path_info['new'])){


		return $path_info['new'];


	}


	


	// 目录不存在则创建


	if(!is_dir($path_info['path'])){


		mkdir($path_info['path'], 0777,1);


	}



	$dd = new \Common\Util\ddwechat($GLOBALS['_CFG']['mp']);


	$path_info['qrcode'] = erweima($uid);


	// 合成


	$im_dst = imagecreatefrompng("./Public/images/erweima.png");


	$im_src = imagecreatefrompng($path_info['qrcode']);


	// 合成二维码（二维码大小282*282)


	/*imagecopyresized( $im_dst, $im_src,170, 281, 0, 0,201,201, 480, 480);*/
	imagecopyresized( $im_dst, $im_src,180, 490, 0, 0,160,160, 490, 470);


	


	// 合成昵称

   if($user_info['id']){
	$str = "ID:".$user_info['id'];


	$color = ImageColorAllocate($im_dst, 0,0,0);


	$font_info = imagettfbbox( 20 , 0 , './Public/font/simhei.ttf' , $str );


	$width = $font_info[2] -  $font_info[0];


	$left = 10;


	$rs = imagettftext($im_dst, '20', 0, $left, 30, $color, './Public/font/simhei.ttf',  $str);

   }
	


	// 远程头像且本地没有保存,则获取远程头像到本地


	if(!(strpos($user_info['headimg'],'http://') === false) && !is_file($path_info['head'])){


		// $head_img = $dd -> exechttp($user_info['headimg'], 'get', null , true);


		// $head = file_put_contents($path_info['head'], $head_img);


	}


	else{


		$path_info['head'] = $user_info['headimg'];


	}


	


	$head_info = getimagesize($path_info['head']);


	$format_arr = array(


		1 => 'gif',


		2 => 'jpeg',


		3 => 'png'


	);


	if(!empty($format_arr[$head_info[2]])){


		$func = 'imagecreatefrom'.$format_arr[$head_info[2]];


		// 合成头像


		// $im_src = $func($path_info['head']);


		


		// imagecopyresized ( $im_dst, $im_src, 276, 60, 0, 0, 80, 80, $head_info[0], $head_info[1]);


	}


	


	// 保存


	imagejpeg($im_dst, $path_info['new']);


	// 销毁


	imagedestroy($im_src);


	imagedestroy($im_dst);


	return $path_info['new'];


}


// 


function is_gongpai($userid){


	$is_info = M('tree')->where(array('user_id'=>$userid))->select();


	return $is_info;


}


  


	


// 执行排位


function paiwei($user_info,$is_fanli=0){

   die;
	// 找系统上的挂靠点


	$parent_node = M('tree') -> where(array(


		'childs' => array('lt',2)


	)) -> order('id asc') -> find();


	//分配土地


	 $land = M('land')->where(array('user_id'=>$user_info['id']))->find();


	 if(empty($land)){


		for($i=0;$i<18;$i++){


			M('land')->add(array('user_id'=>$user_info['id'],'status'=>1,'create_time'=>time()));


		}


	}


	//返利  


	if($is_fanli!=0){


		$fanli = $GLOBALS['_CFG']['paiwei']['fanli'];


		$fanli = $fanli?$fanli:0;


		$daili = $user_info['fanli']+$fanli;


		M('user')->where(array('id'=>$user_info['id']))->save(array('fanli'=>$daili));


		$msg = "恭喜您成功购买公排,并返利金额到你的代理返利账户里!\n"."获得返利金额:".$fanli."元\n您目前的总代返利金额:".$daili."元\n购买时间:".date('Y-m-d H:i');


		sendwen($user_info['openid'],$msg);


	}


	// 数据库是空的，添加到最上的节点


	if(!$parent_node){


		M('tree') -> add(array(


			'user_id' => $user_info['id'],


			'pos' => 0,


			'x' => 0,


			'y' => 0,


			'childs' => 0,


			'parent' => 0,


			'create_time' => get_now(),


			'times' => 0,


			'team' => 0,


			'points' => $GLOBALS['_CFG']['paiwei']['points'],


		));


		return false;


	}


	


	$my_pos = find_child_pos($parent_node['x'],$parent_node['y']);


	$my_pos['y'] += $parent_node['childs'];


	


	// 查询排位次数


	$times = M('tree') -> where('user_id='.$user_info['id']) -> count();


	


	$tree_data = array(


		'user_id' => $user_info['id'],


		'pos' => get_index_by_pos($my_pos['x'],$my_pos['y']),


		'x' => $my_pos['x'],


		'y' => $my_pos['y'],


		'childs' => 0,


		'parent' => $parent_node['id'],


		'create_time' => get_now(),


		'times' => $times,


		'team' => 0


	);


	M('tree') -> add($tree_data);


	


	M('tree') -> where('id='.$parent_node['id']) -> setInc('childs');


	


	// 发放普通懒人奖


	reward_lazy($tree_data);


	


	if(!empty($user_info['openid'])){


		$msg = "恭喜您成功占点:\n".$my_pos['x'].'排'.$my_pos['y'].'列'."\n".date('Y-m-d H:i:s');


		sendwen($user_info['openid'],$msg);


	}


}





// 获得指定层数的上级pos集合


function get_parent_nodes($x,$y,$level){


	// 循环获得上级的pos


	$i = 0;


	$parent_node = get_parent_pos($x,$y);


	$parent_pos_arr = array(); // 上级位置数组


	while($i < $level && !$parent_node === false){


		$parent_pos_arr[] = get_index_by_pos($parent_node['x'],$parent_node['y']);


		$parent_node = get_parent_pos($parent_node['x'],$parent_node['y']);


		$i++;


	}


	return $parent_pos_arr;


}





// 发放直推荐奖


function reward_streight($tree_node,$level){


	if(!$tree_node){


		return false;


	}


	// 父节点


	if($GLOBALS['_CFG']['level'][$level]['streight_person'] == 1 && $tree_node['parent']){


		$parent_node = M('tree') -> where('id='.$tree_node['parent']) -> find();//getField('user_id');


		$parent_user_id = $parent_node['id'];


		$times = $parent_node['times'] +1;


		$remark = $GLOBALS['_CFG']['level'][$parent_node['level']]['name'].$times;


	}


	// 推进人


	elseif($GLOBALS['_CFG']['level'][$level]['streight_person'] == 0 && $tree_node['user_id']){


		$parent_user_id = M('user') -> where('id='.$tree_node['user_id']) -> getField('parent1');


		$remark = '直推';


	}


	// 直推奖金额


	$reward = $GLOBALS['_CFG']['level'][$level]['streight_money'];


	


	if($parent_user_id && $GLOBALS['_CFG']['level'][$level]['streight_money']){


		$tiliu = $reward * $GLOBALS['_CFG']['site']['points_tiliu']/100;


		$money = $reward - $tiliu;


		M('user') -> save(array(


			'id' => $parent_user_id,


			'money' => array('exp','money+'.$money),


			'points' => array('exp','points+'.$tiliu),


			'reward_streight' => array('exp','reward_streight+'.$reward),


			'zhitui' => array('exp','zhitui+1')


		));


		if($money)flog($parent_user_id,'money',$money,31,$remark);


		if($tiliu)flog($parent_user_id,'points',$tiliu,31,$remark);


	}


}





//发放普通懒人奖，level表示懒人奖的层数


function reward_lazy($node_info){


	// 懒人奖金额


	$reward = $GLOBALS['_CFG']['paiwei']['lazy_money'];


	// 奖励层数


	$levels = $GLOBALS['_CFG']['paiwei']['lazy_level'];


	


	// 获得上面levels父级的节点pos


	$parent_pos_arr = get_parent_nodes($node_info['x'],$node_info['y'],$levels);


	if(!empty($parent_pos_arr)){


		// 对level层以内的上级发放懒人奖


		$where = array(


			'pos' => array('in',$parent_pos_arr)


		);


		


		$parent_nodes = M('tree') -> where($where) -> field() -> select();


		


		$user_id_arr = array();


		foreach($parent_nodes as $parent_node){


			$user_id_arr[] = $parent_node['user_id'];


		}


		


		// 发放懒人奖到用户


		// M('user') -> where(array('id' => array('in',$user_id_arr))) -> save(array(


		// 	'money' => array('exp','money+'.$reward),


		// 	'reward_lazy' => array('exp','reward_lazy+'.$reward),


		// ));


		


		// 累加对应的奖励次数


		M('tree') -> where(array(


			'pos' => array('in',$parent_pos_arr)


		)) -> setInc('lazy_times');


		


		// 循环增加懒人奖财务记录


		//$i = 0;


		foreach($parent_nodes as $parent_node){


			$times = $parent_node['times'] +1;


			$remark = $GLOBALS['_CFG']['level'][$parent_node['level']]['name'].$times;


			if($reward)flog($parent_node['user_id'],'money',$reward,10,$remark);


			


			$parent_info = M('user') -> find($parent_node['user_id']);


			M('user') -> where(array('id' => $parent_node['user_id'])) -> save(array(


			'money' => array('exp','money+'.$reward),


			'reward_lazy' => array('exp','reward_lazy+'.$reward),


		    ));


			$msg = "恭喜您获得了一个懒人奖：\n金额：{$reward}\n时间：".date('Y-m-d H:i:s');


			sendwen($parent_info['openid'],$msg);


		}


	}


	return ;


}





// 根据我的位置找我的下级的位置


function find_child_pos($x,$y){


	$x ++;


	if($y==0){


		$y = 0;


	}


	else{


		$y = 2*$y;


	}


	return array('x' => $x,'y' => $y);


}





// 根据位置 （xy）获得序号 


function get_index_by_pos($x,$y){


	return pow(2,$x)-1+$y;


}





// 根据位置获得父节点的位置


function get_parent_pos($x,$y){


	if($x<=0){


		return false;


	}


	


	$x = $x-1;


	$y = floor(($y)/2);


	return array('x' => $x,'y' => $y);


}





// 获得当前时间戳


function get_now(){


	if(CLI === true)return time();


	else return NOW_TIME;


}





// 转为微信头像地址大小,默认原始图片(/0)，其他支持132*132(/132)、96*96(/96)、64*64(/64)、46*46(/46)


// 头像原图可能很大，下载和访问都很慢，根据需要制定大小可以加快访问速度


function headsize($url,$size = 0){


	$arr = explode('/',$url);


	if(!is_numeric($arr[count($arr)-1]))return $url;


	$arr[count($arr)-1] = $size;


	return implode('/',$arr);


}





// 取得支付方式


function get_payway($payway){


	$arr = array(


		'money' => '余额',


		'points' => '积分',


		'wxpay' => '微信',


		'alipay' => '支付宝'


	);


	return $arr[$payway];


}


function guid()
{
    if (function_exists('com_create_guid'))
    {
        return com_create_guid();
    }
    else
    {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = //chr(123).// "{"
            substr($charid, 0, 8)//.$hyphen
            .substr($charid, 8, 4)//.$hyphen
            .substr($charid,12, 4)//.$hyphen
            .substr($charid,16, 4)//.$hyphen
            .substr($charid,20,12);
//.chr(125);// "}"
        return $uuid;
    }
}

function random($length, $numeric = FALSE) {
    $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
    if($numeric) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}


function shai_fx($user,$money,$type){
	$config = explode(';',$GLOBALS['_CFG']['site']['shai_yj']);
	if(!empty($user['parent1'])){
		$parent_info = M('user') ->where(array('id'=>$user['parent1']))->find();
		
		$expense = $config[0]*$money/100;
		if($parent_info){

			$expense= array(

				'user_id' => $parent_info['id'],

				'buyer_id' => $user['id'],

				'money' => $expense,

				'level' => 1,

				'create_time' => time(),

				'type' => $type

			);
			$save = array('expense'=>$parent_info['expense']+$expense['money'],'money'=>$parent_info['money']+$expense['money']);
			M('user')->where(array('id'=>$parent_info['id']))->save($save);
			$info = M('shai_fx')->add($expense);
			if($info){
				$msg = "你的直属下级 ".$user['nickname']." 成功下单 恭喜您获到佣金奖".$expense['money']."元！\n购买时间:".date('Y-m-d H:i:s');
				sendwen($parent_info['openid'],$msg);
			}

		}
		
	}
	if($user['parent1']){
		$parent_info = M('user') ->where(array('id'=>$user['parent1']))->find();
		$up2 = M('user') ->where(array('id'=>$parent_info['parent1']))->find();
		$expense = $config[1]*$money/100;
		if(!empty($up2)){

			$expense= array(

				'user_id' => $up2['id'],

				'buyer_id' => $user['id'],

				'money' => $expense,

				'level' => 2,

				'create_time' => time(),

				'type' => $type

			);
			M('user')->where(array('id'=>$up2['id']))->save(array('expense'=>$up2['expense']+$expense['money'],'money'=>$up2['money']+$expense['money']));
			$info = M('shai_fx')->add($expense);
			if($info){
				$msg = "你的二级下级 ".$user['nickname']." 成功下单 恭喜您获到佣金奖".$expense['money']."元！\n购买时间:".date('Y-m-d H:i:s');
				sendwen($up2['openid'],$msg);
			}

		}
		if($up2['parent1']){

		$up3 = M('user') ->where(array('id'=>$up2['parent1']))->find();
		$expense = $config[2]*$money/100;
		if($up3){

				$expense= array(

					'user_id' => $up3['id'],

					'buyer_id' => $user['id'],

					'money' => $expense,

					'level' => 3,

					'create_time' => time(),

					'type' => $type

				);
				M('user')->where(array('id'=>$up3['id']))->save(array('expense'=>$up3['expense']+$expense['money'],'money'=>$up3['money']+$expense['money']));
				$info = M('shai_fx')->add($expense);
				if($info){
					$msg = "你的三级下级 ".$user['nickname']." 成功下单 恭喜您获到佣金奖".$expense['money']."元！\n购买时间:".date('Y-m-d H:i:s');
					sendwen($parent_info['openid'],$msg);
				}

			}
		}
	}
}
function getRand($proArr) {
    $result = '';

    //概率数组的总概率精度
    $proSum = array_sum($proArr);

    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);

    return $result;
}