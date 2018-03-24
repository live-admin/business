<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>邀请有奖</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
 		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>js/index.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background: #F0E8E4;">
		<input style="display: none" id="vserion" />
		<div class="contaniss">
			<div class="top_box">
				<div class="top_box_tips">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/tip.png"/>
				</div>
				<div class="top_box_txt">
					<p>新人有礼，立即抽奖 >></p>
				</div>
				<div class="top_box_dele">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/dele.png"/>
				</div>
			</div>
			<div class="product_a"></div>
			<div class="bg_img ">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/bg1.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/bg2.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/bg3.jpg"/>
					<div class="suc_txt_box">
						<div class="suc_txt_p1">
							<p>成功邀请好友</p>
						</div>
						<div class="suc_txt_p2">
							<p><span>3</span><span>人</span></p>
						</div>
					</div>
					
					<div class="prz_txt">
						<div class="prz_txt_p1">
							<p>已获奖励</p>
						</div>
						<div class="prz_txt_p2">
							<p><span>3</span><span>饭票</span></p>
						</div>
					</div>
					<div class="prz_mingxi">奖励明细 >></div>
			</div>
			
			<div class="rule_bg">
				<div class="bg_box">
					<div class="regt_btn">
						<a href="javascript:void(0);">分享邀请</a>
					</div>
					<div class="regt_btn_duanxin">
						<a href="javascript:void(0);">短信邀请</a>
					</div>
				</div>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/dian.png"/>
				<div class="rule_txt">
					<p>活动规则</p>
					<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/start.png"/></span><span>新人饭票随机送：</span></p>
					<p>1.新用户首次登录app，即可参与随机抽奖送饭票；</p>
					<p>2.用户所抽到的饭票将直接存入个人彩之云账户中；</p>
					<p>3.每个ID限领取一次。</p>
					<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/start.png"/></span><span>首单有礼：</span></p>
			    	<p>新用户在彩生活特供下首单，赠送满100元减10元抵用券。</p>
					<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/start.png"/></span><span>邀请送好礼：</span></p>
					<p>1.每成功邀请一名好友注册，即可获得1饭票。</p>
					<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/start.png"/></span><span>备注：</span></p>
					<p>1.如活动期间发现作弊行为，则取消该ID的中奖资格，并予以封号处理;</p>
					<p>2.如因活动期间出现网络异常系统问题，导致无法正常显示中奖结果，一切将以获奖记录为准。</p>
					<p>活动由彩之云提供，与设备生产商Apple Inc.公司无关</p>
				</div>
			</div>
		</div>
	</body>
	
	<script type="text/javascript">
		var sum = <?php echo json_encode($sum);?>;
		var num = <?php echo json_encode($num);?>;
		var urlShare = "<?php echo F::getHomeUrl('/september/Share?reUrl='.$urlShare.'&share=ShareWeb&sl_key=september'); ?>";
		var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/img_pro.png";
		
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
		             "title": "邀请你加入彩之云",
		             "url": urlShare,
		             "image": imgUrl,
		             "content": "新人注册送好礼，快来一起瓜分奖品吧！",
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
	             "title": "邀请你加入彩之云",
	             "url": urlShare,
	             "image": imgUrl,
	             "content": "新人注册送好礼，快来一起瓜分奖品吧！",
	         };
	    }
		
   	 	//旧版分享功能参数
		var params = {
	            "text" : "新人注册送好礼，快来一起瓜分奖品吧！",
	            "imageUrl" : imgUrl,
	            "url":urlShare,
	            "title" : "邀请你加入彩之云",
	            "titleUrl" : urlShare,
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : urlShare,
	            "type" : $sharesdk.contentType.WebPage
	        };
	</script>
	
</html>
