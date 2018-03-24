<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>彩富人生-新人有礼</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/ProfitJuly/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('//activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="container">
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/ProfitJuly/');?>images/bg01.jpg"/>
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/ProfitJuly/');?>images/bg02.jpg"/>
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/ProfitJuly/');?>images/bg03.jpg"/>
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/ProfitJuly/');?>images/bg04.jpg"/>
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/ProfitJuly/');?>images/bg05.jpg"/>
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/ProfitJuly/');?>images/bg06.jpg"/>
		</div>
		<div class="conter"></div>
		<div class="meal hide">
			<p><span id="price">4000</span>元</p>
		</div>
		<script>
			$(document).ready(function(){
				$(".conter").click(function(){
					$.ajax({
						url : '/Profit/AjaxJuly',
						type: "GET",
						dataType:'json',
						cache:false,
						success:function(data){
							if(data.retCode==1){
								$('#price').html(data.data.value);
								$(".meal").removeClass("hide");
							}else{
								alert(data.retMsg);
							}
						}

					});
				});
				$(".container").click(function(){
					$(".meal").addClass("hide");
				});
			});
		</script>
	</body>
</html>
