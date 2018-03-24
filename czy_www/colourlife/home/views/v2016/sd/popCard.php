<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>双旦寻宝</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="pop_cark">
			<div class="pop_cark_header zeng">
				<div class="pop_c_img">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/pop_title06.png">
				</div>
				<p class="obtain">恭喜您获得一张拼图</p>
				<a href="javascript:void(0)" class="btn">确定</a>
			</div>
			<div class="pop_close"></div>
		</div>
		<script>
			var data= <?php echo $outData;?>;
			console.log(data.tip.text);
			$(".pop_c_img").find("img").attr("src",data.tip.image);
			$(".obtain").find("p").text(data.tip.text);
			$(".btn").text(data.tip.button_text);
			$(".btn,.pop_close").click(function(){
				mobileCommand("closeBrowser"); 
			});
			
			function mobileCommand(cmd){
			
				if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) 
			{
					var _cmd = "http://colourlifecommand/" + cmd;
					document.location = _cmd;
				} 
			else if (/(Android)/i.test(navigator.userAgent)) 
			{
					var _cmd = "jsObject." + cmd + "();";
					eval(_cmd);
				} 
			else 
			{
				}
			}
			
			
			
		</script>
	</body>
</html>
