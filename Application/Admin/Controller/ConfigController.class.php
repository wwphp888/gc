<?php


namespace Admin\Controller;


use Think\Controller;


class ConfigController extends AdminController {


	public function _initialize(){


		parent::_initialize();


	}


	


	// 站点设置


	public function site(){

         
		$this -> _save();


		$this -> display();


	}

	//客服设置
	public function cs(){

	         
		
		if(IS_POST){


			$_POST['config'] = array();


			foreach($_POST['pic'] as $key => $val){


				$_POST['config'][] = array('pic' => $_POST['pic'][$key], 'url' => $_POST['url'][$key],'sc'=>$_POST['sc'][$key]);


			}


			unset($_POST['pic']);


			unset($_POST['url']);


			unset($_POST['sc']);


		}


		$this -> _save();


		$this -> display();




	}


    // 充值设置


    public function recharge(){


//        $info = M('config_recharge')->select();
//
//
//        $this->assign('recharge_config', $info);


        if(IS_POST){


            $info = array_combine($_POST['money'], $_POST['pic']);

            $saveData = [];
            foreach ($info as $k => $v) {
                $saveData[] = [
                    'pic' => $v,
                    'money' => $k,
                ];
            }

            // 有此配置则更新,没有则新增

            if(array_key_exists('recharge', $this -> _CFG)){

                M('config') -> where(array('name' => 'recharge')) -> save(array(


                    'value' => serialize($saveData)


                ));


            }else{

                M('config') -> add(array(


                    'name' => 'recharge',


                    'value' => serialize($saveData)


                ));
            }

            $this -> success('操作成功！');

        } else {
            $this -> display();
        }
    }


	// 提现设置


	public function withdraw(){


		$this -> _save();


		$this -> display();


	}


	


	// 排位设置


	public function paiwei(){


		$fan= M('fantime')->find();


		$this->assign('fantime',$fan['lasttime']);


		$this -> _save();


		$this -> display();


	}


	// 常见问题设置


	public function alipay(){
		$list=M('question')->select();
		$this->assign('list',$list);	
		/*$this -> _save();*/
		$this -> display();

	}
	
	
	public function quaddact(){
		
		$data['question'] =I('question');
        $data['answer'] =I('answer');           
        $data['time']=time(); 
       // var_dump($data);exit;       
        M('question')->add($data);
		$this->redirect('alipay');
	}
	public function del(){
		//print_r(I('id'));die;
			$m=M('question');
        	$info=$m->delete(I('id'));
        	if ($info<1) {
            	$this->error('删除失败！');
       	 	}else{
            	$this->success('删除成功！',U('alipay'));
        	}
		}
	
	// 配置管理账号
	

	public function user(){


		if(IS_POST){


			if(empty($_POST['name'])){


				


				$this -> error('请正确填写登录名');


				


			}else if($_POST['pass'] != $_POST['pass2'] || empty($_POST['pass'])){


				


				$this -> error('请正确填写密码!');


			}


			


			$_POST['pass'] = xmd5($_POST['pass']);


			unset($_POST['pass2']);


			


			// 调用保存方法


			$this -> _save();


		}


		


		$this -> display();


	}


	


	// 配置公众号


	public function mp(){


		if(IS_POST){


			if(!empty($_FILES['cert'])){


				 $upload = new \Think\Upload();


				 $upload->maxSize   =     3145728 ;


				 $upload->exts      =     array('zip');


				 $upload->rootPath  =     './Public/cert/';


				 $upload->savePath  =     xmd5(time().rand()).'/';


				 $upload ->autoSub = false;


				 $info   =   $upload->upload();


				 if($info){


					$info = $info['cert'];


					


					// 解压


					$path = $upload->rootPath . $info['savepath'];


					$file = $path . $info['savename'];


					


					if(file_exists($file)){


						// 打开压缩文件


						$zip = new \ZipArchive();


						$rs = $zip -> open($file);


						if($rs && $zip -> extractTo($path)){


							$zip -> close();


							$_POST['cert'] = $path;


						}


						else{


							$this -> error('解压失败，请确认上传了正确的cert.zip');


						}


					}


					else{


						$this -> error('系统没找到上传的文件');


					}


				 }


				 else {


					$this -> error('证书上传错误');


				 }


			}


			else{


				$_POST['cert'] = $this -> _mp['cert'];


			}


		}


		$this -> _save();


		$this -> display();


	}


	


	// 轮播图设置


	public function banner(){


		if(IS_POST){


			$_POST['config'] = array();


			foreach($_POST['pic'] as $key => $val){


				$_POST['config'][] = array('pic' => $_POST['pic'][$key], 'url' => $_POST['url'][$key],'sc'=>$_POST['sc'][$key]);


			}


			unset($_POST['pic']);


			unset($_POST['url']);


			unset($_POST['sc']);


		}


		$this -> _save();


		$this -> display();


	}


	


	// 自定义样式


	public function css(){


		$css_file = '.'.__ROOT__ . '/Public/css/user.css';


		if(IS_POST){


			file_put_contents($css_file, $_POST['content']);


			$this -> success('操作成功！');


			exit;


		}


		


		$css_content = file_get_contents($css_file);


		$this -> assign('content', $css_content);


		$this -> display();		


	}


	


	private function _save($exit = true){


		// 通用配置保存操作



		if(IS_POST){


			// 有此配置则更新,没有则新增


			if(array_key_exists(ACTION_NAME, $this -> _CFG)){

				M('config') -> where(array('name' => ACTION_NAME)) -> save(array(


					'value' => serialize($_POST)


				));


			}else{

				M('config') -> add(array(


					'name' => ACTION_NAME,


					'value' => serialize($_POST)


				));


			}


			if($exit){


				$this -> success('操作成功！');


				exit;


			}


		}


	}


}?>