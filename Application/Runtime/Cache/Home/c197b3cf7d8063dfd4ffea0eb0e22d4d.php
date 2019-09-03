<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html>

	<head>

				<meta charset="utf-8">
	    <meta content="yes" name="apple-mobile-web-app-capable">
	    <meta content="black" name="apple-mobile-web-app-status-bar-style">
	    <meta content="telephone=no" name="format-detection">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no">
		<title>佣金记录</title>
		
		<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
		<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>

		<link rel="stylesheet" href="/Public/css/reset.css" />
		<!--script type="text/javascript" src="/Public/plugins/layer_mobile/layer.js" >
		</script-->
		<script type="text/javascript" src="/Public/plugins/layer/layer.js" ></script>
		<style>
		.head{ padding:10px; height:50px; position:relative; padding-left:70px; background:#fff; position:relative;}
		.head img.headimg{ width:50px; height:50px; border-radius:50%; position:absolute; top:10px; left:10px;}
		.head .nickname{ font-size:16px;}
		.head .time{ color:#666; font-size:12px;}
		.head .id{ position:absolute; top:10px; right:10px;}
		
		.page a{ background:#f1f1f1; padding:3px 5px; margin-right:5px;}
		
		/*底部*/
		.footer-blank{ height:50px;}
		.footer{ z-index:999; height:50px; background:#ff8a00; color:#fff; position:fixed; width:100%; bottom:0; left:0;}
		.footer a{ color:inherit;}
		.footer ul{
			list-style:none; padding:0; margin:0; text-align:center;
			display:-moz-box; /* Firefox */
			display:-webkit-box; /* Safari and Chrome */
			display:box;
		}
		.footer ul li{
			font-size:14px;
			font-family: "微软雅黑";
			line-height:50px;
			-moz-box-flex:1.0; /* Firefox */
			-webkit-box-flex:1.0; /* Safari 和 Chrome */
			box-flex:1.0;
		}
		.footer ul li span{font-size:16px; padding-top:5px; color: #fff; margin-right:10px;}
		</style>

		<style>

		.addr-content{ background:none;}

		.addr-item{ background:#fff;}

		.addr-form{}

		.addr-content .items{ background:#fff; margin-bottom:5px; padding:10px; font-size:14px; line-height:30px;}

		.addr-content .items .sn{ border-bottom:1px solid #eee;}

		.addr-content .items .status,

		.addr-content .items .del,

		.addr-content .items .gotopay{ float:right;}

		.addr-content .items .sn a{ margin:0 5px;}

		.addr-content .items .time{ border-top:1px solid #eee; color:#B5ADAD;}

		.addr-content .items .money{ float:right; font-size:16px; color:red; font-weight:bold;}

		.addr-content .items .freez{ background:blue; color:#fff; padding:2px 5px;}

		</style>

	<meta name="poweredby" content="besttool.cn" />
</head>

	<body style="background: #f7f7f7;">

				<style>        .user-img-2{width: 50px;display: block; border-radius: 10px;box-shadow: 0 0 5px #000;            -webkit-transform: scale(.5);            -ms-transform: scale(.5);            transform: scale(.5);            -webkit-animation: avatar .3s forwards;            animation: avatar .3s forwards;}        .user-img{            width: 100px;            height: 100px;            display: block;            border-radius: 50%;            margin: 0px auto;            background-color: #fff;            box-shadow: 0 0 10px #000;            -webkit-transform: scale(.5);            -ms-transform: scale(.5);            transform: scale(.5);            -webkit-animation: avatar .3s forwards;            animation: avatar .3s forwards;        }        .user-title{            font-weight: 400;            font-size: 17px;            width: auto;            overflow: hidden;            text-overflow: ellipsis;            white-space: nowrap;            word-wrap: normal;            word-wrap: break-word;            word-break: break-all;            text-align: center;            color: rgba(255, 255, 255, 0.82);            padding-top: 5px;        }        .user-desc{            color: #999999;            font-size: 13px;            line-height: 2;            overflow: hidden;            text-overflow: ellipsis;            display: -webkit-box;            -webkit-box-orient: vertical;            -webkit-line-clamp: 2;            text-align: center;            color: rgba(255, 255, 255, 0.82);        }        .PcIconWidth{ width: 30px;}        /* fade in and expand image to full size, with a slight bounce */        @-webkit-keyframes avatar {            70% {                opacity: 1;                -webkit-transform: scale(1.1);                transform: scale(1.1);            }            100% {                opacity: 1;                -webkit-transform: scale(1);                transform: scale(1);            }        }        @keyframes avatar {            70% {                opacity: 1;                -webkit-transform: scale(1.1);                transform: scale(1.1);            }            100% {                opacity: 1;                -webkit-transform: scale(1);                transform: scale(1);            }        }        .PcIconWidth{ width: 25px; height: 25px;}        /*提现按钮*/        .tiXian{            box-shadow: 0 0 5px 0 rgba(0,0,0,.24); background-color: #775cc1; border:0; color: #fff;        }        		.addr-content{margin-top: 10px; background: #fff;}		.addr-item{padding-left: 20px; border-bottom: 1px solid #FFD8D8; height: 40px; line-height: 40px; font-family: "微软雅黑"; font-size: 14px; color: #000;}		.addr-form{padding: 20px 20px 0 20px;}		.addr-input{margin: 10px 0; }		.addr-input input{width: 80%; height: 35px;}		.addr-input .name{}		.addr-btn{padding: 20px 0;}		.addr-btn a{display: block; height: 40px; line-height: 40px; background: #FF9900; text-align: center; color: #fff; font-size: 16px; font-family: "微软雅黑";}		.back2ucenter{ float:right; padding-right:10px;}		.ubg{ background:url(/Public/images/uc.png) no-repeat; background-size:100%;}		.head .id{ position:absolute; top:10px; right:0; padding:5px 10px; background:rgba(0,0,0,.5); color:#fff; border-radius:5px 0 0 5px;}		</style><section style="background-image: radial-gradient(circle at 60% 48%, #3D3459, #10112F); padding: 15px 0; position: relative;">    <figure style="padding-top: 20px;"><img src="<?php echo (headsize($user["headimg"],96)); ?>" class="user-img"></figure>    <p class="user-title"><?php echo ($user["nickname"]); ?></p>    <p class="user-desc">我的ID：<?php echo ($user["id"]); ?></p></section><section style="background-color: #fff;">    <div class="weui-cells" style="margin:3px 0;box-shadow: 0 0 5px rgba(0,0,0,.12); border: 0;">        <div class="weui-cell">            <!-- <div class="weui-cell__hd"><img src="http://www.wqzhonghezl.cn/Public/Home/images/person-center/icon-red/team-library.png" style="width: 25px; margin-right: 5px; display: block;"></div> -->            <!-- <div class="weui-cell__bd"> -->                <p>我的余额 ：<span style=" color: red;font-weight: 700; font-size: 15px;"><?php echo ($user["money"]); ?>元</span></p>         </div>		 		 		 		             <!-- <div class="weui-cell__ft"> <a href="javascript:;" onclick="applyWithdraw()" id="a-recharge" class="tiXian weui-btn weui-btn_mini weui-btn_plain-primary">余额提现</a></div>     </div>         </div>         <div class="weui-cells" style="margin:0;">         <a class="weui-cell weui-cell_access" href="/index.php?m=&c=Index&a=playlog">                  <div class="weui-cell__bd">                  <img src="http://www.wqzhonghezl.cn/Public/Home/images/person-center/icon-red/purchase-history.png" class="PcIconWidth">                  <span style="vertical-align: middle; font-size: 14px;">投注记录</span>             </div>              <div class="weui-cell__ft"></div>	</div>  -->	</section>

		

		<div class="addr-content" style=" background:none;">

			<div class="addr-item">

				推广记录

				<a href="<?php echo U('ucenter');?>" class="back2ucenter">返回 <span class="glyphicon glyphicon-chevron-right"></span></a>

			</div>

			

			<div style=" background:#fff; padding:10px;">

				<table style=" width:100%; line-height:30px; text-align:center;">

					<tr>

						

						<th>支付用户</th>

						<th>金额</th>

						<th>类型</th>

						<th>级别</th>

						<th>时间</th>

					</tr>

					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>

						

						<td><?php  $user = get_user($vo['buyer_id']);echo $user['nickname'];?></td>

						<td><?php echo ($vo["moneys"]); ?></td>

						<td><?php if($vo['type']==1){echo '猜大小';}else{echo '猜数字';} ?></td>

						<td><?php echo ($vo["level"]); ?></td>

						<td><?php echo (date("Y-m-d H:i",$vo["create_time"])); ?></td>

					</tr><?php endforeach; endif; else: echo "" ;endif; ?>

					<?php if(empty($list)): ?><tr>

						<td colspan="4">暂时没有记录</td>

					</tr><?php endif; ?>

				</table>

			</div>

			

		</div>

		<div class="page" style=" padding:10px;">

		<?php echo ($page); ?>

		</div>

	</body>

</html>