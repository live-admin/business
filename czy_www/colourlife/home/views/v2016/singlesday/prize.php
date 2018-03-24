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
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>js/prize.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="contaner">
			<div class="prize_bg">
				<div class="bar">
					<div class="title_box">
						<div class="title_box_time">时间</div>
						<div class="title_box_name">签文</div>
						<div class="title_box_prize">奖品</div>
					</div>
					
					<div class="cont"></div>	
				</div>
				
				<!--无奖品展示-->
				<div class="no_prize hide">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>images/no_prize_icon.png"/>
					<p>暂无获奖记录</p>
				</div>
				
			</div>
		</div>
		
		<script type="text/javascript">
			var data=<?php echo $data ?>;
		</script>
	</body>
</html>