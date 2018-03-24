<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>猴赛雷的宝箱</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telphone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>js/baoWay.js"></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
</head>
<body>
	<input style="display: none" id="vserion" />
    <div class="strategy">
        <div class="strategy_top">
            <p>点击完成下面的任务可获得不同类型的宝石！</p>
        </div>
        <div class="strategy_site">
            <a href="javascript:void(0);" class="neig">
                在邻里圈畅所欲言一把！
            </a>
            <a href="javascript:void(0);" class="we_chat">
                把活动告诉邻里圈/微信群的朋友们
            </a>
            <a href="javascript:void(0);" class="Property">
                尝试在彩之云上缴纳物业费
            </a>
            <a href="javascript:void(0);" class="we_chat_frid">
                让好友为你点亮宝石！
            </a>
            <a href="javascript:void(0);" class="stop_regst">
                <p>尝试在彩之云上缴纳停车费！</p>
                <p>邀请好友注册！</p>
            </a>
        </div>
        <div class="rule_footer"></div>
    </div>
    
    <!--邻里弹窗-->
	<div class="pop02 hide">
		<img src="<?php echo F::getStaticsUrl('/activity/v2016/box/');?>images/smile.png"style="width: 50px;padding-top: 15px;">
		<div class="pop02_site" style="width: 100%;">
			<p style="line-height: 1.5rem;">去邻里留言可以获得宝石哦～</p>
		</div>
	</div>
	<div class="bg_mask hide"></div>
    
    
		<script>
		var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/box/');?>images/img_pro.png";
		var url="<?php echo F::getHomeUrl('/Box/Share?reUrl='.$url.'&share=ShareWeb'); ?>"
		
		//新版分享功能参数
		var u = navigator.userAgent, app = navigator.appVersion;
	    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	    
	    if (isAndroid) {
			var param={
		    		"platform": [
		                 "ShareTypeWeixiSession",
		                 "ShareTypeWeixiTimeline",
		                 "ShareTypeQQ",
		                 "ShareTypeSinaWeibo",
		                 "NeighborShare"
		             ],
		             "title": "亲，快帮我点亮宝石吧",
		             "url": url,
		             "image": imgUrl,
		             "content": "集齐7颗宝石，即可开箱获得大奖！",
		         };
	    }
	    else if (isiOS) {
	    	var param={
	    		"platform": [
	                 "ShareTypeWeixiSession",
	                 "ShareTypeWeixiTimeline",
//	                 "ShareTypeQQ",
//	                 "ShareTypeSinaWeibo"
	            	 "NeighborShare"

	
	             ],
	             "title": "亲，快帮我点亮宝石吧",
	             "url": url,
	             "image": imgUrl,
	             "content": "集齐7颗宝石，即可开箱获得大奖！",
	         };
	    }
		
   	 	//旧版分享功能参数
		var params = {
	            "text" : "集齐7颗宝石，即可开箱获得大奖！",
	            "imageUrl" : imgUrl,
	            "url":url,
	            "title" : "亲，快帮我点亮宝石吧！",
	            "titleUrl" : url,
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : url,
	            "type" : $sharesdk.contentType.WebPage
	        };
		</script>
</body>
</html>
