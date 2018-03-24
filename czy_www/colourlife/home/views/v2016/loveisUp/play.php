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
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>css/draw.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>css/normalize.css">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>js/play.js"></script>
	</head>
	<body style="background: #70C5FE;">
		<div class="play">
			<div class="play_top" >
				<p ontouchstart = "return false;">0</p>
			</div>
			
			<div class="bar">
				<ul>
					<li>剩 <span ontouchstart = "return false;">0</span> 次游戏</li>
					<li>本轮积分：<span ontouchstart = "return false;">0分</span></li>
				</ul>
			</div>
			<div class="clear"></div>
		
			<div class="colour_one">
				<img ontouchstart = "event.stopPropagation();return false;" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/yun_big.png"/>
			</div>
			<div class="colour_two">
				<img ontouchstart = "return false;" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/yun_big.png"/>
			</div>
			<div class="colour_three">
				<img ontouchstart = "return false;" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/yun_big.png"/>
			</div>
			
			<div class="play_img">
				<div class="play_move">
					<div class="gou" ontouchstart = "return false;"></div>
					<div class="gouzi" ontouchstart = "return false;"></div>
				</div>
				<img ontouchstart = "return false;" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/rule_bg01.jpg"/>
				<div  ontouchstart = "return false;" class="cartoon"></div>
			</div>
					
			<div id="goods" class="good_one">
				<span ontouchstart = "return false;">+10s</span>
			</div>
			
			<div class="reference" ontouchstart = "return false;"></div>
		</div>
		<div id="reciprocal_page">
			<div id="reciprocal" ontouchstart = "return false;"></div>
			<p ontouchstart = "return false;">点击屏幕，发射吊钩</p>
			<p ontouchstart = "return false;">积分越高，排名越靠前，奖品就会越丰厚哦！</p>
		</div>
		<script>
			var tiems = <?php echo $chance;?>;
		</script>
</body>
</html>
