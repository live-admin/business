<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>双旦寻宝</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>js/share.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>js/bottom-download.js" ></script>
	</head>
	<body>
		<section class="conter share">
			<header class="share_header">
				<p><span class="share_tel">156*****136</span>分享了拼图</p>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/banner01.png">
				<a href="javascript:void(0)" class="get">领取拼图(<span class="surplus_number">1</span>/<span class="share_number">3</span>)</a>
				<a href="javascript:void(0)" class="hui_btn hide">抢完了</a>
			</header>
			<article class="share_c">
				<h4>参与活动即可赢取以下奖品</h4>
			</article>
			<section class="share_b">
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/share_czy_meal.png">
					<p>10万彩饭票</p>
				</a>
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/share_youhuijuan.png">
					<p>彩特供满100减5元优惠券</p>
				</a>
			</section>
			<section class="share_b">
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/share_xiaomi.png">
					<p>彩之云充电宝</p>
				</a>
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/share_u.png">
					<p>彩之云U盘</p>
				</a>
			</section>
		</section>
		<!--下载开始-->
		<div class="btm_bg"></div>
	    <div class="share_footer">
	     	<div class="share_footer_box01">
	     		<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/czy_logo.png">
	     	</div>
	     	<div class="share_footer_box02">
	     		<a href="javascript:void(0);">
	     			立即下载
	     		</a>
	     	</div>
	     	<div class="share_footer_box03">
	     		<a href="javascript:void(0)">
	     			<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/delet.png">
	     		</a>
	     	</div>
	    </div>
		<!--下载结束-->
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗开始-->
		<div class="share_pop hide">
			<div class="share_pop_title">
				<p>请输入手机号领取</p>
			</div>
			<div class="share_pop_site phone">
				<input type="text" placeholder="请输入手机号">
			</div>
			<div class="share_pop_code hide">
				<input type="text" placeholder="请输入验证码">
				<p></p>
				<p class="code">获取验证码</p>
			</div>
			<div class="share_pop_p">
				<p class="hide">手机号码输入错误</p>
			</div>
			<div class="share_btn">
				<a href="javascript:void(0);">确定</a>
			</div>
		</div>
		<!--弹窗结束-->
		
		<script>
				var data= <?php echo $outData;?>;
			    var phone=data.user_mobile;
			    var tel=phone.replace(/(\d{3})\d{5}(\d{3})/, '$1*****$2');
			    var num=parseInt(data.surplus_number);
				$(".share_tel").text(tel);
			    $(".share_header").find("img").attr("src",data.card_image);
			    $(".surplus_number").text(num);
			    $(".share_number").text(data.share_number);
		</script>
	</body>
</html>
