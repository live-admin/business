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
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>js/renQi.js"></script>
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background: #f2f3f4;">
		<div class="contaner">
			<div class="ranking_bar">
				<ul>
				</ul>
			</div>
		</div>
		
		<div class="mask hide"></div>
		
		<div class="change_Pop hide">
			<div class="pop_wrap">
				<div class="line"></div>
				<div class="detail" >
					<p>投票成功！</p>
					<p>邀请好友注册获得更多竞猜机会。</p>
					<a href="javascript:void(0);">立即邀请</a>
				</div>
				<div class="change_Pop_dele">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/dele.png"/>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			var listRen = <?php echo json_encode($listRen);?>;
		</script>
	</body>
</html>
