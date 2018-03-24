<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>11月电商特惠专场</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>js/goods_index.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/singlesday/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
</head>
<body>
	<header>
		<div class="banner"></div>
		
		<div class="goods_banner">
			<div class="left"></div>
			<div class="right">
				<div></div>
				<div></div>
			</div>
		</div>
	</header>
	
	<div class="content">
		<ul class="title">
			<li class="active">粮油副食</li>
			<li>特惠饮品</li>
			<li>休闲零食</li>
			<li>新鲜水果</li>
		</ul>
		<ol class="good_list">
			<li class="item rich"></li>
			<li class="item drink hide"></li>
			<li class="item food hide"></li>
			<li class="item fruit hide"></li>
				
		</ol>
	</div>
	
	<script>
	var goods=<?php echo $goods ?>;
	var url=<?php echo $url ?>;		
	</script>
</body>
</html>
 