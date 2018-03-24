<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>感恩节福袋-中奖记录页面</title>
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
			<img src="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>images/record02.png" style="width:2.58rem;2.16rem;">
			<p>你还没有获得任何奖品哦！</p>
		</div>
		<!--邀请空记录页面结束-->
		<div class="conter hvae_record">
			<div class="mobile_tab">
				<div class="mobile_tab_header">
					<p>时间</p>
					<p>奖品</p>
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
				var result = <?php echo json_encode($rewardRecord);?>	
					
//				var  result=[
//							{time:"2016年11月15日",prize:"彩特供满100减10优惠券"},
//							{time:"2016年11月15日",prize:"彩特供满100减10优惠券"},
//							{time:"2016年11月15日",prize:"1元饭票抵用券"},
//							{time:"2016年11月15日",prize:"2元饭票抵用券"},
//							{time:"2016年11月15日",prize:"乐扣乐扣不锈钢纤巧保温杯"},
//							{time:"2016年11月15日",prize:"全程通W3智能心率手环"},
//						   ];
//				    console.log(mobile[0].phone);
//				    console.log(mobile[0].state);
				for(var i=0;i< result.length;i++){
					$(".mobile_tab_con").append('<div class="mobile_tab_con_p">'+
													'<p>'+result[i].time+'</p>'+
												    '<p>'+result[i].name+'</p>'+
												'</div>')
				}
				if(result.length==0){
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

