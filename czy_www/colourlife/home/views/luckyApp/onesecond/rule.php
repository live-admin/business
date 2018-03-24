<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>按住一秒</title>
		<link href="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>css/gedc.css" rel="stylesheet" type="text/css">
		<!--<link href="css/common.css" rel="stylesheet" type="text/css" />-->

		<!--<script src="http://www.fz222.com/weixin/share/share.php" type="text/javascript"></script>-->
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/jweixin-1.0.0.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/weixin.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/jquery.lazyload.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/other.js"></script>
		<script src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/gunDong.js" type="text/javascript" charset="utf-8"></script>
	</head>

	<body>
		<div class="gedc">
			<div class="top_bg">
				<img src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>images/top_bg.png" />
				<!--<div class="top_img"><img src="images/top.png" /></div>
				<p class="title">你能精确的按出<span class="red_txt">1秒</span>钟吗？</p>-->
			</div>

			<!--活动规则-->
			<div class="rule" style="display: block;">
				<p class="activity_title">活动规则</p>
				<p>1.抽奖资格说明：</p>
				<p>(1)老用户每天登陆彩之云APP或网站则获赠1次挑战机会；</p>
				<p>(2)新注册用户登录APP或网站成功后获赠10次挑战机会；</p>
				<p>(3)推荐好友注册成功获赠5次挑战机会；</p>
				<p>(4)用户使用投诉报修功能每天获赠1次挑战机会；</p>
				<p>(5)任意交易成功(充值、商品交易、缴物业费、缴停车费、参加彩富人生等)获赠5次挑战机会；</p>
				<p>(6)每天每位用户最多可挑战5次。</p>
				<p>2.饭票可用于：缴纳物业费和停车费，预缴物管费，商品交易，手机充值。</p>
				<p>3.用户可在“我-我的饭票”中查看饭票余额。</p>
				<p class="activity_title">奖项设置</p>
				<p>1.100万张饭票，最高奖金88元；</p>
				<p>2.10万张著名景点或酒店的电子优惠券；</p>
				<p>3.1万份泰康人寿一年免费意外险，最高保100万。</p>
				<div class="return_btn">返 回</div>
			</div>

			<div class="top_bg">
				<img src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>images/footer_bg.png" />
				<!--<p class="footer_advice">★注：彩之云在法律规定的范围内具有最终解释权</p>-->
			</div>
		</div>

		<script type="text/javascript">
			$(function() {
				$('.return_btn').click(function(){
					window.history.back();
				})
				});
		</script>
	</body>

</html>