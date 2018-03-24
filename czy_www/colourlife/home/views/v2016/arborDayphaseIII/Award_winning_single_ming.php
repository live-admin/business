<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>神奇的花园</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseIII/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="int int_h">
			<div class="award">
				<div class="award_top">
					<p>获奖名称</p>
					<p>消耗积分</p>
					<p>获奖时间</p>
				</div>
				<!--1开始-->
				<div class="over_fl">
					<?php if(!empty($prizeMobileArr)){
					foreach ($prizeMobileArr as $value){?>
					<div class="award_ban">
						<p><?php echo $value->prize_name;?></p>
						<p><?php echo $value->integration;?></p>
						<p><?php echo date("Y-m-d",$value->create_time);?><br/><?php echo date("H:i:s",$value->create_time);?></p>
					</div>
					 <?php }
					}?>
				</div>
				
			</div>
			<div class="award_p">
				<p>* 实物奖品除“美的挂壁式空调”外，均以一元购码的形式实时发放</p>
			</div>
			<div class="footer_bg"></div>
		</div>
	</body>
</html>
