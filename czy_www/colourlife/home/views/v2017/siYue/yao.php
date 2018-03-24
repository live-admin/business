<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>四月拼团-分享</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/siYue/');?>js/yao.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/siYue/');?>css/layout.css">
	</head>
	<body style="background: #f7f7f7;">
		<input style="display: none" id="vserion" />
		<div class="nav">
			<ul>
			</ul>
			<p class="nav_txt">邀请新用户注册，在邀请对象成功下单并支付后您将获得半价兑换券，可兑换上方两个商品。</p>
			<p class="nav_txt">兑换券将在受邀对象支付成功后自动发送至您的卡包，可在“我的”-“我的卡券”中查看。</p>
		</div>
		<div class="nav_btn">
			<div class="record_left">受邀记录</div>
			<div class="share_right">分享</div>
		</div>
		<div class="record_pic">
			<img src="<?php echo F::getStaticsUrl('/activity/v2017/siYue/');?>images/bot_icon.jpg"/>
		</div>
		
		<script type="text/javascript">
			var tuanUrl = <?php echo json_encode($tuanUrl);?>;
			var list = <?php echo json_encode($yaoProduct);?>;
			var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2017/siYue/');?>images/share_icon.png";
			var url="<?php echo F::getHomeUrl('/SiYue/Share?reUrl='.$url.'&share=ShareWeb'); ?>"
			//分享     start  *****************************************************
			//新版分享功能参数
			var u = navigator.userAgent, app = navigator.appVersion;
		    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
		    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
		    
		    if (isAndroid) {
				var param={
			    		"platform": [
			                 "ShareTypeWeixiSession",
			                 "ShareTypeWeixiTimeline"
//			                 "ShareTypeQQ",
//			                 "ShareTypeSinaWeibo"
//			                 "NeighborShare"
			             ],
			             "title": "精明生活家",
			             "url": url,
			             "image": imgUrl,
			             "content": "半价牛奶限时抢！",
			         };
		    }
		    else if (isiOS) {
		    	var param={
		    		"platform": [
		                 "ShareTypeWeixiSession",
		                 "ShareTypeWeixiTimeline"
	//	                 "ShareTypeQQ",
	//	                 "ShareTypeSinaWeibo"
//		            	 "NeighborShare"
		
		
		             ],
		             "title": "精明生活家",
		             "url": url,
		             "image": imgUrl,
		             "content": "半价牛奶限时抢",
		         };
		    }
			
		</script>
		
	</body>
</html>
