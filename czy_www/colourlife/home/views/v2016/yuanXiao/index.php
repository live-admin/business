<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>元宵节活动</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>js/swiper.min.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>js/smook_flower.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>js/index.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>css/swiper.min.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>css/layout.css" /> 
    <style type="text/css">
    	#cas{
    		width:100%;
    		height:100%;
    		position: fixed;
    		top:0;
    		left:0;
    		z-index:100;
    	}
    	.mask{
    		z-index:1;
    	}
    </style>
</head>
<body>
	<canvas id='cas' style="background-color:rgba(0,0,0,0.5)"> <!--浏览器不支持canvas ;--></canvas>
	<header>
		<div class="swiper-container">
		    <div class="swiper-wrapper">
		        <div class="swiper-slide"><a href="/YuanXiao/Area?type=30"><img src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/banner2.png"/></a></div>
		        <div class="swiper-slide"><a href="/YuanXiao/Area?type=28"><img src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/banner1.png"/></a></div>
		        <div class="swiper-slide"><a href="/YuanXiao/Area?type=22"><img src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/banner3.png"/></a></div>
		    </div>
		    <div class="swiper-pagination"></div>
		</div>
	</header>
	<div class="award_link">
		<a href="/YuanXiao/GetPrize">疯狂大抽奖！GO！</a>
	</div>
	<nav>
		<ul></ul>
	</nav>
	<div class="content">
		<ul>
			<li class="same">
				<p><i></i>爆款TOP</p>
				<span>爆款直降</span>
				<em></em>
			</li>
			<li class="same">
				<p><i></i>大礼包价更低</p>
				<span>低价速囤</span>
				<em></em>
			</li>
			<li class="same">
				<p><i></i>今日疯抢</p>
				<span>1元疯抢中</span>
				<em></em>
			</li>
			 <li>
				<ol>
					<li>
						<p>生鲜区</p>
						<span>急速到家</span>
						<em></em>
					</li>
					<li>
						<p>美酒盛宴</p>
						<span>畅饮嗨翻天</span>
						<em></em>
					</li>
				</ol>
			</li>
			<li>
				<em></em>
				<div>
					<p>2.14浪漫专场</p>
					<span>心动约惠</span>
				</div>

			</li>
			<li>
				<em></em>
				<div>
					<p>满减专场&nbsp;&nbsp;(满99减50)</p>
					<span>狂欢购</span>
				</div>
			</li> 
		</ul>
	</div>
	<!--弹窗-->
	<div class="pop hide">
		<div class="pop_top">
			<p class="p050"></p>
		</div>
		<div class="pop_banner">
			<p class="p_50">恭喜您获得满99减50优惠券！</p>
		</div>
		<div class="pop_p">
			<p>在满减专区购物时请记得使用哟~</p>
			<p>（活动期间无限次使用）</p>
		</div>
		<div class="pop_btn">
			<p style="margin: 0 auto;">确认</p>
		</div>
	</div>
	<!--弹窗end-->
	 <div class="mask hide"></div> 
<script>
	//烟花特效
	var canvas = document.getElementById("cas");	
	var ctx = canvas.getContext("2d");
	canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
	var bigbooms = [];
	
	//弹窗状态/烟花状态 
	var tanCoupons=<?php echo json_encode($tanCoupons);?>;
	//var tanCoupons = true;
	
	setTimeout(function(){
		if(tanCoupons){
			$(".pop").removeClass("hide");
			$(".mask").removeClass("hide");
		}
	},2000)

	//轮播图
	var mySwiper = new Swiper('.swiper-container',{
		loop : true,
		pagination: '.swiper-pagination',
	})
	setInterval("mySwiper.slideNext()", 3000);
	
	//导航栏
	var intro=["全球尖货","吃货狂欢","满汉全席","美丽聚焦","数码清单","智能风暴","家有萌娃","焕新生活"];
	for(var i=1;i<9;i++){
		$("nav ul").append('<li>'+
								'<img src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/'+i+'.png" />'+
								'<span></span>'+
							'</li>'
			);
		$("nav ul li").eq(i-1).find("span").text(intro[i-1]);
	}

	
	

	$("canvas").click(function(){
		$(this).addClass("hide");
	})
		
	//优惠券弹窗按钮
	$(".pop_btn").click(function(){
		$(".mask").addClass("hide");
		$(".pop").addClass("hide");
	})
</script>
</body>
</html>
