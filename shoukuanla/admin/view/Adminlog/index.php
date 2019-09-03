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


//$this->_newCfg('',SKL_MODULE_NAME_1);//加载模块1配置 
$this->_newDbCfg('`cfg_is_hide_search`');//加载用户配置
$is_hide_search=$this->cfg_is_hide_search[SKL_CONTROLLER];

$table_name=$this->db->utable($this->cfg_sys_admin_log_table_name);
	
//获取字段备注
$comments=$this->db->query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$table_name'");
if($comments){ 
 while($comment_arr=$comments->fetch_assoc()){
		 $comments_arr[$comment_arr['COLUMN_NAME']]=$comment_arr['COLUMN_COMMENT'];  	   			
 }
 $comments->free();	 
}
$comments_arr['admin_log_type']='操作类型';

$get=skl_I($_GET);

//检查用户输入数据，开始
require_once(SKL_G_FUNCTIONS_PATH.'skl_check_data.php');
$log_time_new[0]=strtotime($get['search']['admin_log_time']);
$log_time_new[1]=strtotime($get['between']['admin_log_time']);
if($get['search']['admin_log_time'] != '' && empty($log_time_new[0])){ 	skl_error($comments_arr['admin_log_time'].'格式错误！','',3);	}
if($get['between']['admin_log_time'] != '' && empty($log_time_new[1])){ skl_error($comments_arr['admin_log_time'].'格式错误2！','',3); } 
//检查用户输入数据，结束

//在...之间查询的字段
$between_where=array(
	'admin_log_time'     =>array(0=>$log_time_new[0],1=>$log_time_new[1]),
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
unset($search_arr['admin_log_explain']);

//一个字段有多个类型值的字段
$select_type=array('admin_log_type'=>array(1=>'新增',2=>'删除',3=>'修改',4=>'查询'));

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
  $public_count_where="`admin_log_time` BETWEEN unix_timestamp(CURDATE()) AND unix_timestamp(concat(CURDATE(),' 23:59:59'))";   
  echo '<div style="float:left;">今日日志：<font size="+1"><b>'.$this->db->table("`$table_name`")->where($public_count_where)->getField("COUNT(`admin_log_id`)").'</b></font>&nbsp;&nbsp;

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
            <tr>                    
              <!--<th>选择<br/><br/></th>-->
                    
<?php 

//搜索条件拼接，开始		
foreach($get['search'] as $gets_k=>$gets_v){
	 if($gets_v !== ''){

		if(array_key_exists($gets_k,$between_where)){
			 
		 if($between_where[$gets_k][0] && $between_where[$gets_k][1]){
				$where_str.="`$gets_k` BETWEEN ".$between_where[$gets_k][0]." AND ".$between_where[$gets_k][1]." AND "; 

		 }
		
		}else{
				$where_str.="`$gets_k`='$gets_v' AND ";
		}
	 }
}
$where_str=rtrim($where_str,' AND '); 
//搜索条件拼接，结束



$table_top_arr=$comments_arr;
//需要隐藏的表头字段
//unset($table_top_arr['']);


$asc='ASC';  $desc='DESC';  $default_field='admin_log_id';
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

foreach($table_top_arr as  $top_k=>$top_v){
  
  $img_html=$new_get['paixu_field'] == $top_k ? '<img src="'.SKL_STATIC_PATH.'images/'.$paixu_img.'"/>' : '';

	$get['paixu_field']=$top_k;
	$get['paixu_value']=$paixu_value;
	if($top_k == 'admin_log_explain'){ 
	  $explain_style=' style="width: 65%;"'; 
	}else{ $explain_style=''; }
	
  echo '<th'.$explain_style.'><a title="点击表头可改变排序" style="text-decoration:none;" href="'.skl_U('',$get).'">'.$top_v.'</a>'.$img_html.'</th>';	
}

?>            
            </tr>
        </thead>
        
        
    	<tbody>
        
<?php 
		
 		
 
  $order_count=$this->db->table("`$table_name`")->where($where_str)->getField('COUNT(`admin_log_id`)');// 查询满足要求的总记录数  
  require_once(SKL_ClASS_PATH.'ShoukuanlaPage.class.php');
  
  $order_page = new ShoukuanlaPage($order_count,20);// 实例化分页类 传入总记录数和每页显示的记录数		
    
		$where_str_new= empty($where_str) ? '' : "WHERE $where_str" ;	//默认搜索条件
		$search_sql="SELECT * FROM `$table_name` $where_str_new ORDER BY $order_by LIMIT $order_page->firstRow,$order_page->listRows";
		$order_list=$this->db->query($search_sql);

		//需要函数转换的字段
		$function_arr=array(
		'admin_log_time'     =>array('funname'=>'date','cs1'=>'Y-m-d H:i:s'),
		);
		
		if($order_list){
		   while($order_arr=$order_list->fetch_assoc()){	
              echo '<tr align="center">
			  <!--<td><input name="id[]" type="checkbox" value="'.$order_arr['skl_id'].'" /></td>-->'; 
			  
			  foreach($table_top_arr as $row_k=>$row_v){
				if(array_key_exists($row_k,$select_type)){					 
				   $new_order_arr=$select_type[$row_k][$order_arr[$row_k]]; 
				   
				}elseif(array_key_exists($row_k,$function_arr)){
				   $new_order_arr=empty($order_arr[$row_k]) ? '' : $function_arr[$row_k]['funname']($function_arr[$row_k]['cs1'],$order_arr[$row_k]);
				   
				}elseif($row_k == 'admin_log_ip'){				   
				   $new_order_arr=$order_arr[$row_k].'&nbsp;<a style="color: #0847fb;" target="_blank" href="http://ip.tool.chinaz.com/'.$order_arr[$row_k].'">查IP</a>';
				   
				}elseif($row_k == 'admin_log_explain'){				   
				   $new_order_arr='<input style="width:100%;" type="text" value="'.htmlentities($order_arr[$row_k],ENT_QUOTES,"UTF-8").'" />';
				   
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
		<input type="button" class="btn_public" value="清理日志" onClick="deldata(this.value);" />
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

//操作时间点击今天按钮，开始
$('#admin_log_time_today').click(function(){

$.get("<?php echo skl_U('Getdbtime/index'); ?>","is_ajax=1&timetype=between_time",
  function(data){   

     var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){
         
        $('#admin_log_time').val(data.between_time.begin);
				$('#admin_log_time2').val(data.between_time.end);
     }else{
                     
        alert(data.errormsg);
     }
  
  });
});
//操作时间点击今天按钮，结束


<?php
require_once(SKL_VIEW_PATH.'public/function_js.php'); //公共的js函数
?>


});


//清理日志，开始
function deldata(log_name){
    
   if(!confirm('只保留最近60天日志，剩余的日志全部删除，您确定要执行该操作吗？')){return;}
   $.post("<?php echo skl_U(SKL_CONTROLLER.'/deldata',array('is_ajax'=>1)) ?>","table=cfg_sys_admin_log_table_name&log_name="+log_name,function(data){
	   
     var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){           

        alert("成功删除("+data.rows+")条日志");   
		if(data.rows > 0){ window.location.reload(); }
		         
     }else{          
        alert(data.errormsg);                 

     }	   
   });

}
//清理日志，结束



//日期时间插件，开始
lay('#version').html('-v'+ laydate.v);
laydate.render({
   elem: '#admin_log_time' //指定元素
  ,type: 'datetime'
});

lay('#version').html('-v'+ laydate.v);
laydate.render({
   elem: '#admin_log_time2' //指定元素
  ,type: 'datetime'
});
//日期时间插件，结束



</script>
</body>
</html>