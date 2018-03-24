<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="author" content="webname">
<meta name="viewport"	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo $this->title;?></title>

<link type="text/css" rel="stylesheet"	href="<?php echo F::getStaticsUrl("/home/bindcustomer/");?>css/css.css" />
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/bindcustomer/");?>js/jquery-1.11.1.min.js"></script>

<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/bindcustomer/");?>js/common.js"></script>
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/bindcustomer/");?>js/base.js"></script>
<script type="text/javascript"	src="<?php echo F::getStaticsUrl("/home/bindcustomer/");?>js/index.js"></script>

<script type="text/javascript">
var sendCheckcodeUrl = "<?php echo $this->createUrl("SendCheckcode"); ?>";
var doBindingUrl = "<?php echo $this->createUrl("DoBinding"); ?>";
var customer = <?php echo empty($customer) ? '""' : $customer->id ;?>;
</script>
</head>

<body>
	<div class="account_sub">
		<form>
			<h2><?php 
			if($customer == null)
			{
				echo "确认彩之云账号";
			}
			else
			{
				echo "您已经成功绑定彩之云账号";
			}
			?></h2>
			<div class="account_info">
				<div class="account_face">
<?php 

if($customer == null)
{
	echo '<img src="' . F::getStaticsUrl("/home/bindcustomer/"). 'images/account_face2.png" />';
}
else
{
	echo '<img src="' . $customer->getPortraitUrl() .'" />';
}

?>

				</div>
<?php 
if($customer == null) {
?>
<div class="row">
    <div class="control-group">
            <input type="tel" name="mobile" placeholder="请输入彩之云手机号" class="moblie" /><a href="javascript:void(0);" class="btn-send-checkcode" id="btnSendCheckcode">发送验证码</a>
    </div>
</div>

<div class="row">

    <div class="control-group">
        
            <input type="text" name="checkcode" placeholder="请输入验证码" class="checkcode" />
    </div>

</div>

				<dl id="custInfo" style="display:none;">
					<dd>
						<span>你的姓名：</span><em></em>
					</dd>
				</dl>
<?php } else {
?>
				<dl>
					<dd>
						<span>彩之云姓名：</span><em><?php echo $customer->name; ?></em>
					</dd>
				    <dd>
						<span>手机号：</span><em><?php echo $customer->mobile; ?></em>
					</dd>
					<dd>
						<a href="javascript:void(0)" class="unlock" id="unlock">解绑</a>
					</dd>
				</dl>
<?php }?>
				
			</div>
<?php if($customer == null) {?>
			<div class="form-control">
				<a class="sub" id="btnBindCustomer" href="javascript:void(0)" >绑定</a>
			</div>
			<?php }?>
		</form>
	</div>

<script>
	

</script>
</body>
</html>
