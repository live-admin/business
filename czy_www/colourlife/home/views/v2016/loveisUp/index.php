<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>爱就去勾搭</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>js/main.js"></script>
    	<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/warmPurse/js/ShareSDK.js');?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>css/normalize.css">
	</head>
	<body>
		<div class="contaners">
			<div class="contaners_bg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/bg01.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/bg02.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/bg03.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/bg04.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/bg05.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/bg06.jpg"/>
			</div>
			
			<div class="paiming_btn">
				<p>排名榜</p>
			</div>
			
			<div class="staregame_btn">
				<a href="javascript:void(0)">开始游戏</a>
			</div>
			<div class="share_btn">
				<a href="javascript:void(0)">分享</a>
			</div>
			<div class="btn_rule">
				<a href="javascript:void(0)">活动规则</a>
			</div>
			
		<!--遮罩层-->
		<div class="mask hide"></div>
		<div class="shareMask hide"></div>
		
		</div>
		
		<!--排名弹窗-->
		<div class="Pop_paiming hide">
			<div class="paiming_txt">
				
				<div class="clear"></div>
			</div>
			<div class="paiming_tips">
				<p>ps：游戏获奖名单将会在游戏结束后公布，如有积分相同者按获得该总积分时间先后排名</p>
			</div>
		</div>
		
		<!--游戏次数不足弹窗-->
		<div class="Pop_NotPlay hide">
			<div class="Pop_NotPlay_txt">
				<p>陛下！您今天的游戏机会已用完！ 邀请邻国好友注册 可以获得一次游戏机会哦！</p>
			</div>
			<div class="Pop_NotPlay_tips">
			    <a href="javascript:void(0)">邀请注册</a>
			</div>
		</div>
			<input style="display: none" id="vserion" />
		<script>
			var imgUrl="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>";
			var url="<?php echo F::getHomeUrl('/LoveHook/Share?reUrl='.$url); ?>";
		</script>
	</body>
</html>
