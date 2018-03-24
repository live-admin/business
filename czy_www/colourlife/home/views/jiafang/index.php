<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="author" content="webname">
<meta name="viewport"	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo $this->title;?></title>

<link type="text/css" rel="stylesheet"	href="<?php echo F::getStaticsUrl("/home/jiafang/");?>css/css.css" />
<link type="text/css" rel="stylesheet"	href="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/webuploader.css" />
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/jquery-1.11.1.min.js"></script>

<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/webuploader.min.js"></script>
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/common.js"></script>
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/base.js"></script>
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/jiafang/");?>js/index.js"></script>

<script type="text/javascript">

var customerHasPortrait = <?php echo $this->hasPortrait($customer) ? "true" : "false"; ?> ;
var BASE_URL = "<?php echo F::getStaticsUrl("/home/jiafang/");?>";
var uploadFileUrl= "<?php echo $this->createUrl("UploadPortrait"); ?>";
var doBindingUrl = "<?php echo $this->createUrl("DoBinding"); ?>";
</script>
</head>

<body>
	<div class="account_sub">
		<form>
			<h2>确认彩之云账号</h2>
			<div class="account_info">
            <?php if($customer === null) {?>
				<div class="account_tip">
					<p>若未能获取您的彩之云账号信息，请确认您OA账号的手机号码是否和彩之云一致，OA手机号码需要修改请联系人力资源工作人员</p>
				</div>
			<?php }
			else 
			{
			?>	
				<div class="account_face">
                <?php if($this->hasPortrait($customer)) { ?>
					<img src="<?php echo $customer->getPortraitUrl(); ?>" />
				<?php }else {?>
					<div class="upload-customer-face">
                    	
                    </div>
				<?php }?>
				</div>
				<dl>
					<dt>
						<span>彩之云账户：</span><em><?php echo $customer->mobile; ?></em>
					</dt>
					<dd>
						<span>你的姓名：</span><em><?php echo $customer->name; ?></em>
					</dd>
				</dl>
			<?php }?>
			</div>
			<div class="form-control">
				<input type="button" class="sub" value="确认绑定" <?php if($customer === null) echo "disabled=\"disabled\""; ?> id="btnBindCustomer" />
			</div>
		</form>
	</div>
    
<div id="btnUploadCustomerFace" style="display:none"></div>
</body>
</html>
