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
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>js/yh.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="contaner">
			<div class="contaner_bg">
				<div class="present_bg present_bg_51"></div>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>images/26_bg1.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>images/bg2.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>images/bg3.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>images/arrow.png" class="zhizhen"/>
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
				<p>获得一等奖-100彩之云饭票券，</p>
				<p>提交信息即可领取。</p>
			</div>
			<div class="fp_img">
				<p>100饭票券</p>
			</div>
			<p class="p_txt">有效期：2016年9月14日~2016年9月29日</p>	
			<div class="lq_btn">
				<a href="/CaiZhuZhaiActivity/Receive/<?php echo $activity_id;?>">领取</a>
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
			var activtyId = <?php echo $activity_id; ?>;
		</script>
	</body>
</html>
