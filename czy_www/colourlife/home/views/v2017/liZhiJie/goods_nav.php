<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>荔枝</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>css/layout.css">
	</head>
	<body style="background: #fff7f7;">
		<div class="contaner">
			<div class="banenr_one"></div>
			<nav>
				<p>众人识得荔枝都是在杜牧诗句中：一骑红尘妃子笑，无</p>
				<p>人知是荔枝来。</p>
				<p>自此荔枝的甘甜美味就深深印在吃货的脑海里。</p>
				<p>又是一年荔枝季，你准备好了吗？</p>
				<p>要想吃到最美味的荔枝，就要知道各种荔枝上市时间</p>
				<p>今天就给你科普，带你吃。</p>
				
				<div class="_bar">
					<!--one-->
					<div class="to_intro_two">
						<p class="other_p">妃子笑</p>
						<p>妃子笑又名落塘蒲，是广东著名品种，在每年的6月上旬</p>
						<p>成熟。传说当年唐明皇为搏杨贵妃一笑，千里送的荔枝就</p>
						<p>是这样得名的。</p>
						<p>近圆形或倒卵形，肩一边高，一边平阔</p>
						<p>皮淡红色，花枝细小，果子大且皮薄肉厚，清甜多汁，</p>
						<p>种子较小。</p>
						<img src=""/>
						<ul>
							<li>
								<p>¥239.00</p>
								<p>膳魔师（THERMOS）高真空不锈钢 时尚保温/保冷水杯</p>
							</li>
							<li><a href="javascript:void(0);" class="one">立即购买</a></li>
						</ul>
					</div>
					<!--two-->
					<div class="to_intro_two">
						<p class="other_p" style="border-top: 1px solid #ffe2e2;">桂味荔枝</p>
						<p>桂味荔枝，因有桂花味而得名</p>
						<p>桂味荔枝是优质的中熟品种，也是重要的出口商品水果，</p>
						<p>果实品质极佳，以细核、肉质爽脆、清甜、有桂花味</p>
						<p>6月下旬成熟上市。</p>
						<img src=""/>
						<ul>
							<li>
								<p>¥239.00</p>
								<p>膳魔师（THERMOS）高真空不锈钢 时尚保温/保冷水杯</p>
							</li>
							<li><a href="javascript:void(0);" class="two">立即购买</a></li>
						</ul>
					</div>
					<!--three-->
					<div class="to_intro_two">
						<p class="other_p" style="border-top: 1px solid #ffe2e2;">糯米糍荔枝</p>
						<p>糯米糍，又称米枝，古称水晶丸</p>
						<p>果形较大，色泽鲜红间蜡黄，果皮棘感不明显，果肉乳白</p>
						<p>色、半透明、丰厚，口感嫩滑，味极清甜，核瘦小，自然</p>
						<p>糖分高；</p>
						<p>6月下旬成熟上市。</p>
						<img src=""/>
						<ul>
							<li>
								<p>¥239.00</p>
								<p>膳魔师（THERMOS）高真空不锈钢 时尚保温/保冷水杯</p>
							</li>
							<li><a href="javascript:void(0);" class="three">立即购买</a></li>
						</ul>
					</div>
				</div>
				<div class="next_txt">荔枝不仅是荔枝，还有这么多品种的荔枝！</div>
				<div class="next_txt">你最爱吃哪一种？荔枝季 开吃吧！</div>
			</nav>
		</div>
		<script type="text/javascript">
			var goods = <?php echo json_encode($goods);?>;
			var fanUrl = <?php echo json_encode($fanUrl);?>;
			$(document).ready(function(){
				$(".one").click(function(){
					location.href = fanUrl+'&gid='+goods[0].pid;
				});
				$(".two").click(function(){
					location.href = fanUrl+'&gid='+goods[1].pid;
				});
				$(".three").click(function(){
					location.href = fanUrl+'&gid='+goods[2].pid;
				});
				
				$(".to_intro_two:eq(0) ul li p:eq(1)").text(goods[0].name);
				$(".to_intro_two:eq(0)>img").attr("src",goods[0]['imgName']);
				$(".to_intro_two:eq(0) ul li p:eq(0)").text("¥"+goods[0].price);
				
				$(".to_intro_two:eq(1) ul li p:eq(1)").text(goods[1].name);
				$(".to_intro_two:eq(1)>img").attr("src",goods[1]['imgName']);
				$(".to_intro_two:eq(1) ul li p:eq(0)").text("¥"+goods[1].price);
				
				$(".to_intro_two:eq(2) ul li p:eq(1)").text(goods[2].name);
				$(".to_intro_two:eq(2)>img").attr("src",goods[2]['imgName']);
				$(".to_intro_two:eq(2) ul li p:eq(0)").text("¥"+goods[2].price);
				
			});
			
		</script>
			
	</body>
</html>
