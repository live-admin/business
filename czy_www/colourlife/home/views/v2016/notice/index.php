<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>系统通知</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/notice/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		
		<div class="mask"></div>
		<div class="pop">
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/notice/');?>images/dele.png"/>
				<div class="pop_txt">
					<p>彩之云春节假期服务公告</p>
				</div>
			<div class="pop_bottom">
				<p>尊敬的用户：</p>
				<p>彩之云将于<span>1月25日</span> 至 <span>2月5日</span>放假，期间基础服务可正常使用。</p>
				<p>其中京东特供（含京东E卡）正常服务，偏远地区春节期间可正常下单，暂不发货，1月31日（初四）恢复正常运营。春节假期期间京东订单三天内未发货，系统自动取消订单退款。</p>
				<p>彩生活特供1月20号起暂停服务，2月6日恢复正常。其他功能服务详见各功能内部通知。</p>
				<!--<p>彩生活研究院</p>
				<p>2016年12月21日</p>-->
				
			</div>
		</div>
		
		
		
		<script type="text/javascript">
		
			$(document).ready(function(){
				$(".pop img").click(function(){
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
