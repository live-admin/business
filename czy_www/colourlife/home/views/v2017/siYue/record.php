<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>受邀记录</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/siYue/');?>css/layout.css">
	</head>
	<body style="background: #f7f7f7;">
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
					<div class="record_data_call">15845692562</div>
					<div class="record_data_state">已获得优惠券</div>
				</div>-->
			</div>
		</div>
		<div class="record_pic">
			<img src="<?php echo F::getStaticsUrl('/activity/v2017/siYue/');?>images/bot_icon.jpg"/>
		</div>
		
		
		
		<script type="text/javascript">
			var inviteInfo = <?php echo json_encode($inviteInfo);?>;
			
			$(document).ready(function(){
				for (var i=0; i<inviteInfo.length; i++) {
					var phoneList = [];
					phoneList = (inviteInfo[i]['mobile'].substring(0,3)+"****"+(inviteInfo[i]['mobile'].substring(7,11)));
					$(".record_data").append(
						'<div class="record_data_box">'+
							'<div class="record_data_call">'+phoneList+'</div>'+
							'<div class="record_data_state">'+inviteInfo[i].status+'</div>'+
						'</div>'
					);
				}
			});
		</script>
	</body>
</html>
