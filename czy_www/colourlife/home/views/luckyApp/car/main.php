<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>汽车大奖</title>

		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>css/carlottery.css" rel="stylesheet">
	</head>

	<body>
		<div class="car" style="background:#dcdcdc;">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>images/head.png" class="lotteryimg" />
				<img src="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>images/img_03.png" class="lotteryimg" />
				<img src="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>images/img_01.png" class="lotteryimg" />
			</div>
			<div class="btn_box">
				<a href="<?=F::getHomeUrl('/luckyApp/luckyCarResultList')?>" class="btn">
					抽奖码公示
				</a>
				<a href="<?=F::getHomeUrl('/luckyApp/luckyCarResultNew')?>" class="btn">
					开奖结果
				</a>
			</div>
			<div>
                            <a href="<?=F::getHomeUrl('/luckyApp/luckyCarResult')?>">
					<img src="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>images/img_02.png" class="lotteryimg" />
				</a>
				<img src="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>images/foot.png" class="lotteryimg" />
			</div>
		</div>
	</body>

</html>