<?php if (!defined('THINK_PATH')) exit();?><!doctype html>

<html lang="zh-CN">

<head>
<link rel="stylesheet" href="/res/touzi/base.css">
    <link rel="stylesheet" href="/res/touzi/layer.css">
    <link rel="stylesheet" href="/res/touzi/layer2.css">
    <link rel="stylesheet" href="/res/touzi/style.css">
    <link rel="stylesheet" href="/res/touzi/weui.css">
    <style>

        .close-play{
            position: absolute;
            bottom: 10px;
            left: 35%;
            padding: 0px 40px;
            font-size: 13px;
            background-image: linear-gradient(307deg, #347189, #55784e);
        }

        .bigAndSmall{
            display: inline-block;
            text-decoration: none;
            color: #E4C468;

            border-radius: 5px;
            width: 100%;
        }
        .top-a{
            text-decoration: none;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 10px 10px;
            font-size: 12px;
            display: inline-block;
            margin-top: -20px;
            letter-spacing: 1px;
            background-color: rgba(61, 52, 89, 0.3);
            box-shadow: 0 0 5px 0 rgba(61, 52, 89, 0.3);
        }
        .wrap-a-bigAndSmall{
            text-align: center;
            width: 100%;
            font-size: 18px;
            margin: 8px;
            background-color: #999;
            background-image: -webkit-linear-gradient(hsla(0,0%,100%,.05), hsla(0,0%,0%,.1));
            background-image:    -moz-linear-gradient(hsla(0,0%,100%,.05), hsla(0,0%,0%,.1));
            background-image:     -ms-linear-gradient(hsla(0,0%,100%,.05), hsla(0,0%,0%,.1));
            background-image:      -o-linear-gradient(hsla(0,0%,100%,.05), hsla(0,0%,0%,.1));
            background-image:         linear-gradient(hsla(0,0%,100%,.05), hsla(0,0%,0%,.1));
            border: none;
            border-radius: .5em;
            box-shadow: inset 0 0 0 1px hsla(0,0%,0%,.2),
            inset 0 2px 0 hsla(0,0%,100%,.1),
            inset 0 1.2em 0 hsla(0,0%,100%,0.1),
            inset 0 -.2em 0 hsla(0,0%,100%,.1),
            inset 0 -.25em 0 hsla(0,0%,0%,.25),
            0 .25em .25em hsla(0,0%,0%,.05);
            color: #444;
            cursor: pointer;
            display: inline-block;
            font-family: sans-serif;
            font-size: 1em;
            font-weight: bold;
            line-height: 1.5;
            margin: 0 .5em 1em;
            padding: 40px 0;
            position: relative;
            text-decoration: none;
            text-shadow: 0 1px 1px hsla(0,0%,100%,.25);
            vertical-align: middle;
            background-image: radial-gradient(circle at 60% 48%, #9a5656, #351818);
            color: #fff;
            font-weight: bold;
        }

        .wrap-a-span{
            font-weight: bold;
            font-size:30px;
            text-align: center;
            color: #E4C468;
        }


        .jetton-img{ width: 100px; height: 100px; cursor: pointer;}

        .weui-flex__item{
            margin-bottom: 10px;
        }
        /*文字滚动样式*/
        .sliderbox{position:relative;}/*必须加这句css,否则向左右，上下滚动时会没有效果*/
        .text{  height: 30px;  width: 320px;  overflow: hidden;  margin: 0 auto;  position: absolute;  z-index: 999;  top: 17px;  left: 45px;   color: #E4C468;  }
        .text li{line-height:30px; height: 30px; width: 330px; white-space: nowrap; overflow: hidden; text-overflow:ellipsis;}
        .text li a{  color: #E4C468;}

        @media screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2){
            .wrap-a-bigAndSmall{font-size: 18px;}
            .top-a{    margin-top: -30px;  margin-bottom: -7px;}
        }

        @media screen and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2){
            .weui-flex__item{
                margin: 6px;
            }
        }
        @media screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3){
            .weui-flex__item{
                margin: 4px;
            }
            .text{left:50px; top:20px;}
        }


        /*支付方式 微信 ---- 余额 样式*/
        .mgr {  position: relative;  width: 16px;  height: 16px;  background-clip: border-box;  -webkit-appearance: none;  -moz-appearance: none;  appearance: none;  margin: -.15px .6px 0 0;
            vertical-align: text-bottom;  border-radius: 50%;  background-color: #fff;  border: 1px solid #d7d7d7;  }
        .mgr-danger {  background-color: #fff;  border: 1px solid #d7d7d7;  }
        .mgr-lg {  width: 19px;  height: 19px;  }
        .mgr-danger:checked {  border: 1px solid #cf3b3a;  }
        .mgr:checked {  border: 1px solid #555;  }
        .mgr-lg:checked:before {  height: 11px;  width: 11px;  border-radius: 50%;  margin: 3px 0 0 3px;  }
        .mgr-danger:checked:before {  background-color: #cf3b3a;  }
        .mgr:checked:before {  height: 10px;  width: 10px;  border-radius: 50%;  margin: 3px 0 0 3px;  }
        .mgr:before {  content: '';  display: block;  height: 0;  width: 0;  -webkit-transition: width .25s,height .25s;  transition: width .25s,height .25s;  }
        .pay{ text-align: center;  margin: 19px;  color: #fff;  font-size: 14px;}

        .fontSize{ color: #fff; font-size: 15px; display: block;  margin-top: -5px; }


        .topInfo{
            text-align: center;
            background-image: radial-gradient(circle at 60% 48%, #3D3459, #10112F);
            margin: 5px 0;
            color: #fff;
            letter-spacing: 1px;
            box-shadow: inset 0 0 0 1px hsla(0,0%,0%,.2),
            inset 0 2px 0 hsla(0,0%,100%,.1),
            inset 0 1.2em 0 hsla(0,0%,100%,0.1),
            inset 0 -.2em 0 hsla(0,0%,100%,.1),
            inset 0 -.2em 0 hsla(0,0%,0%,.25),
            0 .25em .2em hsla(0,0%,0%,.05);
        }

        /*缩放效果*/
        .scaleTips{
            -webkit-animation: open 0.2s linear 0.5s infinite alternate;
            -webkit-animation-timing-function: cubic-bezier(0.25, 0.1, 0.25, 1);
            animation: open 0.2s linear 0.5s infinite alternate;
            animation-timing-function: cubic-bezier(0.25, 0.1, 0.25, 1);
            color:#E4C468;
            font-size: 13px;
            transition: all 0.2s ease-in-out;
        }
        @keyframes open { 0% {  transform: scale(1);  } 100% {  transform: scale(0.9);  } }
        @-webkit-keyframes open { 0% {  -webkit-transform: scale(1);  } 100% {  -webkit-transform: scale(0.9);  } }
        @-ms-keyframes open { 0% {  -webkit-transform: scale(1);  } 100% {  -webkit-transform: scale(0.9);  } }
        @-moz-keyframes open { 0% {  -webkit-transform: scale(1);  } 100% {  -webkit-transform: scale(0.9);  } }
        @-o-keyframes open { 0% {  -webkit-transform: scale(1);  } 100% {  -webkit-transform: scale(0.9);  } }

        .getKjHb-div{
            background: url(/Public/Home/images/hongbao/alertHb.png) no-repeat;
            background-size: contain;
            width: 345px;
            height: 380px;
        }
        .getKjHb-a{
            width: 60%;
            border: 1px solid #fff;
            color: #E4C468;
            font-size: 20px;
            font-weight: 700;
            position: relative;
            bottom: -200px;
        }
        .getKjHb-p{
            position: relative;
            top: 85px;
            color: #E4C468;
            font-size: 25px;
            text-align: center;
        }
        .text{
            z-index: 10;
        }
        .text li,.text{
            width: 100%;
            left: 0;
            text-align: center;
        }
    </style>

    		<meta charset="utf-8">
	    <meta content="yes" name="apple-mobile-web-app-capable">
	    <meta content="black" name="apple-mobile-web-app-status-bar-style">
	    <meta content="telephone=no" name="format-detection">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no">
		<title>在线充值</title>
		
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

	.header{ height:50px; background:#ff8a00; line-height:50px; color:#fff; text-align:center; font-size:16px;}

	.charge-header{ background:#ff8a00; padding:5px 10px; color:#fff;;}

	.charge-header .money{ font-size:24px;}

	.orderpay-pay{ padding:10px;}

	.orderpay-pay input[type=text]{ height:30px; line-height:30px; width:150px; border:1px solid #ccc;}

	.pay{ display:inline-block; width:45%; height:40px; line-height:40px; background:blue; color:#fff; border-radius:5px; text-align:center;}

	.wxpay{ background:#94C12A;}

	.alipay{ background:#4F7FCA}

	</style>

<meta name="poweredby" content="besttool.cn" />
</head>

<body>

    <div class="header">

		在线充值

		<span class="left">

			<a href="<?php echo U('ucenter');?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>

		</span>

	</div>	

	<div class="charge-main" style="height: 100%; display: flex;flex-direction: column; justify-content: center;align-items: center">
		<div style="color: red">充值必须填上备注,备注为手机号,否则平台将无法入账</div>
		<?php foreach($info as $v):?>
		<div style="text-align: center;margin-bottom: 20px">
			<div><img src="<?php echo $v['pic'];?>"></div>
			<div><?php echo $v['money'];?></div>
		</div>
		<?php endforeach;?>

	</div>

<html lang="en">

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
</html>	
<script>
	function shuoming(obj){
  		$(obj).find('img').show();
  	}
   	function guanming(obj){
  		$(obj).hide(300);
  	}
	function applyWithdraw(){
		layer.prompt({
		   title: '请点击识别二维码',
			  
			}, function(){

			});
		}
</script>
</body>
</html>