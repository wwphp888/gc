<?php

	$mid=$_GET["mid"];
	$from=$_GET["from"];
   $url="http://uuoor.cn/index.php?mid=".$mid."&from=".$from."";
if (isset($url)) 
{ 
Header("HTTP/1.1 303 See Other"); 
Header("Location: $url"); 
exit; 
} 


?>