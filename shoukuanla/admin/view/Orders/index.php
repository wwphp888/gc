<?php 
/*
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-01-13 21:22
*/
?>
<?php require_once(SKL_VIEW_PATH.'public/header.php'); ?>
<body>
<?php
//日期插件，开始
echo '<script type="text/javascript" src="'.SKL_PUBLIC_PATH.'plugins/laydate/laydate/laydate.js"></script>';
//日期插件，结束

//弹窗输入插件，开始
echo '<link rel="stylesheet" type="text/css" href="'.SKL_PUBLIC_PATH.'plugins/alertwindow/css/xcConfirm.css"/>
<script src="'.SKL_PUBLIC_PATH.'plugins/alertwindow/js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>';
//弹窗输入插件，结束

//cookie操作，开始
echo '<script src="'.SKL_PUBLIC_PATH.'js/cookie.js" type="text/javascript" charset="utf-8"></script>';
//cookie操作，结束


$this->_newCfg('',SKL_MODULE_NAME_1);//加载模块1配置 
$this->_newCfg('','','',SKL_ROOT_PATH.'config.php');//加载对接配置  
$this->_newDbCfg('`cfg_is_hide_search`');//加载用户配置
$is_hide_search=$this->cfg_is_hide_search[SKL_CONTROLLER];

$table_name=$this->db->utable($this->cfg_sys_order_table_name);
	
//获取字段备注
$comments=$this->db->query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$table_name'");
if($comments){ 
 while($comment_arr=$comments->fetch_assoc()){
		 $comments_arr[$comment_arr['COLUMN_NAME']]=$comment_arr['COLUMN_COMMENT'];  	   			
 }
 $comments->free();	 
}

$get=skl_I($_GET);

//检查用户输入数据，开始
require_once(SKL_G_FUNCTIONS_PATH.'skl_check_data.php');
$pay_time_new[0]=strtotime($get['search']['skl_pay_time']);
$pay_time_new[1]=strtotime($get['between']['skl_pay_time']);
if($get['search']['skl_pay_time'] != '' && empty($pay_time_new[0])){ 	skl_error($comments_arr['skl_pay_time'].'格式错误！','',3);	}
if($get['between']['skl_pay_time'] != '' && empty($pay_time_new[1])){ skl_error($comments_arr['skl_pay_time'].'格式错误2！','',3); } 

$time_new[0]=strtotime($get['search']['skl_time']);
$time_new[1]=strtotime($get['between']['skl_time']);
if($get['search']['skl_time'] != '' && empty($time_new[0])){ skl_error($comments_arr['skl_time'].'格式错误！','',3); }
if($get['between']['skl_time'] != '' && empty($time_new[1])){ skl_error($comments_arr['skl_time'].'格式错误2！','',3); }


if(!skl_check_data($get['search']['skl_money'],'float')){  skl_error($comments_arr['skl_money'].'格式错误！','',3); }
if(!skl_check_data($get['search']['skl_money_actual'],'float')){  skl_error($comments_arr['skl_money_actual'].'格式错误！','',3); }
//检查用户输入数据，结束


//在...之间查询的字段
$between_where=array(
	'skl_pay_time'     =>array(0=>$pay_time_new[0],1=>$pay_time_new[1]),
	'skl_time'         =>array(0=>$time_new[0],1=>$time_new[1]),
);

//float类型字段
$float_where=array(
	'skl_money'        =>(float)$get['search']['skl_money'],
	'skl_money_actual' =>(float)$get['search']['skl_money_actual'],
);

  
?>
<style type="text/css">
.search {height: auto; padding-top: 6px; padding-bottom: 6px; }
</style>

<div class="pad-lr-10">
<form name="searchform" action="" method="get" >
<input name="c" type="hidden" value="<?php echo $get['c']; ?>" />
<input name="a" type="hidden" value="<?php echo $get['a']; ?>" />

<div id="search_div" class="explain-col" style="<?php if($is_hide_search == 1){ echo 'display:none;';} ?>">

<table width="100%">
<tr>
<td width="8%"></td>
<td width="25%"></td>
<td width="67%"></td>
</tr>

<tr>
<?php 
//需要隐藏的搜索字段
$search_arr=$comments_arr;
unset($search_arr['skl_id']);
unset($search_arr['skl_guishu']);
unset($search_arr['skl_sklorder']);
unset($search_arr['skl_user']);
unset($search_arr['skl_update_time']);
unset($search_arr['skl_receive_dir']);


//一个字段有多个类型值的字段
$select_type=array('skl_paytype'=>$this->cfg_paytype_name,'skl_state'=>array(0=>'未处理',1=>'已处理'),'skl_guishu'=>$this->cfg_order_table_name,'skl_budan'=>array(0=>'否',1=>'是'));

