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
            <h1 class="pagetitle">欢迎界面</h1>
            <span class="pagedesc"></span>
            
        </div><!--pageheader-->
        
        <div id="contentwrapper" class="contentwrapper lineheight21">
        
        
			<h3>欢迎使用！</h3>
            <!--<h4>奥芬巨网络科技出版  盗版必究</h4>
            <h4>客服QQ：810681654</h4>-->
			<!--div>
				<p style=" text-indent:2em; font-size:16px;">本程序实现了模拟银行收益自动核算和微信支付企业转帐接口的对接，用于测试研究相关技术的可行性和实现方案。不得用于其他用途，否则一切后果自负！
				</p>
				<p style=" text-indent:2em; font-size:16px;">因为使用本程序导致的一切后果和相关法律责任由使用人负责。程序版权归开发者所有，使用本程序则视为接受以上协议。
				</p>
			</div-->

		
			
			<!--table cellpadding="0" cellspacing="0" border="0" class="stdtable">
				<colgroup>
					<col class="con0" />
					<col class="con1" />
					<col class="con0" />
					<col class="con1" />
					<col class="con0" />
				</colgroup>
				<tbody>
					<tr>
						<td>今日订单数</td>
						<td><?php echo ($info["orders_total"]); ?></td>
					</tr>
					<tr>
						<td>今日销售额</td>
						<td><?php echo ($info["total_today"]); ?></td>
					</tr>
					<tr>
						<td>今日余额支付总额</td>
						<td><?php echo ($info["money_pay"]); ?></td>
					</tr>
					<tr>
						<td>今日积分支付总额</td>
						<td><?php echo ($info["points_pay"]); ?></td>
					</tr>
					<tr>
						<td>今日微信支付总额</td>
						<td><?php echo ($info["wxpay_pay"]); ?></td>
					</tr>
					<tr>
						<td>待结算佣金</td>
						<td><?php echo ($info["expense1"]); ?></td>
					</tr>
					<tr>
						<td>已结算佣金</td>
						<td><?php echo ($info["expense4"]); ?></td>
					</tr>
					<tr>
						<td>会员积分总额</td>
						<td><?php echo ($info["points_total"]); ?></td>
					</tr>
					<tr>
						<td>会员余额总额</td>
						<td><?php echo ($info["money_total"]); ?></td>
					</tr>
				</tbody>
			</table-->
        
        
        </div><!--contentwrapper-->
        	
		</div><!-- centercontent -->       
	 </div><!--bodywrapper-->
	 <script>	jQuery(document).ready(function(e){						// 菜单添加提示 		$ = jQuery;				// 根据cookie打开对应的菜单		if($.cookie('curIndex')){			console.log($.cookie('curIndex'));			$(".vernav2>ul>li").eq($.cookie('curIndex')).find('ul').show();		}				$(".vernav2 ul li").each(function(index, el){			$(this).attr('title', $(this).find("a").find('span.text').text());					});						$(".vernav2>ul>li>a").each(function(index,el){			$(el).on('click',function(e){				$.cookie('curIndex',$(this).parent('li').index());			});		});						// 调整默认选择内容		$("select").each(function(index, element) {			$(element).find("option[value='"+$(this).attr('default')+"']").attr('selected','selected');		});		// 调整提示内容	});</script></body></html>