<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="author" content="webname">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link type="text/css" rel="stylesheet" href="<?php echo F::getStaticsUrl("/");?>home/propertyfee/css/css.css" />
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/common.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/base.js"></script>
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/common_busi.js"></script>
<script type="text/javascript">
</script>
<title>物业缴费</title>
</head>
<body>	
	<ul class="detail-list">
		<li>
			<div class="label">缴费状态：</div>
			<div class="content"><div class="ico-state"><span><script type="text/javascript"> 
			document.write(getStatusText('<?php echo $data['status'];?>')); </script></div></div>
		</li>
		<li>
			<div class="label">订单号：</div>
			<div class="content"><?php echo $data['sn'];?></div>
		</li>
		<li>
			<div class="label">订单金额：</div>
			<div class="content"><?php echo $data['amount'];?></div>
		</li>
		<li>
			<div class="label">用户姓名：</div>
			<div class="content"><?php echo $data['customer_name'];?></div>
		</li>
		<li>
			<div class="label">电话号码：</div>
			<div class="content"><?php echo $data['mobile'];?></div>
		</li>
		<li>
			<div class="label">缴费小区：</div>
			<div class="content"><?php echo $data['community_name'];?></div>
		</li>
		<li>
			<div class="label">缴费房号：</div>
			<div class="content"><?php echo $data['build'];?></div>
		</li>
		<li>
			<div class="label">收费系统订单号：</div>
			<div class="content"><?php echo $data['colorcloud_order'];?></div>
		</li>
		<li>
			<div class="label">订单创建时间：</div>
			<div class="content"><?php echo date("Y-m-d H:i:s", $data['create_time']);?></div>
		</li>
		<li>
			<div class="label">支付方式：</div>
			<div class="content"><?php echo $data['payment_name'];?></div>
		</li>
		<?php if($data['model'] == "PropertyFees") {?>
		<li>
			<div class="label">备注：</div>
			<div class="content"><?php echo $data['note'];?></div>
		</li>
		<?php }?>
	</ul>
</body>
</html>
