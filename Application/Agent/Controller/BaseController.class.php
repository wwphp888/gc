<?php

/* 
 * @author:Bobo.
 * @date:2017-6-14 17:08:37
 * @qq:836044851@qq.com
 */
namespace Agent\Controller;
use Think\Controller;
class BaseController extends Controller{
    public function __construct() {
        parent::__construct();
        if(!session('user_info')){
            $this->redirect(U('public/login'));
        }
    }
}
