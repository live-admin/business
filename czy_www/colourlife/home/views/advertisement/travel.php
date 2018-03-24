<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>温泉养生</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	     <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	     <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/activity/health/'); ?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="conter">
			<header class="header">
				<img src="<?php echo F::getStaticsUrl('/home/activity/health/'); ?>images/banner.png">
			</header>
			<section class="section">
				<a href="javascript:">
					<img src="<?php echo F::getStaticsUrl('/home/activity/health/'); ?>images/banner02.png">
				</a>
				<a href="javascript:">
					<img src="<?php echo F::getStaticsUrl('/home/activity/health/'); ?>images/banner01.png">
				</a>
				<a href="javascript:">
					<img src="<?php echo F::getStaticsUrl('/home/activity/health/'); ?>images/banner03.png">
				</a>
				<a href="javascript:">
					<img src="<?php echo F::getStaticsUrl('/home/activity/health/'); ?>images/banner04.png">
				</a>
				<span></span>
			</section>
			<footer class="footer">
				<img src="<?php echo F::getStaticsUrl('/home/activity/health/'); ?>images/footer.png">
			</footer>
		</div>
		<script>
			$(document).ready(function(){
				var ctgUrl= <?php echo json_encode($urlArr)?>;
				console.log(ctgUrl.hubei);
				$(".section a:eq(0)").attr("href",ctgUrl.hubei);
				$(".section a:eq(1)").attr("href",ctgUrl.huizhou);
				$(".section a:eq(2)").attr("href",ctgUrl.nanxiong);
				$(".section a:eq(3)").attr("href",ctgUrl.zhuhai);
			});
		</script>
	</body>
</html>