foreach($search_arr as $s_k=>$s_v){

	echo '<td class="search">';
		
	if(array_key_exists($s_k,$select_type)){
			
	   echo $s_v.'：</td><td><select style="width: 17%;" name="search['.$s_k.']">'; 
       $option_arr=array(''=>'请选择')+$select_type[$s_k]; 
	   
	   foreach($option_arr as $type_k=>$type_v){

		 $is_selected=(string)$get['search'][$s_k] === (string)$type_k ? 'selected="selected"' : '' ;  
		 echo '<option '.$is_selected.' value="'.$type_k.'">'.$type_v.'</option>';

	   }
	   
       echo '</select>';
    }else{
		
	   $input_value=$get['search'][$s_k] == '' ? '' : $get['search'][$s_k];
		 $input_value_between=$get['between'][$s_k] == '' ? '' : $get['between'][$s_k];

     $key_exists_between=array_key_exists($s_k,$between_where);
     if($key_exists_between){

       $between_style='width:43.5%;';
		   $between_value= '- <input id="'.$s_k.'2" name="between['.$s_k.']" type="text" class="date input-text" value="'.$input_value_between.'" style="'.$between_style.'"/>';
			 
		 }else{
		   $between_style='width:96%;';
		 }

		 
	   echo $s_v.'：</td><td><input id="'.$s_k.'" name="search['.$s_k.']" type="text" class="input-text" style="'.$between_style.'" value="'.$input_value.'" />'. $between_value;
		 unset($between_value);
    }
	
	echo '</td><td>';
	
	if($key_exists_between){ echo '<input type="button" style="padding-top: 3px;padding-bottom: 3px;" id="'.$s_k.'_today" class="btn_public" value="今日" />'; }
	
	echo '</td></tr>';
	
 	
}

?>


<tr><td></td>
<td class="search"><input type="submit" class="btn_public" value="搜索" /></td>
</tr>
  
</table> 
</form>
            
</div>


<div style="height: 28px;" class="explain-col">
<?php 
  $hide_search_button=$is_hide_search == 1 ? '显示搜索' : '隐藏搜索';
  $public_count_where="`skl_time` BETWEEN unix_timestamp(CURDATE()) AND unix_timestamp(concat(CURDATE(),' 23:59:59'))";   
  echo '<div style="float:left;">今日订单：<font size="+1"><b>'.$this->db->table("`$table_name`")->where($public_count_where)->getField("COUNT(`skl_id`)").'</b></font>&nbsp;&nbsp;
	'.$select_type['skl_state'][1].'：<font size="+1"><b>'.$this->db->table("`$table_name`")->where("$public_count_where AND `skl_state`=1")->getField("COUNT(`skl_id`)").'</b></font>&nbsp;&nbsp;
	'.$select_type['skl_state'][0].'：<font color="#FF0000" size="+1"><b>'.$this->db->table("`$table_name`")->where("$public_count_where AND `skl_state`=0")->getField("COUNT(`skl_id`)").'</b></font>
	&nbsp;&nbsp;	收款总额：<font size="+1"><b>'.(float)$this->db->table("`$table_name`")->where("$public_count_where AND `skl_state`=1")->getField("SUM(`skl_money_actual`)").'</b></font>
	</div>
	<div style="float: right;"><font style="display:none;" id="num_reload" size="3" is_stop_reload="1"><b>0</b> 秒自动刷新</font>&nbsp;&nbsp;&nbsp;<input onClick="window.location.reload();" class="btn_public" value="刷新" type="button" />&nbsp;&nbsp;&nbsp;<input id="auto_reload" class="btn_public" value="自动刷新" type="button" />&nbsp;&nbsp;&nbsp;<input id="hide_search" class="btn_public" value="'.$hide_search_button.'" type="button" /></div>
	';
?>
</div>


 <div style="margin-top:10px;">   
    <form id="myform" name="myform" action="" method="post">
    <div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr style="height:50px;">                    
              <!--<th>选择<br/><br/></th>-->
                    
<?php 

//搜索条件拼接，开始		
foreach($get['search'] as $gets_k=>$gets_v){
	 if($gets_v !== ''){

		if(array_key_exists($gets_k,$between_where)){
			 
		 if($between_where[$gets_k][0] && $between_where[$gets_k][1]){
				$where_str.="`$gets_k` BETWEEN ".$between_where[$gets_k][0]." AND ".$between_where[$gets_k][1]." AND "; 

		 }
		
		}elseif(array_key_exists($gets_k,$float_where)){
				$where_str.="`$gets_k`=".$float_where[$gets_k]." AND ";
		}else{
				$where_str.="`$gets_k`='$gets_v' AND ";
		}
	 }
}
$where_str=rtrim($where_str,' AND '); 
//搜索条件拼接，结束



