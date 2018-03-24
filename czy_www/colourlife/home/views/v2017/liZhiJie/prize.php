<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>抽奖-中奖记录</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>css/chou.css">
	</head>
	<body style="background: #f2f3f4;">
		<div class="content">
			<div class="record hide">
				<div class="record_tit">
					<ul>
						<li>奖品名称</li>
						<li>时间</li>
						<li>操作</li>
					</ul>
				</div>
				<div class="record_detail">
					<!--<div class="record_detail_box">
						<div class="record_detail_box_time">10元优惠券</div>
						<div class="record_detail_box_name">2016-05-01 15:35:18</div>
						<div class="record_detail_stuat">兑换</div>
					</div>-->
				</div>
			</div>
			<div class="no_prize">
				<p>暂无中奖记录</p>
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/no_prize-z.png"/>
			</div>
			<p class="czy_txt">*本次活动最终解释权归彩之云所有</p>
			<div class="bot_img"></div>
		</div>
		
		<script type="text/javascript">
			var prizeMobileArr = <?php echo json_encode($prizeMobileArr);?>;
			
			$(document).ready(function(){
				if (prizeMobileArr) {
					$(".record").removeClass("hide");
					$(".no_prize").addClass("hide");
					for (var i=0; i<prizeMobileArr.length; i++ ) {
//					timestamp2 = prizeMobileArr[i].createtime*1000;
////					timestamp2 = 1492596061*1000;
//					var newTime = new Date(timestamp2);
//					var _time = (newTime.getFullYear()+"-"+checkTime(newTime.getMonth()+1)+"-"+checkTime(newTime.getDate())+"<br/>"+checkTime(newTime.getHours())+":"+checkTime(newTime.getMinutes())+":"+checkTime(newTime.getSeconds()));
					$(".record_detail").append(
						'<div class="record_detail_box">'+
							'<div class="record_detail_box_time">'+prizeMobileArr[i].prize_name+'</div>'+
							'<div class="record_detail_box_name">'+prizeMobileArr[i].create_time+'</div>'+
							'<div class="record_detail_stuat">'+"已兑换"+'</div>'+
						'</div>'
					)
				}
				}else{
					$(".record").addClass("hide");
					$(".no_prize").removeClass("hide");
				}
				
				
//				function checkTime(i){
//					if(i < 10){
//						i = '0' + i;
//					}
//					return i;
//				}
				
			});
		</script>
		
	</body>
</html>
