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
        $list = M('plant')->select();
        $this->assign('list',$list);
        //执行开奖
        /*$dailist = M('kailog')->where(array('status'=>1,'endtime'=>array('lt',time()-4)))->select();
        foreach ($dailist as $key => $va) {
        	if($va['da']==$va['xiao']){
	        	if( $va['kid1']<=$va['kid2'] && $va['kid1']<=$va['kid3'] && $va['kid1']<=$va['kid4']&& $va['kid1']<=$va['kid5']&& $va['kid1']<=$va['kid6']){
	        		$kaiid = 1;
	        	}
	        	if( $va['kid2']<=$va['kid1'] && $va['kid2']<=$va['kid3'] && $va['kid2']<=$va['kid4']&& $va['kid2']<=$va['kid5']&& $va['kid2']<=$va['kid6']){
	        		$kaiid = 2;
	        	}
	        	if( $va['kid3']<=$va['kid2'] && $va['kid3']<=$va['kid1'] && $va['kid3']<=$va['kid4']&& $va['kid3']<=$va['kid5']&& $va['kid3']<=$va['kid6']){
	        		$kaiid = 3;
	        	}
	        	if( $va['kid4']<=$va['kid2'] && $va['kid4']<=$va['kid3'] && $va['kid4']<=$va['kid1']&& $va['kid4']<=$va['kid5']&& $va['kid4']<=$va['kid6']){
	        		$kaiid = 4;
	        	}
	           
	        	if( $va['kid5']<=$va['kid2'] && $va['kid5']<=$va['kid3'] && $va['kid5']<=$va['kid4']&& $va['kid5']<=$va['kid1']&& $va['kid5']<=$va['kid6']){
	        		$kaiid = 5;
	        	}
	        	if( $va['kid6']<=$va['kid2'] && $va['kid6']<=$va['kid3'] && $va['kid6']<=$va['kid4']&& $va['kid16']<=$va['kid5']&& $va['kid6']<=$va['kid1']){
	        		$kaiid = 6;
	        	}
        	}
        	if($va['da']>$va['xiao']){
                if( $va['kid1']<=$va['kid2'] && $va['kid1']<=$va['kid3'] ){
	        		$kaiid = 1;
	        	}
	        	if( $va['kid2']<=$va['kid1'] && $va['kid2']<=$va['kid3'] ){
	        		$kaiid = 2;
	        	}
	        	if( $va['kid3']<=$va['kid2'] && $va['kid3']<=$va['kid1']){
	        		$kaiid = 3;
	        	}
        	}
        	if($va['da']<$va['xiao']){
                if( $va['kid4']<=$va['kid5']&& $va['kid4']<=$va['kid6']){
	        		$kaiid = 4;
	        	}
	           
	        	if(  $va['kid5']<=$va['kid4']&& $va['kid5']<=$va['kid6']){
	        		$kaiid = 5;
	        	}
	        	if( $va['kid6']<=$va['kid4']&& $va['kid16']<=$va['kid5']){
	        		$kaiid = 6;
	        	}
        	}
        	if($va['kid3']==$va['kid2']&&$va['kid3']==$va['kid1']&&$va['kid3']==$va['kid4']&&$va['kid3']==$va['kid5']&&$va['kid3']==$va['kid6']&&$va['da']==$va['xiao']){
        		$kaiid = rand(1,6);
        	}

        	$kaiid = $kaiid?$kaiid:1;
        	if($va['kongid']!=0){
        		$kaiid = $va['kongid'];
        	}
        	M('kailog')->where(array('id'=>$va['id']))->save(array('status'=>2,'isid'=>$kaiid));
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
        }*/
        $kailist = M('kailog')->where(array('status'=>1))->order('id desc')->find();
        $lilist = M('kailog')->where(array('status'=>2))->order('id desc')->find();
        $time = time();
        if(empty($kailist)){
        	if($time-$lilist['endtime']>3){
	        	$data['starttime'] = $time;
	        	$data['endtime'] = $time + 10;
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
		//执行开奖
       /* $dailist = M('kailog')->where(array('status'=>1,'endtime'=>array('lt',time())))->select();
        foreach ($dailist as $key => $va) {
        	if($va['da']==$va['xiao']){
	        	if( $va['kid1']<=$va['kid2'] && $va['kid1']<=$va['kid3'] && $va['kid1']<=$va['kid4']&& $va['kid1']<=$va['kid5']&& $va['kid1']<=$va['kid6']){
	        		$kaiid = 1;
	        	}
	        	if( $va['kid2']<=$va['kid1'] && $va['kid2']<=$va['kid3'] && $va['kid2']<=$va['kid4']&& $va['kid2']<=$va['kid5']&& $va['kid2']<=$va['kid6']){
	        		$kaiid = 2;
	        	}
	        	if( $va['kid3']<=$va['kid2'] && $va['kid3']<=$va['kid1'] && $va['kid3']<=$va['kid4']&& $va['kid3']<=$va['kid5']&& $va['kid3']<=$va['kid6']){
	        		$kaiid = 3;
	        	}
	        	if( $va['kid4']<=$va['kid2'] && $va['kid4']<=$va['kid3'] && $va['kid4']<=$va['kid1']&& $va['kid4']<=$va['kid5']&& $va['kid4']<=$va['kid6']){
	        		$kaiid = 4;
	        	}
	           
	        	if( $va['kid5']<=$va['kid2'] && $va['kid5']<=$va['kid3'] && $va['kid5']<=$va['kid4']&& $va['kid5']<=$va['kid1']&& $va['kid5']<=$va['kid6']){
	        		$kaiid = 5;
	        	}
	        	if( $va['kid6']<=$va['kid2'] && $va['kid6']<=$va['kid3'] && $va['kid6']<=$va['kid4']&& $va['kid16']<=$va['kid5']&& $va['kid6']<=$va['kid1']){
	        		$kaiid = 6;
	        	}
        	}
        	if($va['da']>$va['xiao']){
                if( $va['kid1']<=$va['kid2'] && $va['kid1']<=$va['kid3'] ){
	        		$kaiid = 1;
	        	}
	        	if( $va['kid2']<=$va['kid1'] && $va['kid2']<=$va['kid3'] ){
	        		$kaiid = 2;
	        	}
	        	if( $va['kid3']<=$va['kid2'] && $va['kid3']<=$va['kid1']){
	        		$kaiid = 3;
	        	}
        	}
        	if($va['da']<$va['xiao']){
                if( $va['kid4']<=$va['kid5']&& $va['kid4']<=$va['kid6']){
	        		$kaiid = 4;
	        	}
	           
	        	if(  $va['kid5']<=$va['kid4']&& $va['kid5']<=$va['kid6']){
	        		$kaiid = 5;
	        	}
	        	if( $va['kid6']<=$va['kid4']&& $va['kid16']<=$va['kid5']){
	        		$kaiid = 6;
	        	}
        	}
        	if($va['kid3']==$va['kid2']&&$va['kid3']==$va['kid1']&&$va['kid3']==$va['kid4']&&$va['kid3']==$va['kid5']&&$va['kid3']==$va['kid6']&&$va['da']==$va['xiao']){
        		$kaiid = rand(1,6);
        	}
        	$kaiid = $kaiid?$kaiid:1;
        	if($va['kongid']!=0){
        		$kaiid = $va['kongid'];
        	}
        	M('kailog')->where(array('id'=>$va['id']))->save(array('status'=>2,'isid'=>$kaiid));
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
        $kailist = M('kailog')->where(array('status'=>1))->order('id desc')->find();
        $time = time();
        if(empty($kailist)){        	
        	$data['starttime'] = $time;
        	$data['endtime'] = $time + 60;
        	$data['status'] = 1;
        	M('kailog')->add($data);
        }*/
		$this->display();
	}
	public function kai(){
		//执行开奖
        $dailist = M('kailog')->where(array('status'=>1,'endtime'=>array('lt',time()-4)))->select();
        $kailist=array();
        foreach ($dailist as $key => $va) {
        	if($va['da']==$va['xiao']){
	        	if( $va['kid1']<=$va['kid2'] && $va['kid1']<=$va['kid3'] && $va['kid1']<=$va['kid4']&& $va['kid1']<=$va['kid5']&& $va['kid1']<=$va['kid6']){
	        		$kaiid = 1;
	        	}
	        	if( $va['kid2']<=$va['kid1'] && $va['kid2']<=$va['kid3'] && $va['kid2']<=$va['kid4']&& $va['kid2']<=$va['kid5']&& $va['kid2']<=$va['kid6']){
	        		$kaiid = 2;
	        	}
	        	if( $va['kid3']<=$va['kid2'] && $va['kid3']<=$va['kid1'] && $va['kid3']<=$va['kid4']&& $va['kid3']<=$va['kid5']&& $va['kid3']<=$va['kid6']){
	        		$kaiid = 3;
	        	}
	        	if( $va['kid4']<=$va['kid2'] && $va['kid4']<=$va['kid3'] && $va['kid4']<=$va['kid1']&& $va['kid4']<=$va['kid5']&& $va['kid4']<=$va['kid6']){
	        		$kaiid = 4;
	        	}
	           
	        	if( $va['kid5']<=$va['kid2'] && $va['kid5']<=$va['kid3'] && $va['kid5']<=$va['kid4']&& $va['kid5']<=$va['kid1']&& $va['kid5']<=$va['kid6']){
	        		$kaiid = 5;
	        	}
	        	if( $va['kid6']<=$va['kid2'] && $va['kid6']<=$va['kid3'] && $va['kid6']<=$va['kid4']&& $va['kid16']<=$va['kid5']&& $va['kid6']<=$va['kid1']){
	        		$kaiid = 6;
	        	}
        	}
        	if($va['da']>$va['xiao']){
                if( $va['kid1']<=$va['kid2'] && $va['kid1']<=$va['kid3'] ){
	        		$kaiid = 1;
	        	}
	        	if( $va['kid2']<=$va['kid1'] && $va['kid2']<=$va['kid3'] ){
	        		$kaiid = 2;
	        	}
	        	if( $va['kid3']<=$va['kid2'] && $va['kid3']<=$va['kid1']){
	        		$kaiid = 3;
	        	}
        	}
        	if($va['da']<$va['xiao']){
                if( $va['kid4']<=$va['kid5']&& $va['kid4']<=$va['kid6']){
	        		$kaiid = 4;
	        	}
	           
	        	if(  $va['kid5']<=$va['kid4']&& $va['kid5']<=$va['kid6']){
	        		$kaiid = 5;
	        	}
	        	if( $va['kid6']<=$va['kid4']&& $va['kid16']<=$va['kid5']){
	        		$kaiid = 6;
	        	}
        	}
        	if($va['kid3']==$va['kid2']&&$va['kid3']==$va['kid1']&&$va['kid3']==$va['kid4']&&$va['kid3']==$va['kid5']&&$va['kid3']==$va['kid6']&&$va['da']==$va['xiao']){
        		$kaiid = rand(1,6);
        	}
        	$kaiid = $kaiid?$kaiid:1;

        	if($va['kongid']!=0){
        		$kaiid = $va['kongid'];
        	}
        	M('kailog')->where(array('id'=>$va['id']))->save(array('status'=>2,'isid'=>$kaiid));
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
        $kailist = M('kailog')->where(array('status'=>2,'id'=>$_GET['id']))->order('id desc')->find();  
        if($kailist){
         echo json_encode($kailist); 
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
					$this->ajaxReturn(array('status' => 1, 'info' =>'微信支付暂不开放，敬请见谅'));
				} else {
		            $this->ajaxReturn(array('status' => 2, 'pay_param' => $zym_47, 'info' => '微信支付暂不开放，请选择余额支付'));
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
	    		  $up['kid1'] = 1;
	    		  $up['xiao'] = $kailist['xiao']+$money;
		    	}
		    	if($buyid == 2){
	    		  $up['kid2'] = 2;
	    		  $up['xiao'] = $kailist['xiao']+$money;
		    	}
		    	if($buyid == 3){
	    		  $up['kid3'] = 3;
	    		  $up['xiao'] = $kailist['xiao']+$money;
		    	}
		    	if($buyid == 4){
	    		  $up['kid4'] = 4;
	    		  $up['da'] = $kailist['da']+$money;
		    	}
		    	if($buyid == 5){
	    		  $up['kid5'] = 5;
	    		  $up['da'] = $kailist['da']+$money;
		    	}
		    	if($buyid == 6){
	    		  $up['kid6'] = 6;
	    		  $up['da'] = $kailist['da']+$money;
		    	}
                if($buyid == 7){
	    		  $up['da'] = $kailist['da']+$money;
		    	}
		    	if($buyid == 8){
	    		  $up['xiao'] = $kailist['xiao']+$money;
		    	}
		    	$up['allmoney'] = $kailist['allmoney']+$money;
		    	$up['allnum'] = $kailist['allnum']+1;
		    	M('kailog')->where(array('id'=>$kailist['id']))->save($up);
		    	M('user')->where(array('id'=>$user['id']))->save(array('money'=>$user['money']-$money));
		    	shai_fx($user,$money,$add['type']);
		    	$this->ajaxReturn(array('status' => 1,  'info' => '购买成功'));
	          }else{
	          	 $this->ajaxReturn(array('status' => 0,  'info' => '购买失败'));
	          }	           
              }else{
                  $this->ajaxReturn(array('status' => 0,  'info' => '余额不足'));
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


		$this->_list('shai_fx', $zym_51);


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

        // $this->error('微信公众号商户升级中！12点正常开放有问题联系客服');
        // die;
		if (IS_POST) {




			if (empty($this->user['mobile'])) {


			//	$this->error('请先去完善资料绑定电话号码','/index.php?m=&c=Public&a=wanshan');


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


			if (empty($this->user['openid'])) {


				$this->error('未邦定微信无法提现');


			}


			$zym_45 = time() . mt_rand(1000, 9999);


			$zym_4 = mch_wxpay($zym_45, $this->user['openid'], $zym_12 - $zym_3, '金币兑换');


			if ($zym_4['status']) {


				M('user')->save(array('id' => $this->user['id'], 'money' => array('exp', 'money-' . $zym_12), 'withdraw' => array('exp', 'withdraw+' . $zym_12)));


				flog($this->user['id'], 'money', $zym_12, 5);


				flog($this->user['id'], 'points', $zym_12, 5);


				$zym_6 = '兑换成功，请到微信零钱查收';


			} else {


				$zym_6 = '兑换失败,'.$zym_4['err_code_des'];


			}


			M('withdraw_log')->add(array('user_id' => $this->user['id'], 'money' => $zym_12, 'hand_fee' => $zym_3, 'create_time' => NOW_TIME, 'status' => $zym_4['status'], 'return_code' => $zym_5['return_code'], 'result_code' => $zym_5['result_code'], 'return_msg' => $zym_5['return_msg'], 'err_code_des' => $zym_5['err_code_des'], 'err_code' => $zym_5['err_code'], 'payment_no' => $zym_5['payment_no'], 'server_addr' => $_SERVER['SERVER_ADDR'], 'remote_addr' => $_SERVER['REMOTE_ADDR']));


			$this->success($zym_6);


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


		if (IS_POST) {


			$zym_12 = floatval($_POST['money']);


			$zym_57 = $_POST['payway'];


			if ($zym_12 < 0) {


				$this->error('金额错误');


			}


			if ($zym_57 != 'wxpay' && $zym_57 != 'alipay') {


				$this->error('请选择一种有效的支付方式');


			}


			$zym_14 = M('charge')->add(array('user_id' => $this->user['id'], 'money' => $zym_12, 'payway' => $zym_57, 'create_time' => NOW_TIME, 'status' => 0,));


			if (!$zym_14) {


				$this->error('操作失败，请重试！');


			}


			if ($zym_57 == 'wxpay') {


				$zym_45 = '4' .time(). $zym_14;


				$zym_47 = get_wxpay_parameters($zym_45, $zym_12, $this->user['openid'], '在线充值');


				if (!$zym_47) {


					$this->error('调用微信支付失败');


				}


				$this->assign('pay_param', $zym_47);


				$this->assign('param', json_decode($zym_47,true));


				$this->display('wxpay');


				exit;


			} else if ($zym_57 == 'alipay') {


				$zym_15 = $this->_alipay;


				$zym_25 = array('service' => 'alipay.wap.create.direct.pay.by.user', 'partner' => $this->_alipay['pid'], 'seller_id' => $this->_alipay['pid'], 'payment_type' => 1, 'notify_url' => complete_url('/alipay_notify.php'), 'return_url' => complete_url('/alipay_notify.php'), 'anti_phishing_key' => '', 'exter_invoke_ip' => '', 'out_trade_no' => '4' . $zym_14, 'subject' => '在线充值', 'total_fee' => $zym_12, 'body' => '', '_input_charset' => strtolower('utf-8'));


				$zym_15['sign_type'] = strtoupper('MD5');


				$zym_24 = new \Common\Util\Alipay\AlipaySubmit($zym_15);


				$zym_26 = $zym_24->buildRequestForm($zym_25, 'get', '确认');


				echo $zym_26;


				exit;


			}


			$this->error('发生错误');


		}


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