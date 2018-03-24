<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>疯狂大抽奖</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>js/award.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>css/layout.css" />

	</head>
	<body>
		<div class="contaner">
			<input style="display: none" id="vserion" />
			<div class="contaner_bg">
				<div class="present_bg present_bg_50"></div>
				<p>剩余抽奖次数：<span class="cishu"><?php echo $leftChance;?></span>次</p>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/bg2.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/arrow.png" class="zhizhen"/>
				<div class="start" id="actionBtn">开始抽奖</div>
			</div>
			<div class="contaner_btn">
				<p class="share">分享</p>
				<p class="record">中奖纪录</p>
				<div class="clearfi"></div>
			</div>
			<div class="contaner_rule">
				<h4>活动规则</h4>
				<div class="contaner_p">
					<p>1.活动时间：2017年2月9日－2017年2月28日;</p>
					<p>2.每个ID每天拥有3次游戏机会；</p>
					<p>3.游戏中所得奖励（优惠券），仅限本次活动中使用；</p>
					<p>4.下单完成后，若用户申请退款，只返还减去游戏优惠券以后的金额；</p>
					<p>5.若因系统问题造成优惠券延迟到账，请联系客服人员；</p>
					<p>6.活动期间若出现作弊行为，则取消该帐号活动资格，情节严重者将给予封号处理。</p>
					<p>本活动最终解释权归彩之云所有。</p>
				</div>
				<div class="line"></div>
			</div>
		</div>
		
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗-->
		<div class="pop hide">
			<div class="pop_top">
				<p class="p00"></p>
			</div>
			<div class="pop_banner">
				<p>没有中奖哦！再接再厉！</p>
			</div>
			<div class="pop_btn">
				<p>确认</p>
			</div>
		</div>
		<!--弹窗end-->
		<script>
			var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/yuanXiao/');?>images/img_pro.png";
		    var url="<?php echo F::getHomeUrl('/YuanXiao/Share?reUrl='.$surl.'&share=ShareWeb&sl_key=809'); ?>"
		    var u = navigator.userAgent, app = navigator.appVersion;
	        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	    
		    if (isAndroid) {
				var param={
			    		"platform": [
//			                 "ShareTypeWeixiSession",
//			                 "ShareTypeWeixiTimeline",
//			                 "ShareTypeQQ",
//			                 "ShareTypeSinaWeibo",
							 "NeighborShare"
			             ],
			             "title": "颜值高的，都在这里 ",
			             "url": url,
			             "image": imgUrl,
			             "content": "新年礼，满99减50哟！",
	       //		     "NeighborShare":1 //0不显示，1显示
			       };  
		    }
		    else if (isiOS) {
		    	var param={
		    		"platform": [
//		                 "ShareTypeWeixiSession",
//		                 "ShareTypeWeixiTimeline",
		                 "NeighborShare",
//		                 "ShareTypeQQ",
//		                 "ShareTypeSinaWeibo"
		
		             ],
		             "title": "颜值高的，都在这里 ",
		             "url": url,
		             "image": imgUrl,
		             "content": "新年礼，满99减50哟！",
		             
		       };
		}
		         
		    
			
	   	 	//旧版分享功能参数
			var params = {
		            "text" : "新年礼，满99减50哟！ ",
		            "imageUrl" : imgUrl,
		            "url":url,
		            "title" : "颜值高的，都在这里 ",
		            "titleUrl" : url,
		            "description" : "描述",
		            "site" : "彩之云",
		            "siteUrl" : url,
		            "type" : $sharesdk.contentType.WebPage
		        };
			
		</script>
	</body>
</html>

