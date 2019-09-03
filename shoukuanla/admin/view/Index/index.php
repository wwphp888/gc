<?php 
/*
功能：管理中心首页
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-06-27 19:21
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="off">
    
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
        <link href="<?php echo SKL_STATIC_PATH; ?>css/style.css" rel="stylesheet" type="text/css"/>    
        <script type="text/javascript" src="<?php echo SKL_PUBLIC_PATH; ?>js/jquery.min.js"></script>
        
        <title>收款啦订单管理系统</title>
    </head>
    
    <body scroll="no">
        <div id="header">
            <div class="logo">
            </div>
            <div class="fr">
                <div class="style_but">
                </div>
            </div>
            <div class="col-auto" style="overflow: visible">
                <div class="log white cut_line">
                    欢迎您！<?php echo $this->assign_admin_user; ?> [<?php echo $this->assign_role_info['admin_role_name']; ?>] |
                    <a href="/" target="_blank">
                        [网站首页]
                    </a>
                    | 版本号：<?php echo $skl_main_version; ?> |
                    <a href="<?php echo skl_U('Logout/index'); ?>">
                        [退出]
                    </a>
                </div>
               
            </div>
            <ul class="nav white" id="top_menu">
            

                
                
      <?php 
       if($menu_list){

		  while($list_arr=$menu_list->fetch_assoc()){
			  
			 $node_arr[]=$list_arr; 	
		     echo '<li id="_M'.$list_arr['admin_node_id'].'" class="top_menu">
            	<a href="javascript:_M('.$list_arr['admin_node_id'].',\'\')" hidefocus="true" style="outline:none;">'.$list_arr['admin_node_module_name'].'</a>
         	    </li>';

		  }
	   $menu_list->free();
  }
      ?>   
                
                
                
            </ul>
        </div>
        <div id="content">
            <div class="left_menu fl">
                <div id="leftMain">
                </div>
                <a href="javascript:;" id="openClose" style="outline-style: none; outline-color: invert; outline-width: medium;"
                hideFocus="hidefocus" class="open" title="">
                </a>
            </div>
            <div class="right_main">
                <div class="crumbs">
                    <div class="shortcut cu-span">
        <?php 
		if($update_data['version'] != $skl_main_version && count($update_data['gonggao']) > 0){
		   echo '<label>公告：'.$update_data['gonggao'][0].'</label>';
		} ?>
                    <a onclick="onclick_update();"><span id="update_button">系统更新</span></a>
                      <a href="<?php echo skl_U('Upload/index'); ?>" target="_blank"><span>收款码管理</span></a>

                    </div>
                    <span id="current_pos">
                    </span>
                </div>
                <div class="rmc">
                    <div class="content" style="position:relative; overflow:hidden">
                        <iframe name="right" id="rightMain" src="<?php echo skl_U('Index/main'); ?>" frameborder="false" scrolling="auto" style="overflow-x:hidden;border:none;" width="100%" height="auto" allowtransparency="true"></iframe>
                    </div>
                </div>
            </div>
        </div>
				
<script type="text/javascript">
function windowW() {
		if ($(window).width() < 980) {
				$('#header').css('width', 980 + 'px');
				$('#content').css('width', 980 + 'px');
				$('body').attr('scroll', '');
				$('body').css('overflow', '');
		}
}
windowW();
$(window).resize(function() {
		if ($(window).width() < 980) {
				windowW();
		} else {
				$('#header').css('width', 'auto');
				$('#content').css('width', 'auto');
				$('body').attr('scroll', 'no');
				$('body').css('overflow', 'hidden');

		}
});
window.onresize = function() {
		var heights = document.documentElement.clientHeight - 110;
		document.getElementById('rightMain').height = heights;
		var openClose = $("#rightMain").height() + 9;
		$('#center_frame').height(openClose + 9);
		$("#openClose").height(openClose + 30);
}
window.onresize();

$(function(){
	//默认载入左侧菜单
	$("#leftMain").load("<?php echo skl_U('Lmenu/index',array('tag'=>$node_arr[0]['admin_node_id'])); ?>");			
		
	$("#top_menu li").first().addClass('on');
});

//左侧开关
$("#openClose").click(function() {
		if ($(this).data('clicknum') == 1) {
				$("html").removeClass("on");
				$(".left_menu").removeClass("left_menu_on");
				$(this).removeClass("close");
				$(this).data('clicknum', 0);
		} else {
				$(".left_menu").addClass("left_menu_on");
				$(this).addClass("close");
				$("html").addClass("on");
				$(this).data('clicknum', 1);
		}
		return false;
});

function _M(tag,targetUrl) {
	$.get("<?php echo skl_U('Lmenu/index'); ?>", {tag:tag}, function(data){
		$("#leftMain").html(data);
	});

	//$("#rightMain").attr('src', targetUrl);
	$('.top_menu').removeClass("on");
	$('#_M'+tag).addClass("on");


	//显示左侧菜单，当点击顶部时，展开左侧
	$(".left_menu").removeClass("left_menu_on");
	$("#openClose").removeClass("close");
	$("html").removeClass("on");
	$("#openClose").data('clicknum', 0);
	$("#current_pos").data('clicknum', 1);
}

function _MP(tag,menuid, targetUrl) {
		$("#rightMain").attr('src', targetUrl);
		$('.sub_menu').removeClass("on fb blue");
		$('#_MP' + menuid).addClass("on fb blue");
		$("#current_pos").data('clicknum', 1);

}

function Refresh() {
		var src = $("#rightMain").attr('src');
		$("#rightMain").attr('src', src);
}
		
			
//点击更新按钮,开始
function onclick_update(){
	
var update_button=$('#update_button');	
update_button.text('系统更新...');

var admin_url="<?php 
require_once(SKL_SYS_FUNCTIONS_PATH.'skl_get_request_url.php'); 
echo urlencode(skl_get_request_url());
?>";
$.post("<?php echo skl_U('Onlineupdate/index'); ?>","is_ajax=1&admin_url="+admin_url,function(data){   
   
   var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
     if(data.status == 'success'){        
		if(data.url != undefined){
		   window.location.href=data.url+"?admin_url=<?php  
           require_once(SKL_SYS_FUNCTIONS_PATH.'skl_get_request_url.php'); 
	       echo urlencode(skl_get_request_url()); 
           ?>"; 
		}
        
     }else{
        update_button.text('系统更新');             
        alert(data.errormsg);
   }
  
  });	
	
}	
//点击更新按钮,结束		
			
        </script>

    </body>

</html>