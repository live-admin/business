<!doctype html>
<html lang="zh-CN">

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta http-equiv="pragma" content="no-cache"> 
		<meta http-equiv="Cache-Control" content="no-cache, must-revalidate"> 
		<meta http-equiv="expires" content="0">
		<title>摇一摇</title>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/baoXiang/'); ?>css/layout.css">
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/baoXiang/');?>css/animate.min.css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>js/zepto.min.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>js/red.js"></script>
		<script src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>js/ReFontsize.js" type="text/javascript"></script>
		<style>
				body{
					background-image: url(<?php echo F::getStaticsUrl('/home/baoXiang/');?>/images/banner_bg.jpg) !important;  
				}
       </style>
	</head>

	<body>
		<!-- 摇一摇音乐 -->
		<audio id="shakemusic" src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>music/red-01.mp3" style="display: none;"></audio>
		<audio id="openmusic" src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>music/red-02.mp3" style="display: none;"></audio>
		
		<div class="red_bg" id="red_bg">
			<div class="red-ts"></div>
			<div class="red-ss-bg">
				<span class="red-ss animated"></span>
			</div>
			<div class="shake1">
				<p>摇一摇有几率获得紫宝石、商家折扣券及星晨旅游优惠券。</p>
			</div>
			<div class=""></div>
			<div class="g_bag hide">
				<div class="g_bag1a">
					
				</div>
			</div>
			<div class="stone-tc hide">
				<!--获得宝石-->
				<p>恭喜您获得1个紫宝石</p>
				<div class="baoshi">
					<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/purple.png">
				</div>
			</div>
			<div class="no_weix hide">
				<div class="no_weix1a">
					<p><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/biaoqing.png"></p>
                    <p class="tishi"></p>
				</div>
			</div>
		</div>
        <div class="mask hide"></div>
        <input type="hidden" class="mobile" value="<?php echo $mobile;?>">
		<!-- End 红包 -->
	</body>

</html>