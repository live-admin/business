<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>雀神竞猜</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
 		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>js/share.js"></script>
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="contaner">
			<div class="contaner_bg">
				<div class="contaner_txt">竞猜雀神得主，猜中冠军的用户即可瓜分5000元奖金包。</div>
				<div class="per_info">
					<div class="per_photo"><img src="images/r_icon.png"/></div>
					<p><span>王二小</span><span>35</span><span>岁</span></p>
					<p><span>3</span><span>年牌龄</span><span>战绩：</span><span>100</span><span>分</span></p>
				</div>
				<div class="share_num_box">
					<div class="share_num_txt">人气值</div>
					<div class="share_site_bot_box1a">
						<p><span class="share_plan"></span></p>
					</div>
					<div class="share_cai_btn"><span>30</span><span>票</span></div>
				</div>
				<div class="talk">
					<div class="talk_title">比赛宣言</div>
					<div class="talk_txt">亮出最甜的微笑,展示最美好的自己!太,阳有属于他的光芒,我也一样能创造出我的辉煌!亮出最甜</div>
				</div>
				<div class="boot_share_cai_btn">猜TA赢</div>
			</div>
		</div>
		
		<div class="mask hide"></div>
		
		<div class="change_Pop hide">
			<div class="pop_wrap">
				<div class="line"></div>
				<div class="detail">
					<p>只有彩之云用户才可投票！</p>
					<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">立即下载</a>
				</div>
				<div class="change_Pop_dele">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/dele.png"/>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			var listShare = <?php echo json_encode($listShare);?>;
		</script>
	</body>
</html>
