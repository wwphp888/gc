<?php


namespace Admin\Controller;


use Think\Controller;


class ProductController extends AdminController {


    // 植物列表


	public function plant(){


		$this -> _list('plant',$where,'id desc');


	}


	


	// 编辑、添加


	public function plant_edit(){


		


		$this -> _edit('plant', U('plant'));


	}


	


	// 删除通知


	public function plant_del(){


		$this -> _del('plant', intval($_GET['id']));


		$this -> success('操作成功！', $_SERVER['HTTP_REFERER']);


	}


	


	// 肥料列表


	public function fertilizer(){


		// 查询所有的植物


		$plants_rs = M('plant') -> select();


		$plants = array();


		foreach($plants_rs as $plant){


			$plants[$plant['id']] = $plant;


		}


		$this -> assign('plants',$plants);


		


		


		$this -> _list('fertilizer',$where,'id desc');


	}


	function kai(){
		$where  = array();
		$this->_list('kailog',$where,'id desc');
	}
	function buy(){
		$where  = array();
		$this->_list('buylog',$where,'id desc');
	}
	function charge(){
		$where  = array();
		$this->_list('charge',$where,'id desc');
	}
		function expense(){
		$where  = array();
		$this->_list('expense',$where,'id desc');
	}
    function kong(){
    	$kid = $_POST['kid'];
    	$id = $_POST['id'];
    	$info = M('kailog')->where(array('id'=>$kid))->find();
    	if($info){
            if($info['status']==2){
    		 $data['info'] = '这期已结束';            
            }else{
            	M('kailog')->where(array('id'=>$kid))->save(array('kongid'=>$id));
            	$data['status'] = '1';
            	$data['id'] = $id;

            	
            }
    	}else{
    		$data['info'] = '没有这期';
    	}
      echo json_encode($data);
    }
    /*function money(){
    	$kid = $_POST['kid'];
    	$da = $_POST['da'];
    	$info = M('kailog')->where(array('id'=>$kid))->find();
    	if($info){
            if($info['status']==2){
    		 $data['info'] = '这期已结束';            
            }else{
            	M('kailog')->where(array('id'=>$kid))->save(array('da'=>$da));
            	$data['status'] = '1';
            }
    	}else{
    		$data['info'] = '没有这期';
    	}
      echo json_encode($data);
    }*/

	// 编辑、添加通知


	public function fertilizer_edit(){


		// 不是提交表单则查询所有植物


		if(!IS_POST){


			// 查询所有的植物


			$plants_rs = M('plant') -> select();


			$plants = array();


			foreach($plants_rs as $plant){


				$plants[$plant['id']] = $plant;


			}


			$this -> assign('plants',$plants);


		}


		$this -> _edit('fertilizer', U('fertilizer'));


	}


	


	// 删除通知


	public function fertilizer_del(){


		$this -> _del('fertilizer', intval($_GET['id']));


		$this -> success('操作成功！', $_SERVER['HTTP_REFERER']);


	}


	


}