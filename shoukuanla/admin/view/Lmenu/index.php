<?php 
/*
功能：输出左侧菜单
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-01-08 19:21
*/
?>

<?php 

//读取数据库模块列表生成菜单项
$group_id=intval($_REQUEST['tag']);
$node_table=$this->db->utable($this->cfg_sys_admin_node_table_name);
$public_where=" AND `admin_node_status`=1 AND `admin_node_id` IN($node_access) AND `admin_node_is_show`=1 ORDER BY `admin_node_sort` ASC";

while(true){

//查询一级菜单
$shangji1=$this->db->query("SELECT `admin_node_id`,`admin_node_module_name` FROM `$node_table` WHERE `admin_node_shangji`=$group_id AND `admin_node_auth_type`!=99".$public_where);
if($shangji1){
  while($menu1_list=$shangji1->fetch_assoc()){
	  
	  echo '<h3 class="f14"><span class="switchs cu on" title=""></span>'.$menu1_list['admin_node_module_name'].'</h3>';
	  
	  //查询操作
	  $left_list=$this->db->query("SELECT `admin_node_id`,`admin_node_module`,`admin_node_module_name`,`admin_node_action`,`admin_node_action_name`,`admin_node_data`,`admin_node_sort`,`admin_node_auth_type`,`admin_node_shangji` FROM `$node_table` WHERE `admin_node_shangji`=".$menu1_list['admin_node_id'].$public_where);

      if($left_list){
		 echo '<ul>'; 
         while($left_arr=$left_list->fetch_assoc()){
	
	         echo '<li id="_MP'.$left_arr['admin_node_id'].'" class="sub_menu"><a href="javascript:_MP('.$left_arr['admin_node_shangji'].','.$left_arr['admin_node_id'].',\''.skl_U(ucfirst($left_arr['admin_node_module']).'/'.$left_arr['admin_node_action'].$left_arr['admin_node_data']).'\');" hidefocus="true" style="outline:none;">'.$left_arr['admin_node_action_name'].'</a></li>'; 
         }
		 echo '</ul>';

        $left_list->free();
  
     }
	  
  }
  $shangji1->free();
  $group_id=$menu1_list['admin_node_id'];

}else{ break; }


}


?>

<script type="text/javascript">
    $(".switchs").each(function(i) {
        var ul = $(this).parent().next();
        $(this).click(function() {
            if (ul.is(':visible')) {
                ul.hide();
                $(this).removeClass('on');
            } else {
                ul.show();
                $(this).addClass('on');
            }
        })
    });
</script>