<?php 
//角色权限转字符串
//返回值：1,2,3....
//修改时间：2018-07-15 18:58
function skl_node_access_str(& $role_access=null){

		$node_id='';
		foreach($role_access as $row){//通过反序列化取出数据
			$node_id.=$row.',';
		}
		return substr($node_id, 0,-1);//去掉后边的逗号
}







