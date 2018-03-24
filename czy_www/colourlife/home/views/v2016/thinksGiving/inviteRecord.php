<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>感恩节福袋-邀请记录页面</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>css/layout.css" />
	</head>
	<body>

		<!--邀请空记录页面开始-->
		<div class="conter empty_record hide">
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>images/record01.png">
			<p>你还没有邀请任何好友注册！</p>
		</div>
		<!--邀请空记录页面结束-->
		<div class="conter hvae_record">
			<div class="mobile_tab">
				<div class="mobile_tab_header">
					<p>手机号</p>
					<p>状态</p>
					<i class="hr"></i>
				</div>
				<div class="mobile_tab_con">
					<!--<div class="mobile_tab_con_p">
						<p>131xxxx1234</p>
					    <p>邀请成功</p>
					</div>
					<div class="mobile_tab_con_p">
						<p>131xxxx1234</p>
					    <p>邀请成功</p>
					</div>-->
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				var mobile = <?php echo json_encode($recordArray)?>;
//				var mobile=[
////							{phone:"131xxxx1234",state:"邀请成功"},
////							{phone:"131xxxx1234",state:"失败"},
////							{phone:"131xxxx1234",state:"邀请成功"},
////							{phone:"131xxxx1234",state:"失败"},
////							{phone:"131xxxx1234",state:"邀请成功"},
////							{phone:"131xxxx1234",state:"失败"},
////							{phone:"131xxxx1234",state:"邀请成功"},
////							{phone:"131xxxx1234",state:"失败"},
////							{phone:"131xxxx1234",state:"邀请成功"},
////							{phone:"131xxxx1234",state:"失败"},
////							{phone:"131xxxx1234",state:"邀请成功"},
////							{phone:"131xxxx1234",state:"失败"},
////							{phone:"131xxxx1234",state:"邀请成功"},
////							{phone:"131xxxx1234",state:"失败"}
//						   ];
//				    console.log(mobile[0].phone);
//				    console.log(mobile[0].state);
				for(var i=0;i<mobile.length;i++){
					$(".mobile_tab_con").append('<div class="mobile_tab_con_p">'+
													'<p>'+mobile[i].mobile+'</p>'+
												    '<p>邀请成功</p>'+
												'</div>')
				}
				if(mobile.length==0){
					$(".empty_record").removeClass("hide");
					$(".hvae_record").addClass("hide");
				}else{
					$(".empty_record").addClass("hide");
					$(".hvae_record").removeClass("hide");
				}
				
			});
		</script>
	</body>
</html>
