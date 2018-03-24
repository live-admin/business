<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>微商圈支付成功页面</title>
	<link type="text/css" rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/pay/');?>css/paystyle.css" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/pay/');?>js/jquery-1.11.1.min.js"></script>
</head>
<body>
	<div class="success">
		<div class="logo">
			<img src="<?php echo F::getStaticsUrl('/home/pay/');?>images/success.png"/>
		</div>
		<div class="detail">
			<p>
				<span>支付状态:</span>
				<span>交易成功</span>
			</p>
			<p>
				<span>小区:</span>
				<span><?php echo $model->pay_info?></span>
			</p>
			<p>
				<span>订单金额:</span>
				<span><?php echo $model->amount?></span>
			</p>
			<p>
				<span>订单号:</span>
				<span><?php echo $model->sn?></span>
			</p>

<!--			<p>-->
<!--				<span>订单详情查看</span>-->
<!--				<a href="#">我的订单</a>-->
<!--			</p>-->
		</div>
	</div>
</body>
</html>