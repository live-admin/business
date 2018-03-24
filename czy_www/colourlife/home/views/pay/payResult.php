<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>出货中...</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/pay/');?>js/flexible.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/pay/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/pay/');?>css/normalize.css">
	</head>
	<body>
		<div class="container">
			<section class="icon"><img src="<?php echo F::getStaticsUrl('/home/pay/');?>images/icon.png"></section>
			<div class="site">
				<p><span>订单</span><span><?php echo $sn; ?></span></p>
				<p><span>价格</span><span style="color:#FA7828"><?php echo $amount; ?></span></p>
			</div>
		</div>
		<div class="fixed">
			<ul>
				<li><input type="text" placeholder="没有出货？我要投诉、维修"></li>
				<li></li>
				<li><p class="p_img"><img src="<?php echo F::getStaticsUrl('/home/pay/');?>images/phone.png"</p><p class="p_tel"><a href="tel:4008893893">4008893893</a></p></li>
			</ul>
			
		</div>
		
	</body>
</html>
