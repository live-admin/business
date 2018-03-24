<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>e师傅-首页弹窗</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	   	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/eShiFu/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="mask"></div>
		<div class="index_poo">
			<p>居家保养特惠，把爱留给家人</p>
			<p>空调清洗78元/台（原价120）</p>
			<p>消杀蟑螂168元/100㎡（原价220）</p>
			<div class="to_btn">立即进入</div>
		</div>
		
		
		
		<script type="text/javascript">
			$(document).ready(function(){
				$(".to_btn").click(function(){
					location.href = "/EShiFu";
				});
				
				$(".mask").click(function(){
					mobileCommand("closeBrowser");
				});
				
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
			});
			
			
			
		</script>
		
	</body>
</html>
