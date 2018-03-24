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
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>js/share.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
</head>
	<body>
		<div class="conter">
			<div class="banner">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>images/banner.png">
			</div>
			<div class="site">
				<div class="site_ban">
					<div class="site_ban_top">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>images/tank.png">
					</div>
					<div class="site_ban_buttom01">
						<a href="javascript:;">点亮宝石</a>
					</div>
					<div class="site_ban_buttom02">
						<a href="javascript:;">我也要参加</a>
					</div>
					<div class="site_ban_bottom">
						<!--<div class="site_ban_bottom01">
							<div class="diamond_b"></div>
					        <div class="mask hide"></div>
					        <p class="star hide">20</p>
						</div>
						<div class="site_ban_bottom01">
							<div class="diamond_b"></div>
							<div class="mask"></div>
							<p class="star hide">20</p>
						</div>-->
					</div>
				</div>
				<!--<div class="site_fotter">
					<p>活动奖品</p>
				</div>-->
			</div>
		</div>
		<div class="rule">
			<a href="javascript:;">
				活动规则
			</a>
		</div>
		<!--<div class="top"></div>-->
		<!--宝石开始-->
		<div class="pop01 hide">
			<img class="blue" src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>images/blue_gems.png">
			<img class="green" src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>images/qing_gems.png">
		</div>
		<!--宝石结束-->
		<!--已经点亮了开始-->
		<div class="pop02 hide">
			<img  style="width: 1.0rem;height: 1.0rem;display: block;margin: 0.45rem auto 0;"src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>images/smile.png">
			<div class="pop02_site pop02_site_other">
				<p>今天已经给你的好友点亮过了</p>
				<p>明天再来吧~</p>
			</div>
		</div>
		<!--已经点亮了结束-->
		<div class="bg_mask hide"></div>
		<script>
			var blue=<?php echo $list[4]['num'];?>;
			var green=<?php echo $list[5]['num'];?>;
			var sd_id=<?php echo $sd_id;?>
		</script>
	</body>
</html>

