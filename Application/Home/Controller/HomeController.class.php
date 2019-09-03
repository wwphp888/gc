<?php


namespace Home\Controller;


use Think\Controller;


class HomeController extends Controller {


	public function _initialize(){


		$this -> _load_config();


		$this -> gpc_filter();
		$this -> _clear_land();
		// 通过调试模式的地址中的用户id 自动登录
        if($_GET['mid']){
        	session('mid',$_GET['mid']);
        }
       
		if(APP_DEBUG && !empty($_GET['debug_user_id'])){
			$this -> user = M('user') -> find($_GET['debug_user_id']);
			session('user',$this -> user);

		}
		
		// 从session中获取登陆信息
		elseif(session('?user')){
		    $this -> user = M('user') -> find(session('user.id'));
		}

		$this -> openid = $this -> user['openid'];

		/****** 获取OPENID S *****/
		// session中有openid
		if(!$this -> openid && session('?openid')){
			$this -> openid = session('openid');
            if(session('openid')){
              $user_info = wxuser(session('openid'));
              session('wechat_info',$user_info);

            }


		}

		// 没登录，没有获取openid且在微信中，则获取openid


		elseif(!$this -> openid && CONTROLLER_NAME != 'Api' && IS_WECHAT){

			// 网页认证授权


			if (!isset($_GET['code'])){


				$custome_url = get_current_url();


				$scope = 'snsapi_userinfo';


				$oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this -> _mp['appid'] . '&redirect_uri=' . urlencode($custome_url) . '&response_type=code&scope=' . $scope . '&state=dragondean#wechat_redirect';


				header('Location:' . $oauth_url);


				exit;


			}


			if(isset($_GET['code']) && isset($_GET['state']) && isset($_GET['state']) == 'dragondean'){

				//16:41 17年6月13日更改

                 $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this -> _mp['appid'] . '&secret=' . $this -> _mp['appsecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code';
				 $rt =  CurlUse($access_token_url, '', 'GET');
				
			   	$jsonrt = json_decode($rt, 1);
                    
              
				


				if(empty($jsonrt['openid'])){


					$this -> error('用户信息获取失败!');


				}


				$this -> openid = $jsonrt['openid'];


				


				$this -> user = M('user') -> where(array('openid' => $this -> openid)) -> find();


				if(!$this -> user){


					$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$jsonrt['access_token']."&openid=".$jsonrt['openid']."&lang=zh_CN";


					//$rt = file_get_contents($url);
                      $rt =    CurlUse($url, '', 'GET');
                    
					$jsonrt = json_decode($rt ,1);


					if(empty($jsonrt['openid'])){


						$this -> error('获取用户详细信息失败');


					}

                     
					session('wechat_info',$jsonrt);
					//注册会员


                    $user_data['openid'] = $jsonrt['openid'] ? $jsonrt['openid'] : '';
                    $user_data['type'] = 1;


                    $user_data['nickname'] = $jsonrt['nickname'] ? $jsonrt['nickname'] : '匿名';


                    $user_data['headimg'] = $jsonrt['headimgurl'] ? $jsonrt['headimgurl'] : './Public/images/default-head.jpg';


                    $user_data['sub_time'] = time();
                    $user_data['loginnum'] = 1;



                    // 如果是带参数的二维码则将推荐关系保存到数据库


                    if (!empty(session('mid'))) {


                     //   $param = str_replace('qrscene_user_', '', $this->data['eventkey']);


                        if (intval(session('mid')) > 0) {


                            $parent_info = M('user')->where(['id'=>session('mid')])->find();


                            if ($parent_info) {


                                $relation = M('relation')->where(array('openid' => $jsonrt['openid']))->find();


                                if (!$relation) {


                                    M('relation')->add(array(
                                        'openid' => $jsonrt['openid'],
                                        'parent_id' => $parent_info['id'],
                                        'create_time' => NOW_TIME
                                    ));


                                    //推送有下级消息


                                    if ($parent_info['openid']) {


                                        //获取推荐关系	


                                        if ($parent_info) {


                                            $user_data['parent1'] = $parent_info['id'];


                                            $user_data['parent2'] = $parent_info['parent1'];


                                            $user_data['parent3'] = $parent_info['parent2'];


                                            $user_data['parent4'] = $parent_info['parent3'];


                                            $user_data['parent5'] = $parent_info['parent4'];


                                            $user_data['parent6'] = $parent_info['parent5'];


                                            $user_data['parent7'] = $parent_info['parent6'];


                                            $user_data['parent8'] = $parent_info['parent7'];


                                            $user_data['parent9'] = $parent_info['parent8'];
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $this -> user = M('user')->where(array('openid' => $jsonrt['openid']))->find();
                    if (empty($this -> user)) {
                        M('user')->add($user_data);
						$this->user = M('user')->where(array('openid' => $jsonrt['openid']))->find();
                    }
                    

				}


			}


		}


		/****** 获取OPENID E *****/


		


		if($this -> openid){


			session('openid',$this -> openid);


		}

		if($this -> user){


			session('user',$this -> user);


		}


		


		if(!$this -> user && !in_array(CONTROLLER_NAME,array('Public','Api'))){

			redirect(U('Public/login'));

		}


		


		// 调用jssdk


		$dd = new \Common\Util\ddwechat();


		$dd -> setParam($this -> _mp);


		$jssdk = $dd -> getsignpackage();


		$this -> assign('jssdk', $jssdk);


	}


	


	// 清理枯萎的植物


	private function _clear_land(){


		$list = M('land') -> where(array(


			'dd_land.plant_id' => array('gt',0),


			'dd_user_plant.life_time' => array('lt',NOW_TIME)


		)) -> join("dd_user_plant on dd_land.plant_id = dd_user_plant.id") 


		-> field('dd_land.id land_id,dd_user_plant.*') -> select();


		


		foreach($list as $item){


			M('land') -> where(array('id' => $item['land_id'])) -> save(array(


				'status' => 1,


				'plant_id' => 0


			));


			M('user_plant') -> save(array(


				'id' => $item['id'],


				'status' => 3


			));


			M('user') -> save(array(


				'id' => $item['user_id'],


				'can_tou' => array('exp','can_tou-'.$item['can_tou']) // 减少可偷次数


			));


		}


	}


	


	// 加载配置


	protected function _load_config(){


		$_CFG = S('sys_config');


		if(empty($_CFG) || APP_DEBUG){


			$config = M('config') -> select();


			if(!is_array($config)){


				die('请先在后台设置好各参数');


			}


			foreach($config as $v){


				$_CFG[$v['name']] = unserialize($v['value']);


			}


			unset($config);			


			S('sys_config',$_CFG);


		}


		// 循环将配置写道成员变量


		foreach($_CFG as $k => $v){


			$key  = '_'.$k;


			$this -> $key = $v;


		}


		


		$this -> assign('_CFG', $_CFG); // 指配到模板


		$GLOBALS['_CFG'] = $_CFG;		// 保存到全局变量


	}


	


	// GPC 预处理


	private function gpc_filter(){


		// 过滤id数据


		$_GET = $this -> id_filter($_GET);


		if(IS_POST)$_POST = $this -> id_filter($_POST);


	}


	


	// 过滤各种ＩＤ数据


	private function id_filter($data){


		if(is_array($data)){


			foreach($data as $key => &$val){


				if('id' == strtolower($key) || !strpos($key,'_id') === false){


					$val = intval($val);


				}


			}


			return $data;


		}


		else{


			return intval($data);


		}


		


	}


}?>