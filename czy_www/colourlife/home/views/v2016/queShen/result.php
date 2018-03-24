<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>雀神竞猜</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>js/result.js"></script>
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="contaner">
			<div class="contaner_bg">
				<div class="one"><img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/prize_1.png"/></div>
				<div class="one_box">
					<div class="one_box_left"><img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/r_icon.png"/></div>
					<div class="one_box_right">
						<p><span>张三</span><span>年</span><span>3.5</span><span>牌龄:</span></p>
						<div class="clear"></div>
						<p>亮出最甜的微笑，展示最美好的自己！太阳有属于他的光芒，我也一样能创</p>
					</div>
				</div>
				
				<div class="two"><img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/prize_2.png"/></div>
				<div class="two_box">
					<div class="two_box_left"><img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/r_icon.png"/></div>
					<div class="two_box_right">
						<p><span>张三</span><span>年</span><span>3.5</span><span>牌龄:</span></p>
						<div class="clear"></div>
						<p>亮出最甜的微笑，展示最美好的自己！太阳有属于他的光芒，我也一样能创</p>
					</div>
				</div>
				<div class="two"><img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/prize_3.png"/></div>
				<div class="zhong">
					<p>共有<span>50</span>人猜中</p>
					<div class="zhong_of">
						<ul>
							
						</ul>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			var listGuan = <?php echo json_encode($listGuan);?>;
			var listRenQiTop = <?php echo json_encode($listRenQiTop);?>;
			var listMobile = <?php echo json_encode($listMobile);?>;
			var total = <?php echo json_encode($total);?>;
		</script>
		
	</body>
</html>
