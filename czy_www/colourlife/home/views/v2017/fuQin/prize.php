<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>中奖记录</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>css/layout.css">
	</head>
	<body style="background: url(<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/recode.jpg) no-repeat;background-size: 100% ;">
		<div class="record">
			<div class="contaner_top">
				<ul>
					<li>时间</li>
					<li>奖品名称</li>
				</ul>
			</div>
			<div class="clear"></div>
			<div class="record_data">
				<!--<div class="record_data_box">
					<div class="record_data_call">2016-05-01 15:35:18</div>
					<div class="record_data_state">3元抵用券</div>
				</div>-->
			</div>
		</div>
		
		<script type="text/javascript">
			var prizeMobileArr =<?php echo json_encode($prizeMobileArr);?>;
			$(document).ready(function(){
				for (var i=0; i<prizeMobileArr.length; i++) {
					$(".record_data").append(
						'<div class="record_data_box">'+
							'<div class="record_data_call">'+prizeMobileArr[i].create_time+'</div>'+
							'<div class="record_data_state">'+prizeMobileArr[i].prize_name+'</div>'+
						'</div>'
					);
				}
			});
		</script>
		
	</body>
</html>
