<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>尊宠女王节</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/women/');?>js/award.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/women/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="contaner">
			
			<div class="contaner_bg">
				<div class="present_bg present_bg_50"></div>
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/women/');?>images/banner.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/women/');?>images/bg2.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/women/');?>images/arrow.png" class="zhizhen"/>
				<div class="start" id="actionBtn">开始抽奖</div>
			</div>
			<div class="contaner_btn">
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/women/');?>images/qiqiu.png"/>
				<p class="share">活动规则</p>
				<p class="record">中奖记录</p>
				<div class="clearfi"></div>
				<div class="conte">如有疑问请联系客服  联系电话：0755-33930303</div>
				
			</div>
		</div>
		
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗-->
		<div class="pop hide">
			<div class="pop_top"></div>
			<div class="pop_bg">
				<div class="pop_txt">
					<p>恭喜您注册成功，</p>
					<p>点击确认参与抽奖！</p>
				</div>
			<div class="pop_btn">确定</div>	
				
			</div>
		</div>
		<script type="text/javascript">
			document.body.onselectstart=document.body.oncontextmenu=function(){ return false;}
			var num=<?php echo json_encode($num);?>;
		</script>
	</body>
</html>
