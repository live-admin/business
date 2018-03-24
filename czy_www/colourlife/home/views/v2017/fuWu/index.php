<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>服务节-首页</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	 	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>js/swiper.min.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>js/index.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>css/layout.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>css/swiper.min.css"/>
	</head>
	<body style="background: #f7f7f7;">
		<div class="contaner">
			<div class="swiper-container">
			    <div class="swiper-wrapper">
			        <div class="swiper-slide" ><a href="http://elh.colourlife.net/"><img src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>images/banner1.jpg"/></a></div>
			        <div class="swiper-slide"><a href="<?php echo $eUrl;?>"><img src="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>images/banner2.jpg"/></a></div>
			    </div>
			    <div class="pp">
			  	  <div class="swiper-pagination"></div>
			    </div>
			</div>
			<aside>居家小能手</aside>
			<section>
				<ul>
					<li>
						<p>空调清洗</p>
					</li>
					<li>
						<p>绿植领养</p>
					</li>
					<li>
						<p>彩优选</p>
					</li>
				</ul>
			</section>
			<figure>生活攻略</figure>
			
			<div class="life_img">
				<ul>
					<li>
						<p>让工作日不再手忙脚乱</p>
					</li>
					<li>
						<p>居家必备，每天都像住新家</p>
					</li>
				</ul>
				<div class="chou_btn hide">抽奖</div>
			</div>
		</div>	
		
		<div class="mask hide"></div>
		<div class="pop hide">
			<div class="pop_top">
				<p class="p050"></p>
			</div>
			<div class="pop_banner">
				<p>家庭空调免费清洗</p>
			</div>
			
			<div class="pop_btn">
				<p style="margin: 0 auto;">立即参与抽奖</p>
			</div>
		</div>
		
		<script type="text/javascript">
			var _flag = <?php echo json_encode($flag);?>;
			var eUrl = <?php echo json_encode($eUrl);?>;
			var cUrl = <?php echo json_encode($cUrl);?>;
			//轮播图
			var mySwiper = new Swiper('.swiper-container',{
				loop : true,
				pagination: '.swiper-pagination',
			});
			setInterval("mySwiper.slideNext()", 3000);
		</script>
	</body>
</html>
