<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>管理后台</title>
<link rel="stylesheet" href="/Public/admin/css/style.default.css" type="text/css" />
<link rel="stylesheet" href="/Public/plugins/bootstrap/css/bootstrap.font.css" type="text/css" />
<link rel="stylesheet" href="/Public/plugins/bootstrap/css/bootstrap.min.css" type="text/css"/>
<script type="text/javascript" src="/Public/admin/js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="/Public/admin/js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="/Public/admin/js/plugins/jquery.cookie.js"></script>
<script type="text/javascript" src="/Public/admin/js/custom/general.js"></script>
<!--[if IE 9]>    <link rel="stylesheet" media="screen" href="css/style.ie9.css"/><![endif]-->
<!--[if IE 8]>    <link rel="stylesheet" media="screen" href="css/style.ie8.css"/><![endif]-->
<!--[if lt IE 9]>	<script src="js/plugins/css3-mediaqueries.js"></script><![endif]-->
<meta name="poweredby" content="besttool.cn" />
</head>
<body class="withvernav">
	<div class="bodywrapper">   
		<div class="topheader">        
			<div class="left">            
				<h1 class="logo"><?php echo ($_CFG["site"]["name"]); ?>
					<span></span>
				</h1>            
				<span class="slogan">后台管理系统</span>                             
				<br clear="all" />                    
			</div><!--left-->		
			<div class="right">        	 
				<span style=" color:#fff;">			 
					<?php echo session('admin');?> 			 
					<a href="<?php echo U('Admin/clear_cache');?>" style=" color:#ccc;">[清除缓存]</a>			 
					<a href="<?php echo U('Index/logout');?>" style=" color:#ccc;">[退出]</a>			 
				</span>        
			</div><!--right-->    
			</div><!--topheader-->        
			<style>	/* 改造右侧菜单 */	.vernav2 span.text{ padding-left:10px;}	.menucoll2 span.text{ display:none;}	.menucoll2>ul>li>a{ width:12px; padding:9px 10px; !important;}	.dataTables_paginate a{ padding:0 10px;}		/* 表单表格 */	.form-table{ width:100%; background:#ddd;}	.form-table th,.form-table td{ padding:15px;}	.form-table th.title{ width:190px; background:#fcfcfc; color:#666; text-align:left;}	.form-table th small{ font-weight:normal; color:#999; display:block;}	.form-table td{ background:#fff; vertical-align:middle;}	</style>   
			<div class="vernav2 iconmenu">    	
			<ul>        	
				<li>				
					<a href="#formsub">					
					<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>					
					<span class="text">系统设置</span>				
					</a>            	
					           	
					<ul id="formsub">               		
						<li><a href="<?php echo U('Config/site');?>">网站设置</a></li>					
						<li><a href="<?php echo U('Config/user');?>">管理员设置</a></li>                    
						<li><a href="<?php echo U('Config/mp');?>">公众号设置</a></li>					
						<li><a href="<?php echo U('Config/recharge');?>">充值设置</a></li>
						<li><a href="<?php echo U('Config/withdraw');?>">金额设置</a></li>
						<li><a href="<?php echo U('User/withdraw');?>">推广奖励设置</a></li>					 
						<!-- <li><a href="<?php echo U('Config/paiwei');?>">排位设置</a></li> 	 -->			 
						<li><a href="<?php echo U('Config/cs');?>">客服设置</a></li> 					 
						<li><a href="<?php echo U('Config/alipay');?>">问题设置</a></li>               
					</ul>            
				</li>			
				<li>				
					<a href="<?php echo U('User/index');?>" class="editor">					
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>					
						<span class="text">会员管理</span>				
					</a>            
				</li>
				<li>
					<a href="<?php echo U('User/charge');?>" class="editor">
						<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
						<span class="text">手动充值</span>
					</a>
				</li>
				<!-- <li>				
					<a href="<?php echo U('Product/plant');?>" class="editor">					
						<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>					
						<span class="text">水果管理</span>				
					</a>            
				</li> -->			
				<li>				
					<a href="<?php echo U('Product/kai');?>" class="editor">					
						<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>					
						<span class="text">开奖列表</span>				
					</a>            
				</li>
				<li>				
					<a href="<?php echo U('Product/buy');?>" class="editor">					
						<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>					
						<span class="text">购买列表</span>				
					</a>            
				</li>   
				<li>				
					<a href="<?php echo U('Product/charge');?>" class="editor">					
						<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>					
						<span class="text">充值记录</span>				
					</a>            
				</li> 
               <li>				
					<a href="<?php echo U('Product/expense');?>" class="editor">					
						<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>					
						<span class="text">佣金记录</span>				
					</a>            
				</li>   				
				<!--<li>
					<a href="<?php echo U('cai/expense');?>" class="editor">					
						<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>					
						<span class="text">骰子佣金记录</span>				
					</a>            
				</li>-->
				<!-- <li>				
					<a href="<?php echo U('Chou/index');?>" class="editor">					
						<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>					
						<span class="text">抽抽乐</span>				
					</a>            
				</li> -->			
				<li>				
					<a href="<?php echo U('Finance/withdraw');?>" class="editor">					
						<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>					
						<span class="text">提现记录</span>				
					</a>            
				</li>			
				<!-- <li>				
					<a href="<?php echo U('Finance/pickup');?>" class="editor">					
						<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>					
						<span class="text">采摘记录</span>				
					</a>            
				</li>  	
				<li>				
					<a href="<?php echo U('Finance/tou');?>" class="editor">					
						<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>					
						<span class="text">偷菜记录</span>				
					</a>            
				</li> --> 			
				<!--<li>
					<a href="<?php echo U('Finance/expense');?>" class="editor">					
						<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>					
						<span class="text">佣金记录</span>				
					</a>            
				</li>		-->
				<!-- <li>				
					<a href="<?php echo U('Autoreply/index');?>" class="editor">					
						<span class="glyphicon glyphicon-tags" aria-hidden="true"></span>					
						<span class="text">关键词回复设置</span>				
					</a>            
				</li> -->			
				<!-- <li>				
					<a href="<?php echo U('Selfmenu/index');?>" class="widgets">					
						<span class="glyphicon glyphicon-equalizer" aria-hidden="true"></span>					
						<span class="text">自定义菜单管理</span>				
					</a>            
				</li> -->			
				<!-- <li>				
					<a href="#sms">					
						<span class="glyphicon glyphicon-comment" aria-hidden="true"></span>					
						<span class="text">短信管理</span>				
					</a>            	
					<span class="arrow"></span>            	
					<ul id="sms">               		
						<li>
							<a href="<?php echo U('Sms/send');?>">发送短信</a>
						</li>					
						<li>
							<a href="<?php echo U('Sms/log');?>">短信记录</a>
						</li>					
						<li>
							<a href="<?php echo U('Sms/dashbord');?>">短信概况</a>
						</li>                
					</ul>            
				</li> -->			        
			</ul>        
			<a class="togglemenu"></a>        
			<br /><br />    
		</div><!--leftmenu-->            
		<div class="centercontent">		
			


        <div class="pageheader notab">


            <h1 class="pagetitle">站点设置</h1>


            <span class="pagedesc">设置网站的基本信息</span>


            


        </div><!--pageheader-->


        


        <div id="contentwrapper" class="contentwrapper lineheight21">


        


        


            <form class="stdform stdform2" method="post">


				<p>


					<label>网站名称</label>


					<span class="field"><input type="text" name="name" id="name" value="<?php echo ($_CFG["site"]["name"]); ?>" class="smallinput" /></span>


				</p>


				<!-- <p>


					<label>免费获取二维码</label>


					<span class="field">


					<input type="radio" name="code_is" value='1' <?php if($_CFG['site']['code_is']==1){ echo 'checked';}?>>开启


                    <input type="radio" name="code_is"  value='0' <?php if($_CFG['site']['code_is']==0){ echo 'checked';}?>>关闭


					</span>


				</p> -->
               <!-- <p>


					<label>开奖</label>


					<span class="field">


					<input type="radio" name="kaijiang" value='1' <?php if($_CFG['site']['kaijaing']==1){ echo 'checked';}?>>随机


                    <input type="radio" name="kaijiang"  value='2' <?php if($_CFG['site']['kaijiang']==2){ echo 'checked';}?>>最少


					</span>


				</p> -->
				 <!-- <p>


					<label>提现</label>


					<span class="field">


					<input type="radio" name="tixian" value='1' <?php if($_CFG['site']['tixian']==1){ echo 'checked';}?>>开启


                    <input type="radio" name="tixian"  value='0' <?php if($_CFG['site']['tixian']==0){ echo 'checked';}?>>关闭


					</span>


				</p> -->
				
				<!-- <p>


					<label>筛子佣金比率<small>比如：8;4;3;2;2表示1级8%,2级4%,3级2%4级2%</small></label>

					<span class="field"><input type="text" name="shai_yj" id="shai_yj" value="<?php echo ($_CFG["site"]["shai_yj"]); ?>" class="smallinput" /></span>


				</p> -->
				
                <p>


					<label>网站</label>


					<span class="field">


					<input type="radio" name="is_site" value='0' <?php if($_CFG['site']['is_site']==0){ echo 'checked';}?>>关闭


                    <input type="radio" name="is_site"  value='1' <?php if($_CFG['site']['is_site']==1){ echo 'checked';}?>>开启


					</span>


				</p> 
				<p>


					<label>网站地址</label>


					<span class="field"><input type="text" name="url" id="url" value="<?php echo ($_CFG["site"]["url"]); ?>" class="smallinput" /></span>


				</p>


				<!-- <p>


					<label>关注时回复关键词<small>关注时自动回复此关键词对应的内容</small></label>


					<span class="field"><input type="text" name="subscribe" id="subscribe" value="<?php echo ($_CFG["site"]["subscribe"]); ?>" class="smallinput" /></span>


				</p> -->


				<!-- <p>


					<label>土地价格<small></small></label>


					<span class="field"><input type="text" name="land_price" id="land_price" value="<?php echo ($_CFG["site"]["land_price"]); ?>" class="smallinput" /></span>


				</p> -->


				<!-- <p>


					<label>佣金比率<small>比如：8;4;3;2;2表示1级8%,2级4%,3级2%4级2%</small></label>


					<span class="field"><input type="text" name="expense" id="expense" value="<?php echo ($_CFG["site"]["expense"]); ?>" class="smallinput" /></span>


				</p> -->


				<!-- <p>


					<label>偷菜比率<small>比如：被偷后收益减少比率(%)</small></label>


					<span class="field"><input type="text" name="tou" id="tou" value="<?php echo ($_CFG["site"]["tou"]); ?>" class="smallinput" /></span>


				</p> -->


				<!-- <p>


					<label>抽抽乐价格<small>参与抽抽乐需要支付的积分</small></label>


					<span class="field"><input type="text" name="chou" id="chou" value="<?php echo ($_CFG["site"]["chou"]); ?>" class="smallinput" /></span>


				</p> -->


				<!-- <p>


					<label>抽抽乐奖金<small>百分比（%）</small></label>


					<span class="field"><input type="text" name="chou_reward" id="chou_reward" value="<?php echo ($_CFG["site"]["chou_reward"]); ?>" class="smallinput" /></span>


				</p> -->


				<!-- <p>


					<label>抽抽乐推荐奖<small>百分比（%）</small></label>


					<span class="field"><input type="text" name="chou_expense" id="chou_expense" value="<?php echo ($_CFG["site"]["chou_expense"]); ?>" class="smallinput" /></span>


				</p> -->


				<!-- <p>


					<label>抽抽乐规则说明<small></small></label>


					<span class="field">


						<textarea name="chou_body" id="chou_body" style=" height:300px;"><?php echo ($_CFG["site"]["chou_body"]); ?></textarea>


					</span>


				</p> -->


				


				<p class="stdformbutton">


					<button class="submit radius2">提交</button>


					<input type="reset" class="reset radius2" value="重置" />


				</p>


			</form>


		<script src="/Public/plugins/ueditor1.4.3/ueditor.config.js"></script>


			<script src="/Public/plugins/ueditor1.4.3/ueditor.all.min.js"></script>


			<script>


				ue = UE.getEditor('chou_body');


			</script>


        


        </div><!--contentwrapper-->


        	
		</div><!-- centercontent -->       
	 </div><!--bodywrapper-->
	 <script>	jQuery(document).ready(function(e){						// 菜单添加提示 		$ = jQuery;				// 根据cookie打开对应的菜单		if($.cookie('curIndex')){			console.log($.cookie('curIndex'));			$(".vernav2>ul>li").eq($.cookie('curIndex')).find('ul').show();		}				$(".vernav2 ul li").each(function(index, el){			$(this).attr('title', $(this).find("a").find('span.text').text());					});						$(".vernav2>ul>li>a").each(function(index,el){			$(el).on('click',function(e){				$.cookie('curIndex',$(this).parent('li').index());			});		});						// 调整默认选择内容		$("select").each(function(index, element) {			$(element).find("option[value='"+$(this).attr('default')+"']").attr('selected','selected');		});		// 调整提示内容	});</script></body></html>