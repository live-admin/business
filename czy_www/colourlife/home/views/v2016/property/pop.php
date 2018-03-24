<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>首页弹窗</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>css/layout.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<!--弹窗01开始-->
		<div  class="pop">
			<div class="pop_header pop_index">
				<p>你预缴  我送礼</p>
			</div>
			<div class="pop_con">
				<p>恭喜！您已获得<span>1</span>次抽奖机会,请点击首页下方轮播图进行抽奖。</p>
			</div>
			<div class="pop_footer">
				<a href="javascript:void(0);">
					我知道了
				</a>
			</div>
		</div>
	    <!--弹窗01结束-->
	    <script>
	    var chance_number = "<?php echo $chance_number ?>"
	    
	    	$(document).ready(function(){
	    		
	    		$(".pop_con>p>span").text(chance_number);
	    		//弹窗关闭
	    		$(".pop_footer").click(function(){
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
