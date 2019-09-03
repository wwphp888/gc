<?php
/*
功能：基础类
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net 
备用域名：www.chonty.com
修改时间：2019-06-26 18:59
*/
class ShoukuanlaBase{
public $db;

/*function __construct(){  

}*/

//读取配置文件并且转换成员变量
public function _newCfg($field_key=null,$module_name=null,$fileName=null,$file_path=null){

	//配置信息转成 成员变量	
	$cfg=skl_C($module_name,$fileName,$file_path);
  
	if(empty($field_key)){
		
		//获取全部键值
		foreach($cfg as $k=>$v){
			 $this->$k=$v;
		}

	}else{
	
	  //获取部分键值
		$field_key_explode=explode(',',$field_key);
		foreach($field_key_explode as $fv){
			 $this->$fv=$cfg[$fv];
		}
	}

	unset($cfg);
}


//读取数据库配置并且转换成员变量
public function _newDbCfg($fieldNames=null){

   if(empty($this->cfg_sys_cfg_table_name)){  
	    $sys_cfg=skl_C(SKL_MODULE_NAME_1);//加载模块1配置
	 }else{
	    $sys_cfg['cfg_sys_cfg_table_name']=& $this->cfg_sys_cfg_table_name;
	 }   
	 
	 if(empty($fieldNames)){   $fieldNames='*';	 }

   $this->_newDb();
   $cfg_info=$this->db->table("`@#_".$sys_cfg['cfg_sys_cfg_table_name']."`")->field($fieldNames)->where("`cfg_id`=1")->find();

	 //需要转换数组的字段
	 $zhuan_arr=array('cfg_money_group','cfg_is_float','cfg_is_remittance','cfg_is_write_note','cfg_paytype_order','cfg_users_toggle','cfg_users_toggle_next','cfg_qrcode_path','cfg_is_hide_search');

	 foreach($cfg_info as $k=>$v){
		  //如果需要转换数组
			if(in_array($k,$zhuan_arr)){
			   $this->$k=json_decode($v,true);
			}else{
			   $this->$k=$v;
			}
	    
	 }

	 unset($cfg_info);

}

//修改数据库配置，修改成功返回影响行数
public function _upDbCfg($update_field=null){

	 if(empty($this->cfg_sys_cfg_table_name)){  
			$sys_cfg=skl_C(SKL_MODULE_NAME_1);//加载模块1配置
	 }else{
			$sys_cfg['cfg_sys_cfg_table_name']=& $this->cfg_sys_cfg_table_name;
	 } 
	 
	 $is_update=$this->db->table("`@#_".$sys_cfg['cfg_sys_cfg_table_name']."`")->where("`cfg_id`=1")->update($update_field.',`cfg_time`=unix_timestamp()');
	 if($is_update > 0){
		  return $is_update;
	 }else{
	    skl_error('数据库配置表修改失败！'); 
	 }

}


//新建数据库对象,改函数可执行多次，$this->db对象都不会改变
public function _newDb(){

  if(!is_object($this->db)){
		require_once(SKL_ClASS_PATH.'ShoukuanlaDbi.class.php');

		$config=skl_C('','',SKL_ROOT_PATH.'db.php');
		$config['cfg_DB_CHARSET']='utf8';
		$this->db=new ShoukuanlaDb($config);
	
	}

}


}
?>