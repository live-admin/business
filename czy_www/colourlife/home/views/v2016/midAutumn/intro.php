<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>活动介绍</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>js/intro.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<input style="display: none" id="vserion" />
		<div class="contaner">
			<div class="contaner_bg"></div>	
			
			<div class="contaner_bar">
				<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/stars.png"/></span>活动规则：</p>
				<p>1.活动期间，用户单笔订单满800元，立返50饭票，满300元，立返15饭票;（彩子佳人款月饼（2个装）不参与满返活动）</p>
				<p>2.用户成功付款后，所返金额 将以饭票的形式返回到用户账户；</p>
				<p>3.若用户下单后退款，只退回不包括活动奖励的部分金额。</p>
			</div>
			
			<div class="contaner_bar">
				<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/stars.png"/></span>活动时间：</p>
				<p>2016年8月15日~2016年9月15日。</p>
			</div>
			
			<div class="contaner_btn">
				<a href="javascript:void(0);">分享</a>
			</div>
			
		</div>
		
		<script>
    	var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/midAutumn/');?>images/img_pro.png";
		var url="<?php echo F::getHomeUrl('/MidAutumn/Share?reUrl='.$url.'&share=ShareWeb&sl_key=656'); ?>"
        	
        
       //新版分享功能参数
		var u = navigator.userAgent, app = navigator.appVersion;
	    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	    
	    	if (isAndroid) {
			var param={
		    		"platform": [
		                 "ShareTypeWeixiSession",
		                 "ShareTypeWeixiTimeline",
//		                 "ShareTypeQQ",
//		                 "ShareTypeSinaWeibo",
		                 "NeighborShare"
		             ],
		             "title": "浓情中秋",
		             "url": url,
		             "image": imgUrl,
		             "content": "彩之云中秋特惠礼包",
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
	             "title": "浓情中秋",
	             "url": url,
	             "image": imgUrl,
	             "content": "彩之云中秋特惠礼包",
	         };
	    }
		
   	 	//旧版分享功能参数
		var params = {
	            "text" : "彩之云中秋特惠礼包",
	            "imageUrl" : imgUrl,
	            "url":url,
	            "title" : "浓情中秋",
	            "titleUrl" : url,
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : url,
	            "type" : $sharesdk.contentType.WebPage
	        };

			function ColourlifeShareHandler(response){
				if(response.state==1){
					shareAfter(2);
				}
			}
			function ColourlifeShareHandlerAndroid(response){
				var data=JSON.parse(response);
					if(data.state==1){
						shareAfter(2);
					}
				}
			function shareAfter(type){
					$.ajax({
				          type: 'POST',
				          url: '/MidAutumn/Log',
				          data: 'type='+type,
				          dataType: 'json',
				          success: function (result) {
					          
				          }
				        });
				}
		</script>
	</body>
</html>
