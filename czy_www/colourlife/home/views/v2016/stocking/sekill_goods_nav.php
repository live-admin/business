<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>2016年货盛典</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/stocking/');?>js/sekill_goods_nav.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/stocking/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background: #f7f7f7;">
		<div class="top_nav">
			<ul>
				<li>
					<p>10:00</p>
					<p>已开抢</p>
				</li>
				<li >
					<p>14:00</p>
					<p>抢购进行中</p>
				</li>
				<li>
					<p>16:00</p>
					<p>即将开场</p>
				</li>
				<li>
					<p>20:00</p>
					<p>即将开场</p>
				</li>
			</ul>
		</div>
		
		<div class="ranking_bar">
			<ul class="rich hide"><div class="clear"></div></ul>
			<ul class="drink hide"><div class="clear"></div></ul>
			<ul class="food hide"><div class="clear"></div></ul>
			<ul class="fruit hide"><div class="clear"></div></ul>
		</div>
		
		<div class="mask hide"></div>
		<div class="Pop_qiang hide">
			<div class="Pop_qiang_txt">
				<p>亲，本场秒杀时间</p>
				<p>还未到哦，请稍后再来！</p>
			</div>
			<div class="Pop_qiang_btn_box">
				<div class="Pop_qiang_btn">确定</div>
			</div>
		</div>
		
	<script type="text/javascript">
		var _time = <?php echo $time;?>;
		var goods=<?php echo $goods ?>;
		var url=<?php echo $url ?>;
	</script>
	</body>
</html>
