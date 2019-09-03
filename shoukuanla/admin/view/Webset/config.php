<?php 
/*
功能：后台首页
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-06-30 19:21
*/
?>

<?php require_once(SKL_VIEW_PATH.'public/header.php'); ?>
<body>
<style type="text/css">
.remark { color: #494BF8;}
.alipay_path_id th{ color: #FF2F2F; }
.wxpay_path_id th{ color: #00FF00; }
.tenpay_path_id th{ color: #9900FF; }
</style> 

<form id="myform" name="myform" action="<?php echo skl_U('Webset/config'); ?>" method="post">
    <div class="pad-10">
      
      <table width="100%" cellpadding="2" cellspacing="1" class="table_form">
          
        <tr>
          <th width="12%">静态秘钥 :</th>
          <td width="88%"><input type="text" name="site[cfg_sign]" size="50" value="<?php echo $this->cfg_sign; ?>"></td>
          </tr>
        
        <tr>
          <th>订单有效期(分钟) :</th>
          <td><input type="text" name="site[cfg_ge_time]" size="50" value="<?php echo $this->cfg_ge_time/60; ?>"></td>
          </tr>
        
        
        <tr>
          <th>指定金额组 :</th>
          <td><input type="text" name="site[cfg_money_group]" size="50" value="<?php echo $money_group; ?>"><span class="remark">注：必须是整数用"-"号隔开，正确格式(50-100-200) 特别注意：如果修改了金额组必须上传对应金额的收款码</span></td>
          </tr>
        
        
        <tr>
          <th>付款成功返回地址 :</th>
          <td><input type="text" name="site[cfg_return_url]" size="50" value="<?php echo $this->cfg_return_url; ?>"><span class="remark">注： 推荐使用相对路径去掉域名部分，例如 /abc/return_url.php</span></td>
        </tr>   
        
        <tr>
          <th>付款成功返回地址(手机端) :</th>
          <td><input type="text" name="site[cfg_return_url_m]" size="50" value="<?php echo $this->cfg_return_url_m; ?>"></td>
        </tr>     
        
        
        <tr>
          <th>是否开启支付页面 :</th>
          <td><label><input name="site[cfg_is_open_pay]" type="radio" value="1" <?php if($this->cfg_is_open_pay == 1){ echo 'checked="checked"';} ?> />开启</label>&nbsp;&nbsp;&nbsp; <label><input name="site[cfg_is_open_pay]" type="radio" value="0" <?php if($this->cfg_is_open_pay == 0){ echo 'checked="checked"';} ?> />关闭</label> </td>
        </tr>
        
        
        <tr>
          <th>是否开启任意金额 :</th>
          <td><label><input name="site[cfg_is_other_money]" type="radio" value="1" <?php if($this->cfg_is_other_money == 1){ echo 'checked="checked"';} ?> />开启</label>&nbsp;&nbsp;&nbsp; <label><input name="site[cfg_is_other_money]" type="radio" value="0" <?php if($this->cfg_is_other_money == 0){ echo 'checked="checked"';} ?> />关闭</label> </td>
        </tr> 
 
 
        <tr>
          <th>是否开启手动转账 :</th>
          <td>
          <?php foreach($this->cfg_paytype_name as $remittance_k=>$remittance_v){	
		  
	  if($remittance_k != SKL_PAYTYPE_WXPAY){
	    $is_checked_remittance=in_array($remittance_k,$this->cfg_is_remittance) ? 'checked="checked"' : '';
	  	  
	    echo '<label>'.$remittance_v.'&nbsp;<input name="site[cfg_is_remittance][]" type="checkbox" value="'.$remittance_k.'" '.$is_checked_remittance.' /></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';			  
	  }
	 }?> <span class="remark" style="padding-left:220px;">注： 被勾选的支付类型开启手动转账功能</span></td>
        </tr>
        
        
        <tr>
          <th>强制使用任意金额支付类型 :</th>
          <td>
          <?php foreach($this->cfg_paytype_name as $paytype_k=>$paytype_v){	
	  
	  $is_checked=in_array($paytype_k,$this->cfg_is_write_note) ? 'checked="checked"' : '';
	  	  
	  echo '<label>'.$paytype_v.'&nbsp;<input name="site[cfg_is_write_note][]" type="checkbox" value="'.$paytype_k.'" '.$is_checked.' /></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';			  
				  
	 }?> <span class="remark" style="padding-left:160px;">注： 被勾选的支付类型强制使用任意金额收款码，指定金额收款码则失效</span></td>
        </tr> 
        

        <tr>
          <th>支付开关和排序 :</th>
          <td>
          <div id="pay_order">
          <?php foreach($this->cfg_paytype_order as $order_k=>$order_v){
			  	  
	  $is_order_checked=$order_v == 1 ? 'checked="checked"' : '' ;
	  	  
	  echo '<li style="float:left;margin-right:15px;"><label>'.$this->cfg_paytype_name[$order_k].'&nbsp;<input name="site[cfg_paytype_order]['.$order_k.']" type="checkbox" value="1" '.$is_order_checked.' /></label>&nbsp;<img id="order_'.$order_k.'" src="'.SKL_STATIC_PATH.'images/lrydtb.png" width="17" title="点击鼠标左键往左移右键往右移" /></li>';			  
				  
	 }?> </div><span class="remark" style="padding-left:116px;">注： 被勾选的支付类型为开启，点击图标改变排序，鼠标左键往左移右键往右移</span></td>
        </tr>   
        
        
        <tr id="is_open_users">
          <th>是否开启多账号收款 :</th>
          <td><label><input onClick="is_show_users(true)" name="site[cfg_is_open_users]" type="radio" value="1" <?php if($this->cfg_is_open_users == 1){ echo 'checked="checked"';} ?> />开启</label>&nbsp;&nbsp;&nbsp; <label><input onClick="is_show_users(false)" name="site[cfg_is_open_users]" type="radio" value="0" <?php if($this->cfg_is_open_users == 0){ echo 'checked="checked"';} ?> />关闭</label> </td>
        </tr>        
    

        <tr id="users_toggle">
          <th>多账号收款切换方式 :</th>
          <td>
          <?php 
		  $users_toggle_arr=array(1=>'轮换',2=>'随机');
		  foreach($this->cfg_paytype_name as $toggle_k=>$toggle_v){
		  	  
          echo $toggle_v.'&nbsp;<select name="site[cfg_users_toggle]['.$toggle_k.'][toggle]">';
          	  
		  foreach($users_toggle_arr as $option_k=>$option_v){
			
			$is_select=$this->cfg_users_toggle[$toggle_k]['toggle'] == $option_k ? 'selected="selected"' : ''; 
			
			echo '<option '.$is_select.' value="'.$option_k.'">'.$option_v.'</option>';  
		  }
		  		  
          echo '</select>&nbsp;&nbsp;&nbsp;';
		  
		  } ?>
          <span style="padding-left:58px;" class="remark">注： 每个支付类型填写两个以上收款码目录才会生效</span>
         </td>
        </tr>       
        
        
         <tr id="add_pay_path">
          <th>添加收款码目录 :</th>
          <td>
          <?php foreach($this->cfg_paytype_name as $add_k=>$add_v){
          echo '<input style="width:110px;" class="btn_public" onclick="add_pay_path(\''.$add_k.'\')" id="add_'.$add_k.'_path" type="button" value="添加'.$add_v.'目录">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		  }?>
          
		  </td>
        </tr>        
              
        
        <?php 
		$qrocde_path_name='收款码目录';
		
		foreach($this->cfg_paytype_name as $payname_k=>$payname_v){

		foreach($this->cfg_qrcode_path[$payname_k] as $path_k=>$path_v){
		 $id_name=$payname_k.$path_k;
		 
         echo '<tr num-data="'.$path_k.'" class="'.$payname_k.'_path_id'.'">
          <th>'.$payname_v.$qrocde_path_name.$path_k.' :</th>
          <td><input type="text" name="site[cfg_qrcode_path]['.$payname_k.']['.$path_k.'][path]" size="50" value="'.$path_v['path'].'">';

      //收款账号
      echo '收款账号:&nbsp;<input type="text" style="width: 200px;" class="input-text" name="site[cfg_qrcode_path]['.$payname_k.']['.$path_k.'][user]" value="'.$path_v['user'].'" />';
		 		  	  		  
		  $is_display_dlt=$path_k == 1 ?'style="display:none;"' : '';
		  $is_checked_open=empty($path_v['open']) ? '' : 'checked="checked"' ;
		  echo '<label>启用&nbsp;<input type="checkbox" name="site[cfg_qrcode_path]['.$payname_k.']['.$path_k.'][open]" value="1" '.$is_checked_open.' /></label>&nbsp;&nbsp;'.'<input '.$is_display_dlt.' class="btn_public btn_red" onClick="remove_qrcode_path(this)" type="button" value="删除目录" />';
		  
		  if($payname_k == 'alipay' && $path_k == 1){ echo '<a target="_blank" class="btn_public" href="'.skl_U('Upload/index').'">上传收款码</a><span style="margin-left: 5px;" class="remark">注： 推荐使用收款账号作为目录名称，正确格式 (user1或alipay/user1) 目录名称只能用数字或字母组合</span>';}	
		  
          echo '</td></tr>';       
                            
		 }
		
		}?>
         
      </table>
      <div class="bk15"></div>
      
      <div class="btn" style="padding-left: 216px;"><input type="button" value="保存配置" style="width:90px;" class="btn_public" id="submit"> <input type="button" value="刷新配置" style="width:90px;margin-left: 10px;background-color: #009688;" class="btn_public" onClick="window.location.reload();"></div>
      </form>
      </div>
    
    
  </body>
  
<script type="text/javascript">

//删除收款码目录
function remove_qrcode_path(paytype_id){

  $(paytype_id).parent().parent().remove();	
	
}


//添加收款码目录
function add_pay_path(paytype){
	
  var select_pay=$("."+paytype+"_path_id");
  var pay_id=select_pay.last(); 
  var num_data=pay_id.attr("num-data");
  var pay_int_replace=parseInt(num_data)+1; 
  
  if(paytype == "alipay"){	  
	 var path_name="<?php echo $this->cfg_paytype_name['alipay']; ?>";	
  }else if(paytype == "wxpay"){
	 var path_name="<?php echo $this->cfg_paytype_name['wxpay']; ?>";	
  }else if(paytype == "tenpay"){
	 var path_name="<?php echo $this->cfg_paytype_name['tenpay']; ?>";
  }
    
  if(select_pay.size() >= 10){ alert(path_name+'收款码目录数量已超出限制'); return; }
    
  var new_pay_id=pay_id.clone(true);
  new_pay_id.children("th").text(path_name+"<?php echo $qrocde_path_name; ?>"+pay_int_replace+" :");
  new_pay_id.attr("num-data",pay_int_replace);
  var children_td=new_pay_id.children("td");
  
  if(paytype == "alipay"){
    children_td.children("a").remove();
    children_td.children("span").remove();
  }
  
  var children_input=children_td.children("input");
  var input_1=children_input.first();
  input_1.prop("name",input_1.prop("name").replace("["+num_data+"]","["+pay_int_replace+"]"));
  input_1.val("");

  var input_2=children_input.eq(1);
	input_2.prop("name",input_2.prop("name").replace("["+num_data+"]","["+pay_int_replace+"]"));
  input_2.val("");
	
  var children_open=children_td.children("label").children("input");  
  children_open.prop("name",children_open.prop("name").replace("["+num_data+"]","["+pay_int_replace+"]"));  
  if(!children_open.prop("checked")){ children_open.prop("checked",true); }
  
  children_input.last().show();
  
  pay_id.after(new_pay_id);
   

}



//显示隐藏多账号收款功能
function is_show_users(is_open){
   var users_toggle="#users_toggle";
   var add_pay_path="#add_pay_path";
   var alipay_path_id=".alipay_path_id";
   var wxpay_path_id=".wxpay_path_id";
   var tenpay_path_id=".tenpay_path_id";
	
   if(is_open){
	   
	  $(users_toggle).show(500); 
	  $(add_pay_path).show(1400);
	  $(alipay_path_id).show(1600);
	  $(wxpay_path_id).show(1800);
	  $(tenpay_path_id).show(2000);
	  
   }else{
	  
	  $(users_toggle).hide(); 
	  $(add_pay_path).hide();
	  
	  var alipay_path_tr=$(alipay_path_id);
	  alipay_path_tr.hide();
	  alipay_path_tr.first().show();
	  
	  var wxpay_path_tr=$(wxpay_path_id);
	  wxpay_path_tr.hide();
	  wxpay_path_tr.first().show();
	  
	  var tenpay_path_tr=$(tenpay_path_id);
	  tenpay_path_tr.hide();
	  tenpay_path_tr.first().show();
   }	
	
}


//如果多账号收款关闭就隐藏功能
<?php if($this->cfg_is_open_users == 0){ echo 'is_show_users(false);';} ?>



$(function($){	

$("#pay_order li img").mousedown(function(e){
	
  //单机鼠标左键往前移
  if(e.which == 1){	
  var objParentTR = $(this).parent();
  var prevTR = objParentTR.prev();
  if (prevTR.length > 0) {
     prevTR.insertAfter(objParentTR);
  }  
  
 }else if(e.which == 3){ 

   //单机鼠标右键往后移
  var objParentTR2 = $(this).parent();
  var nextTR = objParentTR2.next();
  if(nextTR.length > 0) {
     nextTR.insertBefore(objParentTR2);
  } 
	 
 }
   
});

 
 
 //ajax提交表单，开始
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
         click_button.val('保存配置');
         click_button.removeAttr("disabled");  
  }
  
  
  click_button.click(function(){ 
         
   //防止提交数据未返回时禁止提交多次          
   if(is_open_post){
          is_open_post=false;
          click_button.val('正在保存...');
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

   $.post("<?php echo skl_U('Webset/config'); ?>",form_data,
  function(data){   
     
     var data=jQuery.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){ 
	     restore_button();
	     alert('保存成功');
         window.location.reload();
     }else{   
	     
		 restore_button();     
         alert(data.errormsg);                        

     }
  
  });

  }); 
 
 //ajax提交表单，结束
 
 
 
});

</script> 
</html>

