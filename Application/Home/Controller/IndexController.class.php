<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends HomeController {
	public function _initialize() {
		parent::_initialize();
		$user = $this->user;
		$time_1 = strtotime('2016-12-13 21:30');
        if( $user['id'] == 40){
			 //	$this->error('网络异常');
		}
		if( time() < $time_1 && $user['is_tong'] != 1){
			 	$this->error('开放访问时间是:2016-12-13 21:30');

		}
	}
	public function index() {
        //执行开奖
        $dailist = M('kailog')->where(array('status'=>1,'endtime'=>array('lt',time()-4)))->select();
        $kailist = M('kailog')->where(array('status'=>1))->order('id desc')->find();
        $lilist = M('kailog')->where(array('status'=>2))->order('id desc')->find();
        $time = time();
        if(empty($kailist)){
        	if($time-$lilist['endtime']>3){
	        	$data['starttime'] = $time;
	        	$data['endtime'] = $time + 30;
	        	$data['status'] = 1;
	        	M('kailog')->add($data);
	        	$kailist = M('kailog')->where(array('status'=>1))->order('id desc')->find();
        	}
        	
        }
        $mybuy = M('buylog')->where(array('uid'=>$this->user['id'],'kid'=>$kailist['id']))->find();
        $miao = $kailist['endtime']-time();
        $miao = $miao>0?$miao:0;
        if($miao<10){
        	$miao = '0'.$miao;
        }

        $this->assign('miao',$miao);  
        $this->assign('kailist',$kailist);  
        $this->assign('mybuy',$mybuy);  
        $this->assign('lilist',$lilist);  
		$this->display();
        

	}
	public function zhixing(){
		$this->display();
	}
	public function kai(){
		//执行开奖
        $dailist= M('kailog')->where(array('status'=>1,'endtime'=>array('lt',time()-4)))->select();
       //$kailist[1]=M()->GetLastSql();
       	$money = $GLOBALS['_CFG']['withdraw']['money'];
        foreach ($dailist as $key => $va) {
        	if($va['kongid']!=0){
        		$kaiid = $va['kongid'];
        	}elseif($va['allmoney']<$money){
        		$kaiid = rand(1,6);
        	}      	

			else
				if( $va['kid2']==$va['kid1'] && $va['kid2']==$va['kid3'] && $va['kid2']==$va['kid4']&& $va['kid2']==$va['kid5']&& $va['kid2']==$va['kid6']){
    			$kaiid = rand(1,6);
    			//echo $kaiid;
			}else{
				$a = $va['kid1']<=$va['kid2']?$va['kid1']:$va['kid2'];
				$b = $va['kid3']<=$va['kid4']?$va['kid3']:$va['kid4'];
				$c = $va['kid5']<=$va['kid6']?$va['kid5']:$va['kid6'];
				$d =$a<=$b?$a:$b;
				$e = $d<=$c?$d:$c;
				$kainum = array();
				if ($va['kid1'] == $e){
				    $kainum['1']=1;
				}
				if ($va['kid2'] == $e){
				    $kainum['2']=2;
				}
				if ($va['kid3'] == $e){
				    $kainum['3']=3;
				}
				if ($va['kid4'] == $e){
				    $kainum['4']=4;
				}
				if ($va['kid5'] == $e){
				    $kainum['5']=5;
				}
				if ($va['kid6'] == $e){
				    $kainum['6']=6;
				}
				if (count($kainum)==1){
				    $kaiid3 = array_values($kainum);
				    $kaiid = $kaiid3[0];				    
				}else{
				    //rand(3,5,6);
				     $kaiid = getRand($kainum);
				}
			}
        $cainum = (string)rand(600,1000);
        	$zhongnum = (string)rand(500,590);
        	M('kailog')->where(array('id'=>$va['id']))->save(array('status'=>2,'isid'=>$kaiid,'cainum' =>$cainum,'zhongnum' => $zhongnum));
        	//微信通知获奖情况
        	$list = M('buylog')->where(array('kid'=>$va['id'],'status'=>1))->select();
        	foreach ($list as $key => $val) {
        		$newinfo = M('buylog')->where(array('id'=>$val['id']))->find();
        		if($newinfo['status']==1){
        			//猜大小 
        			if($val['buyid']<=6){
	        		if($val['buyid']==$kaiid){
	        			$yingmoney = $val['money']*5;
	        			M('buylog')->where(array('id'=>$val['id']))->save(array('yingmoney'=>$yingmoney,'status'=>2,'isid'=>$kaiid));
	                    $userinfo = M('user')->where(array('id'=>$val['uid']))->find();
	                    if($userinfo){
	                    	$msg = "恭喜您在".$val['kid']."期中奖，获到奖金".$yingmoney."元！\n时间:".date('Y-m-d H:i:s');
	                        sendwen($userinfo['openid'],$msg);
	                        M('user')->where(array('id'=>$userinfo['id']))->save(array('money'=>$userinfo['money']+$yingmoney));
	                    }
	        		}else{
	        			M('buylog')->where(array('id'=>$val['id']))->save(array('status'=>2,'isid'=>$kaiid));
	        		}
	        	  }else{
	        	  	 if(($val['buyid']==7&&$kaiid>=4)||($val['buyid']==8&&$kaiid<=3)){
	        			$yingmoney = $val['money']*1.9;
	        			M('buylog')->where(array('id'=>$val['id']))->save(array('yingmoney'=>$yingmoney,'status'=>2,'isid'=>$kaiid));
	                    $userinfo = M('user')->where(array('id'=>$val['uid']))->find();
	                    if($userinfo){
	                    	$msg = "恭喜您在".$val['kid']."期中奖，获到奖金".$yingmoney."元！\n时间:".date('Y-m-d H:i:s');
	                        sendwen($userinfo['openid'],$msg);
	                        M('user')->where(array('id'=>$userinfo['id']))->save(array('money'=>$userinfo['money']+$yingmoney));
	                    }
	        		}else{
	        			M('buylog')->where(array('id'=>$val['id']))->save(array('status'=>2,'isid'=>$kaiid));
	        		}
	        	  }	
        		}
        	}
        }
        $kailist= M('kailog')->where(array('status'=>2,'id'=>$_GET['id']))->order('id desc')->find(); 
//        $kailist[2]=M()->GetLastSql();
//        print_r($_SESSION);
        $data = array();
        $kai = M('buylog')->where(array('status'=>2,'kid'=>$_GET['id'],'uid'=>$_SESSION['user']['id']))->order('yingmoney desc')->find();
        if(empty($kai)){
        	$data['type'] =1;
        }
		$data['id'] = $kailist['id'];
		$data['isid'] = $kailist['isid'];
		$data['money'] = $kai['yingmoney'];
		$data['cainum'] = $kailist['cainum'];
		$data['zhongnum'] = $kailist['zhongnum'];
        if($kailist){
         echo json_encode($data); 
      }
	}
    public function buynumber(){
    	$this->display();
    }
    public function daili(){
    	$user = $this->user;
    	if(empty($user)){
    		$this->redirect('/index.php?m=&c=Public&a=login');
    	}
    	$img = newqrcode($user['id']);
    	$this->assign('img',$img);    	
    	$this->assign('user',$user);
    	$this->_list('expense',array('user_id'=>$user['id']));
    }
    public function cai(){
    	$user = $this->user;
    	if(empty($user)){
    		$this->redirect('/index.php?m=&c=Public&a=login');
    	}
    	$buyid = $_POST['yanum']?$_POST['yanum']:1;
		  $kailist = M('kailog')->where(array('status'=>1))->order('id desc')->find();
  	   $time = time();
  	  if(empty($kailist)){
	    	$data['starttime'] = $time;
	    	//60秒/30秒/开奖时间控制
	    	$data['endtime'] = $time + 40;
	    	$data['status'] = 1;
	    	M('kailog')->add($data);
	    	$kailist = M('kailog')->where(array('status'=>1))->order('id desc')->find();
        }
      $info = M('buylog')->where(array('kid'=>$kailist['id'],'uid'=>$_POST['uid']))->find();
       if($info && $info['uid']==$_POST['uid']){
          $this->ajaxReturn(array('status' => 0,  'info' => '每轮只能选择一种进行投注！'));
       }
    	if($_POST['money']<0){
			$this->ajaxReturn(array('status' => 0,  'info' => '您在非法提交！'));
		}
    	if(IS_POST){
    		$money = $_POST['money']?$_POST['money']:10;
	    	//$money = 0.01;
    		if($_POST['buytype']==1){	    	  
	          $zym_45 = '6'.time().rand(1,1000).$buyid;
	          // $kailist = M('kailog')->where(array('status'=>1))->order('id desc')->find();
	          // $info = M('buylog')->where(array('kid'=>$kailist['id']))->find();
	          // if($info && $info['buyid']!=$buyid){
	          //    $this->ajaxReturn(array('status' => 0,  'info' => '每轮只能选择一种'));
	          // }else{		
	          $zym_47 = get_wxpay_parameters($zym_45, $money, $this->user['openid'], '下单');
				if (!$zym_47) {
					$this->ajaxReturn(array('status' => 0, 'info' =>'微信支付暂不开放，敬请见谅'));
				} else {
		            $this->ajaxReturn(array('status' => 1, 'pay_param' => $zym_47, 'info' => '调用微信支付成功'));
		    	 }
		    }else{
              if($money<=$user['money']){
                  $add['uid'] = $user['id'];
		          $add['money'] = $money;
		          $add['buyid'] = $buyid;
		          if($buyid <= 6){
		    		$add['type'] = 2;
			    	}else{
			    	$add['type'] = 1;	
			    	}
			      $kailist = M('kailog')->where(array('status'=>1))->order('id desc')->find();
		          $time = time();
		          if(empty($kailist)){
		        	$data['starttime'] = $time;
		        	$data['endtime'] = $time + 60;
		        	$data['status'] = 1;
		        	M('kailog')->add($data);
		        	$kailist = M('kailog')->where(array('status'=>1))->order('id desc')->find();
			          }
	          $add['kid'] = $kailist['id'];
	          $add['starttime'] = time();
	          $add['status'] = 1;
	          $add['endtime'] = $kailist['endtime'];
	          $info = M('buylog')->add($add);
	          if($info){
	          	if($buyid == 1){
	    		  $up['kid1'] = $kailist['kid1']+$money*5;	
                  $up['xiao'] = $kailist['xiao']+$money*1.9;				  
		    	}
		    	if($buyid == 2){
	    		 $up['kid2'] = $kailist['kid2']+$money*5;
				  $up['xiao'] = $kailist['xiao']+$money*1.9;
		    	}
		    	if($buyid == 3){
	    		  $up['kid3'] = $kailist['kid3']+$money*5;
				   $up['xiao'] = $kailist['xiao']+$money*1.9;
		    	}
		    	if($buyid == 4){
	    		  $up['kid4'] = $kailist['kid4']+$money*5;
				   $up['da'] = $kailist['da']+$money*1.9;
		    	}
		    	if($buyid == 5){
	    		  $up['kid5'] = $kailist['kid5']+$money*5;
				   $up['da'] = $kailist['da']+$money*1.9;
		    	}
		    	if($buyid == 6){
	    		  $up['kid6'] = $kailist['kid6']+$money*5;
				   $up['da'] = $kailist['da']+$money*1.9;
		    	}
		    	if($buyid == 7){	//押大
	    		 $up['da'] = $kailist['da']+$money*1.9;
	    		
		    	}
                if($buyid == 8){	//押小
	    		  $up['xiao'] = $kailist['xiao']+$money*1.9;
	    		
		    	}
		    	
		    	$up['allmoney'] = $kailist['allmoney']+$money;
		    	$up['allnu'] = $kailist['allnum']+1;
		    	M('kailog')->where(array('id'=>$kailist['id']))->save($up);
		    	M('user')->where(array('id'=>$user['id']))->save(array('money'=>$user['money']-$money));
				//var_dump($user);die;
		    	//expense($user_info,$money,$type);
				$type = 1;
				expense($user,$money,$type);
		    	 $this->ajaxReturn(array('status' => 1,  'info' => '购买成功'));
	          }else{
	          	 $this->ajaxReturn(array('status' => 0,  'info' => '购买失败'));
	          }	           
              }else{
                  $this->ajaxReturn(array('status' => 2,  'info' => '余额不足,请确定充值'));
              }
		    }	 
		}
		
    	$this->assign('name',$name);
    	$this->assign('id',$id);
    	$this->display();
    }
    public function usercode(){
    	$user = $this->user;
    	if(empty($user)){
    		$this->redirect('/index.php?m=&c=Public&a=login');
    	}
     	$img = create_qrcode($user);

    	$this->assign('img',$img);
    	$this->display();
    }
    public function playlog(){
    	$user = $this->user;
    	if(empty($user)){
    		$this->redirect('/index.php?m=&c=Public&a=login');
    	}
 	
    	$this->assign('user',$user);
    	$this->_list('buylog',array('uid'=>$user['id']));
    }
    public function lishi(){
    	$this->_list('kailog',array('status'=>2));
    }
    public function expensewen(){
    	$this->display();
    }
    public function kefu(){
    	$img = $GLOBALS['_CFG']['cs']['config']['0']['pic'];
    	$this->assign('url',$img);   	
    	$this->display();
    }
    public function question(){
		$list=M('question')->select();
		$this->assign('list',$list);
		$this->display();
	} 
	public function store() {


		$zym_34 = M('plant')->select();


		$this->assign('plants', $zym_34);


		$zym_52 = M('fertilizer')->select();


		$this->assign('fertilizer', $zym_52);


		$this->display();


	}

	public function ucenter() {


		if ($this->user['parent1']) {


			$zym_13 = M('user')->find($this->user['parent1']);


			$this->assign('parent_info', $zym_13);


		}


		$this->display();


	}


	public function log() {


		$zym_51 = array('user_id' => $this->user['id']);


		if ($_GET['action']) {


			$zym_51['action'] = intval($_GET['action']);


		}


		if ($_GET['type']) {


			$zym_51['type'] = I('get.type');


		}


		$this->_list('finance_log', $zym_51);


	}


	public function expense_log() {


		$zym_51 = array('user_id' => $this->user['id']);


		$this->_list('expense', $zym_51);


	}


	public function pickup_log() {


		$zym_51 = array('user_id' => $this->user['id']);


		$this->_list('pickup', $zym_51);


	}


	public function friend() {


		$zym_51 = array('parent1|parent2|parent3|parent4|parent5|parent6|parent7|parent8|parent9' => $this->user['id']);


		$this->_list('user', $zym_51);


	}


	public function extend() {


		$zym_57 = I('post.payway');


		if ($zym_57 != 'wxpay' && $zym_57 != 'money') {


			$this->error('请选择支付方式');


		}


		if ($this->user['lands'] >= 18) {


			$this->error('您不能再扩建了');


		}


		$zym_8 = M('land')->where(array('user_id' => $this->user['id'], 'status' => 0))->find();


		if ($zym_8) {


			$zym_46 = $zym_8;


		} else {


			$zym_46 = array('user_id' => $this->user['id'], 'price' => $this->_site['land_price'], 'status' => 0, 'create_time' => NOW_TIME,);


			$zym_46['id'] = M('land')->add($zym_46);


			if (!$zym_46['id']) {


				$this->error('系统错误');


			}


		}


		if ($zym_57 == 'money') {


			if ($this->user['money'] < $this->_site['land_price']) {


				$this->error('余额不足');


			}


			M('user')->save(array('id' => $this->user['id'], 'money' => array('exp', 'money-' . $this->_site['land_price']), 'lands' => array('exp', 'lands+1')));


			flog($this->user['id'], 'money', $this->_site['land_price'], 1);


			$zym_46['payway'] = 'money';


			$zym_46['paid'] = $this->_site['land_price'];


			$zym_46['pay_time'] = NOW_TIME;


			$zym_46['status'] = 1;


			$zym_46['price'] = $this->_site['land_price'];


			M('land')->save($zym_46);


			expense($this->user, $zym_46['price'], 1);


			$this->success('扩建成功');


			exit;


		}


		$zym_45 = '1' .time(). $zym_46['id'];


		if (!empty($zym_46['pay_param']) && $zym_46['pay_param_expire'] > NOW_TIME) {


			$zym_47 = unserialize($zym_46['pay_param']);


		} else {


			$zym_47 = get_wxpay_parameters($zym_45, $this->_site['land_price'], $this->user['openid'], '购买土地');


		}


		if (!$zym_47) {


			$this->error('调用微信支付失败');


		} else {


			M('land')->save(array('id' => $zym_46['id'], 'pay_param' => serialize($zym_47), 'pay_param_expire' => NOW_TIME + 7200));


		}


		$this->ajaxReturn(array('status' => 1, 'pay_param' => $zym_47, 'info' => '调用微信支付成功'));


	}


	public function expense() {


		$zym_51 = array('user_id' => $this->user['id']);


		$this->_list('expense', $zym_51);


	}


	public function pickup() {


		$zym_51 = array('user_id' => $this->user['id']);


		$this->_list('pickup', $zym_51);


	}


	public function profile() {


		redirect(U('ucenter'));


	}


	// function  demozhuan(){


	// 	$zym_4 = mch_wxpay( time().mt_rand(1000, 9999),'oUNdqw1-gombWKqLesFVGsGEgLvA',1, '金币兑换');


	// 	dump($zym_4);


	// }


	public function withdraw() {
		$id = $_SESSION['user']['id'];
		$img = M("erweima")->where(array('uid'=>$id))->find();
		if (IS_POST) {
			if (empty($this->user['mobile'])) {
			}
			if (empty($img)) {
				$this->error('请设置提现账户');
			}
			$zym_2 = $this->_withdraw;
			$zym_12 = sprintf('%.2f', $_POST['money']);
			$zym_1 = $zym_2['min_money'] > 1 ? $zym_2['min_money'] : 1;
			if ($zym_12 < $zym_1) {
				$this->error('最少提现' . $zym_1 . '元钱');
			} elseif ($zym_12 > $zym_2['max_money']) {
				$this->error('每次最多提现' . $zym_2['max_money'] . '元');
			}
			$zym_3 = $zym_12 * $zym_2['hand_fee'] / 100;
			if ($this->user['money'] < $zym_12) {
			$this->error('余额不足');
			}
            $money = $this->user['money']-$zym_12;
			if(M('withdraw_log')->add(array('user_id' => $this->user['id'], 'money' => $zym_12,'create_time' => NOW_TIME,'status' =>0))){
                M('user')->where(array('id'=>$this->user['id']))->save(array('money'=>$money));
				$this->success("申请成功,请等待回应");
			}else{
				$this->success("申请失败,请重新申请");
			}
			exit;
		}
		$zym_51 = array('user_id' => $this->user['id']);
		$this->_list('withdraw_log', $zym_51);
	}


		public function tuiguang() {
		$id = $_SESSION['user']['id'];
		$img = M("erweima")->where(array('uid'=>$id))->find();
		if (IS_POST) {
			if (empty($img)) {
				$this->error('请设置提现账户');
			}
			$zym_2 = $this->_withdraw;
			$zym_12 = sprintf('%.2f', $_POST['money']);
			$zym_1 = $zym_2['min_money'] > 1 ? $zym_2['min_money'] : 1;
			if ($zym_12 < $zym_1) {
				$this->error('最少提现' . $zym_1 . '元钱');
			} elseif ($zym_12 > $zym_2['max_money']) {
				$this->error('每次最多提现' . $zym_2['max_money'] . '元');
			}
			$zym_3 = $zym_12 * $zym_2['hand_fee'] / 100;
			if ($this->user['expense'] < $zym_12) {
				$this->error('佣金余额不足，去推广赚佣金吧！');
			}
			M('user')->save(array('id' => $this->user['id'], 'expense' => array('exp', 'expense-' . $zym_12), 'withdraw' => array('exp', 'withdraw+' . $zym_12)));
			M('withdraw_log')->add(array('user_id' => $this->user['id'], 'money' => $zym_12 - $zym_3, 'hand_fee' => $zym_3, 'create_time' => NOW_TIME, 'status' => '0', 'return_code' => '0', 'result_code' => '0','err_code_des' => $this->user['cashimg'],'err_code'=>'佣金提现' ,  'server_addr' => $_SERVER['SERVER_ADDR'], 'remote_addr' => $_SERVER['REMOTE_ADDR']));
			$this->success('提现申请成功，请等待客服处理！');
			exit;
		}
		$zym_51 = array('user_id' => $this->user['id']);
		$this->_list('withdraw_log', $zym_51);
	}

	
	
	
	
	
	
	
	
	public function dianwei() {


		$this->_list('tree', array('user_id' => $this->user['id']));


	}


	public function qrcode() {


		// $this->error('推广二维码暂时关闭访问');


		// die;


		$is_plant = M('user_plant')->where(array('user_id'=>$this->user['id'],'status'=>2))->find();
		$is_fertilizer = M('user_fertilizer')->where(array('user_id'=>$this->user['id'],'status'=>1))->find();


		if( ! $is_plant && ! $is_fertilizer){


			$this->error('需要先购买一单才能获取推广二维码','/index.php?m=&c=Index&a=index');


		}


		// $zym_7 = M('land')->where(array('user_id' => $this->user['id'], 'status' => array('gt', 0)))->find();


		// if (!$zym_7) {


		// 	$this->error('需要最少拥有一块地才可以生成二维码');


		// }


		$zym_14 = create_qrcode($this->user);


		//$zym_14 = cancode($this->user['id']);
       if(!is_file($zym_14)){


		$zym_14 = '/Public/code/'.$this->user['id'].'_dragondean.jpg';


	   }
		if (!$zym_14) {


			$this->error('二维码生成失败，请稍候再试');


		}


		redirect(C('SITE_URL') . '/qrcode.php?url=' . urlencode($zym_14));


	}

	public function charge() {
        $info = M('config') -> where(array('name' => 'recharge'))->getField('value');
        $info = $info ? unserialize($info) : [];
        $this->assign('info', $info);
        //收款啦配置，结束

        $this->display();
    }


	public function charge1() {
		if (IS_POST) {


			$zym_12 = floatval($_POST['money']);


			$zym_57 = $_POST['payway'];


			if ($zym_12 < 0) {


				$this->error('金额错误');


			}


			if ($zym_57 != 'alipay'&& $zym_57 != 'wxpay') {


				$this->error('请选择一种有效的支付方式');


			}

			$orderid = '670' .time();
			$zym_14 = M('charge')->add(array('user_id' => $this->user['id'], 'money' => $zym_12, 'payway' => $zym_57, 'create_time' => NOW_TIME, 'orderid'=>$orderid, 'status' => 0,));


			if (!$zym_14) {


				$this->error('操作失败，请重试！');


			}



			//收款啦配置，开始，官网www.shoukuanla.net
			//提交订单处理后跳转的付款页面
			$_GET['c']='Shoukuanla';
			$_GET['a']='external';
			require_once('./shoukuanla/index.php');

			if ($zym_57 == 'wxpay') {
				$payType='wxpay';
			}elseif($zym_57 == 'alipay'){
				$payType='alipay';	
			}else{
				$payType='wxpay';
			}
            $shoukuanla->uid = $this->user['id'];
			$shoukuanla->publicPost=array('payType'=>$payType,'out_trade_no'=>$orderid,'price'=>$zym_12);
			$shoukuanla->dopay();exit;
			//收款啦配置，结束



			if($zym_57 == 'codepaywx'|| $zym_57 == 'codepayali'){
				$type = '1';
				
			}


			$this->error('发生错误');


		}

		//收款啦配置，开始
		$list = M('charge')->order('`create_time` DESC')->limit(10)->select();
		$this->assign('list', $list);
		//收款啦配置，结束

		$this->display();


	}


	public function chou() {


		$zym_27 = M('chou')->where(array('create_time' => array('gt', strtotime('today')), 'status' => 3))->find();


		$this->assign('reward', $zym_27);


		if (IS_POST) {


			if ($_POST['payway'] == 'money' || $_POST['payway'] == 'points') {


				$zym_57 = $_POST['payway'];


				if ($this->user[$zym_57] < $this->_site['chou']) {


					$this->error('余额不足,请更换其它支付方式');


				}


				M('user')->save(array('id' => $this->user['id'], $zym_57 => array('exp', $zym_57 . '-' . $this->_site['chou'])));


				flog($this->user['id'], $zym_57, $this->_site['chou'], 6);


				M('chou')->add(array('user_id' => $this->user['id'], 'nickname' => $this->user['nickname'], 'money' => $this->_site['chou'], 'create_time' => NOW_TIME, 'payway' => $_POST['payway'], 'paid' => $this->_site['chou'], 'status' => 1));


				$this->success('参与成功，请等待开奖');


				exit;


			} elseif ($_POST['payway'] == 'wxpay') {


				$zym_55 = M('chou')->add(array('user_id' => $this->user['id'], 'nickname' => $this->user['nickname'], 'money' => $this->_site['chou'], 'create_time' => NOW_TIME, 'payway' => $_POST['payway'], 'paid' => 0, 'status' => - 1));


				$zym_45 = '8' . $zym_55;


				$zym_47 = get_wxpay_parameters($zym_45, $this->_site['chou'], $this->user['openid'], '参与抽抽乐');


				if (!$zym_47) {


					$this->error('调用微信支付失败');


				}


				$this->ajaxReturn(array('status' => 1, 'pay_param' => $zym_47, 'info' => '调用微信支付成功'));


				exit;


			} else $this->error('请选择合适的支付方式');


		}


		$zym_28 = M('chou')->where(array('user_id' => $this->user['id'], 'status' => array('gt', 0)))->count();


		$this->assign('count', $zym_28);


		$zym_23 = M('chou')->where(array('status' => 1))->sum('money');


		$this->assign('total', $zym_23);


		$this->display();


	}


	public function chou_log() {


		$this->_list('chou', array('user_id' => $this->user['id']));


	}


	public function chou_reward() {


		$this->_list('chou', array('status' => 3));


	}

	public function cash() {
		$id = $_SESSION['user']['id'];
		$img = M("erweima")->where(array('uid'=>$id))->find();
		$this->assign('img',$img['imgurl']);
		$this->display();
	}

	public function upload(){
		if(!empty($_GET['url']))
			$this -> assign('url', $_GET['url']);
		if(IS_POST){
			$id = $_SESSION['user']['id'];
			$type = M("erweima")->field('type')->where(array('uid'=>$id))->find();
				if($_GET['field'])
					$field = $_GET['field'];
				if(empty($field))
					$field = 'file';
				if($_FILES[$field]['size'] < 1 && $_FILES[$field]['size']>0){
					$this -> assign('errmsg', '上传错误！');
				}else{
					$ext = $this -> _get_ext($_FILES[$field]['name']);
					$new_name = $this -> _get_new_name($ext, 'images');
					if(!in_array(strtolower($ext),C('ALLOWED_FILE_TYPES'))){
						$this -> error('上传文件不允许');
					}
					if(move_uploaded_file($_FILES[$field]['tmp_name'], $new_name['fullname'])){
						$data['uid'] = $id;
						$data['imgurl'] = $new_name['fullname'];
						if($type['type'] == 1){
							if (M("erweima")->where(array('uid'=>$id))->save($data)) {
								$this -> assign('url', $new_name['fullname']);
							} else {
								

								$this -> assign('errmsg', '文件保存错误！');
						}	
						}else{
							
						if (M("erweima")->add($data)) {
								$this -> assign('url', $new_name['fullname']);
							} else {
								$this -> assign('errmsg', '文件保存错误！');
							}	
						
					}
					
				}
			}
		
		}
		C('LAYOUT_ON', false);
		$this -> display();
	}

	/**
	*	根据文件名获取后缀
	*/
	private function _get_ext($file_name){
        return substr(strtolower(strrchr($file_name, '.')),1);
    }

    /**
	*	根据文件类型(后缀)生成文件名和路径
	*	@param return array('name', 'fullname' )
	*	* 文件名取时间戳和随机数的36进制而不是62进制是为了防止windows下大小写不敏感导致文件重名
	*/
	private function _get_new_name($ext, $dir = 'default'){
        $name 		= date('His') . substr(microtime(),2,8) . rand(1000,9999) . '.' . $ext;
        $path 		= './Public/upload/' . $dir . date('/ym/d') .'/';

        // 如果路径不存在则递归创建
        if(!is_dir($path)){
        	mkdir($path, 0777, 1);
        }

        return array(
        		'name'		=> $name,
        		'fullname'	=> $path . $name
        	);
    }
	private function _is_friend($zym_40) {


		$zym_22 = false;


		for ($zym_17 = 1;$zym_17 <= 9;$zym_17++) {


			if ($zym_40['parent' . $zym_17] == $this->user['id']) {


				$zym_22 = true;


				break;


			}


		}


		return $zym_22;


	}


	private function _list($zym_16, $zym_51 = null, $zym_18 = null) {


		$zym_19 = $this->_get_list($zym_16, $zym_51, $zym_18);


		$this->assign('list', $zym_19);


		$this->assign('page', $this->data['page']);


		$this->display();


	}


	private function _get_list($zym_16, $zym_51 = null, $zym_18 = null) {


		$zym_21 = M($zym_16);


		$zym_28 = $zym_21->where($zym_51)->count();


		$zym_20 = new \Think\Page($zym_28, 15);


		if (!$zym_18) {


			$zym_18 = 'id desc';


		}


		$zym_19 = $zym_21->where($zym_51)->limit($zym_20->limit())->order($zym_18)->select();


		$this->data = array('list' => $zym_19, 'page' => $zym_20->show(), 'count' => $zym_28);


		return $zym_19;


	}

} ?>