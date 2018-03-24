<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>中奖记录</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/women/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background: #ffe2dd;">
		<div class="contanr">
			<div class="bar">
				<div class="bar_top">
					<ul>
						<li>时间</li>
						<li>抽奖记录</li>
					</ul>
				</div>
				<div class="clear"></div>
				
				<div class="bar_cont">
					<!--<div class="cont_box">
						<div class="box_time">2016-05-01<br>15:35:18</div>
						<div class="box_prize">3.8元优惠券</div>
					</div>
					
					<div class="cont_box">
						<div class="box_time">2016-05-01<br>15:35:18</br></div>
						<div class="box_prize">3.8元优惠券</div>
					</div>
					
					<div class="cont_box">
						<div class="box_time">2016-05-01<br>15:35:18</br></div>
						<div class="box_prize">3.8元优惠券</div>
					</div>
					<div class="cont_box">
						<div class="box_time">2016-05-01<br>15:35:18</br></div>
						<div class="box_prize">3.8元优惠券</div>
					</div>
					<div class="cont_box">
						<div class="box_time">2016-05-01<br>15:35:18</br></div>
						<div class="box_prize">3.8元优惠券</div>
					</div>
					<div class="cont_box">
						<div class="box_time">2016-05-01<br>15:35:18</br></div>
						<div class="box_prize">3.8元优惠券</div>
					</div>
					<div class="cont_box">
						<div class="box_time">2016-05-01<br>15:35:18</br></div>
						<div class="box_prize">3.8元优惠券</div>
					</div>-->
				</div>
			</div>
			
			<div class="foot">
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/women/');?>images/rule.jpg"/>
			</div>
		</div>
		
		<script type="text/javascript">
			var record=<?php echo json_encode($record);?>;
			
			$(document).ready(function(){
//				var list = [
//					{time:"2016-05-01 15:35:18",name:"3.8元优惠券"},
//					{time:"2016-05-01 15:35:18",name:"3.8元优惠券"},
//					{time:"2016-05-01 15:35:18",name:"3.8元优惠券"},
//					{time:"2016-05-01 15:35:18",name:"3.8元优惠券"},
//					{time:"2016-05-01 15:35:18",name:"3.8元优惠券"},
//				];
				
				for (var i=0; i<record.length;i++) {
					timestamp2 = record[i].send_time*1000;
					var newTime = new Date(timestamp2);
					var _time = (newTime.getFullYear()+"-"+checkTime(newTime.getMonth()+1)+"-"+checkTime(newTime.getDate())+"<br/>"+checkTime(newTime.getHours())+":"+checkTime(newTime.getMinutes())+":"+checkTime(newTime.getSeconds()));
					var sub = _time.substring('15');
					console.log(typeof(sub))
					console.log(sub);
					$(".bar_cont").append(
						'<div class="cont_box">'+
							'<div class="box_time">'+_time+'</div>'+
							'<div class="box_prize">'+record[i].prize_name+'</div>'+
						'</div>'
					)
				}
				function checkTime(i){
					if(i < 10){
						i = '0' + i;
					}
					return i;
				}
			});
			
			
		</script>
		
	</body>
</html>
