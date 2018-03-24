<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>人气召集令-好友助力</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/popularity/');?>js/share.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/popularity/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/popularity/');?>css/new1.css" />
	</head>
	<body>
		<div class="conter help">
			<div class="help_banner"></div>
			<div class="help_site">
				<p>助力规则</p>
				<p>1.每个好友每天仅可助力一次;</p>
				<p>2.每次可增加1点人气值。</p>
			</div>
			<div class="help_site">
				<p>参与方式</p>
				<p>彩之云app内点击“人气召集令”广告轮播图参与。</p>
			</div>
			<div class="help_con">
				<p>当前已累计人气：<span id="num"><?php echo $num?></span></p>
			</div>
			<div class="help_button">
				<p>我要助力</p>
			</div>
		</div>
		<div class="mask hide"></div>
		<!--弹窗开始-->
		<div class="pop_help hide">
			<div class="pop_site">
				<p>助力成功！</p>
			</div>
			<div class="pop_button">
				<p>我也要发起</p>
			</div>
		</div>
		<!--弹窗结束-->
		
		<!--加载-->
		<div class="loaders hide">
	 	 	<div class="loader">
		    	<div class="loader-inner ball-pulse">
			      	<div></div>
			      	<div></div>
			      	<div></div>
			    </div>
		  	</div>
		</div>
		
		<script>
		    var sd_id=<?php echo $sd_id;?>;
		   
		</script>
	</body>
</html>


