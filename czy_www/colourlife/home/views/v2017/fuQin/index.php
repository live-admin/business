<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>父亲节</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	  	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>js/swiper.min.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>js/index.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>css/layout.css" /> 
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>css/swiper.min.css" />
	</head>
	<body style="background: #f7f7f7;">
		<div class="contaner">
			<div class="swiper-container">
			    <div class="swiper-wrapper">
			        <div class="swiper-slide"><img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/banner1.jpg"/></div>
			    </div>
			    <div class="pp">
			  	  <div class="swiper-pagination"></div>
			    </div>
			</div>
	 	  	<article>
	 	  		<aside>
	 	  			<ul>
	 	  				<li>
	 	  					<p>#发现好货#</p>
	 	  					<p>爆款推荐</p>
	 	  				</li>
	 	  				<li>
	 	  					<p>#抢福袋#</p>
	 	  					<p>千万豪礼送祝福</p>
	 	  				</li>
	 	  				<div class="clear"></div>
	 	  				
	 	  				<li>
	 	  					<p><span>居家必备</span></p>
	 	  					<p>良心推荐</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/one_bg.png"/>
	 	  				</li>
	 	  				<li>
	 	  					<p><span>送礼佳品</span></p>
	 	  					<p>独享尊贵</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/two_bg.png"/>
	 	  				</li>
	 	  				<div class="clear"></div>
	 	  				<li>
	 	  					<p><span>吃货必备</span></p>
	 	  					<p>口碑推荐</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/three_bg.png"/>
	 	  				</li>
	 	  				<li>
	 	  					<p><span>热销机型</span></p>
	 	  					<p>惊喜直降</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/four_bg.png"/>
	 	  				</li>
	 	  				<div class="clear"></div>
	 	  			</ul>
	 	  		</aside>
	 	  		<div class="two_other">
	 	  			<h6>为爱精选·精明持家</h6>
	 	  			<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/icon.png"/>
	 	  			<ul>
	 	  				<li>
	 	  					<p><span>智慧生活</span></p>
	 	  					<p>智能家电</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/five_bg.png"/>
	 	  				</li>
	 	  				<li>
	 	  					<p><span>彩食惠</span></p>
	 	  					<p>优选小龙虾</p>
	 	  					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/six_bg.png"/>
	 	  				</li>
	 	  			</ul>
	 	  		</div>
	 	  		<section>
	 	  			<h6>爸爸在哪·家就在哪</h6>
	 	  			<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/icon_2.png"/>
	 	  			<ul>
	 	  				<li>好房热荐</li>
	 	  				<li>为家理财</li>
	 	  				<li>为家绿化</li>
	 	  			</ul>
	 	  		</section>
				<footer>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/bot_1.jpg"/>
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/fuQin/');?>images/bot_2.jpg"/>
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
//					loop : true,
					pagination: '.swiper-pagination',
				});
				setInterval("mySwiper.slideNext()", 3000);
		</script>
		
	</body>
</html>
