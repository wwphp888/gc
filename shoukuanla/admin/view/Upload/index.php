<?php 
/*
功能：上传收款码
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-06-09 19:21
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>收款码管理、上传、删除、新建目录</title>
<link rel="stylesheet" type="text/css" href="<?php echo SKL_PUBLIC_PATH; ?>plugins/fileupload/css/common.css" />
<script src="<?php echo SKL_PUBLIC_PATH; ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SKL_PUBLIC_PATH; ?>plugins/fileupload/js/plupload.full.min.js"></script>

<!--弹窗输入插件，开始-->
<link rel="stylesheet" type="text/css" href="<?php echo SKL_PUBLIC_PATH; ?>plugins/alertwindow/css/xcConfirm.css"/>
<script src="<?php echo SKL_PUBLIC_PATH; ?>plugins/alertwindow/js/xcConfirm.js" type="text/javascript" charset="utf-8"></script>
<!--弹窗输入插件，结束-->

<style type="text/css">
.btn {	-webkit-border-radius: 3px;	-moz-border-radius: 3px;	-ms-border-radius: 3px;	-o-border-radius: 3px;	border-radius: 3px;	background-color: #3a80f9;color: #fff;display: inline-block;height: 28px;	line-height: 28px;text-align: center;width: 72px;transition: background-color 0.2s linear 0s;border: none;cursor: pointer;margin: 0 0 20px;}
.btn:hover {background-color: #1066fb;text-decoration: none}
.demo { width: 900px; margin-top: 0; margin-right: auto; margin-bottom: 0; margin-left: auto; }
.delete_btn{background-color: #f64c39;}
.delete_btn:hover{background-color: #f3250e;text-decoration: none}
.ul_pics li { float: left; width: 200px; height: 280px; border: 1px solid #ddd; text-align: center; margin-top: 0; margin-right: 6px; margin-bottom: 10px; margin-left: 6; padding: 8px; }
.ul_pics li .img {width: 200px;	height: 260px;display: table-cell;	vertical-align: middle;overflow: hidden;}
.ul_pics li img {max-width: 200px; max-height: 260px;vertical-align: middle;
transition: all 0.3s;}
.ul_pics li img:hover{ transform: scale(1.1);}
.progress {	position: relative;	padding: 1px;border-radius: 3px;margin: 60px 0 0 0;}
.selectall { padding-top: 10px; padding-bottom: 10px; display: none; }
.bar {background-color: green;display: block;width: 0%;	height: 20px;	border-radius: 3px;}
.percent {position: absolute;height: 20px;display: inline-block;top: 3px;	left: 2%;color: #fff;}
.imginput{ float: left; cursor: pointer;  }
.dir_path{ width: 980px; margin-top: 10px; margin-right: auto; margin-bottom: 0; margin-left: auto; padding-top: 8px; padding-bottom: 8px; border-radius: 5px; height: auto; overflow: hidden; display: none; }
.currentpos{ width: 960px; font-size: 15px; border: 1px double #CCC; margin-top: 10px; margin-right: auto; margin-bottom: 0; margin-left: auto; padding-top: 8px; padding-right: 10px; padding-bottom: 8px; padding-left: 10px; border-radius: 5px; height: auto; overflow: hidden; }
.currentpos li{ float: left; padding-top: 2px; }
.currentpos li a{ color:#0b49f8;}
.currentpos li a:hover{ text-decoration: none;}
.currentposimg{ height: 12px; margin-right: 2px; margin-left: 2px; padding-top: 3px; }
.top_but_div{ margin-top: 10px; }
</style> 

</head>

	<body>
    <div class="currentpos">
    <?php 
	  echo '<li><a href="'.skl_U('Upload/index').'"><img title="根目录" src="'.SKL_STATIC_PATH.'images/zhuye.png" height="20" /></a></li>';
	
	  krsort($this->assign_dir_pos);
	  foreach($this->assign_dir_pos as $pos_v){ 
	    $dir_path.=$pos_v['dir_name'].$gengefu_dir;
		
	    echo '<li><img class="currentposimg" src="'.SKL_STATIC_PATH.'images/leftarrow.png" /><a href="'.skl_U('Upload/index',array('sid'=>$pos_v['dir_id'])).'">'.$pos_v['dir_name'].'</a></li>';
	
	  }
	?>

    </div>
    
    <div class="dir_path"><input onclick="this.select();" style="width:99.3%;height:25px; font-size: 16px;" type="text" value="<?php echo $gengefu_dir.rtrim($dir_path,$gengefu_dir); ?>" /></div>
    
    
		<div class="container">
		  <h2 class="title"></h2>
			<div class="demo">
			  <a class="btn" id="newdir">新建目录</a>&nbsp;&nbsp;<a class="btn" id="btn" title="单张图片最大500KB，支持jpg，gif，png格式，相同目录下文件名相同则覆盖，可以选择多张收款码同时上传。">上传收款码</a>&nbsp;&nbsp;<a class="btn" id="show_path">显示路径</a>&nbsp;&nbsp;<a class="btn" onclick="window.location.reload();">刷新</a>&nbsp;&nbsp;
<?php 
if($this->assign_dir_int > 0){
   $upji=end($this->assign_dir_pos);
   echo '<a class="btn" href="'.skl_U('Upload/index',array('sid'=>$upji['dir_shangji'])).'">返回上一级</a>';   
   
}
?>

<div style="float: right;width:auto;font-size: 14px;">
<a href="http://www.shoukuanla.net/index-article-id-9.html" target="_blank">支付宝</a>&nbsp;
<a href="http://www.shoukuanla.net/index-article-id-10.html" target="_blank">微信</a>收款码制作教程
</div>

              <form id="imgform" method="post" >
				<ul id="ul_pics" class="ul_pics clearfix">
                
       <?php	
	   
   //根据目录ID查下级目录
   $dir_info=$this->db->query("SELECT `dir_id`,`dir_name` FROM `".$this->db->utable($this->cfg_sys_dir_table_name)."` WHERE `dir_shangji`=$shangji_id ORDER BY `dir_id` ASC");	   
   if($dir_info){
	   	   
	  while($dir_arr=$dir_info->fetch_assoc()){ 
         if(!$is_echo_dir){ $is_echo_dir=true; } 
	     echo '<li><div id="qid'.$qrcode_arr['qrcode_id'].'" class="img"><a href="'.skl_U('Upload/index',array('sid'=>$dir_arr['dir_id'])).'"><img src="'.SKL_STATIC_PATH.'images/dir.jpg"></a></div><label><p><input class="imginput" name="selectdir[]" value="'.$dir_arr['dir_id'].'" type="checkbox">'.$dir_arr['dir_name'].'</p></label></li>';	      
	  }
	  $dir_info->free();
	   
   }   
	   
	   			

   //查收款码
   $qrcode_info=$this->db->query("SELECT `qrcode_id`,`qrcode_dir`,`qrcode_name` FROM `".$this->db->utable($this->cfg_sys_qrcode_table_name)."` WHERE `qrcode_dir`=$shangji_id ORDER BY `qrcode_id` ASC");   
   
   if($qrcode_info){
	  while($qrcode_arr=$qrcode_info->fetch_assoc()){ 
	  
         if(!$is_echo_qrcode){ $is_echo_qrcode=true; } 
		 $img_path=skl_U(SKL_MODULE_NAME_1.'/Showimg/index',array('dir'=>$qrcode_arr['qrcode_dir'],'name'=>$qrcode_arr['qrcode_name']));
	     echo '<li><div class="img"><a target="_blank" href="'.$img_path.'"><img src="'.$img_path.'"></a></div><label><p><input class="imginput" name="selectimg[]" value="'.$qrcode_arr['qrcode_id'].'" type="checkbox">'.$qrcode_arr['qrcode_name'].'</p></label></li>';	      
	  }
	  $qrcode_info->free();
	   
   }
      ?>
                
                
                </ul>
                </form>
				<div class="selectall"><a id="selectall_button" class="btn">全选</a>&nbsp;&nbsp;&nbsp;&nbsp;<a id="delete_button" class="btn delete_btn" >删除</a></div>
                
			</div>
		</div>

		<script type="text/javascript">
		
			var uploader = new plupload.Uploader({ //创建实例的构造方法
				runtimes: 'html5,flash,silverlight,html4', //上传插件初始化选用那种方式的优先级顺序
				browse_button: 'btn', // 上传按钮
				url: "<?php echo skl_U('Upload/qrcode',array('is_ajax'=>1,'sid'=>$_GET['sid'])); ?>", //远程上传地址
				flash_swf_url: 'plupload/Moxie.swf', //flash文件地址
				silverlight_xap_url: 'plupload/Moxie.xap', //silverlight文件地址
				filters: {
					max_file_size: '500kb', //最大上传文件大小（格式100b, 10kb, 10mb, 1gb）
					mime_types: [ //允许文件上传类型
						{
							title: "files",
							extensions: "jpg,png,gif,ico"
						}
					]
				},
				multi_selection: true, //true:ctrl多文件上传, false 单文件上传
				init: {
					FilesAdded: function(up, files) { //文件上传前
						if ($("#ul_pics").children("li").length > 30) {
							alert("您上传的图片太多了！");
							uploader.destroy();
						} else {
							var li = '';
							plupload.each(files, function(file) { //遍历文件
								li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
							});
							$("#ul_pics").append(li);
							uploader.start();
						}
					},
					UploadProgress: function(up, file) { //上传中，显示进度条
						var percent = file.percent;
						$("#" + file.id).find('.bar').css({
							"width": percent + "%"
						});
						$("#" + file.id).find(".percent").text(percent + "%");
					},
					FileUploaded: function(up, file, info) { //文件上传成功的时候触发
						var data = eval("(" + info.response + ")");

						$("#" + file.id).html("<div id='qid"+data.id+"' class='img'><a target='_blank' href='"+data.pic+"'><img src='" + data.pic + "'/></a></div><label><p>"+"<input style='float:left;cursor:pointer;' name='selectimg[]' type='checkbox' value='"+data.id+"' />"+ data.name + "</p></label>");
						
						if(data.status != 'success'){				   
						   alert(data.errormsg);//上传失败提示错误信息
						   return;	
						}
						
						//删除重复收款码
						if(data.update_img_id > 0){
						   $("#qid"+data.update_img_id).parent().remove();	
						}
						
						
						//显示全选按钮
						show_selectall_css();
						
					},
					Error: function(up, err) { //上传出错的时候触发
						alert(err.message);
					}
				}
			});
			uploader.init();
			
			
		  //显示全选按钮
		  var is_execute_selectall=false;
		  function show_selectall_css(){
			  
			if(!is_execute_selectall){
			   is_execute_selectall=true;			   
			   var selectall_css=$(".selectall");			   
			   if(selectall_css.css("display") == "none"){
			     selectall_css.css("display","block");
			   }					
			}			
		  }		
		  	
			
        //有数据就显示全选按钮
		<?php  
		  if($is_echo_dir ||$is_echo_qrcode){  
		     echo 'show_selectall_css();';
		  }
		?>	
		
		$(function($){		
			
		  //全选checkbox
		  var is_check=true;
		  $("#selectall_button").click(function(){
			  var ul_pics_checkbox=$("#ul_pics :checkbox");
			  if(is_check){
				 ul_pics_checkbox.prop("checked", true);
				 is_check=false;	
		      }else{
				 ul_pics_checkbox.prop("checked", false);
				 is_check=true;	 
			  }
			  
			   
		   });	
		   
		   
		  //删除被选目录或图片		   
		 $("#delete_button").click(function(){
	 
	        var imgform_data=$('#imgform').serialize(); //获取表单数据
			if(imgform_data != ''){
			   var is_delete=confirm('您确定要删除吗？');
               if(!is_delete){return false;} 	
			}			
			
		   $.post("<?php echo skl_U('Upload/delete',array('is_ajax'=>'1','sid'=>$_GET['sid'])); ?>",imgform_data,
            function(data){   
  
           var data=jQuery.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
         if(data.status == 'success'){
			//alert('删除成功'); 
			window.location.reload(); 
            
         }else{                
                 
            alert(data.errormsg);
         }
  
        });		  
			   
		 });
		 
		 
		 
		 //新建收款码目录	   
		 $("#newdir").click(function(){ 			

			window.wxc.xcConfirm("请输入目录名称，如果新建多个目录用\"-\"号隔开例如a-b-c", window.wxc.xcConfirm.typeEnum.input,{ onOk:function(v){
							
			 if(v != ""){
			  var dirname_data="sid=<?php echo $_GET['sid']; ?>&dirname="+v;			
		   $.post("<?php echo skl_U('Upload/newdir',array('is_ajax'=>'1')); ?>",dirname_data,
            function(data){   
  
           var data=jQuery.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
         if(data.status == 'success'){
			//alert("新建成功"); 
			window.location.reload(); 
            
         }else{                
                 
            alert(data.errormsg);
         }		

  
        });		  
			}	
				
				
		}
			});
				   
		 });	
		  
		
		
//点击显示目录，开始
var imgpath_div=$('.dir_path');
var show_path_button=$('#show_path');
show_path_button.click(function(){
  	if(imgpath_div.is(':visible')){
	   imgpath_div.fadeOut(500);
       show_path_button.text("显示路径");
	}else{

	   imgpath_div.fadeIn(500);	
	   show_path_button.text("隐藏路径"); 		 

	}
  
});
//点击显示目录，结束		
		 
		 
		 
});
		
			
		</script>
	</body>

</html>