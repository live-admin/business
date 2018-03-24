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
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>js/ranking.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>css/layout.css"/>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css"/>
	</head>
	<body >
		<div class="content">
			<div class="deng_bg">
				<ul>
					<li></li>
					<li>
						<p></p>
						<p class="animal"></p>
					</li>
				</ul>
			</div>
			
			<div class="rank_nav">
				<div class="rank_nav_title">
					<ul>
						<li>
							<p class="p1">用户</p>
						</li>
						<li>
							<p class="p2">生肖</p>
						</li>
					</ul>
				</div>
				
				<div class="user_hide">
					<div class="user_rank_nav_title">
						<ul>
							<li>排名</li>
							<li>用户名</li>
							<li>生肖</li>
							<li>福气值</li>
							<li>操作</li>
						</ul>
					</div>
					<!--<div class="me_user_rank">
					</div>-->
					<div class="other_user_rank">
					</div>
				</div>
				
				<div class="shenxiao_hide hide">
					<div class="shenxiao_rank_nav_title">
						<ul>
							<li>排名</li>
							<li>生肖</li>
							<li>累计福气值</li>
						</ul>
					</div>
					<!--<div class="me_shenxiao_rank">
					</div>-->
					<div class="other_shenxiao_rank">
					</div>
				</div>
			</div>
			<div class="clour_bg">
			</div>
		</div>
		
		<script type="text/javascript">
		var customer_info = <?php echo json_encode($customer_info) ?>;
		
		var user = <?php echo json_encode($user_ranking) ?>;
		var shenxiao = <?php echo json_encode($zodiac_ranking) ?>;
		
		
		var img_url = "<?php echo F::getStaticsUrl('/activity/v2016/spring/images');?>";
		</script>
	</body>
</html>
