<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>春节活动</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="rule_content">
			<div class="deng_bg">
				<ul>
					<li></li>
					<li>
						<p></p>
						<p class="animal"></p>
					</li>
				</ul>
			</div>
		<div class="rule_nav">
			<div class="rule_time">
				<h5>活动玩法说明：</h5>
				<p>1、打扫卫生：每天打扫卫生获得1个福气值，连续2天不打扫卫生，扣除1个福气值。</p>
				<p>2、放鞭炮：放鞭炮可获得1个福气值，连续3天燃放鞭炮扣除1点福气值。每成功支付1笔订单，且单笔订单金额大于49元，即可获得一个鞭炮。活动期间若出现恶意退单情况，取消活动资格，情节严重者封号处理。</p>
				<p>3、邻里和睦：分享到邻里圈，邀请好友在分享页面点赞，达成邻里和睦，每一个赞可获得一点福气值，同个ID每天只能给另一个ID点赞１次，  每人每天最多获得5个赞。</p>
				<p>4、紫金葫芦：吸取其他用户福气值，普通用户每天最多使用5次，彩富用户每天最多使用8次；每天只能对同个ID吸取一次。</p>
				<p>5、聚宝盆：活动期间投彩富人生，达成家财万贯，可获得8个福气值；所有用户每天自动生成3个福气。</p>
			</div>
			
			<div class="rule_r">
				<h5>活动规则：</h5>
				<p>1、所有活动奖品均在活动结束后15个工作日内发放。</p>
				<p>2、活动根据用户所集福气值进行排名，并对福气值排名前50名的用户给予奖励。</p>
				<p>3、活动期间，在“放鞭炮”任务中，若出现恶意退单情况，取消活动资格。</p>
				<p>4、活动期间若出现作弊行为，则取消该帐号活动资格，情节严重者将给予封号处理。</p>
			</div>
			
			<div class="rule_r">
				<h5>活动奖励：</h5>
				<p>一等奖：iphone7(128G,颜色随机);</p>
				<p>二等奖： Apple iPad Air 2(64G,颜色随机);</p>
				<p>三等奖：666彩饭票;</p>
				<p>第4-50名：每人20彩饭票;</p>
			</div>
			<p class="r1">活动时间：1月26日至2月10日</p>
			<p class="r2">本活动最终解释权归彩之云所有。</p>
		</div>
			<div class="clour_bg">
			</div>
		</div>	
		<script type="text/javascript">
			var customer_info = <?php echo json_encode($customer_info)?>;
			var img_url = "<?php echo F::getStaticsUrl('/activity/v2016/spring/images');?>";
			$(document).ready(function(){
			var zodiac = customer_info.zodiac ? customer_info.zodiac : "";
			var phoneList;
			phoneList = (customer_info.mobile.substring(0,3)+"****"+(customer_info.mobile.substring(7,11)));
			$(".deng_bg ul li:eq(1)>p:eq(0)").text(phoneList);
			
			switch(customer_info['zodiac']){
				case '1':
					$(".deng_bg ul").find('.animal').text('生肖鼠');
					break;
				case '2':
					$(".deng_bg ul").find('.animal').text('生肖牛');
					break;
				case '3':
					$(".deng_bg ul").find('.animal').text('生肖虎');
					break;	
				case '4':
					$(".deng_bg ul").find('.animal').text('生肖兔');
					break;	
				case '5':
					$(".deng_bg ul").find('.animal').text('生肖龙');
					break;	
				case '6':
					$(".deng_bg ul").find('.animal').text('生肖蛇');
					break;	
				case '7':
					$(".deng_bg ul").find('.animal').text('生肖马');
					break;	
				case '8':
					$(".deng_bg ul").find('.animal').text('生肖羊');
					break;
				case '9':
					$(".deng_bg ul").find('.animal').text('生肖猴');
					break;
				case '10':
					$(".deng_bg ul").find('.animal').text('生肖鸡');
					break;	
				case '11':
					$(".deng_bg ul").find('.animal').text('生肖狗');
					break;	
				case '12':
					$(".deng_bg ul").find('.animal').text('生肖猪');
					break;	
			}
		if(zodiac){
			$(".deng_bg ul li:eq(1)").append('<img src="'+img_url+'/'+zodiac+'.png'+'">');
		}
		
		$(".deng_bg ul li:eq(0)").append('<img src="'+customer_info.avatar+'">');
				
	});
			
			
		</script>
		
	</body>
</html>
