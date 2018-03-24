<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>彩富国庆巨献</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
     	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>css/draw.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>css/normalize.css">
	</head>
	<body style="background: #f7f7f7;">
		<input style="display: none" id="vserion" />
		<div class="content">
			<div class="caifu">
				<p>彩富人生 至尊服务</p>
				<p>点击“发福利”邀请好友各获得彩富价购买油或米的尊享特价</p>
				<div class="caifu_box">
					<div class="caifu_box_a">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/pr1.png"/>
					</div>
					<div class="caifu_box_b">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/pr2.png"/>
					</div>
				</div>
				<div class="caifu_btn">
					<div class="caifu_btn_fuli">
						<a href="javascript:void(0);">发福利</a>
					</div>
					<div class="caifu_btn_see">
						<a href="/ProfitShop">去看看</a>
					</div>
				</div>
			</div>
			
			<div class="content_banner">
				<h5>彩富尊享特权</h5>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/banner1.png"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/banner2.png"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/banner3.png"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/banner4.png"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/banner5.png"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/banner6.png"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/banner7.png"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/banner8.png"/>
			</div>
		</div>
		<!--遮罩层-->
		<div class="mask hide"></div>
		<!--弹窗-->
		<div class="Pop hide">
			<div class="Pop_img">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/pop.png"/>
			</div>
			<div class="Pop_tb">
				<p>只有彩富用户才能有分享功能</p>
				<a href="javascript:void(0);">立即参加</a>
			</div>
		</div>
	
	<script type="text/javascript">
		var shareUrl = '<?php echo $share_url; ?>';
		var national_day_url = '<?php echo $national_day_url; ?>';
		var car_insure_url = '<?php echo $car_insure_url; ?>';
		var daZhaiXie_url = '<?php echo $daZhaiXie_url; ?>';
		var eTravel_url = '<?php echo $eTravel_url; ?>';
		var eZuFang_url = '<?php echo $eZuFang_url; ?>';
		var eWeiXiu_url = '<?php echo $eWeiXiu_url; ?>';
		var pingTuan_url = '<?php echo $pingTuan_url; ?>';
		var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/img_pro.png";
		var url="<?php echo F::getHomeUrl('/profitShop/Share?reUrl='.$share_url.'&share=ShareWeb'); ?>"
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
			
			
		$(".caifu_btn_fuli").click(function(){
			if (shareUrl=="") {
				$(".Pop").removeClass("hide");
				$(".mask").removeClass("hide");
				
				
			}
			else{
				showShareMenuClickHandler();
			}
		});
		
		$(".Pop_tb>a").click(function(){
			mobileJump("EReduceList");
		});
		$(".content_banner>img:eq(0)").click(function(){
			location.href = "/ProfitShop";
		});
		$(".content_banner>img:eq(1)").click(function(){
			location.href = national_day_url;
		});
		$(".content_banner>img:eq(2)").click(function(){
			location.href = car_insure_url;
		});
		$(".content_banner>img:eq(3)").click(function(){
			location.href = daZhaiXie_url;
		});
		$(".content_banner>img:eq(4)").click(function(){
			location .href = eTravel_url;
		});
		$(".content_banner>img:eq(5)").click(function(){
			location.href = eZuFang_url;
		});
		$(".content_banner>img:eq(6)").click(function(){
			location.href = eWeiXiu_url;
		});
		$(".content_banner>img:eq(7)").click(function(){
			location.href = pingTuan_url;
		});
		
		$(".mask").click(function(){
			$(".Pop").addClass("hide");
			$(".mask").addClass("hide");
		});
		
		function mobileJump(cmd){
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
