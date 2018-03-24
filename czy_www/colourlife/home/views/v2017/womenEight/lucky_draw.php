<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>疯狂大抽奖</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/WomenEight/');?>js/jquery.slotmachine.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/WomenEight/');?>js/award.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/WomenEight/');?>css/layout.css" /> 
</head>
	<body>
		<div class="award">
		<div class="award_area">
			<div>
				<span>尊宠女王节</span>
				<p>你还有&nbsp;<span class="cishu"></span>&nbsp;次抽奖机会</p>
			</div>
			<!-- start -->
			<div class="machine_wrap">
				<div class="laohuji" id="switch">
					<div class="laohuji_machine"></div>
					<div class="handel up"></div>
				</div>
				<!--滚动图案 start-->
				<div class="content_draw">
					<div id="machine1" class="slotMachine">
						<div class="slot slot1"></div>
						<div class="slot slot2"></div>
						<div class="slot slot3"></div>
						<div class="slot slot4"></div>
						<div class="slot slot5"></div>
						<div class="slot slot6"></div>
						<div class="slot slot7"></div>
						<div class="slot slot8"></div>
					</div>
					<div id="machine2" class="slotMachine">
						<div class="slot slot1"></div>
						<div class="slot slot2"></div>
						<div class="slot slot3"></div>
						<div class="slot slot4"></div>
						<div class="slot slot5"></div>
						<div class="slot slot6"></div>
						<div class="slot slot7"></div>
						<div class="slot slot8"></div>
					</div>
					<div id="machine3" class="slotMachine">
						<div class="slot slot1"></div>
						<div class="slot slot2"></div>
						<div class="slot slot3"></div>
						<div class="slot slot4"></div>
						<div class="slot slot5"></div>
						<div class="slot slot6"></div>
						<div class="slot slot7"></div>
						<div class="slot slot8"></div>
					</div>
				</div>
				<!--滚动图案 end-->
			</div>
		</div>
		<div class="award_record">
				<a href="/WomenEight/PrizeDetail" >中奖记录</a>
			</div>
	</div>
	<footer class="award_footer"></footer>

	<div class="pop hide">
		<div class="pop_wrap">
			<div class="pic_tip">
				<i class="cry_icon"></i>
			</div>
			<div class="font_tip">
				<p></p>
				<p></p>
			</div>
			<a href="javascript:void(0);">确定</a>
		</div>
	</div>

	<div class="mask hide"></div>
	
	<script>
		var leftChance=<?php echo json_encode($leftChance);?>;
		$(".cishu").text(leftChance);
		
	</script>
	</body>
</html>

