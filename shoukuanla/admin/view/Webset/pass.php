<?php 
/*
功能：后台首页
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2018-12-26 19:21
*/
?>

<?php require_once(SKL_VIEW_PATH.'public/header.php'); ?>
<body>
<script language="javascript" type="text/javascript" src="<?php echo SKL_PUBLIC_PATH; ?>plugins/formvalidator/js/formvalidator.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo SKL_PUBLIC_PATH; ?>plugins/formvalidator/js/formvalidatorregex.js"></script>

<form id="myform" method='post' name="login" action="<?php echo skl_U('Webset/pass') ;?>">
<table cellpadding=0 cellspacing=0 class="table_form" width="100%">
<tr><td width="140">旧密码</td><td><input type="password" class="input-text" name="oldpassword" id="oldpassword"></td></tr>
<tr><td>新密码</td><td><input type="password" class="input-text"  name="password" id="password" ></td></tr>
<tr><td>确认密码</td><td><input type="password" class="input-text"  name="repassword" id="repassword"></td></tr>

</table>
<div class="btn">

<div style="padding-left:145px;">
<INPUT TYPE="button"  name="dosubmit" id="dosubmit" value="修改" class="btn_public" >&nbsp;&nbsp;
<input type="reset"  value="取消" class="btn_public btn_red">
</div>

</div>
</form>

<script type="text/javascript">
	$(function() {
    $.formValidator.initConfig({
        formid: "myform",
        autotip: true,
        onerror: function(msg, obj) {
            window.top.art.dialog({
                content: msg,
                lock: true,
                width: '200',
                height: '50'
            },
            function() {
                this.close();
                $(obj).focus();
            })
        }
    });

    $("#oldpassword").formValidator({
        onshow: "请填写旧密码",
        onfocus: "请填写旧密码"
    }).inputValidator({
        min: 6,
        onerror: "请填写旧密码"
    }).ajaxValidator({
        type: "get",
        url: "<?php echo skl_U('Webset/check_old_pass') ;?>",
        data: "",
        datatype: "html",
        async: 'false',
        success: function(data) {
			//alert(data);
            if (data == 1) {
                return true;
            } else {
                return false;
            }
        },
        buttons: $("#dosubmit"),
        onerror: "旧密码不正确!",
        onwait: "正在检测..."
    });

    $("#password").formValidator({
        onshow: "填写密码",
        onfocus: "填写6位以上密码"
    }).inputValidator({
        min: 6,
        onerror: "请填写6位以上密码"
    });

    $("#repassword").formValidator({
        onshow: "确认密码",
        onfocus: "确认密码",
        oncorrect: "填写正确"
    }).compareValidator({
        desid: "password",
        operateor: "=",
        onerror: "两次输入密码不一致"
    }).inputValidator({
        min: 6,
        onerror: "请填写6位以上密码"
    });
	
	
	
	
  //点击提交数据	
  var is_open_post=true;        
  
  var click_button=$('#dosubmit');
  
  //绑定回车键事件
  $(document).keydown(function(event){
    if(event.keyCode == 13){
       click_button.click();
    }
  });
  
  
  //恢复按钮
  function restore_button(){
         is_open_post=true;  
         click_button.val('修改');
         click_button.removeAttr("disabled");  
  }
  
  
  click_button.click(function(){  
         
   //防止提交数据未返回时禁止提交多次          
   if(is_open_post){
          is_open_post=false;
          click_button.val('正在修改...');
          click_button.prop('disabled',"true");//禁用登录按钮
          
   }else{
          return false;
   }   
   
   
   
   
   //如果请求超时恢复按钮
   setTimeout(function(){
         if(!is_open_post){ restore_button(); alert('请求超时，或者出现异常了！');  }              
   },1000*10);
    
   
   var form_data=$('#myform').serialize(); //获取表单数据
   form_data=form_data+"&is_ajax=1&timestamp="+new Date().getTime();//防止缓存影响提交
        
   $.post("<?php echo skl_U('Webset/pass'); ?>",form_data,
  function(data){   
  
     var data=jQuery.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式     
     if(data.status == 'success'){
		  
	     restore_button(); 
         alert('修改成功');
	     window.location.reload();
     }else{
		 
         restore_button(); 
         alert(data.errormsg);            
                                

     }
  
  });

  });
  
	
})
</script>

</body>
</html>