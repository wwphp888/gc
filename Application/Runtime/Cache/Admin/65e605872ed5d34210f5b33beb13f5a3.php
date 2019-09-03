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
    <h1 class="pagetitle">会员管理</h1>
</div><!--pageheader-->                
<div id="contentwrapper" class="contentwrapper lineheight21">			
	<div class="tableoptions">        				
		<form method="post">
			手机:<input type="text" name="mobile" value="<?php echo ($_GET['mobile']); ?>" class="smallinput" style="width:100px;" />		姓名:<input type="text" name="name" value="<?php echo ($_GET['name']); ?>" class="smallinput" style="width:100px;" />			用户ID:<input type="text" name="id" value="<?php echo ($_GET['id']); ?>" class="smallinput" style="width:100px;" />			<input type="submit" value="查找" />
		</form>			
	</div><!--tableoptions-->			
	<form action="" method="post">			
		<table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">				
			<thead>					
				<tr>						
					<!-- <th class="head1">
						<input type="checkbox" class="check_all">
					</th> -->						
					<th class="head1">编号</th>
					<!--th class="head0" style=" width:280px">OPENID</th-->
					<th class="head0">昵称</th>						
					<th class="head0">手机</th>						
					<th class="head0">注册日期</th>						
					<th class="head0">上级</th>						
					<th class="head0">余额</th>						
					<!-- <th class="head0">盈亏</th>						
					<th class="head0">下单量</th>						
					<th class="head0">通道会员</th>	 -->					
					<th class="head0">累计佣金</th>						
					<th class="head0">累计提现</th>						
					<th class="head0">操作</th>					
				</tr>				
			</thead>				
			<tbody>
				
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>					    
						<!-- <td>
							<input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>">
						</td> -->					
						<td><?php echo ($vo["id"]); ?></td>
						<!--td><?php echo ($vo["openid"]); ?></td-->						
						<td><?php echo urldecode($vo['nickname']);?></td>						
						<td><?php echo ($vo["login_name"]); ?></td>						
						<td><?php echo (date("Y-m-d H:i",$vo["sub_time"])); ?></td>						
						<td><?php if($vo['parent1'] > 0): echo ($vo["parent1"]); endif; ?></td>						
						<td>							
							<?php echo ($vo["money"]); ?>
						</td>						
						<!-- <td>		
							<?php echo ($vo["yingkui"]); ?>
						</td>                       
						<td> <?php echo ($vo["num"]); ?> </td>						
						<td>						
							<?php if($vo['is_tong'] == 1): ?>是<?php endif; ?>
							<?php if($vo['is_tong'] == 0): ?>否<?php endif; ?>
						</td> -->
						<td>
							<a href="<?php echo U('Finance/expense?user_id='.$vo['id']);?>"><?php echo ($vo["expense"]); ?></a>
						</td>											
						<td>							
							<a href="<?php echo U('Finance/withdraw?user_id='.$vo['id']);?>"><?php echo ($vo["withdraw"]); ?></a>
						</td>
						<td class="center">							
							<a href="<?php echo U('charge', 'id='.$vo['id']);?>">充值</a> |
							<!-- <a href="<?php echo U('edit', 'id='.$vo['id']);?>">修改</a> |--> 							
							<a href="<?php echo U('del', 'id='.$vo['id']);?>" onclick="return confirm('你确实要删除这个会员吗？')">删除 </a>|
							<a href="<?php echo U('parent1', 'id='.$vo['id']);?>">我的下级</a>
                                                        <a href="<?php echo U('setAgentAccount', 'user_id='.$vo['id']);?>">设置代理</a>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		
							
			</tbody>			
		</table>			
		<!-- <div style="margin:30px;">			
			<input value="成为通道会员" type="submit" name="tongdao">&nbsp; &nbsp;&nbsp;			
			<input value="取消通道会员" type="submit" name="tongdao">&nbsp; &nbsp;&nbsp;			
		</div> -->			
	</form>			
	<div class="dataTables_paginate paging_full_numbers" id="dyntable2_paginate">			
		<?php echo ((isset($page) && ($page !== ""))?($page):"<p style='text-align:center'>暂时没有数据</p>"); ?>			
	</div>                
	</div><!--contentwrapper-->       
	<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>       
	<script type="text/javascript">       	
		$(function(){           
			$(".check_all").click(function(){            
				var checked = $(this).get(0).checked;           
				$("input[type=checkbox]").attr("checked", checked);
	        });        
		});       
	</script>	
		</div><!-- centercontent -->       
	 </div><!--bodywrapper-->
	 <script>	jQuery(document).ready(function(e){						// 菜单添加提示 		$ = jQuery;				// 根据cookie打开对应的菜单		if($.cookie('curIndex')){			console.log($.cookie('curIndex'));			$(".vernav2>ul>li").eq($.cookie('curIndex')).find('ul').show();		}				$(".vernav2 ul li").each(function(index, el){			$(this).attr('title', $(this).find("a").find('span.text').text());					});						$(".vernav2>ul>li>a").each(function(index,el){			$(el).on('click',function(e){				$.cookie('curIndex',$(this).parent('li').index());			});		});						// 调整默认选择内容		$("select").each(function(index, element) {			$(element).find("option[value='"+$(this).attr('default')+"']").attr('selected','selected');		});		// 调整提示内容	});</script></body></html>