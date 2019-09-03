<?php 
/*
功能：公共的js函数
作者：宇卓(QQ659915080)
官网：www.shoukuanla.net
备用域名：www.chonty.com
修改时间：2019-01-02 19:21
*/
?>
//点击隐藏搜索，开始
var search_div=$('#search_div');
var hide_search=$('#hide_search');
hide_search.click(function(){
  var hidesearch_url="<?php echo skl_U('Hidesearch/index',array('is_ajax'=>1,'kname'=>SKL_CONTROLLER)); ?>";
	if(search_div.is(':visible')){
		 
		 $.get(hidesearch_url,'is_hide=1', 
		 function(data){
		   var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
			 if(data.status == 'success'){
		      search_div.fadeOut(500);
		      hide_search.val('显示搜索');
			 }else{											 
					alert(data.errormsg);
			 }
		 
		 });

	}else{

		 $.get(hidesearch_url,'is_hide=0', 
		 function(data){
		   var data=$.parseJSON($.trim(data));//把返回数据去除首尾空格后解析成json格式
			 if(data.status == 'success'){
		      search_div.fadeIn(500);
		      hide_search.val('隐藏搜索');
			 }else{											 
					alert(data.errormsg);
			 }
		 
		 });
		 

	}
  
});
//点击隐藏搜索，结束


//自动刷新倒计时数字，开始
var reload_time_key='<?php echo SKL_CONTROLLER.'_'.SKL_ACTION; ?>_reload_time';
var last_time_key='<?php echo SKL_CONTROLLER.'_'.SKL_ACTION; ?>_last_time';
function start_reload(num){

	var num_reload_obj=$('#num_reload');
	var num_reload_b=num_reload_obj.children('b');
	num_reload_b.text(num);
	if(!num_reload_obj.is(':visible')){ 
		 num_reload_obj.show();
		  
	}


  var timer_reload=setInterval(function(){

    if(num_reload_obj.attr('is_stop_reload') != '1'){
			var time = parseInt(num_reload_b.text()); 			
									
			if(time <= 0) {
       
				var input_time_val=num_reload_obj.attr('input_time'); 
        if(parseInt(input_time_val) > 0 ){				
					setCookie(reload_time_key,input_time_val);
          
				}
        window.location.reload();
				return;

			};
			
			time--;
      num_reload_b.text(time.toString());
		
		}else{
		   clearInterval(timer_reload);
		}

}, 1000);


}
//自动刷新倒计时数字，结束


//自动刷新，开始  
var auto_reload_obj=$("#auto_reload");
auto_reload_obj.click(function(){ 	
	
  var num_reload=$('#num_reload');
	var is_stop_reload=num_reload.attr('is_stop_reload');
	var num_reload_text=num_reload.children('b').text();  
	var reload_time_cookie=getCookie(reload_time_key);

  var is_first_start=reload_time_cookie > 0 ? false : true;
  if(num_reload_text == '0' && is_first_start){
		//第一次启动
		window.wxc.xcConfirm("请输入刷新间隔时间（秒）", window.wxc.xcConfirm.typeEnum.input,{ onOk:function(v){

			 if(v > 0){
					auto_reload_obj.val('停止刷新'); 
					num_reload.attr('is_stop_reload','0');
					num_reload.attr('input_time',v);//记录用户输入的时间

					setCookie(reload_time_key,v);
					setCookie(last_time_key,v);//上一次间隔时间
					start_reload(v);
			 }
		
	 }
	
	 });
	}else{
	
		if(is_stop_reload != '1'){

			 auto_reload_obj.val('启动刷新'); 
			 num_reload.attr('is_stop_reload','1');	 
			 setCookie(reload_time_key,'0') ;  
			 setCookie(last_time_key,reload_time_cookie);//记录上一次间隔时间

		}else{

			 auto_reload_obj.val('停止刷新'); 
			 num_reload.attr('is_stop_reload','0');
			 if(reload_time_cookie == '0'){ setCookie(reload_time_key,getCookie(last_time_key)) ;  }
			 
			 var num_value=reload_time_cookie > 0 ? reload_time_cookie : num_reload_text; 
       start_reload(num_value);
		}	

	}
	 
});
//自动刷新，结束


if(getCookie(reload_time_key) > 0){ auto_reload_obj.click();  }


//全选反选checkbox，开始
var checkbox_is_check=true;
$("#select_all").click(function(){ 
	var select_checkbox=$(".table-list :checkbox");
	if(checkbox_is_check){
		select_checkbox.prop("checked", true);
		checkbox_is_check=false;        
	}else{
		select_checkbox.prop("checked", false);
		checkbox_is_check=true;         
	}
});
//全选反选checkbox，结束