<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>爱就去勾搭</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>css/normalize.css">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>js/share_over.js"></script>
	    <style>
	    	//浏览器指示
		        .download {
		            width: 100%;
		            height: 100%;
		            position: absolute;
		            top: 0;
		            left: 0;
		            border:1px red solid;
		        }
		        .download img{
		            width:100%;
		            height: 100%;
		            position: absolute;
		            top: -8%;
		        }
	    </style>
	</head>
	<body>
		<div class="gameover">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/loveisUp/');?>images/gameover_img.png"/>
				<p>1000份奖品近在咫尺，生命不息，游戏精神不衰，加油赚积分吧！</p>
				<p>恭喜本次游戏一共获得积分：<?php echo $integ;?>分</p>
				
				<div class="agame_btn">
					<a href="#">打开彩之云</a>
				</div>
		</div>
		 <!--遮罩层开始-->
	     <div class="mask hide"></div>
	     <!--遮罩层结束-->
	     <div class="download hide" style=" width: 100%;height: 100%;position: absolute;top: 0;left: 0;z-index: 10;">
              <img src="<?php echo F::getStaticsUrl('/activity/v2016/arborDayphaseII/');?>images/download_ios.png" />
         </div> 
	</body>
</html>
