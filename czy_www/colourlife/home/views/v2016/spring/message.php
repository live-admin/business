<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>春节活动</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>js/message.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="content">
			<div class="deng_bg">
				<ul>
					<li></li>
					<li>
						<p></p>
						<p class="animal"></p>
					</li>
				</ul>
			</div>
			<div class="msg_bar">
			</div>
			<div class="clour_bg">
				<div class="qiang_btn_meg">首页</div>
			</div>
		</div>	
			
		<script type="text/javascript">
		var message = <?php echo json_encode($message)?>;
		var customer_info = <?php echo json_encode($customer_info)?>;
		var type = <?php echo json_encode($type)?>;
		var img_url = "<?php echo F::getStaticsUrl('/activity/v2016/spring/images');?>";
		</script>
		
	</body>
</html>
