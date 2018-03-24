<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>服务节-产品展示</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
  	 	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>css/layout.css">
	</head>
	<body style="background: #f7f7f7;">
		<div class="contaner">
			<nav>
				<!--<ul>
					<a href="http://www.baidu.com">
						<li><img src="images/pic_icon.png"/></li>
						<li>
							<p>绿色清新小植物绿色清新小植物绿色清新小植物</p>
							<p>装饰卧室的最佳绿植，你值得拥有！</p>
							<p><span>¥</span><span>19.90</span></p>
						</li>
					</a>
				</ul>-->
			</nav>
		</div>
		
		
		<script type="text/javascript">
			var goods = <?php echo json_encode($goods);?>;
			var tuanUrl = <?php echo json_encode($tuanUrl);?>;
			$(document).ready(function(){
//				'<a href="'+tuanUrl+'&pid='+i+'">'+
				for (var i=0; i<goods.length; i++ ) {
					$("nav").append(
						'<ul>'+
							'<a href="'+tuanUrl+'&pid='+goods[i].pid+'">'+
								'<li><img src="'+goods[i].imgName+'"/></li>'+
								'<li>'+
									'<p>'+goods[i].name+'</p>'+
									'<p>'+goods[i].name+'</p>'+
									'<p><span>¥</span><span>'+goods[i].price+'</span></p>'+
								'</li>'+
							'</a>'+
						'</ul>'
					)
				}
			});
			
			
		</script>
		
	</body>
</html>
