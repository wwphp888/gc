<?php

/* 
 * @author:Bobo.
 * @date:2017-6-14 17:05:32
 * @qq:836044851@qq.com
 */
namespace Agent\Controller;
//use Think\Controller;
use Agent\Controller\BaseController;
class AdminController extends BaseController{
      
       //查询出当前代理的所有推广记录
       public function index(){
		   
		
        $time_range = I('request.timerange'); //时间范围
        if ($time_range) {
            list($tmp_start_time, $tmp_end_time) = explode(' - ', $time_range);
        } else {
            $tmp_start_time = date('Y-m-d', strtotime("-1 month")); //30天前
            $tmp_end_time = date('Y-m-d'); //当天的0点0分0秒
        }
        $start_time = strtotime($tmp_start_time);
        $end_time = strtotime($tmp_end_time)+24*60*60-1;
          $this->assign('user_info',session('user_info'));
		  //获取到当前用户的下级的所有的购买记录
		  
		  // $zym_51 = array('user_id' => session('user_info')['relation_user_id']);
		   $commission_get_user_id = session('user_info')['relation_user_id'];//佣金获得者ID
		   
		 //  var_dump($commission_get_user_id);
		  
		
		  
		  if($time_range){
			  $where = "e.user_id=$commission_get_user_id and b.starttime>=$start_time and b.starttime<=$end_time";
		  }else{
			   $where = "e.user_id=$commission_get_user_id";
		  }
		   $res =  M()->query("select b.* from  dd_buylog as b where uid in
(select  e.buyer_id from dd_expense as e where ".$where." group by buyer_id)");         	
		  $total = count($res);
		    $params = ['timerange'=>$time_range];
		  $page = new \Think\Page($total, 25,$params);
		//  var_dump($where);
		  $expense_where =  $time_range ? "user_id=$commission_get_user_id and create_time>=$start_time and create_time<=$end_time" : "user_id=$commission_get_user_id";
		 $total_money = M('expense')->field('sum(moneys) as total_money')->where($expense_where)->find()['total_money'];
		 
		  $lose_money = [];
		  $win_money = [];
		  foreach($res as $k=>$v){
			  if($v['yingmoney'] == 0){
				  $lose_money[] = $v['money'];
			  }else{
				  $win_money[] = $v['yingmoney']-$v['money'];
			  }
			  
		  }
		  $lose_money_ok = array_sum($lose_money);
		  $win_money_ok = array_sum($win_money);
		 
		
		 
         		
		
          
          $sql = "select b.* from  dd_buylog as b where uid in
(select  e.buyer_id from dd_expense as e where ".$where." group by buyer_id) order by b.id desc limit ".$page -> firstRow.','.$page -> listRows;
         		
		$list =  M()->query( $sql);	
        			  
           $this->assign('list',$list);                       							  
		   $this->assign('page',$page->show());
		   $this->assign('count',$count);
		   $this->assign('total_money',$total_money);
		   $this->assign('lose_money',$lose_money_ok);
		   $this->assign('win_money',$win_money_ok);
		   $this->assign('start_time',$tmp_start_time);
		   $this->assign('end_time',$tmp_end_time);
		  
		   $this -> display();
      //  $this->_list('expense', $zym_51); 
		
		
	
       }
	   // 通用简单列表方法
	private function _list($table, $where= null, $order = null){
		$list = $this -> _get_list($table, $where, $order);
		$this -> assign('list', $list);
		$this -> assign('page', $this -> data['page']);
		$this -> display();
	}
	
	// 获得一个列表,返回而不输出
	private function _get_list($table, $where= null, $order = null){
		$model = M($table);
		$count = $model -> where($where) -> count();
		$page = new \Think\Page($count, 25);
		if(!$order){
			$order = "id desc";
		}
		$list = $model -> where($where) -> limit($page -> firstRow . ',' . $page -> listRows ) -> order($order) -> select();
		
		// 将数据保存到成员变量
		$this -> data = array(
			'list' => $list,
			'page' => $page -> show(),
			'count' => $count
		);
		
		return $list;
	}
}
