<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>购买记录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <link rel="stylesheet" href="/res/touzi/base.css">
    <link rel="stylesheet" href="/res/touzi/style.css">
    <style>
        .flex-cont{
            /*定义为flexbox*/display: -webkit-box;
            display: -webkit-flex;
            display: flex;
        }
        .flex-item{
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            flex: 1;
            width: 0%;
        }
        /*导航*/
        .flex-nav{
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            height: 80px;
            line-height: 44px;
            /*定义子元素垂直居中*/
            -webkit-box-align: center;
            -webkit-align-items: center;
            align-items: center;
            /*子元素沿主轴对齐方式均分*/
            -webkit-box-pack: justify;
            -webkit-justify-content: space-between;
            justify-content: space-between;
            background-color: #000;
            color: #fff;
        }
        .flex-nav .nav-title {
            text-align: center;
            line-height: 1.2;
            width: 0%;
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            flex: 1;
        }

        .flex-nav .nav-title p{
            color:#aaa;
            font-size: 12px;
        }
        /*列表*/
        .flex-simple {
            /*定义子元素垂直居中*/
            -webkit-box-align: center;
            -webkit-align-items: center;
            align-items: center;
            padding: 10px 15px;
        }
        .betRecord-ul li{ padding: 5px;  border:1px solid rgba(85, 85, 85, 0.08); margin: 10px; border-right: 5px solid #3D3459; border-radius: 5px; font-size: 15px;}
        .betRecord-ul li:last-child{ border-bottom: none;}
        .s-tit{
            font-size: 14px;
            font-weight: bold;
            color: #353535;
        }
        .s-tit,.s-desc {
            line-height: 1.5;
            font-size: 13px;
        }
        p.p-money{ color: #3D3459; font-weight: 700;}
    .page a{
     padding: 5px !important;
     margin:5px !important;;
     text-decoration: none;
    }
    </style>
<meta name="poweredby" content="besttool.cn" />
</head>
<body style="background-color: #fff;">
<section>
    <div class="content">
        <ul class="betRecord-ul">
            <div class="lists">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                        <div class="flex-cont flex-simple">
                            <div class="flex-item s-word">
                                <p class="s-tit">开奖号码：<?php echo ($vo["isid"]); ?></p>
                                <p class="s-desc">收益：<?php echo ($vo["yingmoney"]); ?>元</p>
                                <p class="s-desc">我的投注：<?php $arr = array(1 => '1',2=> '2',3=> '3',4=> '4',5=> '5',6=>'6',7=> '大',8=>'小');echo $arr[$vo['buyid']]?></p>
                                <p class="s-desc">投注金额：<?php echo ($vo["money"]); ?>元</p>
                                <p class="s-desc"><?php echo date('Y-m-d H:i',$vo['endtime']);?></p>
                            </div>
                            <p class="p-money"><?php echo ($vo["kid"]); ?>期</p>
                        </div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </ul>
</section>
  <div class="page" style=" padding:10px;margin-bottom: 50px;">
<?php echo ($page); ?>
</div>
<?php if(empty($list)): ?><div style=" padding:10px 0; color:#666;text-align: center;">暂时没有记录</div><?php endif; ?>
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
</body>