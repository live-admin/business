<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>活动战绩</title>

		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<script type="text/javascript" src="../hwzg/js/jquery.min.js"></script>
		<link href="<?php echo F::getStaticsUrl('/common/css/lucky/litchi/zongzi.css'); ?>" rel="stylesheet">
	</head>

	<body>
		<div class="zongzi">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/QLZ_01.jpg');?>" />
			</div>
			<p class="current_tm clearfix">
				<span class="fristtime" >活动战绩</span>
				<!--<span value=14>14:00</span>-->
			</p>
			<div class="content">
				<table>
					<thead>
						<tr>
							<th>抢荔枝时间</th>
							<th>1元换购特权码</th>
							<th>兑换状况</th>
						</tr>
					</thead>
					<tbody>
	         			<?php foreach ($list as $val){?>
							<tr>
								<td><?php echo $val['lucky_date'] ;?></td>
								<td><?php echo $val['mycode']; ?></td>
								<td><?php echo $val['isUse']==1?"已兑换":"<a href='".$url."&pid=6936' style=\"color:red;text-decoration:underline;\">去兑换</a>" ;?></td>
							</tr>
						<?php }?>	
					</tbody>
				</table>
			</div>

			<div class="rule">
				<ul>
					<li>活动规则：</li>
					<li>1.活动时间：6月15日- 6月22日</li>
					<li>2.每天10点、16点准时开抢；</li>
					<li>3.每次限量50份开抢，随机抢到。</li>
					<li>4.用户成功抢到后需要支付1元进行换购（中奖页面直接点击“换购”或前往<a href="<?=$url;?>" style="color: red;text-decoration: underline;">1元购专区</a>，换购订单提交后， 则换购码失效， 须于24小时内完成支付，否则订单将自动关闭）。</li>
					<li>5.荔枝统一在活动结束后一个星期内由彩生活客户经理配送到家</li>
					<li>6.彩生活享有法律范围内本次活动的最终解释权。</li>
				</ul>
			</div>
		</div>
		<!--<a href="" class="fxdimg_rob"><img src="images/QLZ_03.png"/></a>-->
	</body>

</html>