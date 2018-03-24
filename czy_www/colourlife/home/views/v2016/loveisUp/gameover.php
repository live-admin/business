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
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>js/gameover.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>css/normalize.css">
	</head>
	<body>
		<div class="gameover">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/gameover_img.png"/>
				<p>1000份奖品近在咫尺，生命不息，游戏精神不衰，加油赚积分吧！</p>
				<p>恭喜本次游戏一共获得积分：<span><?php echo $integ;?></span>分</p>
				<div class="agame_btn">
				<?php if ($chance>0){?>
					<a href="/LoveHook/play">再来一次</a>
				<?php }else{?>
					<a href="javascript:void(0)" class="splay">再来一次</a>
				<?php }?>
				</div>
				<div class="search_btn">
					<a href="javascript:void(0)">查看排名</a>
				</div>
				
			<!--遮罩层-->
			<div class="mask hide"></div>
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
		
		
	</body>
</html>