$table_top_arr=$comments_arr;
//需要隐藏的表头字段
unset($table_top_arr['skl_update_time']);
unset($table_top_arr['skl_receive_dir']);

$asc='ASC';  $desc='DESC';  $default_field='skl_id';
if($get['paixu_field'] == ''){	$get['paixu_field']=$default_field; } 
if($get['paixu_value'] == ''){	$get['paixu_value']=$desc;  }

//检测排序字段是否存在
if(!array_key_exists($get['paixu_field'],$table_top_arr)){  $get['paixu_field']=$default_field;  }

if($get['paixu_value'] == $asc){
	 $order_by='`'.$get['paixu_field'].'` '.$asc;
   $paixu_value=$desc;
	 $paixu_img='asc.gif';

}elseif($get['paixu_value'] == $desc){
   $order_by='`'.$get['paixu_field'].'` '.$desc;
   $paixu_value=$asc;
   $paixu_img='desc.gif';
}else{
   $order_by="`skl_id` $desc";
	 $paixu_img='desc.gif';
}

//遍历输出表头
$new_get=$get;
$skl_money_field='skl_money';
$skl_money_actual_field='skl_money_actual';
$skl_state_field='skl_state';
foreach($table_top_arr as  $top_k=>$top_v){
  
	//求和字段
	if($top_k == $skl_money_field){		 
		 $money_sum='<font color="#1066fb" title="'.$top_v.'总和">'.$this->db->table("`$table_name`")->where($where_str)->getField("SUM(`$skl_money_field`)").'</font>';
	}elseif($top_k == $skl_money_actual_field){
	   $money_sum='<font color="#1066fb" title="'.$top_v.'总和">'.$this->db->table("`$table_name`")->where($where_str)->getField("SUM(`$skl_money_actual_field`)").'</font>';
	}elseif($top_k == $skl_state_field){
     		 
     if($get['search']['skl_state'] != 1){
			 if($get['search']['skl_state'] == ''){
					$where_str_state=empty($where_str) ? "`$skl_state_field`=0" : $where_str." AND `$skl_state_field`=0";
			 }else{
					$where_str_state=preg_replace("/`$skl_state_field`='(.*)'/","`$skl_state_field`=0",$where_str);
			 }
			 $state_count_0=$this->db->table("`$table_name`")->where($where_str_state)->getField("COUNT(`$skl_state_field`)");

     }

     $state_count_0=$get['search']['skl_state'] != 1 ? $state_count_0 : 0;
	   $money_sum='<font color="#FF0000" title="未处理订单总条数">'.$state_count_0.'</font>';
	}

  $img_html=$new_get['paixu_field'] == $top_k ? '<img src="'.SKL_STATIC_PATH.'images/'.$paixu_img.'"/>' : '';

	$get['paixu_field']=$top_k;
	$get['paixu_value']=$paixu_value;
  echo '<th><a title="点击表头可改变排序" style="text-decoration:none;" href="'.skl_U('',$get).'">'.$top_v.'</a>'.$img_html.'<br/>'.$money_sum.'<br/></th>';	 
	unset($money_sum);
}

?>            
            </tr>
        </thead>
        
        
    	<tbody>
        
