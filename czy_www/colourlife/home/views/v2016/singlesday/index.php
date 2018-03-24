<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>11月电商活动</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>js/index.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background: #92483F;">
		<div class="contaner">
			<div class="tips">11.11购物狂欢盛典将在11月4号正式开放，敬请期待</div>
			<div class="contaner_bg">
				<div class="three_btn">
					<div class="rule_btn">活动规则</div>
					<div class="prize_btn">我的奖励</div>
					<div class="qian_btn">签到</div>
				</div>
				<div class="change">你还有<span></span>次抽奖机会</div>
				<div class="qian"><img src="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>images/qian.png"/></div>
				<div class="chou_btn">抽 签</div>
			</div>
		</div>
		
		<!--遮罩层-->
		<div class="mask hide"></div> 
		
		<!--抽签弹窗--财 -->
		<div class="cai_Pop hide">
			<div class="quan">
				<p></p>
				<p>有效期2016.11.4~2016.11.15</p>
			</div>
			<div class="boom">
				<p>彩富用户有一次换签机会</p>
				<div class="look">去看看</div>
				<div class="change_btn">换签</div>
				<div class="clear"></div>
			</div>
			<div class="dele_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>images/dele.png"/>
			</div>
		</div>
		
		<!--抽签弹窗--缘 -->
		<div class="yuan_pop hide">
			<div class="yuan">
				<p>3彩饭票</p>
			</div>
			<div class="boom">
				<p>彩富用户有一次换签机会</p>
				<div class="look">去看看</div>
				<div class="change_btn">换签</div>
				<div class="clear"></div>
			</div>
			<div class="dele_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>images/dele.png"/>
			</div>
		</div>
			
		<!--抽签弹窗--升 -->
		<div class="sheng_Pop hide">
			<div class="quan sheng_quan">
				<p>1元换购码</p>
			</div>
			<div class="boom">
				<p>彩富用户有一次换签机会</p>
				<div class="look">去看看</div>
				<div class="change_btn">换签</div>
				<div class="clear"></div>
			</div>
			<div class="dele_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>images/dele.png"/>
			</div>
		</div>
		
		<!--弹窗共用-->
		<div class="change_Pop hide">
			<div class="pop_wrap">
				<div class="line"></div>
				<div class="detail">
					<p>抽奖机会已用完，</p>
					<p>邀请好友注册，获得更多抽签机会！</p>
					<p class="qian_suc hide">获得一次抽奖的机会</p>
					<a href="javascript:void(0);">立即邀请</a>
				</div>
				<div class="change_Pop_dele">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>images/dele.png"/>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			var data = <?php echo $data;?>;
			var isShow = <?php echo $isShow;?>;
		</script>
	</body>
</html>
