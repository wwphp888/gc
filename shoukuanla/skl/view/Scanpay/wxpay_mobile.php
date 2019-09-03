<?php 
/*
功能：实现微信扫码自动充值
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-01-08 19:00
*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0">
<title>微信付款操作流程</title>
<meta name="keywords" content="微信付款操作流程"/>
<meta name="description" content="微信付款操作流程"/>

<script type="text/javascript" src="<?php echo SKL_PUBLIC_PATH; ?>js/jquery.min.js"></script>

<style type="text/css">
.contens{
  background-color: #FFF;
  width:86%;
	max-width: 640px;
	box-shadow: 0px 3px 10px #0070A6;
	margin-right: auto;
	margin-left: auto;
	margin-top: 5px;
	height: auto;
	border-radius: 6px;
	font-family: "微软雅黑";
	margin-bottom: 50px;
	padding-top: 5px;
	padding-right: 20px;
	padding-bottom: 20px;
	padding-left: 20px;
}
p{font-size: 13px; margin-top: 6px;	margin-bottom: 6px;}
.inputsty{ font-size: 16px; width: 230px; height: 28px; }
.sweep{  background-repeat: no-repeat; text-align: center; 
}
.buttonStyle{
	margin-right: auto;
	margin-left: auto;	
	width: 100%;
}

.buttonStyle a{
	text-decoration: none;
	font-size: 18px;
	background-color: #0080C0;
	border-radius: 6px;
	color: #FFF;
	padding-top: 6px;
	padding-bottom: 6px;
	display: block;
}
.buttonStyle a:hover{
	border-radius: 6px;
	background-color: #2377F5;
}

.menu{ height: 30px; text-align: center; width: 100%; margin-right: auto; margin-left: auto; margin-bottom: 5px; margin-top: 5px; }

.menu a{ width: 48%; text-decoration: none; font-size: 15px; background-color: #0080C0; border-radius: 6px; color: #FFF; cursor: pointer; display: block; float: left; padding-top: 3px; padding-bottom: 3px; }

.top a:hover{
	border-radius: 6px;
	background-color: #2377F5;
}
.topButton{ background-color: #AAF; 	
}
.inputEmail { width: 30px; padding-left: 36px; padding-top: 160px; }
.tableRight { text-align: right; width:40%;}
.tableFeft { text-align: left; width:60%;}
.alertInfoStyle{ padding: 10px; border: 1px solid #f6c8b5; background-color: #edffcc; border-radius: 6px; font-size: 16px; display: none;}
.moneyNotice{color: #F00;font-size: 17px;}


@font-face {
	font-family:rei;
	src:url(https://i.alipayobjects.com/common/fonts/rei.eot?20130819);
	src:url(https://i.alipayobjects.com/common/fonts/rei.eot?20130819#iefix) format("embedded-opentype"),url(https://i.alipayobjects.com/common/fonts/rei.woff?20130819) format("woff"),url(https://i.alipayobjects.com/common/fonts/rei.ttf?20130819) format("truetype"),url(https://i.alipayobjects.com/common/fonts/rei.svg?20130819#rei) format("svg")
}
.iconfont { font-family: rei; font-style: normal; font-weight: 400; cursor: default; -webkit-font-smoothing: antialiased; color: #FF0000; font-size: 20px; }
</style>


</head>
<body>
<div class="contens">

<div class="sweep">

<div class="menu">
<a id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',2,1);">扫码支付
</a>
<a style="width:4%;background-color: #FFF;"></a>
<a id="tab_setting_2" onclick="SwapTab('setting','on','',2,2);">识别二维码</a>
</div>

<p class="alertInfoStyle" id="alertInfoSuccess">
<i class="iconfont" style="color:#3C3;" title="支付成功">&#xF049;</i>&nbsp;恭喜您支付成功，<b style="color: #F60;">6</b>秒后返回网站。
</p>

<p class="alertInfoStyle" style="background-color: #fceee8;" id="alertInfoTimeout">
<i class="iconfont" style="color:#f33;" title="交易超时">&#xF045;</i>&nbsp;<a>该订单已过期如果您正在付款请停止操作。</a>
</p>

<div id="div_setting_1">

<p>
<b>第一步：登陆您的手机微信扫描下边的二维码，或者把二维码截图保存后登录手机微信扫码</b>
</p>


<?php 
if($post['is_write_note'] == '1'){

   $alertNotify=$post['is_float'] == '1' ? '金额':'金额和备注';
   echo '
   <p><b>第二步：输入指定'.$alertNotify.'后付款，注意必须需输入指定信息否则会充值失败</b></p>  ';

}

if($post['is_float'] == '1'){
  echo '<p><b style="color: #0B48FF;">指定金额：<label class="moneyNotice">'.$post['short_order'].'</label></b></p>';

}else{

  echo '<p><b style="color: #0B48FF;">指定金额：<label class="moneyNotice">'.$post['money'].'</label></b> &nbsp; <b style="color: #0B48FF;">指定备注：<label class="moneyNotice">'.$post['short_order'].'</label></b></p>';
}


echo '<p><img id="Qrcode" src="'.$qrcodePath.'" width="70%" title="'.$post['in_path'].'" /></p>';		


?>

</div>

<div id="div_setting_2" style="display: none;">

<div>
<span>
<p><b>第一步：登录您的手机微信，点击右上角的搜索按钮输入自己的微信号</b></p>
<p><b>第二步：将付款链接复制发送到自己的微信号然后点击链接进入付款页面</b></p>



<p><b>付款链接：<input style="width: 73%;height: 25px;" onClick="this.select();" class="inputsty" id="payUrl" type="text" value="<?php
require_once(SKL_G_FUNCTIONS_PATH.'skl_create_wx_url.php');
echo skl_create_wx_url($post); ?>" ></b></p>

<p>演示图</p>
<p><img width="100%" src="<?php echo SKL_STATIC_PATH; ?>images/wxsearch.jpg" /><br>
<img width="100%" src="<?php echo SKL_STATIC_PATH; ?>images/clickurl.jpg" />
</p>


</span>
</form>


</div>


</div>

<p style="padding-top:8px;"><?php echo empty($post['web_order']) ? '系统订单号：<label>'.$post['sys_order'].'</label>': '网站订单号：<label>'.$post['web_order'].'</label>'; ?> </p>

<script type="text/javascript">
var intDiff = parseInt(<?php echo $post['ge_time']; ?>);//倒计时总秒数量

var update_timer= window.setInterval(function(){
    var day=0,
        hour=0,
        minute=0,
        second=0;//时间默认值       
    if(intDiff > 0){
        day = Math.floor(intDiff / (60 * 60 * 24));
        hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
        minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
        second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
    }
    if (minute <= 9) minute = '0' + minute;
    if (second <= 9) second = '0' + second;
    $('#day_show').html(day+"天");
    $('#hour_show').html('<s id="h"></s>'+hour+'时');
    $('#minute_show').html('<s></s>'+minute+'分');
    $('#second_show').html('<s></s>'+second+'秒');
    intDiff--;
}, 1000);


</script>
<style type="text/css">
h1 {
    font-family:"微软雅黑";
    font-size:30px;
    border-bottom:solid 1px #ccc;
    padding-bottom:10px;
    letter-spacing:2px;
}
h2 {
    font-family:"微软雅黑";
    font-size:20px;
    letter-spacing:1px;
}
.time-item strong {
    background:#00AEEF;
    color:#fff;
    line-height:30px;
    font-size:18px;
    font-family:Arial;
    padding:0 10px;
		margin-left:5px;
    margin-right:5px;
    border-radius:5px;
	margin-top:10px;
	margin-bottom:10px;
    box-shadow:1px 1px 3px rgba(0,0,0,0.2);
}
#day_show {
    float:left;
    line-height:49px;
    color:#c71c60;
    font-size:32px;
    margin:0 10px;
    font-family:Arial,Helvetica,sans-serif;
}
.item-title .unit {
    background:none;
    line-height:49px;
    font-size:24px;
    padding:0 10px;
    float:left;
}
</style>


<h2 style="font-size:14px;margin-top:10px;margin-bottom:1px">距离该订单过期还有</h2>
<div class="time-item">
    <!--<span id="day_show">0天</span>
    <strong id="hour_show">0时</strong>-->
    <strong id="minute_show">0分</strong>
    <strong id="second_show">0秒</strong>
</div>

<p class="buttonStyle"><a style="font-size:16px;" href="<?php echo $post['return_url']; ?>">返回网站</a></p>

<p style="color:#F2230B;margin-top: 5px;">温馨提示：付款成功后1-20秒到账请耐心等候，如果超时未到账请联系网站客服处理。</a></p>


</div>

</div>




<script type="text/javascript">   
   
function SwapTab(name,cls_show,cls_hide,cnt,cur){
    for(i=1;i<=cnt;i++){
		if(i==cur){
			 $('#div_'+name+'_'+i).show(800);
			 $('#tab_'+name+'_'+i).attr('class',cls_show);
		}else{
			 $('#div_'+name+'_'+i).hide(800);
			 $('#tab_'+name+'_'+i).attr('class',cls_hide);
		}
	}
}   


<?php 
if($post['is_write_note'] == '1'){

  $alertMoney=$post['is_float'] == '1' ? '金额('.$post['short_order'].')' : '金额('.$post['money'].')和备注('.$post['short_order'].')'; 				

   echo 'setTimeout("alert(\'重要提示：扫码付款时必须输入指定'.$alertMoney.'，否则会充值失败哦\')",2000);';
}
?>


var timerQuerystatus =window.setInterval("querystatus('<?php echo $post['sys_order']; ?>')",3000);


//查询订单状态
function querystatus(sys_order){
 $.get("<?php echo skl_U('Querystatus/index'); ?>", { sys_order: sys_order,timestamp:new Date().getTime() },
  function(data){   
  
	   var data=jQuery.parseJSON($.trim(data));
     if(data.skl_state == '1'){	
		$("#Qrcode").attr("src","<?php echo SKL_STATIC_PATH; ?>images/failure.png"); 
		$("#alertInfoSuccess").show(100);
		upTimeout();
		window.clearInterval(timerQuerystatus);//停止ajax

	  }else if(data.isEmpty == '1'){
		  //订单不存在提示失效
		  var alertInfoTimeout=$("#alertInfoTimeout");
      alertInfoTimeout.find('a').text("该订单已失效如果您正在付款请停止操作");	 
		  alertInfoTimeout.show(100);
	    $("#Qrcode").attr("src","<?php echo SKL_STATIC_PATH; ?>images/failure.png"); 
		stop_timer();//停止订单剩余时间
	    window.clearInterval(timerQuerystatus);//停止ajax
	  }else if(data.isTimeout == '1'){
			alertOverdue();
		}
  });

}

//跳转
function jump(){
  
  window.location.href='<?php echo $post['return_url']; ?>';
}

//订单过期处理
function alertOverdue(){
	
 $("#Qrcode").attr("src","<?php echo SKL_STATIC_PATH; ?>images/failure.png");
 var alertInfoTimeout=$("#alertInfoTimeout");
 alertInfoTimeout.find('a').text("该订单已过期如果您正在付款请停止操作");	
 alertInfoTimeout.show(100);
 
 stop_timer();//停止订单剩余时间

 window.clearInterval(timerQuerystatus);//停止ajax
	
}


//更新倒返回网站计时时间
function upTimeout(){
	
	var timeoutspan=$('#alertInfoSuccess b').text();
	if(timeoutspan == "0"){
		 jump();
	}else{	
     timeoutspan--;		
		 $('#alertInfoSuccess b').text(timeoutspan);		
		 window.setTimeout("upTimeout()",1000);
		
	}
}

//停止订单剩余时间
function stop_timer(){	
 
 $('#minute_show').html('<s></s>00分');
 $('#second_show').html('<s></s>00秒');
 window.clearInterval(update_timer);	
	
}


window.setTimeout("alertOverdue()",1000*<?php echo $post['ge_time']; ?>);

</script>

</body>

</html>