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
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>js/award.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>css/chou.css">
	</head>
	<body>
		<div class="contaner">
	   		<div class="mar">
		   		<marquee id="list" direction="up" behavior="scroll"  scrolldelay="220" >
				<!--<p><span>恭喜</span><span>136****8698</span>用户获得<span>555</span></p>
				<p><span>恭喜</span><span>136****8698</span>用户获得<span>88</span></p>
				<p><span>恭喜</span><span>136****8698</span>用户获得<span>88</span></p>
				<p><span>恭喜</span><span>136****8698</span>用户获得<span>88</span></p>
				<p><span>恭喜</span><span>136****8698</span>用户获得<span>88</span></p>-->
				</marquee>
			</div>
			
			<div class="contaner_chou">
				<div class="contaner_bg">
					<div class="present_bg present_bg_50"></div>
					<p>今日剩余抽奖次数：<span class="cishu">1</span>次</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/bg2.jpg"/>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/arrow.png" class="zhizhen"/>
					<div class="start" id="actionBtn">开始抽奖</div>
				</div>
				<div class="contaner_btn">
					<p class="share">中奖记录</p>
					<div class="clearfi"></div>
				</div>
				<div class="contaner_rule">
					<p class="rule_x">活动规则</p>
					<div class="contaner_p">
						<p>活动时间：6月9日至6月25日  </p>
						<p>1.游戏中所得奖励，仅限本次活动中使用； </p>
						<p>2.下单完成后，若用户申请退款，只返还减去奖励以后的金额；</p>
						<p>3.若因系统问题造成优惠券延迟到账，请联系客服人员；</p>
						<p>4.活动期间若出现作弊行为，则取消该帐号活动资格，情节严重者将给予封号处理。</p>
						<p>本活动最终解释权归彩之云所有。</p>
					</div>
					<div class="line"></div>
				</div>
				<div class="bto_img">
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/bot_img.png"/>
				</div>
			</div>
		</div>
		<div class="mask hide"></div>
		<div class="pop_prize hide">
			<div class="quan_pic"></div>
			<div class="quan_name">
				<p>恭喜您获得零度果品荔枝</p>
				<p>3元优惠券1张</p>
			</div>
			<div class="quan_detail">
				<p>除超值特惠全场满3元即可使用</p>
			</div>
			<div class="sure_btn">
				<p>确定</p>
			</div>
			<div class="close"></div>
		</div>
		
		<div class="pop_no hide">
			<div class="no_quan_pic"></div>
			<div class="no_quan_name">
				<p>很遗憾，</p>
				<p>您没有抽中呢~</p>
				<p>您没有抽中呢</p>
			</div>
			<div class="sure_btn">确定</div>
			<div class="close"></div>
		</div>
		<script type="text/javascript">
			var leftChance = "<?php echo $leftChance;?>";	
			var _tip = <?php echo json_encode($tip);?>;	
			
		</script>
		<!--弹窗end-->
	</body>
</html>

