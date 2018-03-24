<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>彩住宅抽奖</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/newcaiZhuZhai/');?>js/cj.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/newcaiZhuZhai/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="contaner">
			<div class="contaner_bg">
				<div class="present_bg">
					<div class="one">
						<p>一等奖</p>
						<p>200饭票券</p>
					</div>
					<div class="two"><p>3.8元</p>
						<p>无门槛优惠券</p>
					</div>
					<div class="three"><p>3.8元</p>
						<p>无门槛优惠券</p>
					</div>
					<div class="four"><p>3.8元</p>
						<p>无门槛优惠券</p>
					</div>
					<div class="five"><p>3.8元</p>
						<p>无门槛优惠券</p>
					</div>
				</div>
				
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/newcaiZhuZhai/');?>images/bg1.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/newcaiZhuZhai/');?>images/bg2.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/newcaiZhuZhai/');?>images/bg3.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/newcaiZhuZhai/');?>images/arrow.png" class="zhizhen"/>
				<div class="start" id="actionBtn">开始抽奖</div>
			</div>
			<div class="rule">活动规则 >></div>
		</div>
		
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<div class="mask01 hide"></div>
		<!--遮罩层结束-->
		
		<!--得奖弹窗-->
		<div class="pop hide">
			<div class="pop_txt">
				<p>恭喜您</p>
				<p>获得一等奖-20彩之云饭票券，</p>
				<p>提交信息即可领取。</p>
			</div>
			<div class="fp_img">
				<p>20饭票券</p>
			</div>
			<p class="p_txt">有效期：2017年1月19日~2016年2月19日</p>	
			<div class="lq_btn">
				<a href="/CaiZhuZhaiActivity/Receive/<?php echo $activity_id;?>?parent=add">领取</a>
			</div>
		</div>
		
		<!--结束弹窗-->
		<div class="pop_over hide">
			<div class="pop_over_txt">
				<p>温馨提示</p>
				<p>您来晚一步，奖品都被领走了</p>
				<p>马上下载彩之云APP抢更多福利。</p>
			</div>
			<div class="lq_btn_over">
				<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">去看看</a>
			</div>
		</div>
		<script type="text/javascript">
			var prize = <?php echo json_encode($prize); ?>;
			var activtyId = <?php echo $activity_id; ?>;
			
			$(document).ready(function(){
				$(".one>p:eq(0)").text(prize[5].award);
				$(".one>p:eq(1)").text(prize[5].prize+'饭票券');
				
				$(".three>p:eq(0)").text(prize[1].award);
				$(".three>p:eq(1)").text(prize[1].prize+'饭票券');
				
				$(".five>p:eq(0)").text(prize[2].award);
				$(".five>p:eq(1)").text(prize[2].prize+'饭票券');
				
				$(".four>p:eq(0)").text(prize[3].award);
				$(".four>p:eq(1)").text(prize[3].prize+'饭票券');
				
				$(".two>p:eq(0)").text(prize[4].award);
				$(".two>p:eq(1)").text(prize[4].prize+'饭票券');
			});
		</script>
		
	</body>
</html>
