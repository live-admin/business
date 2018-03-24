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
<script type="text/javascript" src="<?php echo F::getStaticsUrl("/");?>home/propertyfee/js/customeraudit_view.js"></script>
<script type="text/javascript">
var submitAuditUrl = "<?php echo $this->createUrl('Update');?>";
</script>
<title>物业缴费</title>
</head>
<body>
<form onsubmit="return false;">
	<input type="hidden" name="id" value="<?php echo $customer->id;?>"/>
	<input type="hidden" name="type" value="<?php echo $customer->audit == '0' ? "1" : '2';?>"/>
	<dl class="customer-info">
		<dt>请核对业主的相关资料：</dt>
		<dd>
			<div class="label">业主姓名：</div>
			<div class="content"><input type="text" name="name" value="<?php echo $customer->name;?>" /></div>
		</dd>
		<dd>
			<div class="label">楼栋：</div>
			<div class="content"><select name="build_id">
			<?php foreach ($builds as $b) {?>
				<option value="<?php echo $b['id'];?>" <?php echo $customer->build_id == $b['id'] ? 'selected="selected"' : ''?> ><?php echo $b['name'];?></option>
			<?php }?>
			</select></div>
		</dd>
		<dd>
			<div class="label">房间号：</div>
			<div class="content"><input type="text" name="room" value="<?php echo $customer->room;?>" /></div>
		</dd>
		<dd>
			<div class="label">业主手机号：</div>
			<div class="content">13565880884</div>
		</dd>
	</dl>
	<button type="button" class="btn-yellow" id="btnSaveAudit"><?php 
	if($customer->audit == '0')
	{
	    echo '保存审核';
	}
	else 
	{
	    echo '保存业主资料';
	}
	?></button>
</form>
</body>
</html>
