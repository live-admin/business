<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>商品详情</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>js/swiper.min.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>css/swiper.min.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>css/layout.css" /> 
</head>
<body>
	<header class="hide">
		<div class="swiper-container">
		    <div class="swiper-wrapper">
		        <div class="swiper-slide first"><img src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/inner_banner1.jpg"/></div>
		        <div class="swiper-slide second"><img src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/inner_banner2.jpg"/></div>
		    </div>
		    <div class="swiper-pagination"></div>
		</div>
	</header>
	<div class="nav">
		<ul></ul>
	</div>
	
	<script>
	var url =<?php echo json_encode($url);?>;
	var goods =<?php echo json_encode($goods);?>;
	//商品内容
	for(var k in goods){
		if(k == 29){
			$("header").removeClass("hide");
		}
		if(k == 30){
			//满返
			for (var i=0;i<goods[k].length;i++) {
				$(".nav ul").append(
					'<li>'+
						'<div class="xianshi"></div>'+
						'<img src="'+goods[k][i].img_name+'"/>'+
						'<p>'+goods[k][i].name+'</p>'+
						'<p><span>¥&nbsp;<span>'+goods[k][i].customer_price+'</span></span></p>'+
					'</li>'
				);
			}
		}else{
			for(var i=0;i<goods[k].length;i++){
				$(".nav ul").append(
					'<li>'+
						'<img src="'+goods[k][i].img_name+'"/>'+
						'<p>'+goods[k][i].name+'</p>'+
						'<p><span>¥&nbsp;<span>'+goods[k][i].customer_price+'</span></span></p>'+
					'</li>'
				);
			}	
		}
	
		$(".nav ul li").click(function(){
			var _index = $(this).index();
			switch(goods[k][_index].shop_type){//0彩特供,1京东
				case '1':                  
					window.location.href=url.jdUrl+"&pid="+goods[k][_index].gid;
					break;
				case '0':
					window.location.href=url.tuanUrl+"&pid="+goods[k][_index].pid;
					break;
			}
		});		
	}
	
	//轮播图
	var mySwiper = new Swiper('.swiper-container',{
		loop : true,
		pagination: '.swiper-pagination',
	})
	setInterval("mySwiper.slideNext()", 3000);
	
	//轮播跳转
	$(".first").click(function(){
		window.location.href=url['xiUrl'];
	});
	$(".second").click(function(){
		window.location.href=url['huaUrl'];
	});
	
	</script>
</body>
</html>