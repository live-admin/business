<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>荔枝节</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>js/swiper.min.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>js/index.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>css/swiper.min.css"/>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>css/layout.css">
	</head>
	<body style="background: #f8f4ef;">
		<div class="conten">
			<div class="banenr_top"></div>
			<div class="ban_box">
				<div class="box_left">
					<p>9.9 限时秒杀</p>
					<p>每天10:00/16:00</p>
				</div>
				<div class="box_right">
					<p>邀请好友</p>
					<p>邀请购买返饭票</p>
				</div>
			</div>
			
			<div class="swiper-container">
			    <div class="swiper-wrapper">
			    	<div class="swiper-slide one_ba"><img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/banner_one.jpg"/></div>
			    	<div class="swiper-slide two_ba"><img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/banner_five.png"/></div>
			    	<div class="swiper-slide three_ba"><img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/banner_four.png"/></div>
			        <div class="swiper-slide four_ba"><img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/banner_two.jpg"/></div>
			        <div class="swiper-slide five_ba"><img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/banner_three.jpg"/></div>
			    </div>
			  	  <div class="swiper-pagination"></div>
			</div>
			
			<div class="nav">
				<!--<a href="javascript:void(0);">
					<div class="nav_box">
						<div class="nav_box_left">
							<div class="time_bg"></div>
							<img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/icon_img.png"/>
						</div>
						<div class="nav_box_right">
							<p>新鲜水果泰国进口荔枝</p>
							<p>果粒饱满 皮薄肉厚</p>
							<p><span>¥</span><span>99</span><span>.00</span><span>¥99.00</span></p>
						</div>
					</div>		
				</a>
			</div>		-->
		</div>	
		
		<script type="text/javascript">
			var goods = <?php echo json_encode($goods);?>;
			var fanUrl = <?php echo json_encode($fanUrl);?>;
			var _flag = 0;
				//轮播图
				var mySwiper = new Swiper('.swiper-container',{
					loop : true,
					pagination: '.swiper-pagination',
				});
				setInterval("mySwiper.slideNext()", 3000);
		</script>
		
	</body>
</html>
