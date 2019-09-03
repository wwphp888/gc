<?php 
/*
功能：实现扫码自动充值
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-06-27 19:21
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>收款啦订单管理系统登录</title>
<link href="<?php echo SKL_STATIC_PATH; ?>css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SKL_STATIC_PATH; ?>css/login.css" rel="stylesheet" type="text/css"/> 
<script type="text/javascript" src="<?php echo SKL_PUBLIC_PATH; ?>js/jquery.min.js"></script>
</head>
<body>
<div id="login">
	<form action="<?php echo skl_U('Login/check_admin');?>" method="post" id="myform">
		<dl>
			<dd>管理员账号：<input class="text user" type="text" name="username" id="username" /></dd>
			<dd>管理员密码：<input class="text pass" type="password" name="password" id="password" /></dd>
			
<dd>验　证　码：<input class="text pass" id="verify" type="text" name="verify"></dd> 
                              
                
               <dd style="padding-left:73px;"><img id="verify_img" src="<?php echo skl_U('Verify/index',array('rand'=>time())); ?>" title="看不清？点击刷新" onclick="javascript:this.src='<?php echo skl_U('Verify/index');?>&mt='+Math.random()" />
				</dd>	
			
			<dd><input style="font-size:15px" type="button" class="submit" name="dosubmit" value="登录管理中心" /></dd>			
		</dl>
	</form>   
    
  
<script type="text/javascript">  
$(function($){  
  var is_open_post=true;	
  
  var click_button=$('.submit');
  
  //绑定回车键事件
  $(document).keydown(function(event){
    if(event.keyCode == 13){
       click_button.click();
    }
  });
  
  
  //恢复按钮
  function restore_button(){
	 is_open_post=true;  
	 click_button.val('登录管理中心');
	 click_button.removeAttr("disabled");  
  }
  
  
  click_button.click(function(){  
	 
   //防止提交数据未返回时禁止提交多次 	 
   if(is_open_post){
	  is_open_post=false;
	  click_button.val('正在登录...');
	  click_button.prop('disabled',"true");//禁用登录按钮
	  
   }else{
	  return false;
   }   
   
   
   
   
   //如果请求超时恢复按钮
   setTimeout(function(){
	 if(!is_open_post){
	   restore_button(); 
	   alert('登录超时，或者出现异常了！');	
     }     	 
   },1000*10);
    
   
   var form_data=$('#myform').serialize(); //获取表单数据
   form_data=form_data+"&is_ajax=1&timestamp="+new Date().getTime();//防止缓存影响提交
	
   $.post("<?php echo skl_U('Login/check_admin'); ?>",form_data,
  function(data){   
  
  	 var data=jQuery.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){ 
	    window.location.href="<?php echo skl_U('Index/index'); ?>";
	 }else{
		 
		restore_button();		 
        alert(data.errormsg); 
		$("#verify_img").click();//更新验证码				

	 }
  
  });
 
  });
});
</script>

    
</div>
</body>
</html>