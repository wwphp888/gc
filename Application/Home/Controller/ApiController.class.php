<?php


namespace Home\Controller;


use Think\Controller;


class ApiController extends Controller {


    public function index(){
		// 加载配置
		$config = M('config') -> select();


		if(!is_array($config)){


			die('请先在后台设置好各参数');


		}


		foreach($config as $v){


			$key = '_'.$v['name'];


			$this -> $key = unserialize($v['value']);


			$_CFG[$v['name']] = $this -> $key;


			$GLOBALS['_CFG'] = $_CFG;


		}





		// 验证URL


		if(isset($_GET['echostr'])){


			die($_GET['echostr']);


		}


		


		$dd = new \Common\Util\ddwechat;


		$this -> dd = $dd;


		$this -> data = $dd -> request();




		// 判断mp配置


		if(!$this -> _mp){


			$dd -> response('管理员没有配置公众号信息');


			exit;


		}


		


		// TODO 可以在这里判断fromusername和配置中的微信数据是否匹配来增加安全性


		


		$dd -> setParam($this -> _mp);


		


		//file_put_contents('a.txt', var_export($this -> data,1));


		$user_info = M('user') -> where(array('openid'=>$this -> data['fromusername'])) -> find();


		$this -> user = $user_info;


		


		//如果是关注


		if($this -> data['msgtype'] == 'event'){


			// 关注


			if($this -> data['event'] == 'subscribe'){


				


				if(!$user_info){


					$userinfo = wxuser($this -> data['fromusername']);


				   //注册会员


				  $user_data['openid'] = $userinfo['openid']?$userinfo['openid']:'';
				  $user_data['type'] = 1;


			    $user_data['nickname'] = $userinfo['nickname']?$userinfo['nickname']:'匿名';


			    $user_data['headimg'] = $userinfo['headimgurl']?$userinfo['headimgurl']:'./Public/images/default-head.jpg';


			    $user_data['sub_time'] = time();
			    $user_data['loginnum'] = 1;
                    


					// 如果是带参数的二维码则将推荐关系保存到数据库


					if(!empty($this -> data['eventkey'])){


						$param = str_replace('qrscene_user_','', $this -> data['eventkey']);


						if(intval($param) >0){


							$parent_info = M('user') -> find(intval($param));


							if($parent_info){


								$relation = M('relation') -> where(array('openid' => $this -> data['fromusername'])) -> find();


								if(!$relation){


									M('relation') -> add(array(


										'openid' => $this -> data['fromusername'],


										'parent_id' => $parent_info['id'],


										'create_time' => NOW_TIME


									));


									//推送有下级消息


									if($parent_info['openid']){


									//获取推荐关系	


									 if($parent_info){


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


									$msg = $userinfo['nickname'].'在'.date('Y-m-d H:i').'成为你的下线';


									sendwen($parent_info['openid'],$msg);	


									}


									


								}


							}


						}


					}

                   $user = M('user') -> where(array('openid'=>$user_data['fromusername'])) -> find();
                 if(empty($user)){
                 	M('user')->add($user_data);
                 }
					


				}


				


                  


                


			    $this -> reply_by_keyword('关注');


				//如果设置了关注时回复关键词则调用回复


				// if(!empty($this -> _site['subscribe'])){


				// 	$this -> reply_by_keyword($this -> _site['subscribe']);


				// }


			}


			


			// 取消关注


			elseif( $this -> data['event'] == 'unsubscribe'){


				$rs = M('user') -> where(array('openid' => $this -> data['fromusername'])) -> setField('subscribe', 0);


			}


			


			// 点击自定义菜单


			elseif( $this -> data['event'] == 'CLICK'){


               $this -> reply_by_keyword($this -> data['eventkey']);


			


			}


			


			// 模板消息回调


			elseif($this -> data['event'] == 'TEMPLATESENDJOBFINISH'){


				M('tplmsg') -> where(array(


					'msgid' => $this -> data['msgid']


				)) -> save(array(


					'send_result' =>  $this -> data['status']


				));


			}


		}


		


		


		// 如果是发送文字


		elseif($this -> data['msgtype'] == 'text' && !empty($this -> data['content'])){


			$this -> reply_by_keyword($this -> data['content']);


			


		}


		


		// 未处理的事件全部返回空


		else{


			exit('success');


		}


		


		exit('success');


    }


	


	// 根据关键词回复


	private function reply_by_keyword($key){


		$dd = &$this -> dd;


		$replys = M('autoreply') -> where(array(


			'status' => 1,


			'_string' => "find_in_set('{$key}',keyword)"


		)) -> fetchSql(0) -> select();


		


		// 没有关键词对应回复


		if(empty($replys) || count($replys)<1){


			if($key == '#qrcode'){


				$this -> reply_qrcode();


			}


			exit('success');


		}


		


		// 只有用一条记录,且是文本回复


		elseif(count($replys) ==1 && $replys[0]['type'] == 1){ // 


			$row = $replys[0];


			if(!empty($row['content'])){


				$dd -> response($row['content']);


			}


		}


		


		// 多条记录或者一条图文记录都是图文回复


		else{


			$pids = array();


			foreach($replys as $row){


				if($row['type'] ==2){


					$pids[] = $row['id'];


				}


			}


			if(count($pids) >0){


				// 查询所有文章


				$articles = M('article') -> where(array(


					'autoreply_id' => array('in', $pids)


				)) -> limit(10) -> order('id desc') -> select();





				foreach($articles as $article){


					$msgs[] = array(


						'title' => $article['title'],


						'description' => $article['desc'],


						'picurl' => complete_url($article['cover']),


						'url' => complete_url(U('Article/read?id='.$article['id']))


					);


				}


				$dd -> response(array('articles' => $msgs), 'news');


			}


		}


		$dd -> response('3');


	}


	


	// 回复二维码


	function reply_qrcode(){


		$dd = $this -> dd;





		


		$user = M('user')->where(array('openid'=>$this -> data['fromusername']))->find();


		// if($user['id']!=7){


		//  $dd -> response('推广二维码暂时关闭访问');


		//  die;    


		// }


		if(!$user){


          $dd -> response('平台还没你资料记录，请先进入农场一次再获取');


		}


		$is_pay = M('wxpay_log')->where(array('openid'=>$user['openid'],'type'=>9,'return_code'=>'success'))->find();


		if($GLOBALS['_CFG']['site']['code_is']==0 && empty($is_pay) ){


			//$dd -> response('需要先购买公排才能获取推广二维码');


		}


		


		// if(!$planed){


		// 	$dd -> response('需要最少种植一次才可以生成二维码');


		// 	exit;


		// }


		$rs = create_qrcode($user);


		


		if($rs){


			$acces_token = $dd -> getaccesstoken();			


			$media_id = $dd -> uploadmedia($rs,'image');


			$dd -> response($media_id,'image');


			


		}


		else $dd -> response('生成二维码失败，请稍候重试');


		exit;


	}


}