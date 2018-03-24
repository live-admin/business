<!DOCTYPE html>
<html style="width: 100%;height: 100%">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>选择奖品</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>js/chooseprize.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>css/layout.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="width: 100%; height: 100%;background: #f2f3f4;">
		<div id="selectPage">
			<p>以下奖品任选其一：</p>
			<div></div>
		</div>
		<div class="mask hide"></div>
		<div class="pop hide">
			<div class="pop_header">
				<p>恭喜您获得佳能（Canon）EOS 700D单反双头套机</p>
			</div>
			<div class="pop_con">
				<p>奖品已领取成功，快去填写领奖地址吧！</p>
			</div>
			<div class="pop_footer">
				<a href="javascript:void(0);">
					领奖地址
				</a>
			</div>
			<div class="close"></div>
		</div>
		<script>
			var prizeList = <?php echo $prizeList ?>;
			var level_id = <?php echo $level_id ?>;
			var time = <?php echo $time ?>;
		</script>
	</body>
</html>
