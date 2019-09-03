<?php 
/*
功能：后台首页
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-06-27 19:21
*/
?>

<?php require_once(SKL_VIEW_PATH.'public/header.php'); ?>
<body>
<link href="<?php echo SKL_STATIC_PATH; ?>css/main.css" rel="stylesheet" type="text/css"/>

<?php
//弹窗输入插件，开始
echo '<link rel="stylesheet" type="text/css" href="'.SKL_PUBLIC_PATH.'plugins/alertwindow/css/xcConfirm.css"/>
<script src="'.SKL_PUBLIC_PATH.'plugins/alertwindow/js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>';
//弹窗输入插件，结
?>

<div style="padding:10px; overflow:hidden;">
	
	<div style="width:50%;" class="fl">
      	
		<div class="main_con fl">
			<h6>配置信息</h6>
			<div class="content">				

                
                <?php 
				
   $disk_space = @disk_free_space(".")/pow(1024,2);
				
		echo '<p>操作系统 : '.PHP_OS.'</p>';
		echo '<p>运行环境 : '.$_SERVER["SERVER_SOFTWARE"].'</p>';
		echo '<p>上传附件限制 : '.ini_get('upload_max_filesize').'</p>';
		echo '<p>执行时间限制 : '.ini_get('max_execution_time').'秒</p>';
		echo '<p>服务器域名/IP : '.$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]'.'</p>';
		echo '<p>剩余空间 : '.round($disk_space < 1024 ? $disk_space:$disk_space/1024 ,2).($disk_space<1024?'M':'G').'</p>';			
				
				?>
                
                
				<div class="hr">			
				</div>					
			
			</div>
		</div>
		
	</div>
	
</div>

<script type="text/javascript">
$(function(){
				
  <?php 
  if($is_show_alert){
	 echo 'window.wxc.xcConfirm("为了您的网站安全，请及时修改登录密码", window.wxc.xcConfirm.typeEnum.warning);';
  }
  ?>
  

});
</script>


</body>
</html>