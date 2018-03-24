<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>彩管家-引导页</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>js/swiper.min.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/caiGuanJia/');?>css/swiper.min.css" />
    <style type="text/css">
	    html,body{
	    	width:100%;
	    	height:100%;
	    }
	    img {
			display: block;
			max-width: 100%;
		}
		header{
			width:7.5rem;
			height:100%;
			margin:0 auto;
		}
    </style>
</head>
<body>
	
	<header>
		<div class="swiper-container">
		    <div class="swiper-wrapper">
		        <div class="swiper-slide"><img src="<?php echo F::getStaticsUrl('/activity/v2016/caiGuanJia/');?>img/banner01.jpg"/></div>
		        <div class="swiper-slide"><img src="<?php echo F::getStaticsUrl('/activity/v2016/caiGuanJia/');?>img/banner02.jpg"/></div>
		    </div>
		    <div class="swiper-pagination"></div>
		</div>
	</header>
<script>
	//轮播图
	var mySwiper = new Swiper('.swiper-container',{
		loop : false,
		pagination: '.swiper-pagination',
	})
</script>
<?php require_once 'cs.php';echo '< img src="'._cnzzTrackPageView(1261301329).'" width="0" height="0"/>';?>
</body>
</html>