<?php 
		
 		
 
  $order_count=$this->db->table("`$table_name`")->where($where_str)->getField('COUNT(`skl_id`)');// 查询满足要求的总记录数  
  require_once(SKL_ClASS_PATH.'ShoukuanlaPage.class.php');
  $order_page = new ShoukuanlaPage($order_count,20);// 实例化分页类 传入总记录数和每页显示的记录数		
    
		$where_str_new= empty($where_str) ? '' : "WHERE $where_str" ;	//默认搜索条件
		$search_sql="SELECT * FROM `$table_name` $where_str_new ORDER BY $order_by LIMIT $order_page->firstRow,$order_page->listRows";
		$order_list=$this->db->query($search_sql);

		//需要函数转换的字段
		$function_arr=array(
		'skl_pay_time'     =>array('funname'=>'date','cs1'=>'Y-m-d H:i:s'),
		'skl_time'         =>array('funname'=>'date','cs1'=>'Y-m-d H:i:s'),
		);
		
		if($order_list){
		   while($order_arr=$order_list->fetch_assoc()){	
              echo '<tr align="center">
			  <!--<td><input name="id[]" type="checkbox" value="'.$order_arr['skl_id'].'" /></td>-->'; 
			  
			  foreach($table_top_arr as $row_k=>$row_v){
				if(array_key_exists($row_k,$select_type)){					 
				   $new_order_arr=$select_type[$row_k][$order_arr[$row_k]];
					 if($row_k == 'skl_state' && $order_arr[$row_k] != 1){
					    $new_order_arr='<div>'.$new_order_arr.'<a onClick="$(this).remove();" href="'.skl_U('Orders/edit',array('id'=>$order_arr['skl_id'])).'" target="_blank" style="font-size: 12px;padding-top: 3px;padding-bottom: 3px;padding-left: 5px;padding-right: 5px;margin-left: 3px;" class="btn_public btn_red">补单</a></div>';
					 }		 
				   
				}elseif(array_key_exists($row_k,$function_arr)){
				   $new_order_arr=empty($order_arr[$row_k]) ? '' : $function_arr[$row_k]['funname']($function_arr[$row_k]['cs1'],$order_arr[$row_k]);
				   
				}elseif($row_k == 'skl_beizhu'){
					 $sub_num=10;
				   $new_order_arr=mb_strlen($order_arr[$row_k],'utf-8') > $sub_num ? '<label title="'.$order_arr[$row_k].'">'.mb_substr($order_arr[$row_k], 0, $sub_num, 'utf-8').'...</label>' : $order_arr[$row_k];
				}else{  
			     $new_order_arr=$order_arr[$row_k];
				}
				echo '<td>'.$new_order_arr.'</td>';
			  }
              echo '</tr>';
		   }
		   $order_list->free();	 
		}
        ?>
    	</tbody>
    </table>

    <div class="btn">
		<div style="float: left;">
		<!--<input class="btn_public" id="select_all" value="全选" type="button"/>-->
		<input type="button" class="btn_public" value="清理未处理订单" onClick="deldata(0,this.value);" />
        <input type="button" class="btn_public" value="清理已处理订单" onClick="deldata(1,this.value);" />
        <!--<input type="button" class="btn_public btn_red" value="删除" onclick=""/>-->
		</div>
		<div id="pages"><?php echo $order_page->show();?></div>
    </div>
    
    <div class="explain-col">
    <b>SQL语句：</b><?php echo $search_sql; ?>
	</div>
    
    </div>
    </form>   
    
</div>
<script type="text/javascript">

$(function($){  

//订单处理点击今天按钮，开始
$('#skl_pay_time_today').click(function(){

$.get("<?php echo skl_U('Getdbtime/index'); ?>","is_ajax=1&timetype=between_time",
  function(data){   

     var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){
         
        $('#skl_pay_time').val(data.between_time.begin);
				$('#skl_pay_time2').val(data.between_time.end);
     }else{
                     
        alert(data.errormsg);
     }
  
  });
});
//订单处理点击今天按钮，结束


//订单创建点击今天按钮，开始
$('#skl_time_today').click(function(){

$.get("<?php echo skl_U('Getdbtime/index'); ?>","is_ajax=1&timetype=between_time",
  function(data){   

     var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){
         
        $('#skl_time').val(data.between_time.begin);
				$('#skl_time2').val(data.between_time.end);
     }else{
                     
        alert(data.errormsg);
     }
  
  });
});
//订单创建点击今天按钮，结束


<?php
require_once(SKL_VIEW_PATH.'public/function_js.php'); //公共的js函数
?>


});


//清理订单，开始
function deldata(status,log_name){
   
   var order_status;
   if(status == 0){
	  order_status="未处理";
   }else if(status == 1){
	  order_status="已处理";
   }else{ return;  }  
   
   if(!confirm('只保留最近60天订单，剩余的（'+order_status+'）订单全部删除，您确定要执行该操作吗？')){return;}
   
   $.post("<?php echo skl_U(SKL_CONTROLLER.'/deldata',array('is_ajax'=>1)) ?>","table=cfg_sys_order_table_name&skl_state="+status+"&log_name="+log_name,function(data){
	   
     var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){           

        alert("成功删除("+data.rows+")条订单");     
		if(data.rows > 0){ window.location.reload(); }     
     }else{          
        alert(data.errormsg);                 

     }	   
   });

}
//清理订单，结束


//日期时间插件，开始
lay('#version').html('-v'+ laydate.v);
laydate.render({
   elem: '#skl_pay_time' //指定元素
  ,type: 'datetime'
});

lay('#version').html('-v'+ laydate.v);
laydate.render({
   elem: '#skl_pay_time2' //指定元素
  ,type: 'datetime'
});

lay('#version').html('-v'+ laydate.v);
laydate.render({
   elem: '#skl_time' //指定元素
  ,type: 'datetime'
});

lay('#version').html('-v'+ laydate.v);
laydate.render({
   elem: '#skl_time2' //指定元素
  ,type: 'datetime'
});
//日期时间插件，结束



</script>
</body>
</html>