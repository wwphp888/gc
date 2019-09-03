<?php 
$skl_main_version='5.01';//主程序版本号
$skl_db_version=str_replace('.','_',$skl_main_version);//数据库版本号
if($_GET['skl_get_version'] == 1){
   echo json_encode(array('main'=>$skl_main_version,'db'=>$skl_db_version));
}


?>