<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>新人有礼！最高可领1G流量！</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	   	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
        <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>js/share.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>css/normalize.css">
    	<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>css/new1.css">
	</head>
	<body>
		<div class="content">
			<div class="content_bg">
				<div class="content_txt">
					<p>恭喜领到流量</p>
					<p>10M</p>
					<p>已发放到你的登录手机号</p>
					<p>实际到账情况请以运营商短信为准</p>
				</div>
				<div class="content_btn">
					<a href="javascript:void(0);">领取</a>
				</div>
			</div>
			<div class="dele_btn">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>images/dele.png"/>
			</div>
		</div>
		<input type="hidden" name="" id="hide_id" value="<?php echo $id ?>;" />
		
		
		<script type="text/javascript">
		 var prize_name = "<?php echo $prize_name ?>"
		 
			$(document).ready(function(){
				$(".content_txt p:eq(1)").text(prize_name);
				
				var hide_id = $("#hide_id").val();
				$(".content_btn").click(function(){
					$.ajax({
					async:true,
					type:"POST",
					url:"/Liang/GetByApp",
					data:{'id' :hide_id},
					dataType:'json',
					success:function(data){
						if(data.status == 1){
							console.log("领取成功");
							mobileCommand("closeBrowser");
						}
						else {
							alert(data.msg);
							console.log("领取失败");
						}
					} 
				});
				});
				
				$(".dele_btn>img").click(function(){
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
