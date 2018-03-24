<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>邀请有奖</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background: #F0E8E4;">
		<div class="contanis">
			<div class="title">我的奖励明细</div>
			<div class="bar">
				<div class="title_top">
					<div class="frid">
						<p>好友</p>
					</div>
					<div class="method">
						<p>方式</p>
					</div>
					<div class="time">
						<p>时间</p>
					</div>
					<div class="reward">
						<p>奖励</p>
					</div>
				</div>
				
				<div class="overflow">
					
					<?php if(!empty($reward)){
			      		foreach($reward as $list){     //$list自己定义，后面的拿取数据取$list
					?>
					<div class="title_top_box">
						<div class="frid">
							<p><?php echo substr_replace($list['mobile'],'****',3,4)?></p>
						</div>
						<div class="method">
							<p><?php echo $list['type']?></p>
						</div>
						<div class="time">
							<p><?php echo $list['time']?></p>
						</div>
						<div class="reward">
							<p><?php echo $list['reward']?>饭票</p>
						</div>
					</div>
					<?php 
						}
					}?>
					
				</div>
				
				
			</div>
			<div class="obtain">
				<p><span>累计获得奖励：</span><span>2元</span></p>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		var sum = <?php echo json_encode($sum);?>;
		$(document).ready(function(){
			$(".obtain>p>span:eq(1)").text(sum);
		});
		
	</script>
</html>