<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>荔枝节-规则</title>
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
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>js/yao.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>css/layout.css">
	</head>
	<body style="background: #fff7f7;">
		<input style="display: none" id="vserion" />
		<div class="conten_rule">
			<p>1、当抢购周期已结束或抢购商品已卖光，则抢购结束。</p>
			<p>2、抢购商品下单后需立即支付，只有当付款成功才视为抢购成功，否则由于数量有限，您下单的产品很可能被别人先付款抢走。由此给您带来的不便，敬请谅解。</p>
			<p>3、抢购未结束：该款抢购商品被售光，则该商品所有“等待支付”状态的订单将被自动取消，请抢到后尽快支付订单。</p>
			<p>4、抢购已结束：该款抢购商品未售光，则该商品所有“等待支付”状态的订单，从抢购截止日期开始的15分钟内仍未支付的，将被自动取消。</p>
			<p>5、每场抢购时间为30分钟，不在规定时间内抢购的商品视为无效，所支付金额将在五个工作日内原路退回。</p>
			<p style="font-size: 0.32rem; padding-top: 0.7rem;">荔枝节邀请送规则：</p>
			<p>1、邀请新用户成功下单购买5斤荔枝，邀请者可获得5彩饭票作为奖励；</p>
			<p>2、若出现作弊行为的账号，将做封号处理；</p>
			<p>3、奖励饭票金额将以邀请记录为准。</p>
			
			<div class="btn">
				<ul>
					<li>邀请记录</li>
					<li>邀请</li>
				</ul>
			</div>
		</div>
		
		<script type="text/javascript">
			var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2017/liZhiJie/');?>images/share_icon.jpg";
			var url="<?php echo F::getHomeUrl('/LiZhiJie/Share?reUrl='.$url.'&share=ShareWeb'); ?>"
			
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
			             "title": "彩之云岭南荔枝9.9元限时抢！！",
			             "url": url,
			             "image": imgUrl,
			             "content": "荔枝节暨彩生活上市周年庆！美味荔枝限时抢购～来晚了就没有啦！！快来抢购吧！！",
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
		             "title": "彩之云岭南荔枝9.9元限时抢！！",
		             "url": url,
		             "image": imgUrl,
		             "content": "荔枝节暨彩生活上市周年庆！美味荔枝限时抢购～来晚了就没有啦！！快来抢购吧！",
		         };
		    }
			
		</script>
		
		
	</body>
</html>
