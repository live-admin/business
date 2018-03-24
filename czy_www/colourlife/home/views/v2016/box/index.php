<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>猴赛雷的宝箱</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>js/index.js"></script> 
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>css/layout.css" />
	<style>
		@font-face {
			font-family:fontstyle;
			src: url('<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>fonts/fontstyle.ttf');
		}
	</style>
</head>
<body>
    <div class="header"></div>
    <div class="container">
        <div class="navigationL">
            <span>活动规则</span>
        </div>
        <div class="boxBlock">
            <div class="box"></div>
            <div class="btn">
                <input type="button" value="开启宝箱" />
            </div>
        </div>
        <div class="navigationR">
            <span>寻宝攻略</span>
            <span>查询宝藏</span>
        </div>

        <div class="footer">
            <div class="spacing01"></div>
            <div class="spacing02"></div>
        </div>
    </div>
    <!--宝箱开箱弹窗-->
    <div class="pop02 hide"></div>
	
	<div class="bg_mask hide"></div>
<script>
	//钻石数组
	var list=<?php echo json_encode($list);?>;
	var isOpen='<?php echo $isOpen;?>';

            
//	<!--活动规则页面跳转-->		
	$(".navigationL span").click(function(){
		window.location.href="/Box/Rule";
		})
//	寻宝攻略/查询宝藏
	$(".navigationR span").eq(0).click(function(){
		window.location.href="/Box/XunBaoWay";
		})
	$(".navigationR span").eq(1).click(function(){
		window.location.href="/Box/SelectPrize";
		})
</script>   
</body>
</html>
