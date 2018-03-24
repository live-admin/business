<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>星座专题活动-购买页</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/constellation/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="container">
			<div class="good_wrap"></div>	
		</div>
		
		<script>
			var shangChengUrl = "<?php echo $shangChengUrl;?>";
			var goods = <?php echo $goods?>;
			getNum(goods.length);	
			
			function getNum(goodsList){
				for(var i = 0;i < goodsList; i++){
					$(".good_wrap").append(
					'<figure>'+
					'<img src="'+goods[i].img_name+'">'+
					'<figcaption>'+
					'<p>'+goods[i].name+'</p>'+	
					'<span><i>¥&nbsp;</i><em class="curPrice">'+goods[i].customer_price+'</em></span>&nbsp;&nbsp;'+	
					'<del><i>¥&nbsp;</i><em class="oriPrice">'+goods[i].market_price+'</em></del>'+
					'</figcaption>'+
					'<a href="javascript:;">立即抢购</a>'+
					'</figure>'
					);				
				}
			}
			//跳转商品
			$(".good_wrap").delegate("figure","click",function(){
				var index = $(this).index();
				window.location.href = shangChengUrl+"&pid="+goods[index].id;
			});
		</script>
	</body>
</html>


<!--			
	<figure>
		<img src="images/goods_pic01.png">
		<figcaption>
			<p>SONY 4K数码摄像机  FDR-AXP55</p>
			<span><i>¥&nbsp;</i><em class="curPrice">5480.00</em></span>&nbsp;&nbsp;
			<del><i>¥&nbsp;</i><em class="oriPrice">6880.00</em></del>
		</figcaption>
		<a href="javascript:;">立即抢购</a>
	</figure>
-->