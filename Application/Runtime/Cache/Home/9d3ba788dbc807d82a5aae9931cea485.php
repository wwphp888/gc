<?php if (!defined('THINK_PATH')) exit();?><html lang="en"><head>		    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1,user-scalable=no">    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">	<title>个人中心</title>    <link rel="stylesheet" href="/res/touzi/base.css">    <link rel="stylesheet" href="/res/touzi/weui.css">    <link rel="stylesheet" href="/res/touzi/layer.css">		    <style>        .user-img-2{width: 50px;display: block; border-radius: 10px;box-shadow: 0 0 5px #000;            -webkit-transform: scale(.5);            -ms-transform: scale(.5);            transform: scale(.5);            -webkit-animation: avatar .3s forwards;            animation: avatar .3s forwards;}        .user-img{            width: 100px;            height: 100px;            display: block;            border-radius: 50%;            margin: 0px auto;            background-color: #fff;            box-shadow: 0 0 10px #000;            -webkit-transform: scale(.5);            -ms-transform: scale(.5);            transform: scale(.5);            -webkit-animation: avatar .3s forwards;            animation: avatar .3s forwards;        }        .user-title{            font-weight: 400;            font-size: 17px;            width: auto;            overflow: hidden;            text-overflow: ellipsis;            white-space: nowrap;            word-wrap: normal;            word-wrap: break-word;            word-break: break-all;            text-align: center;            color: rgba(255, 255, 255, 0.82);            padding-top: 5px;        }        .user-desc{            color: #999999;            font-size: 13px;            line-height: 2;            overflow: hidden;            text-overflow: ellipsis;            display: -webkit-box;            -webkit-box-orient: vertical;            -webkit-line-clamp: 2;            text-align: center;            color: rgba(255, 255, 255, 0.82);        }		.PcIconWidth{ width: 30px;}        /* fade in and expand image to full size, with a slight bounce */        @-webkit-keyframes avatar {            70% {                opacity: 1;                -webkit-transform: scale(1.1);                transform: scale(1.1);            }            100% {                opacity: 1;                -webkit-transform: scale(1);                transform: scale(1);            }        }        @keyframes avatar {            70% {                opacity: 1;                -webkit-transform: scale(1.1);                transform: scale(1.1);            }            100% {                opacity: 1;                -webkit-transform: scale(1);                transform: scale(1);            }        }        .PcIconWidth{ width: 25px; height: 25px;}        /*提现按钮*/        .tiXian{            box-shadow: 0 0 5px 0 rgba(0,0,0,.24); background-color: #775cc1; border:0; color: #fff;        }		</style>    <!--[if IE]>    <script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>    [endif]--><meta name="poweredby" content="besttool.cn" />
</head><body style="background-color:#F3F3F3;"><section style="background-image: radial-gradient(circle at 60% 48%, #3D3459, #10112F); padding: 15px 0; position: relative;">    <figure style="padding-top: 20px;"><img src="<?php echo (headsize($user["headimg"],96)); ?>" class="user-img"></figure>    <p class="user-title"><?php echo ($user["nickname"]); ?></p>    <p class="user-desc">我的ID：<?php echo ($user["id"]); ?></p></section><!-- <section style="background-color: #fff; margin-bottom: 40px;"> -->    <!-- <div class="weui-cells" style="margin:3px 0;box-shadow: 0 0 5px rgba(0,0,0,.12); border: 0;"> -->        <!-- <div class="weui-cell"> -->            <!-- <div class="weui-cell__hd"><img src="http://www.wqzhonghezl.cn/Public/Home/images/person-center/icon-red/team-library.png" style="width: 25px; margin-right: 5px; display: block;"></div> -->             <!-- <div class="weui-cell__bd"> -->                <!-- <p>我的余额 ：<span style=" color: red;font-weight: 700; font-size: 15px;"><?php echo ($user["money"]); ?>元</span></p> -->            <!-- </div> -->        <!-- </div> -->    <!-- </div> --></body>	<head>				<meta charset="utf-8">
	    <meta content="yes" name="apple-mobile-web-app-capable">
	    <meta content="black" name="apple-mobile-web-app-status-bar-style">
	    <meta content="telephone=no" name="format-detection">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no">
		<title></title>
		
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
		</style> 		<link href="//at.alicdn.com/t/font_1467614459_1046817.css" rel="stylesheet" />	<meta name="poweredby" content="besttool.cn" />
</head>	<body style=" background:#f2f2f2;">			<style>		.ucenter-account,.ucenter-menu{ background:#fff;}		.ucenter-account ul li{ padding:30px;}		.ucenter-account ul li a span.number{ display:block; font-size:16px; color:#357CB4;}		.ucenter-account ul li a span.text{ display:block; font-size:14px; color:#666;}				.ucenter-menu ul li span.glyphicon{ font-size:18px; color:#666;}				.ucenter-header{ background:#357CB4; padding:10px; position:relative;}		.ucenter-header .widthdraw{ position:absolute; right:10px; top:45px; padding:5px 15px; background:#ccc; color:#000;}		.ucenter-menu,.ucenter-account{margin: 5px 0; font-family: "微软雅黑"; border-top: 1px solid #EAEAEA;}		.ucenter-menu ul li,.ucenter-account ul li{float: left; text-align: center; border-right: 1px solid #EAEAEA; border-bottom: 1px solid #EAEAEA;}		.ucenter-account ul li{width: 33.33%; padding: 10px 0;}		.ucenter-menu ul li{width: 33.3%; line-height: 20px; padding: 15px 0;}		.ucenter-menu ul li.li-none,.ucenter-account ul li.li-none{ border-right: none;}		.ucenter-menu ul li a,.ucenter-account ul li a{display: block; color: #000;}		.ucenter-menu ul li a span,.ucenter-account ul li a span{color: #FF0000;}		.ucenter-content ul li{ box-sizing:border-box;}				.iconfont{ font-size:24px;}		</style>						<div class="ucenter-content" style=" margin-top:10px;">			<div class="ucenter-account">				<ul class="clearfix">					<li>						 <a href="">							<span class="number">￥<?php echo ($user["money"]); ?>元</span>							<span class="text">我的余额</span>						</a>					</li> 				    <!-- <li class="">						<a href="">							<span class="number">￥<?php echo ($user["fanli"]); ?></span>							<span class="text">待返利</span>						</a>					</li> --> 					 <li>						<a href="<?php echo U('expense_log');?>">							<span class="number">￥<?php echo ($user["expense"]); ?></span>							<span class="text">推广奖励</span>						</a>					</li> 										<!-- <li> -->						<!-- <a href="<?php echo U('pickup_log');?>"> -->							<!-- <span class="number">￥<?php echo ($user["pickup"]); ?></span> -->							<!-- <span class="text">累计采摘</span> -->						<!-- </a> -->					<!-- </li> -->				 	 <!-- <li class="">						<a href="<?php echo U('log?action=10');?>">							<span class="number">￥<?php echo ($user["reward_lazy"]); ?></span>							<span class="text">公排奖励</span>						</a>				     </li>  -->					<li>						<a href="<?php echo U('withdraw');?>">							<span class="number">￥<?php echo ($user["withdraw"]); ?></span>							<span class="text">														已提现</span>						</a>					</li>				</ul>			</div>			<div class="ucenter-menu">				<ul class="clearfix">					<li class="">						<a href="<?php echo U('charge');?>">							<span class="iconfont icon-sponsor"></span><br>							在线充值						</a>					</li> 					<li class="">						<a href="/index.php?m=&c=Index&a=playlog">							<span class="iconfont icon-sortlight"></span><br>							投注记录						</a>					</li>					<li>						<a href="/index.php?m=&c=Index&a=lishi">							<span class="iconfont icon-friendfill"></span><br>							开奖记录						</a>					</li>					<li>						<a href="javascript:;" onclick="applyWithdraw()">							<span class="iconfont icon-coin"></span><br>							我要提现						</a>					</li>					<li>						<a href="<?php echo U('withdraw');?>">							<span class="iconfont icon-list"></span><br>							提现记录						</a>					</li>					<li class="">						<a href="<?php echo U('cash');?>">							<span class="iconfont icon-emoji"></span><br>							提现收款码						</a>					</li> 					<li>						<a href="<?php echo U('kefu');?>">							<span class="iconfont icon-qrcode"></span><br>							联系客服						</a>					</li>																		 		<!-- <li class="">						<a href="<?php echo U('buygong');?>">							<span class="iconfont icon-sponsor"></span><br>							购买公排						</a>					</li>  --> 					<li>						<a href="javascript:;" onclick="applyTuiguang()">							<span class="iconfont icon-coin"></span><br>							佣金提现						</a>					</li>									 	<li class="">						<a href="/index.php?m=&c=Index&a=question">							<span class="iconfont icon-circle"></span><br>							常见问题						</a>					</li> 				</ul>			</div>		</div>		<script>		window.money = <?php echo ((isset($user["money"]) && ($user["money"] !== ""))?($user["money"]):0); ?>;		var money = <?php echo ($user["money"]); ?>;				function applyTuiguang(){			layer.prompt({			  title: '请输入提现金额',			  formType: 0 //prompt风格，支持0-2			}, function(input){				input = parseFloat(input);				if(isNaN(input) || input < 49){					alert('每次最少提现50块钱');				}else if(input > <?php echo ((isset($user["expense"]) && ($user["expense"] !== ""))?($user["expense"]):0); ?>){					alert('最多只能提现<?php echo ($user["expense"]); ?>元');				}else{					layer.loading();					$.post("<?php echo U('tuiguang');?>",{money:input},function(d){						layer.msg(d.info);						setTimeout(function(){location.href = location.href;},1000)					});				}			});		}		function applyWithdraw(){			layer.prompt({			  title: '请输入提现金额',			  formType: 0 //prompt风格，支持0-2			}, function(input){				input = parseFloat(input);				if(money == 0){					alert('您的账户没有资金');				}else if(isNaN(input) || input < 49){					alert('每次最少提现50');				}else if(input > <?php echo ((isset($user["money"]) && ($user["money"] !== ""))?($user["money"]):0); ?>){					alert('最多只能提现'+window.money+'元');				}else{					layer.loading();					$.post("<?php echo U('withdraw');?>",{money:input},function(d){						layer.msg(d.info);                            setTimeout(function () {location.href = location.href;}, 1000)					});				}			});		}		</script>				<html lang="en">

<head>  
    
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1,user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>个人中心</title>
    <link rel="stylesheet" href="/res/touzi/base.css">
    <link rel="stylesheet" href="/res/touzi/weui.css">
    <link rel="stylesheet" href="/res/touzi/layer.css">
<meta name="poweredby" content="besttool.cn" />
</head>
<body>
<div class="weui-tabbar">
        <!-- <a href="/Ssc/index.html" class="weui-tabbar__item">
            <i class="my-bet-1 weui-tabbar__icon "></i>
            <p class="weui-tabbar__label">时时彩</p>
        </a> -->
         <a href="/" class="weui-tabbar__item">
            <i class="dice-icon-1 weui-tabbar__icon "></i>
            <p class="weui-tabbar__label">猜大小</p>
        </a>
        <a href="/index.php?m=&c=Index&a=usercode" class="weui-tabbar__item">  <!-- /index.php?m=&c=Index&a=usercode -->
            <i class="myShare-icon-1 weui-tabbar__icon "></i>
            <p class="weui-tabbar__label">分享赚钱</p>
        </a>
        <a href="/index.php?m=&c=Index&a=ucenter" class="weui-tabbar__item">
            <i class="personCenter-icon-2 weui-tabbar__icon "></i>
            <p class="weui-tabbar__label">个人中心</p>
        </a>
    </div>
</body>
</html>					<script>


	// 唤起微信支付


	function call_pay(param,url,id){


		param = eval('('+param+')');


		if(typeof url == 'undefined' || !url)url = location.href;


		if (typeof WeixinJSBridge == "undefined"){


			if( document.addEventListener ){


				document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);


			}else if (document.attachEvent){


				document.attachEvent('WeixinJSBridgeReady', jsApiCall); 


				document.attachEvent('onWeixinJSBridgeReady', jsApiCall);


			}





		}else{


			WeixinJSBridge.invoke(


				'getBrandWCPayRequest',


				param,


				function(res){


					WeixinJSBridge.log(res.err_msg);


					if(res.err_msg == 'get_brand_wcpay_request:cancle'){


						alert('你取消了支付');


						location.href = '';


					}else if(res.err_msg == 'get_brand_wcpay_request:ok'){

						//alert('支付成功');


			        	location.href = '/';


					}else{


						alert(res.err_msg)


						location.href = url;


					}


					


					


				}


			);


		}


	}


	function  jsApiCall(){


        WeixinJSBridge.invoke(


				'getBrandWCPayRequest',


				{


                   "appId" : "<?php echo $param['appId']?>",     //公众号名称，由商户传入     


                   "timeStamp":"<?php echo $param['timeStamp']?>",         //时间戳，自1970年以来的秒数     


                   "nonceStr" : "<?php echo $param['nonceStr']?>", //随机串     


                   "package" : "<?php echo $param['package']?>",     


                   "signType" : "MD5",         //微信签名方式:     


                   "paySign" : "<?php echo $param['paySign']?>" //微信签名 


               },


				function(res){


					WeixinJSBridge.log(res.err_msg);


					if(res.err_msg == 'get_brand_wcpay_request:cancle'){


						alert('你取消了支付');


						location.href = url;


					}else if(res.err_msg == 'get_brand_wcpay_request:ok'){


						//alert('支付成功');


				        	location.href = '/';


					}else{


						alert(res.err_msg)


						location.href = url;


					}


					


					


				}


			);


	}


	function clickPlantB(plant_id){


		<!--点击播种-->


		$.post("<?php echo U('do_plant');?>",{plant_id:plant_id,index:<?php echo ((isset($_GET['index']) && ($_GET['index'] !== ""))?($_GET['index']):0); ?>},function(d){


			layer.msg(d.info);


			// if(d.status == 1){


			// 	location.href = location.href;


			// }


		});


	}


	// 通用ajax表单提交


	function ajaxFormSubmit(seletor){
		if(!seletor || seletor == '')seletor = "form";
		data = $(seletor).serialize();
		layer.load(0, {shade: [0.1,'#fff']});
		$.post($(seletor).attr('action'),data,function(data){
			layer.closeAll();
			_index = layer.msg(data.info);
			if(data.url && data.url != ''){
				// 延迟一秒钟跳转
				setTimeout(function(){
					location.href = data.url;
				},1000)
			}
			else{
				setTimeout(function(){
					layer.close(_index);
				},3000)
			}
		})
	}


	


	layer.loading = function(param){


		layer.load(0, {shade: false});


		//var par =$.extend({},{type: 2,shadeClose:false},param);


		//layer.open(par)


	}


	</script>


	</body></html>