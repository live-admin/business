<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>服务节-中奖记录</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/fuWu/');?>css/layout.css">
	</head>
	<body>
		<div class="record">
			<div class="record_tit">
				<ul>
					<li>时间</li>
					<li>奖品名称</li>
					<li>状态</li>
				</ul>
			</div>
			<div class="record_detail">
				<!--<div class="record_detail_box">
					<div class="record_detail_box_time">2016-05-01 15:35:18</div>
					<div class="record_detail_box_name">3元抵用券</div>
					<div class="record_detail_stuat">已领取</div>
				</div>-->
			</div>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){
				var prizeDetailArr = <?php echo json_encode($prizeDetailArr);?>;
				for (var i=0; i<prizeDetailArr.length; i++ ) {
					$(".record_detail").append(
						'<div class="record_detail_box">'+
							'<div class="record_detail_box_time">'+prizeDetailArr[i].create_time+'</div>'+
							'<div class="record_detail_box_name">'+prizeDetailArr[i].prize_name+'</div>'+
							'<div class="record_detail_stuat">'+prizeDetailArr[i].status+'</div>'+
						'</div>'
					);
				}
				
				$(".record_detail_stuat").click(function(){
					var _this = $(this);
					var _index = _this.parents(".record_detail_box").index();
					console.log(_index);
					var _id = prizeDetailArr[_index].id;
					if ($(this).text() == "未领取") {
						location.href= "/FuWu/Address?id="+_id;
					}
				});
			});
			
		</script>
		
	</body>
</html>
