<?php 
//获取服务器IP
//修改时间：2018-02-19 18:58
function skl_serverIp(){
   
 if(isset($_SERVER)){   
	 
    if($_SERVER['SERVER_ADDR']){    
       $server_ip=$_SERVER['SERVER_ADDR'];    
    }else{    
       $server_ip=$_SERVER['LOCAL_ADDR'];    
    }   
		
 }else{    
    $server_ip = getenv('SERVER_ADDR');    
 }    

 return $server_ip;     

}

