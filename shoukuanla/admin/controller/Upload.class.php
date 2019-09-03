<?php
/*
功能：接收处理上传图片
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-01-22 18:04
*/
require_once(SKL_CONTROLLER_PATH.'AdminBase.class.php');
class Upload extends AdminBase{
private $assign_dir_pos=array();
private $assign_dir_int=0;
private $del_qrcode_count=array('sum'=>0,'id'=>'');
private $del_dir_count=array('sum'=>0,'id'=>'');

function __construct(){  
  
  parent::__construct(); 
	$this->_newCfg('cfg_sys_dir_table_name,cfg_sys_qrcode_table_name',SKL_MODULE_NAME_1);//加载模块1配置 

}

//显示上传页面
public function index(){ 

	 $shangji_id=intval(trim($_GET['sid']));

	 $gengefu_dir='/';//分隔符

	 //查询当前位置
	 $this->_cha_shangji_id($shangji_id);

   require_once(SKL_CONTROLLER_VIEW_PATH.'index.php');
}

//递归查询上级ID
private function _cha_shangji_id($sid=null){

  $sid=(int)$sid; 
  if($sid == ''){ return false; }
  $sid_info=$this->db->query("SELECT `dir_id`,`dir_name`,`dir_shangji` FROM `".$this->db->utable($this->cfg_sys_dir_table_name)."` WHERE `dir_id`=$sid LIMIT 1");
	if($sid_info){
		$sid_arr=$sid_info->fetch_assoc();
		$sid_info->free();

		if($sid_arr['dir_shangji'] != ''){
			 $this->assign_dir_int++; 
			 $this->assign_dir_pos[$this->assign_dir_int]=$sid_arr;
		   $this->_cha_shangji_id($sid_arr['dir_shangji']); 
		}
		 
		
	}

}


//接收上传收款码
public function qrcode(){

 $typeArr = array('jpeg','jpg','png','gif','ico');//允许上传文件格式

 $dir_name=intval(trim($_GET['sid']));//目录名称

if (isset($_POST)){
	$img_name = $_FILES['file']['name'];
	$img_size = $_FILES['file']['size'];
	$name_tmp = $_FILES['file']['tmp_name'];
  $img_type = $_FILES['file']['type'];   

	if (empty($img_name)) {		exit(json_encode(array('errormsg' =>'您还未选择图片')));  	}
	$type = strtolower(substr(strrchr($img_name, '.'), 1));//获取文件类型
	if (empty($name_tmp)) {		exit(json_encode(array('errormsg' =>'上传出错！$_FILES[\'file\'][\'error\']错误代码：'.$_FILES['file']['error'])));  	}

	if (!in_array($type, $typeArr)) {	 exit(json_encode(array('errormsg' => '请上传jpg,png或gif类型的图片！'))); 	}
	if ($img_size > (500 * 1024)) {	exit(json_encode(array('errormsg' => '图片大小已超过500KB！'))); 	}

  $img_data=addslashes(file_get_contents($name_tmp));


	//覆盖相同的图片
	$img_info=$this->db->table("`@#_$this->cfg_sys_qrcode_table_name`")->where("`qrcode_dir`='$dir_name' AND `qrcode_name`='$img_name'")->field("`qrcode_id`,`qrcode_dir`,`qrcode_name`")->find();
	$img_id=$img_info['qrcode_id'];
	if($img_id > 0){	
	   
	   $is_ok=$this->db->table("`@#_$this->cfg_sys_qrcode_table_name`")->where("`qrcode_id`=$img_id")->limit(1)->update("`qrcode_data`='$img_data',`qrcode_time`=UNIX_TIMESTAMP()");
     
		 if($is_ok > 0){ $update_img_id=$img_id; }  //替换成功返回ID

	}else{

		//存放到数据库
		$img_id=$is_ok=$this->db->table("`@#_$this->cfg_sys_qrcode_table_name`")->add(array('qrcode_dir'=>"'$dir_name'",'qrcode_name'=>"'$img_name'",'qrcode_type'=>"'$img_type'",'qrcode_data'=>"'$img_data'",'qrcode_time'=>'UNIX_TIMESTAMP()'),false);				

	}

	//记录管理员操作日志，开始
	require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
	$AddLog=new AddLog($this);
	$log_arr['admin_log_type']    =1;
	$log_arr['admin_log_explain'] ='上传收款码: 1张 收款码ID:'.$img_id.' 文件名:'.$img_name.' 归属目录ID:'.$dir_name;
	$AddLog->index($log_arr);
  //记录管理员操作日志，结束


  if($is_ok < 1){   exit(json_encode(array('errormsg' => '上传有误，请检查服务器配置！'))); 	}

	echo json_encode(array('status'=>'success','id'=>$img_id, 'pic' =>skl_U(SKL_MODULE_NAME_1.'/Showimg/index',array('dir'=>$dir_name,'name'=>$img_name)), 'name' => $img_name,'update_img_id'=>$update_img_id));

}

}

//删除目录或收款码
public function delete(){ 

  $post=skl_I($_POST);
	$sid=intval($_GET['sid']);
	$fengefu=',';
	$log_del_dir_count     =array('sum'=>0,'id'=>'');
	$log_del_qrcode_count  =array('sum'=>0,'id'=>'');

	//选中删除的目录ID和下级内容
	foreach($post['selectdir'] as $dir_v){
		 if(intval($dir_v) > 0){ 
			  //调用$this->_delete_all()函数前必须先清空变量
			  $this->del_qrcode_count='';
        $this->del_dir_count='';

		    $this->_delete_all($dir_v);//删除选中目录下级所有内容				
				
				//删除选中目录ID
				$single_dir_rows=$this->_delete_dir($dir_v);
				if($single_dir_rows < 1){  exit(json_encode(array('errormsg' =>'选中ID为'.$dir_v.'的目录删除失败！')));	}

        $log_del_dir_count['sum']+=$single_dir_rows;
				$log_del_dir_count['id'].=$dir_v.$fengefu;
		 }   
	
	} 

	if($log_del_dir_count['sum'] > 0){
		 //删除目录后自动对表进行优化
		 $this->db->query('OPTIMIZE TABLE `'.$this->db->utable($this->cfg_sys_dir_table_name).'`');
	}


  //选中删除的收款码ID
	foreach($post['selectimg'] as $qrcode_v){
		 if(intval($qrcode_v) > 0){
		    $qrcode_where.=$qrcode_v.$fengefu;
		 }   
	
	} 
	$qrcode_where=rtrim($qrcode_where,$fengefu);
  $qrcode_is_empty=empty($qrcode_where) ? true : false ;
	if(!$qrcode_is_empty){   
		 $log_del_qrcode_count['sum']=$this->_delete_img($qrcode_where);
		 if($log_del_qrcode_count['sum'] < 1){  exit(json_encode(array('errormsg' =>'选中ID为'.$qrcode_where.'的收款码删除失败！'))); 	}			 
		 $log_del_qrcode_count['id']  =$qrcode_where;
	}
	
	if(empty($post['selectdir']) && $qrcode_is_empty){ exit(json_encode(array('errormsg' =>'请选择要删除的目录或收款码！')));  }
  
	//删除收款码后自动对表进行优化
	if($this->del_qrcode_count['sum'] > 0 || $log_del_qrcode_count['sum'] > 0){
	   $this->db->query('OPTIMIZE TABLE `'.$this->db->utable($this->cfg_sys_qrcode_table_name).'`');
	}

	//记录管理员操作日志，开始
	require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
	$AddLog=new AddLog($this);
	$log_arr['admin_log_type']=2;

	if($log_del_dir_count['sum'] > 0){ 
		 
		 $log_dir_xiaji=$this->del_dir_count['sum'] > 0 ? ' 删除下级目录: '.$this->del_dir_count['sum'].'个 下级目录ID:'.rtrim($this->del_dir_count['id'],$fengefu) : '';
		 
	   $log_arr['admin_log_explain']='删除目录: '.$log_del_dir_count['sum'].'个 目录ID:'.rtrim($log_del_dir_count['id'],$fengefu).$log_dir_xiaji;
	   $AddLog->index($log_arr);  
	}

	if($log_del_qrcode_count['sum'] > 0){
     
		 $log_arr['admin_log_explain']='删除收款码: '.$log_del_qrcode_count['sum'].'张 归属目录ID:'.$sid.' 收款码ID:'.rtrim($log_del_qrcode_count['id'],$fengefu);
		 $AddLog->index($log_arr);
	}

	if($this->del_qrcode_count['sum'] > 0){
		 
		 $log_arr['admin_log_explain']='删除下级收款码: '.$this->del_qrcode_count['sum'].'张 下级收款码ID:'.rtrim($this->del_qrcode_count['id'],$fengefu);
		 $AddLog->index($log_arr);
	}
	//记录管理员操作日志，结束


	echo json_encode(array('status' =>'success'));
		

}

/*
删除下级目录所有文件类型
注意：
$this->del_qrcode_count='';
$this->del_dir_count='';
调用$this->_delete_all()函数前必须先清空变量
*/
private function _delete_all($shangji_id=null){

   $shangji_id=(int)$shangji_id;
   if($shangji_id == ''){ return false; }

	 //根据上级目录ID查下级目录 
   $dir_info=$this->db->query("SELECT `dir_id` FROM `".$this->db->utable($this->cfg_sys_dir_table_name)."` WHERE `dir_shangji`=$shangji_id ORDER BY `dir_id`"); 
   if($dir_info){
	  while($dir_arr=$dir_info->fetch_assoc()){ 
			   $dir_rows=$this->_delete_dir($dir_arr['dir_id']);
         if($dir_rows < 1){
				    exit(json_encode(array('errormsg' =>$dir_arr['dir_id'].'该目录ID删除失败！')));
				 }

         //记录被删除的目录ID和数量
				 $this->del_dir_count['id'].=$dir_arr['dir_id'].',';
				 $this->del_dir_count['sum']+=$dir_rows;

				 $this->_delete_all($dir_arr['dir_id']);
	  }
	  $dir_info->free();
	   
   } 


   //根据上级目录ID查下级文件
	 $qrcode_info=$this->db->query("SELECT `qrcode_id` FROM `".$this->db->utable($this->cfg_sys_qrcode_table_name)."` WHERE `qrcode_dir`=$shangji_id ORDER BY `qrcode_id` ASC");   
 
	 if($qrcode_info){
		  while($qrcode_arr=$qrcode_info->fetch_assoc()){ 
           $union_qrcode_id.=$qrcode_arr['qrcode_id'].',';	        
		  }
			$union_qrcode_id=rtrim($union_qrcode_id,',');
      if($union_qrcode_id != ''){
				$qrcode_rows=$this->_delete_img($union_qrcode_id);
				if($qrcode_rows < 1){
					 exit(json_encode(array('errormsg' =>$union_qrcode_id.'该收款码ID删除失败！')));
				}

				//记录被删除的收款码ID和数量
				$this->del_qrcode_count['id'].=$union_qrcode_id.',';
				$this->del_qrcode_count['sum']+=$qrcode_rows;

			}

		  $qrcode_info->free();
		 
	 }

}


//删除文件夹
private function _delete_dir($dir_id=null){

  return $this->db->table("`@#_$this->cfg_sys_dir_table_name`")->where("`dir_id` IN($dir_id)")->delete();

}

//删除图片
private function _delete_img($img_id=null){

	return $this->db->table("`@#_$this->cfg_sys_qrcode_table_name`")->where("`qrcode_id` IN($img_id)")->delete();

}


//新建收款码目录
public function newdir(){

   $post=skl_I($_POST);
   $shangji_id=intval($post['sid']);
   if($shangji_id < 1 && $post['sid'] != 0){  exit(json_encode(array('errormsg' =>'上级目录ID格式错误！'))); }

   //目录名称只能用数字或字母
	 $delimiter_dir='-';
	 $dir_name_arr=array_unique(explode($delimiter_dir,$post['dirname']));
	 foreach($dir_name_arr as $name_v){
     $name_v=trim($name_v);
		 if($name_v !== ''){
			 if(preg_match("/^(\w){1,50}$/",$name_v) < 1){  exit(json_encode(array('errormsg' =>'目录名称只能用数字或字母！')));  }
			 
			 $dir_name_where.="`dir_name`='$name_v' OR ";//组合条件
			 $insert_value.="('$name_v','$shangji_id',UNIX_TIMESTAMP()),";//组合插入数据

			 $log_dir_count++;
			 $log_dir_name.=$name_v.$delimiter_dir;
		 
		 }

	 }
   if(empty($dir_name_where)){   exit(json_encode(array('errormsg' =>'目录名称不能为空！')));  }
	 $dir_name_where=rtrim($dir_name_where,' OR ');
	 $insert_value=rtrim($insert_value,',');
	 

   //判断上级目录id是否存在
	 if($shangji_id > 0){
	    if($this->db->table("`@#_$this->cfg_sys_dir_table_name`")->where("`dir_id`=$shangji_id")->getField('COUNT(`dir_id`)') < 1){
	       exit(json_encode(array('errormsg' =>$shangji_id.'该上级目录ID不存在！')));
	    }
	 }

   

   //查询目录是否已经存在
   if($this->db->table("`@#_$this->cfg_sys_dir_table_name`")->where("($dir_name_where) AND `dir_shangji`=$shangji_id")->getField('COUNT(`dir_id`)') > 0){
	     exit(json_encode(array('errormsg' =>$post['dirname'].'该目录名称已被占用，同级目录下不能有重名！')));
	 }


	 if($this->db->table("`@#_$this->cfg_sys_dir_table_name`")->add("(`dir_name`,`dir_shangji`,`dir_time`) VALUES $insert_value") < 1){	    
     exit(json_encode(array('errormsg' =>'新建目录失败！')));	    
	 }

	 //记录管理员操作日志，开始
	 require_once(SKL_G_CONTROLLER_PATH.'AddLog.class.php');
	 $AddLog=new AddLog($this);
	 $log_arr['admin_log_type']    =1;
	 $log_arr['admin_log_explain'] ='新建目录: '.$log_dir_count.'个 目录名称:'.rtrim($log_dir_name,$delimiter_dir).' 上级目录ID:'.$shangji_id;
	 $AddLog->index($log_arr);
	 //记录管理员操作日志，结束


   echo json_encode(array('status' =>'success'));
}



}
?>