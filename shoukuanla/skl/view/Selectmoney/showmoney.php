<?php 
/*
功能：选择金额
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2018-12-25 19:11
*/
?>

<?php echo $this->include_jQuery(); ?>

<style type="text/css">

<?php echo $this->public_style(); ?>

.skl_moneyBox{
	height: auto;
	float: left;
	width: auto;
	padding-top: 5px;
	padding-bottom: 5px;
}
.skl_moneyBox li{
  list-style:none; 
	font-family: "微软雅黑";
	font-size: 15px;
	float: left;
	height: 20px;
	width: 75px;
	margin-right: 10px;
	border: 1px double #C4DEFF;
	border-radius: 5px;
	text-align: center;
	padding-top: 6px;
	padding-right: 5px;
	padding-bottom: 6px;
	padding-left: 5px;
	margin-top: 3px;
	margin-bottom: 3px;
	cursor: pointer;
	color: #333;
}
.skl_gediv{
    height:20px;width: 820px;float: left;
}

</style>


<div class="skl_moneyBox">

<?php 
//遍历输出金额组
foreach($this->cfg_money_group as $dirValue){	
  echo '<li '.$stylesheet.' money-type="0" data-value="'.$dirValue.'">'.$dirValue.'元</li>';	
}
?>

<?php 
if($this->cfg_is_other_money == '1'){
echo '
<li '.$stylesheet.' money-type="1"><input style="height:93%;width:93%;color: #666;" name="skl_custom_money" type="text" value="其他金额" /></li>
';
}
?>


<input type="hidden" id="skl_money_id" name="<?php echo $skl_moneyName; ?>" value="1" />
<input type="hidden" name="skl_money_type" value="" />

</div>

<!--<div class="skl_gediv"></div>-->

<script type="text/javascript">
$(function($){

 //选择金额
 var skl_moneyBoxLi=$(".skl_moneyBox li");
 var skl_money_id=$("input[id='skl_money_id']");
 var skl_custom_money=$("input[name='skl_custom_money']");
 var skl_otherMoney="其他金额";
 
 skl_moneyBoxLi.click(function(){	 
	  
	//先移除样式
	skl_moneyBoxLi.removeClass("skl_selectli");
	
	var thisLi=$(this);
	thisLi.addClass("skl_selectli");
	
	skl_money_id.val(thisLi.attr("data-value"));
	var money_type_value=thisLi.attr("money-type");	
	if(money_type_value == "1"){
		
	   var custom_value=skl_custom_money.val();
       if(custom_value == skl_otherMoney){
		  skl_custom_money.val(""); 

	   }else{

		  skl_money_id.val(custom_value);		   
	   }			
	
	}
	
	$("input[name='skl_money_type']").val(money_type_value);
		 
});

		
    //其他金额值改变
    skl_custom_money.bind("input propertychange",function() {
       skl_money_id.val(skl_custom_money.val());	   
    }); 	
		

  //显示默认金额
  skl_moneyBoxLi.first().click();
   
 });
</script>
