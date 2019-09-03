<?php

/* 
 * @author:Bobo.
 * @date:2017-6-14 17:09:46
 * @qq:836044851@qq.com
 */
namespace Agent\Controller;
use Think\Controller;
class PublicController extends Controller{
	/*
    public function login(){
        if(IS_POST){
            $user_name = $_POST['username'];
            $password = $_POST['password'];
            $where = ['username'=>$user_name,'password'=>$password];
            $res = M('agent')->where($where)->find();
            if($res){
                session('user_info',$res);
                $this->redirect(U('Admin/index'));
            }else{
                $this->error('账号或者密码错误');
            }
        }else{
            $this->display();
        }
    }
	*/
	// 登录


	public function login(){


		if(IS_POST){
			 $user_name = $_POST['user'];
            $password = $_POST['pass'];
			
			 $where = ['username'=>$user_name,'password'=>md5(md5($password))];
             $res = M('agent')->where($where)->find();
             
                   
			if(empty($_POST['user']) || empty($_POST['pass'])){


				$this -> assign('errmsg', '账号密码不能为空');


			}else if($res){


				 session('user_info',$res);
             


				


				if(isset($_POST['remember'])){


					cookie('admin_user', $_POST['user']);


				}


				


				redirect(U('Admin/index'));


				exit;


			}else{


				$this -> assign('errmsg', '账号或密码不对');


			}


		}


		


		$this -> display();


	}
    public function logout(){
         session('user_info',null);
         $this->redirect(U('public/login'));
    }
}

