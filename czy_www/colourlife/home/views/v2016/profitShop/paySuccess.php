<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>VIP尊享</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>js/paySuccess.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>css/normalize.css">
	</head>
	<body style="background: #F1F2F3;">
		<input style="display: none" id="vserion" />
		<div class="conter">
			<div class="conter_suc">
				<p>恭喜您，成功参与彩富人生！</p>
				<p>您获得一次至尊vip购物机会，分享给好友，机会翻倍哦！</p>
			</div>
			<!--商品-->
			<div class="conter_img">
				<?php foreach($goods as $goodsInfo): ?>
					<div class="conter_img_left">
						<a href="javascript:void(0);">
							<img src="<?php echo $goodsInfo['img_name']; ?>"/>
							<p>原价：<span class="yuanjia">¥ </span><span class="yuanjia_money"><?php echo $goodsInfo['market_price']; ?></span></p>
							<p>彩富价：<span class="cfjia">¥ </span><span class="cfjia_money"><?php echo $goodsInfo['customer_price']; ?></span></p>
							<p>为你节省<span><?php echo ($goodsInfo['market_price'] - $goodsInfo['customer_price']); ?></span>元</p>
							<div class="hot"></div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
			
			<div class="conter_reg">
				<p>邀请好友参加</p>
				<p>各得至尊VIP购买机会</p>
				<p>奖励规则：好友领取次数越多，您尊享购买次数也越多</p>
			</div>
			
			<div class="conter_btn">
				<div class="conter_btn_send">发福利</div>
				<div class="conter_btn_mark">去商城看看</div>
			</div>
		</div>
		
		
		<script>
		var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/img_pro.png";
		var url="<?php echo F::getHomeUrl('/profitShop/Share?reUrl='.$url.'&share=ShareWeb'); ?>"
			
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
		             "title": "10元一桶油、10元一袋米",
		             "url": url,
		             "image": imgUrl,
		             "content": "生活回到解放前",
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
	             "title": "10元一桶油、10元一袋米",
	             "url": url,
	             "image": imgUrl,
	             "content": "生活回到解放前",
	         };
	    }
		
   	 	//旧版分享功能参数
		var params = {
	            "text" : "生活回到解放前",
	            "imageUrl" : imgUrl,
	            "url":url,
	            "title" : "10元一桶油、10元一袋米",
	            "titleUrl" : url,
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : url,
	            "type" : $sharesdk.contentType.WebPage
	        };
        function ColourlifeShareHandler(response){
//				alert(response.shareType);
//				alert(response.state);
		if (response.state==1) {
			
		} 
			
		}
		function ColourlifeShareHandlerAndroid(response){
			var data=JSON.parse(response);
	//				alert(data.shareType);
	//				alert(data.state);
				if (response.state==1) {
					
				} 
			}
		function shareAfter(){
				
			}

		$(".conter_btn_mark").click(function(){
			//mobileJump("EReduceList");
			var url = '<?php echo $shop_url; ?>';
			window.location.href = url;
		});
		
		function mobileJump(cmd)
	    {
	        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
	            var _cmd = "http://www.colourlife.com/*jumpPrototype*colourlife://proto?type=" + cmd;
	            document.location = _cmd;
	        } else if (/(Android)/i.test(navigator.userAgent)) {
	            var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
	            eval(_cmd);
	        } else {
	
	        }
	    }
		</script>
	</body>
</html>
