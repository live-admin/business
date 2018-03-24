<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>母亲节</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
     	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>js/swiper.min.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>js/index.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>css/layout.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>css/swiper.min.css"/>
	</head>
	<body style="background: #f7f7f7;">
		<div class="contaner">
			<div class="swiper-container">
			    <div class="swiper-wrapper">
			        <div class="swiper-slide"><img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/banner1.jpg"/></div>
			    </div>
			    <div class="pp">
			  	  <div class="swiper-pagination"></div>
			    </div>
			</div>
	 	  	<article>
	 	  		<aside>
	 	  			<ul>
	 	  				<li>
	 	  					<p>#晒出母亲感动时刻的照片#</p>
	 	  					<p>赢2000元现金券</p>
	 	  				</li>
	 	  				<li>
	 	  					<p>#抢福袋#</p>
	 	  					<p>千万豪礼送祝福</p>
	 	  				</li>
	 	  				<div class="clear"></div>
	 	  				
	 	  				<li>
	 	  					<p><span>发现好货</span></p>
	 	  					<p>爆款推荐</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/one_bg.png"/>
	 	  				</li>
	 	  				<li>
	 	  					<p><span>手机数码</span></p>
	 	  					<p>惊喜直降</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/two_bg.png"/>
	 	  				</li>
	 	  				<div class="clear"></div>
	 	  				<li>
	 	  					<p><span>口碑零食</span></p>
	 	  					<p>吃货必备</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/three_bg.png"/>
	 	  				</li>
	 	  				<li>
	 	  					<p><span>纸品狂欢</span></p>
	 	  					<p>韧性品质</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/four_bg.png"/>
	 	  				</li>
	 	  				<div class="clear"></div>
	 	  			</ul>
	 	  		</aside>
	 	  		<section>
	 	  			<h6>为爱精选·心意礼物</h6>
	 	  			<img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/icon.png"/>
	 	  			<ul>
	 	  				<li>花礼网</li>
	 	  				<li>千禧珠宝</li>
	 	  				<li>环球精选</li>
	 	  			</ul>
	 	  		</section>
				<footer>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/bot_1.jpg"/>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/bot_2.jpg"/>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/bot_3.jpg"/>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/bot_4.jpg"/>
				</footer>
	 	  	</article>
		</div>
		
		<div class="mask hide"></div>
		<div class="pop_no hide">
			<div class="index_pop"></div>
			<div class="no_quan_name">
				<p>拆福袋，拿福利</p>
				<p>别再犹豫啦~</p>
			</div>
			<div class="sure_btn">确定</div>
			<div class="close"></div>
		</div>
		
		
		
		<script type="text/javascript">
			var _flag =<?php echo json_encode($flag);?>;
			var url =<?php echo json_encode($url);?>;
				//轮播图
				var mySwiper = new Swiper('.swiper-container',{
					pagination: '.swiper-pagination',
				});
				setInterval("mySwiper.slideNext()", 3000);
		</script>
		
	</body>
</html>
