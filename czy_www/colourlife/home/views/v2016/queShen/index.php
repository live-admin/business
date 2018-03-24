<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>雀神竞猜</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>js/index.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="contaner">
			<div class="top_banner"></div>
			<div class="bar">
				<div class="jinji"></div>
				<div class="liucheng"></div>
				<a href="/QueShen/PaiHang">马上开始竞猜</a>
				<div class="pinlun"></div>
				<div class="rule">活动规则</div>
				
				<div class="no_pinglun">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/no_pinlun.png"/>
				</div>
				
				<div class="nav hide">
				</div>
				
				<div class="write">评论：
					<input type="text" name="write" value="" class="discuss_val "maxlength="35">
                    <a href="javascript:void(0);">发送</a>
                </div>
			</div>
		</div>
		
		<script type="text/javascript">
		var commentDetail = <?php echo json_encode($commentDetail);?>;
			
		</script>
	</body>
</html>
