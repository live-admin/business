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
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/parkingfee_view.js"></script>
<script type="text/javascript">
var submitContinueCardUrl = "<?php echo $this->createUrl('SubmitContinueCard');?>"; 
</script>
<title>物业缴费</title>
</head>
<body>
<?php if($data['status']==1) {?>
	<button type="button" class="btn-yellow" id="btnContinueCard" style="">续卡</button>
<?php }?>
	<form class="form-continue-card">
		<input type="hidden" name="parking_id" value="<?php echo $data['parking_id'];?>" />
		<textarea rows="" cols="" placeholder="请填写处理方法及处理事宜" name="note"></textarea>
		<button type="button" class="btn-yellow" id="btnSubmitContinueCard">准确无误，提交</button>
	</form>
	<ul class="detail-list">
		<li>
			<div class="label">缴费状态：</div>
			<div class="content"><div class="ico-state"><span <?php if($data['status']==1) echo "class='row2'";?>><script type="text/javascript"> 
			document.write(getParkingStatusText('<?php echo $data['status'];?>')); </script></span></div></div>
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
			<div class="label">缴费车牌号：</div>
			<div class="content"><?php echo $data['car_number'];?></div>
		</li>
		<li>
			<div class="label">缴费房号：</div>
			<div class="content"><?php echo $data['build'];?></div>
		</li>
		<li>
			<div class="label">订单创建时间：</div>
			<div class="content"><?php echo date("Y-m-d H:i:s", $data['create_time']);?></div>
		</li>
		<li>
			<div class="label">支付方式：</div>
			<div class="content"><?php echo $data['payment_name'];?></div>
		</li>
	</ul>
</body>
</html>
