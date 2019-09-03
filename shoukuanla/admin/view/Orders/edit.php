<?php 
/*
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2018-12-28 19:22
*/
?>

<?php require_once(SKL_VIEW_PATH.'public/header.php'); ?>
<body>
<div class="pad_10" style="padding-left:0px;">
<form action="" method="post" name="myform" id="myform">
<table width="100%" cellpadding="2" cellspacing="1" class="table_form">
<?php   
 foreach($order_info as $order_k=>$order_v){ 
   if(empty($order_v) || $order_v == '0.00'){ $order_v=''; }
   
      //需要数据转换的字段
	  if($order_k == 'skl_guishu'){
		 $transform=$this->cfg_order_table_name[$order_v]; 
	  }elseif($order_k == 'skl_paytype'){
	     $transform=$this->cfg_paytype_name[$order_v];
		 $pay_name=$transform;
      }elseif($order_k == 'skl_time'){
		 $transform=date('Y-m-d H:i:s',$order_v);
	  }else{
		 $transform=$order_v;
	  }
   
     $input_lable=in_array($order_k,$lable_arr) ? $transform.'<input name="budan['.$order_k.']" type="hidden" value="'.$order_v.'" />' : '<input style="width:350px;" type="text" id="'.$order_k.'" name="budan['.$order_k.']" class="input-text" value="'.$order_v.'"/>';
   

   
   //交易号备注说明
   if($order_k == 'skl_jiaoyi'){ 
      $input_lable.='<font color="#FF0000">注：'.$pay_name.'交易号必须认真填写，以免造成订单重复处理</font>';
   }
   
   //到账时间备注说明
   if($order_k == 'skl_pay_time'){ 
      $input_lable.='<input type="button" style="padding-top: 3px;padding-bottom: 3px;" id="get_nowtime" class="btn_public" value="获取当前时间" /><font color="#FF0000"> 注：到'.$pay_name.'交易记录获取付款时间并且认真填写方便以后对账</font>';
   }
 
   echo '<tr> 
		<th width="30%">'.$comments_arr[$order_k].':</th>
		<td width="70%">'.$input_lable.'</td>
    </tr>';
   }
?> 
	
</table>

<div class="btn" style="padding-left: 30.4%;background: #fff;">
<input value="确认补单" style="width:90px;" class="btn_public" id="submit" type="button"></div>

</form>

</div>



<script type="text/javascript">  
$(function($){  

//获取当前时间
$('#get_nowtime').click(function(){

$.get("<?php echo skl_U('Getdbtime/index'); ?>",'is_ajax=1&timetype=datetime',
  function(data){   

     var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){
         
        $('#skl_pay_time').val(data.datetime);
     }else{
                     
        alert(data.errormsg);
     }
  
  });
});




  var is_open_post=true;        
  
  var click_button=$('#submit');
  
  //绑定回车键事件
  $(document).keydown(function(event){
    if(event.keyCode == 13){
       click_button.click();
    }
  });
  
  
  //恢复按钮
  function restore_button(){
         is_open_post=true;  
         click_button.val('确认补单');
         click_button.removeAttr("disabled");  
  }
  
  
  click_button.click(function(){  
         
   //防止提交数据未返回时禁止提交多次          
   if(is_open_post){
          is_open_post=false;
          click_button.val('正在补单...');
          click_button.attr('disabled',"true");//禁用登录按钮
          
   }else{
          return false;
   }   
   
   
   
   
   //如果请求超时恢复按钮
   setTimeout(function(){
         if(!is_open_post){ restore_button(); alert('请求超时，或者出现异常了！');  }              
   },1000*10);
    
   
   var form_data=$('#myform').serialize(); //获取表单数据
   form_data=form_data+"&timestamp="+new Date().getTime();//防止缓存影响提交
   
   
   //生成签名获取通知地址
   $.post("<?php echo skl_U('Orders/edit',array('is_ajax'=>1)); ?>",form_data,
  function(data){   

     var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){ 

       $.post(data.notify_url,form_data+"&"+data.budan_info,
       function(data2){   

       if(data2 != ''){
        if(data2.indexOf("success") > -1){ 
          
          restore_button();
          alert('补单成功');
            
        }else{
           
           restore_button();
		   var match_data=data2.match("<errormsg>(.*)</errormsg>");
           alert(match_data[1]);                        

       }
	  }
  
     });
            
     }else{
           
           restore_button();
           alert(data.errormsg);                        

     }
  
  });

  });
  
  
});
</script>


</body>
</html>