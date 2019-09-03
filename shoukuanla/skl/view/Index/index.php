<?php 
/*
功能：实现扫码自动充值
作者：宇卓
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-06-28 19:21
*/
$setWidth=empty($post['width']) ? 820 : $post['width'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>账户充值</title>
<meta name="keywords" content="账户充值操作流程"/>
<meta name="description" content="账户充值付款操作流程"/>

<script type="text/javascript" src="<?php echo SKL_PUBLIC_PATH; ?>js/jquery.min.js"></script>

<style type="text/css">
body,li{list-style:none; font-family: "微软雅黑";}
.skl_contens{
	background-color: #FFF;
	width: <?php echo $setWidth; ?>px;
	overflow:hidden; 
	height:auto!important; 
	box-shadow: 0px 3px 10px #0070A6;
	margin-right: auto;
	margin-left: auto;
	margin-top: 20px;
	border-radius: 6px;
	margin-bottom: 50px;
	padding-top: 10px;
	padding-right: 20px;
	padding-bottom: 20px;
	padding-left: 20px;	
}
p{ color: #0B48FF; 	margin-top: 6px;	margin-bottom: 6px;}
.buttonStyle {
	border: 2px solid #D7DCFF;
	color: #FFF;
	font-size: 18px;
	cursor: pointer;
	background-attachment: scroll;
	background-color: #6A7DFF;
	background-image: none;
	background-repeat: repeat;
	background-position: 0% 0%;
	padding-top: 5px;
	padding-right: 18px;
	padding-bottom: 5px;
	padding-left: 18px;
	border-radius: 5px;
}

.moneyBox{
	border-bottom-width: 1px;
	border-bottom-style: dashed;
	border-bottom-color: #CCC;
	height: auto;
	float: left;
	width: <?php echo $setWidth; ?>px;
	padding-top: 10px;
	padding-bottom: 10px;
}
.moneyBox li{
	font-size: 16px;
	float: left;
	height: auto;
	width: 80px;
	margin-right: 15px;
	border: 1px double #C4DEFF;
	border-radius: 5px;
	text-align: center;
	padding-top: 5px;
	padding-right: 10px;
	padding-bottom: 5px;
	padding-left: 10px;
	margin-bottom: 15px;
	cursor: pointer;
	color: #333;
}
.selectli{
	background-image: url(<?php echo SKL_STATIC_PATH; ?>images/select.png);
	background-repeat: no-repeat;
	background-position: right bottom;
	background-color: #E1F5FF;
}
.payBox{
	border-bottom-width: 1px;
	border-bottom-style: dashed;
	border-bottom-color: #CCC;
	height: auto;
	float: left;
	width: <?php echo $setWidth; ?>px;
	padding-top: 10px;
	padding-bottom: 10px;   	
}
.payBox li{
	font-size: 18px;
	float: left;
	height: 45px;
	width: 145px;
	margin-right: 15px;
	border: 1px double #C4DEFF;
	border-radius: 5px;
	text-align: center;
	padding-top: 5px;
	padding-right: 10px;
	padding-bottom: 5px;
	padding-left: 10px;
	margin-bottom: 15px;
	cursor: pointer;
	color: #333;
}
.gediv{
    height:20px;width: <?php echo $setWidth; ?>px;float: left;
}

</style>


</head>
<body>
<div class="skl_contens">
<form target="_blank" action="<?php echo $skl_action_url; ?>" method="post">
<h1 style="font-size:17px;">请选择支付金额</h1>
<div id="id_money_box" class="moneyBox">

<?php 
//遍历输出金额组
foreach($this->cfg_money_group
 as $dirValue){	
  echo '<li id="'.$dirValue.'" money-type="0">'.$dirValue.'元</li>';	
}
?>


<?php 
if($this->cfg_is_other_money == '1'){
echo '<li id="other" money-type="1">
<input money-type="1" style="font-size: 16px;width:75px;height:16px;color: #666;" name="skl_custom_money" type="text" value="其他金额" />
</li>';

}
?>
</div>


<?php 
if(!$paytype_start_empty){
echo '<div class="gediv"></div>
<h2 style="font-size:17px;">请选择支付方式</h2>
<div id="id_pay_box" class="payBox">';

foreach($paytype_start as $v){
		
  echo '<li id="'.$v.'"><img src="'.SKL_STATIC_PATH.'images/'.$v.'.png" height="45" /></li>';	

}

echo '</div>';
}
?>

<div class="gediv"></div>
<h2 style="font-size:17px;">请输入用户名</h2>

<div class="payBox">
<input type="text" name="<?php echo $alias_obj->post_alias['username']; ?>" style="width:200px;height:30px;font-size:18px" value="<?php echo $poUsername; ?>" />
</div>

<div class="gediv"></div>


<!--隐藏域,开始-->
<?php 
foreach($alias_obj->post_alias as $alias_k=>$alias_v){
 
  if(!in_array($alias_k,$alias_not)){	  

	 echo '<input type="hidden" id="id_'.$alias_k.'" name="'.$alias_k.'" value="'.$post[$alias_v].'">'; 
  }	

}

?>

<input type="hidden" name="skl_money_type" value="" />
<!--隐藏域,结束-->

<input class="buttonStyle" type="submit" value="确认付款" />
</form>


<script type="text/javascript">
$(function($) {

 //选择金额
 var allMoneyLi=$("#id_money_box li");
 var skl_money_id=$("input[id='id_price']");
 var skl_custom_money=$("input[name='skl_custom_money']");
 var skl_otherMoney="其他金额";
 
 allMoneyLi.click(function(){	  
	  
	//先移除样式
	allMoneyLi.removeClass("selectli");
	
	var thisLi=$(this);
	thisLi.addClass("selectli");
	
	var id_value=thisLi.prop("id");
	if(id_value != "other"){
	   skl_money_id.val(id_value);
	}else{
		
	   var custom_value=skl_custom_money.val();
       if(custom_value == skl_otherMoney){
		  skl_custom_money.val(""); 

	   }else{

		  skl_money_id.val(custom_value);		   
	   }	   
	}
	
	$("input[name='skl_money_type']").val(thisLi.attr("money-type"));
	 
  });
  
  
 //选择支付方式
 var allPayLi=$("#id_pay_box li");

 allPayLi.click(function(){	  
	  
	//先移除样式
	allPayLi.removeClass("selectli");
	
	var thisPayLi=$(this);
	thisPayLi.addClass("selectli");	

	$("input[id='id_paytype']").val(thisPayLi.prop("id"));
	 
  }); 


  //其他金额值改变
  skl_custom_money.bind("input propertychange",function() {
     skl_money_id.val(skl_custom_money.val());	   
  }); 
 
  
  //显示默认金额
  $("#id_money_box").children("#<?php echo $in_money_group; ?>").click();  
  <?php
  if($in_money_group == 'other'){
	 echo 'skl_custom_money.val("'.$poMoney.'");';
  }  
  ?>

  
  //显示默认的支付方式  
  <?php
  if(!$paytype_start_empty){
    echo '$("#id_pay_box").children("#'.$in_paytype_start.'").click();';	  
  }  
  ?>
  
 
//alert(addds);
 });
</script>


</body>

</html>