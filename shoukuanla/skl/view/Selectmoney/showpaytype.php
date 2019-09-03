<?php 
/*
功能：输出支付方式样式
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2018-12-25 19:11
*/
?>

<?php echo $this->include_jQuery(); ?>

<style type="text/css">
<?php echo $this->public_style(); ?>
.skl_pay_box{
	padding-top: 5px;
	padding-bottom: 5px;   	
}
.skl_pay_box li{ font-size: 18px; float: left; height: auto; width: auto; margin-right: 15px; border: 1px double #C4DEFF; border-radius: 5px; cursor: pointer; padding-top: 5px; padding-right: 8px; padding-bottom: 5px; padding-left: 8px; }
</style>



<?php 

echo '
<div id="id_skl_pay_box" class="skl_pay_box">';

$is_first=true;
foreach($paytype_start as $v){

  if($is_first){
	 $is_first=false;
	 $checked='checked="checked"'; }else{ $checked='';  
  }  	
		
  echo '<li id="'.$v.'">
  <img src="'.SKL_STATIC_PATH.'images/'.$v.'.png" '.$stylesheet.' />
  <input style="display:none;" name="'.$inputName.'" value="'.$paytype_new[$v].'" type="radio" '.$checked.' />
  </li>';	

}

echo '</div>';

?>



<script type="text/javascript">
$(function($){

 //选择支付方式
 var id_skl_pay_box_li=$("#id_skl_pay_box li");
 id_skl_pay_box_li.click(function(){
	  
	//先移除样式
	id_skl_pay_box_li.removeClass("skl_selectli");
	
	var this_pay_li=$(this);
	this_pay_li.addClass("skl_selectli");	

    //选择当前radio
	this_pay_li.children("input").prop("checked", true);
	 
  }); 


 //显示默认的支付方式  
 <?php
 echo 'id_skl_pay_box_li.first().click();';	  
 ?>

});
</script>
