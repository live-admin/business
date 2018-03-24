<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>记录</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	     <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>js/record.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>css/layout.css">
	</head>
	<body style="background: #fff7f7;">
		<div class="contaner">
			<div class="record">
				<div class="contaner_top">
					<ul>
						<li>用户</li>
						<li>状态</li>
					</ul>
				</div>
				<div class="clear"></div>
				<div class="record_data">
					<!--<div class="record_data_box">
						<div class="record_data_call">188****8888</div>
						<div class="record_data_state">3元抵用券</div>
					</div>-->
				</div>
			</div>
			
			<div class="no_prize hide">
				<p>暂无邀请记录</p>
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/no_por.png"/>
			</div>
			<p class="czy_txt">*本次活动最终解释权归彩之云所有</p>
			<div class="bto_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/bot_img.png"/>
			</div>
		</div>
		<script type="text/javascript">
			var inviteInfo = <?php echo json_encode($inviteInfo);?>;
			
		</script>
	</body>
</html>
