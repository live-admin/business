<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>疯狂大抽奖</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	 	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>js/award.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>css/layout.css">
	</head>
	<body>
		<div class="contaner_chou">
			<div class="contaner_bg">
				<div class="present_bg present_bg_50"></div>
				<p>剩余抽奖次数：<span class="cishu"><?php echo json_encode($leftChance);?></span>次</p>
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>images/bg2.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>images/arrow.png" class="zhizhen"/>
				<div class="start" id="actionBtn">开始抽奖</div>
			</div>
			<div class="contaner_btn">
				<p class="share">中奖记录</p>
				<div class="clearfi"></div>
			</div>
			<div class="contaner_rule">
				<h4>活动规则</h4>
				<div class="contaner_p">
					<p>1.活动时间：2017年4月24日－2017年5月7日;</p>
					<p>2.每个ID每天拥有3次抽奖机会；</p>
					<p>3.所抽到的奖品需到活动相关页面进行兑换；</p>
					<p>4.若部分清洁服务不在可服务范围内，将以同等金额的商品代替；</p>
					<p>5.活动结束后所有奖品将停止兑换；</p>
					<p>6.下单完成后，若用户申请退款，只返还减去奖励以后的金额；</p>
					<p>7.若因系统问题造成优惠券延迟到账，请联系客服人员；</p>
					<p>8.活动期间若出现作弊行为，则取消该帐号活动资格，情节严重者将给予封号处理。</p>
					<p>本活动最终解释权归彩之云所有。</p>
				</div>
				<div class="line"></div>
			</div>
		</div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗-->
		<div class="pop hide">
			<div class="pop_top">
				<p class="p00"></p>
			</div>
			<div class="pop_banner">
				<p>没有中奖哦！再接再厉！</p>
			</div>
			<div class="pop_btn">
				<p>确定</p>
			</div>
		</div>
		<!--弹窗end-->
	</body>
</html>

