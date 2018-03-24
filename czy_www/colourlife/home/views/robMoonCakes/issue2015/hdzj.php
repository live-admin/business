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
        <script src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>js/jquery.min.js"></script>
		<link href="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>css/zongzi.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div class="zongzi">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/head.png" />
			</div>
			<div class="title">
				<span>活动战绩</span>
			</div>

			<div class="content">
				<table>
					<tr>
						<th>抢月饼时间</th>
						<th>1元换购特权码</th>
						<th>兑换状况</th>
					</tr>
                    <?php foreach ($list as $val){?>
                        <tr>
                            <td><?php echo $val['lucky_date']; ?></td>
                            <td><?php echo $val['mycode']; ?></td>
                            <td><?php echo $val['isUse']==1?"已兑换":"<a href='".$url."&pid=20755' style=\"color:red;text-decoration:underline;\">去兑换</a>" ;?></td>
                        </tr>
                    <?php }?>
                </table>
			</div>

			<div class="rule">
				<ul>
					<li>1.活动时间：8月20号—8月31号</li>
					<li>2.每天10点、16点、20点准时开抢</li>
					<li>3.每次限量开抢，随机抢到</li>
					<li>4.用户成功抢到后，需要支付1元进行换购彩云追月月饼</li>
					<li>5.换购有效期：8月20号—9月2号</li>
					<li>6.月饼在活动结束后10天内，由彩生活客户经理配送到家</li>
				</ul>
			</div>
			 <div style="margin-top: 30%;"><img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/ZQ_25.png" class="lotteryimg" /></div>
			<!--<p class="botp">★注：彩生活享有本次活动的最终解释权 </p>-->
		</div>
		<script>
		</script>
	</body>

</html>