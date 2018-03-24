<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>中奖记录</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>css/layout.css">
	</head>
	<body>
		<div class="rule">
			<div class="bg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/bg01.jpg">
			</div>
			<div class="rule_con">
				<div class="rule_head">
					<p>时间</p>
					<p>奖品名称</p>
				</div>
				<div class="rule_site">
					<?php if(!empty($prizeMobileArr)){
					foreach ($prizeMobileArr as $value){?>
					<div class="rule_banner">
						<p>
							<span><?php echo date("Y-m-d",$value->create_time);?></span>
							<span><?php echo date("H:i:s",$value->create_time);?></span>
						</p>
						<p><?php echo $value->prize_name;?></p>
					</div>
					<?php }
					}?>
				</div>
			</div>
		</div>
		<script>
//			var prizeMobileArr =<?php echo json_encode($prizeMobileArr);?>;
		</script>
	</body>
</html>
