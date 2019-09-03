<?php
namespace Admin\Controller;
use Think\Controller;
class CaiController extends AdminController {
    // 骰子佣金记录 列表
	public function expense(){
		$_GET = array_merge($_GET,$_POST);
		$where = array();
		if(!empty($_GET['user_id'])){
			$where['user_id'] = intval($_GET['user_id']);
		}
		if(!empty($_GET['buyre_id'])){
			$where['buyre_id'] = intval($_GET['buyre_id']);
		}
		if(isset($_GET['level']) && $_GET['level'] != '' ){
			$where['level'] = intval($_GET['level']);
		}
		if(isset($_GET['type']) && $_GET['type'] != '' ){
			$where['type'] = intval($_GET['type']);
		}
		if(isset($_GET['sn']) && $_GET['sn'] != '' ){
			$where['sn'] = I('get.sn');
		}
		$this -> _list('shai_fx',$where);
	}
	
}?>