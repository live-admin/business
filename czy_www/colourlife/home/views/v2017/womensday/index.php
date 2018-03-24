<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>三八节福利转盘</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/womensday/');?>js/index.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/womensday/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="conter">
			<div class="rotary">
				<div class="rotary_title">
					<p>剩余抽奖次数：<span class="num">2</span>次</p>
				</div>
				<div class="rotary_ban">
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/womensday/');?>images/back.png">
					<img src="<?php echo F::getStaticsUrl('/activity/v2017/womensday/');?>images/piece.png">
					<div class="pointer"></div>
					<p>
						<span>开始</span>
						<span>抽奖</span>
					</p>
					<div class="result hide"></div>
				</div>
			</div>
			<div class="btn">
				<p>活动规则</p>
				<p>中奖记录</p>
			</div>
			<span class="p">*本次活动最终解释权归彩之云所有</span>
		</div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--中奖弹窗开始-->
		<div class="pop hide">
			<div class="pop_header">
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/womensday/');?>images/gift.png">
			</div>
			<div class="pop_con">
				<p>恭喜您过节了，并获得奖品</p>
				<p class="jiang_active">京东券一张</p>
			</div>
			<div class="pop_btn">
					确定
			</div>
		</div>
		<!--中奖弹窗结束-->
		<script>
			var data=<?php echo json_encode($chance);?>;
			//抽奖次数
			var cishu=data.chance;
			//状态为3时
			var state=data.status
			
			$(".num").text(cishu);
			//饭票商城的链接
			var czyLink=data.url;
			console.log(czyLink);
		</script>
	</body>
</html>


