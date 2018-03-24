<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>线下缴费扫码签到</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/signSucces/');?>css/layout.css" />
</head>
<body>
<div class="wrap">
	<div class="content">
		<p class="time">活动时间：2016.11.1-2016.12.31</p>
		<div class="pop">
			<div class="top">
				<p>你预缴  我送礼</p>
			</div>
			<div class="bottom">
				<p class="suc">恭喜您登记成功，请与现场工作人员确认领取“甘肃泾川苹果1袋”</p>
				<p class="fall">网络繁忙,请再次扫描</p>
				<a href="javascript:void(0);">确 认</a>
			</div>
		</div>
		<p class="des">如有疑问请联系客服  联系电话：0755-33930303</p>
	</div>
	<div class="apple_bg"></div>
</div>
<script>

	var confirm =<?php echo $confirm ?>;
	if(confirm){
		$(".fall").hide();
	}else{
		$(".suc").hide();
		$(".fall").css("margin-bottom","0.4rem");
	}
	
	$(".pop .bottom a").click(function(){
		mobileCommand("closeBrowser");	
	})
	
	function mobileCommand(cmd){
		if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
			var _cmd = "http://colourlifecommand/" + cmd;
			document.location = _cmd;
		} else if (/(Android)/i.test(navigator.userAgent)) {
			var _cmd = "jsObject." + cmd + "();";
			eval(_cmd);
			} else {
		}
	}
</script>
</body>
</html>