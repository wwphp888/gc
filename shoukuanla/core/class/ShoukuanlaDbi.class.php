<?php
/*
功能：MySql数据库操作
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-01-23 23:35
*/
class ShoukuanlaDb extends Mysqli{

public $update_query;//执行$this->update()函数后query的结果
public $db_prefix; //表前缀
public $db_name;//数据库名
public $db_replace='@#_';//替换成表前缀

private $set_table;//表名
private $set_field;//字段
private $set_where;//条件
private $set_order;//排序
private $set_limit;//限制条数

/**连接数据库
 * 参数:表名 数据库配置数组 或 数据库配置文件路径
 * @param $table
 * @param string $db_config_arr_path
 */
function __construct(& $config=array()){

$this->db_prefix=$config['cfg_DB_PREFIX'];
$this->db_name=$config['cfg_DB_NAME'];

$dbPort=empty($config['cfg_DB_PORT']) ? '3306':$config['cfg_DB_PORT'];

//mysqli方式连接
parent::__construct($config['cfg_DB_HOST'],$config['cfg_DB_USER'],$config['cfg_DB_PWD'],$this->db_name,$dbPort);

if($this->connect_error){

  $dbErrorMsg='数据库连接错误:(' . $this->connect_errno . ') '. $this->connect_error;
	if(function_exists('skl_error')){
	   skl_error($dbErrorMsg);
	}else{
	   exit($dbErrorMsg);
	}
	

}else{
	//设置字符集
  $this->set_charset($config['cfg_DB_CHARSET']); 
}


}

//合并表前缀
public function utable(& $table_name=null){
  return $this->db_prefix.$table_name;
}


//设置表
public function table($tableNane=null){
  
	$this->set_table=str_replace($this->db_replace,$this->db_prefix,$tableNane);   
  return $this;
}

//设置字段
public function field($fieldNane=null){
  
	$this->set_field=$fieldNane;   
  return $this;
}

//设置条件
public function where($whereNane=null){
  
	$this->set_where=$whereNane;   
  return $this;
}

//设置排序
public function order($orderNane=null){
  
	$this->set_order=$orderNane;   
  return $this;
}

//设置限制条数
public function limit($limitNane=null){
  
	$this->set_limit=$limitNane;   
  return $this;
}


//清空sql语句
private function clearSql(){

  $this->set_table='';
	$this->set_field='';
  $this->set_where='';
  $this->set_order='';
  $this->set_limit='';

}


//合并sql语句
private function unionSql(){

	$where=(string)$this->set_where == '' ? '' : " WHERE $this->set_where";
	$order=(string)$this->set_order == '' ? '' : " ORDER BY $this->set_order";
	$limit=(string)$this->set_limit == '' ? '' : " LIMIT $this->set_limit";

  return $where.$order.$limit;
}


/**
 * 查询一条数据
 * SELECT * FROM `表名` WHERE `id`=1 ORDER BY `id` DESC LIMIT 1
 * 返回值:数组(如果出错返回空)
 */
public function find(){

  if((string)$this->set_limit == ''){ $this->set_limit=1; }

  $field=(string)$this->set_field == '' ? ' *': " $this->set_field";
	$table=(string)$this->set_table == '' ? '' : " FROM $this->set_table";

  $rarr='';
	$rt=$this->query('SELECT'.$field.$table.$this->unionSql());
	$this->clearSql();
	if($rt){
		$rarr=$rt->fetch_assoc();
	  $rt->free();
  }

  return $rarr;
}


/**
 * 查询某个字段值
 * 参数:字段名
 * SELECT * FROM `表名` WHERE `id`=1 ORDER BY `id` DESC LIMIT 1
 * 返回值:字符串(如果出错返回空)
 */
public function getField($fields=null){

  if((string)$fields != ''){ $this->set_field=$fields; }
	$field=(string)$this->set_field == '' ? '': " $this->set_field";
	$table=(string)$this->set_table == '' ? '' : " FROM $this->set_table";	

	$rarr='';   
	$rt =$this->query('SELECT'.$field.$table.$this->unionSql());
	$this->clearSql();
	if($rt){
		 $rarr=$rt->fetch_assoc();
     $rt->free();
     foreach($rarr as $v){		 
		   return $v;
		 }
		 
	}

  return $rarr;
}


/**
 * 数据更新
 * 参数:$fields=mixed(字段名='值' 或 数组)，$is_quotes=bool($fields是数组情况下值是否带单引号)
 * 返回值: int(影响行数)
 * UPDATE `表名` SET `username`='AAA' WHERE `id`=1
 * 注意：语句执行成功或失败,执行成功并不意味着对数据库做出了影响,可以结合$this->update_query变量做判断
 if($this->update() < 1 && !$this->update_query){ exit('修改失败'); }
 */
public function update($fields=null,$is_quotes=true){
  //关联数组
  if(is_array($fields)){	
		$is_first=true; 
		
		if($is_quotes){
       //加单引号
       foreach($fields as $k=>$v){
			    if($is_first){
						 $field_merge="`$k` = '$v'";
						 $is_first=false;				
					}else{
					   $field_merge.=",`$k` = '$v'";
					}			 
			 }		
		}else{
			 //不加单引号
	     foreach($fields as $k=>$v){
			    if($is_first){
						 $field_merge="`$k` = $v";
						 $is_first=false;				
					}else{
					   $field_merge.=",`$k` = $v";
					}			 
			 }	
		
		}		
		$field=$field_merge == '' ? '' :" SET $field_merge";

	}else{

	  if((string)$fields != ''){ $this->set_field=$fields; } 
    $field=(string)$this->set_field == '' ? '': " SET $this->set_field";
	
	}

	$table=(string)$this->set_table == '' ? '': " $this->set_table";

	$this->update_query=$this->query('UPDATE'.$table.$field.$this->unionSql());
	$this->clearSql();
	return $this->affected_rows;

}


/**
 * 删除数据
 * DELETE FROM `表名` WHERE `id`=1
 * 返回值: 影响行数
 */
public function delete(){

	$table=(string)$this->set_table == '' ? '' : " FROM $this->set_table";

	$this->query('DELETE'.$table.$this->unionSql());
	$this->clearSql();
	return $this->affected_rows;

}


/**
 * 添加数据
 * 参数:$data=mixed(关联数组 或 字符串)，$is_quotes=bool($data是数组情况下值是否带单引号)
 * 返回值:主键ID(失败返回空)
 * 使用方法:$this->table('xxx')->add(array('字段名1'=>"'abc'",'字段名2'=>1));
 */
public function add($data=null,$is_quotes=true){

	  $table=(string)$this->set_table == '' ? '' : " $this->set_table";

		$field='';
		$idata='';
		//关联数组
		if(is_array($data)){			  
			  $is_first=true;

				if($is_quotes){
           //加单引号
           foreach($data as $k=>$v){
					    if($is_first){
							   $field="`$k`";
								 $idata="'$v'";
								 $is_first=false;
							}else{
							   $field.=",`$k`";	
								 $idata.=",'$v'";
							}
					 }

				}else{
           //不加单引号
           foreach($data as $k=>$v){
					    if($is_first){
							   $field="`$k`";
								 $idata=$v;
								 $is_first=false;
							}else{
							   $field.=",`$k`";	
								 $idata.=",$v";
							}
					 }

				}
				$sqlStr="INSERT INTO".$table." ($field) VALUES ($idata)";				

		}else{
				//字符串
				$sqlStr="INSERT INTO".$table." $data";

		}

    $execSql=$this->query($sqlStr);
		$this->clearSql();
		if($execSql){	return $this->insert_id;	} 

		return '';		 

} 

//析构函数
function __destruct(){
	//关闭MySQL连接
  $this->close();
}

/*
查数据集
$list=$this->db->query("SELECT * FROM `".$this->db->utable($this->cfg_sys_xxx)."` WHERE `id` > 0");
if($list){
	while($arr=$list->fetch_assoc()){
		 //循环遍历
	}
	$list->free();	
}
*/





}


?>