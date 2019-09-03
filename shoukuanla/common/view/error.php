<?php 
//修改时间：2018-12-07 18:58
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>错误信息</title>

<style type="text/css">
.contens{
	width: 30%;
	background-color: #FFF;
	box-shadow: 0px 3px 10px #0070A6;
	margin-right: auto;
	margin-left: auto;
	margin-top: 200px;
	height: auto;
	border-radius: 6px;
	font-family: "微软雅黑";
	margin-bottom: 50px;
	padding-top: 20px;
	padding-right: 20px;
	padding-bottom: 20px;
	padding-left: 20px;
	text-align: center;
}
a{ cursor:pointer; }	
p{ font-size: 20px; margin-top: 15px; margin-bottom: 15px; letter-spacing:1px; }	
.jump{ font-size: 14px; }	

		</style>
</head>

<body>
<div class="contens">
<p><?php echo $title; ?></p>

<p class="jump">
页面自动 <b><a id="href" onclick="jump();">跳转</a></b> 等待时间： <b id="wait"><?php echo $returnTime; ?></b>
</p>

</div>

<script type="text/javascript">
//跳转
function jump(){
  
  <?php    
   if(empty($returnPath)){
	  echo 'window.history.back(-1);';
   }else{
	  echo "window.location.href='$returnPath';"; 
	   
	}
  ?>
}



(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		jump();
		clearInterval(interval);
	};
}, 1000);
})();

</script>
</body>
</html>
