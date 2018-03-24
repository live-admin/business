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
		<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
		<script src="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>js/gunDong.js"></script>
	</head>

	<body>
		<div class="carlottery">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>images/head.jpg" class="lotteryimg" />
			</div>
			<div class="car_box">
                <img src="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>images/title.jpg" class="lotteryimg" />
                <div class="list_box">
				  <img src="<?php echo F::getStaticsUrl('/home/luckyApp/'); ?>images/back2.jpg" class="lotteryimg" />
                  <div class="car_content">
                    <div class="tickerbox">
                     <dl id="ticker">           
                        <?php foreach ($list as $k=>$v){?>
                         <dt>业主<?php echo substr($v['mobile'], 0,3), '****', substr($v['mobile'], -4)?>获得抽奖码<?php echo $obj->getFullDrawCode($v['mycode']);?>！</dt>
                        <?php } ?>
                     </dl>
                   </div>   
                  </div>
                </div>
			</div>
            <p class="bot_stamp">★注：彩之云拥有本次活动的最终解释权</p>
		</div>
        
	</body>

</html>