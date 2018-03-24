<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>雀神竞猜</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>js/sai.js"></script>
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<input style="display: none" id="vserion" />
		<div class="contaner">
			<div class="contaner_bg">
				<div class="per_info">
					<div class="per_photo"><img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/r_icon.png"/></div>
					<p><span class="test">王二小</span><span>35</span><span>岁</span></p>
					<p><span>3</span><span>年牌龄</span><span>战绩：</span><span>100</span><span>分</span></p>
				</div>
				
				<div class="talk">
					<div class="talk_title">比赛宣言</div>
					<div class="talk_txt">亮出最甜的微笑,展示最美好的自己!太阳有属于他的光芒,我也一样能创造出我的辉煌!亮出最甜</div>
				</div>
				
				<div class="user_info">
					<p><span>32</span>位用户认为你就是雀神</p>
					<div class="user">
						<ul>
							<!--<li><img src="images/t_icon.png"/></li>
							<li><img src="images/t_icon.png"/></li>
							<li><img src="images/t_icon.png"/></li>
							<li><img src="images/t_icon.png"/></li>
							<li><img src="images/t_icon.png"/></li>
							<li><img src="images/t_icon.png"/></li>
							<li><img src="images/t_icon.png"/></li>
							<li><img src="images/t_icon.png"/></li>
							<li><img src="images/t_icon.png"/></li>
							<li><img src="images/t_icon.png"/></li>-->
							<!--<li class="more hide"><p>查看全部</p></li>-->
						</ul>
					</div>
				</div>
				
				<div class="bom_btn">
					<div class="share_btn">拉好友竞猜</div>
					<div class="cat_btn">猜TA赢</div>
				</div>
			</div>
		</div>
		<div class="mask hide"></div>
		
		<!--更多弹窗-->
		<div class="Pop_more_list hide">
			<div class="Pop_more_list_nav">
				<p>共<span>32</span>位用户支持夺冠</p>
				<ul>
					<!--<li><img src="images/t_icon.png"/></li>
					<li><img src="images/t_icon.png"/></li>
					<li><img src="images/t_icon.png"/></li>
					<li><img src="images/t_icon.png"/></li>
					<li><img src="images/t_icon.png"/></li>
					<li><img src="images/t_icon.png"/></li>
					<li><img src="images/t_icon.png"/></li>-->
				</ul>
			</div>
			<div class="Pop_more_list_dele">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/dele.png"/>
			</div>
		</div>
		
		<!--投票btn弹窗-->
		<div class="change_Pop hide">
			<div class="pop_wrap">
				<div class="line"></div>
				<div class="detail">
					<p>投票成功！</p>
					<p>邀请好友注册获得更多竞猜机会。</p>
					<a href="javascript:void(0);">立即邀请</a>
				</div>
				<div class="change_Pop_dele">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/queShen/');?>images/dele.png"/>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		var listOne = <?php echo json_encode($listOne);?>;
		var url="<?php echo F::getHomeUrl('/QueShen/Share?reUrl='.$url.'&share=ShareWeb&sl_key=720&isWx=1'); ?>"
		var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/property/');?>images/img_pro.png";
	
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
		             "title": "竞猜雀神得主，赢5000元奖金包！",
		             "url": url,
		             "image": imgUrl,
		             "content": "邀请好友为心目中的雀神投票，共赢5000奖包金！",
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
	             "title": "竞猜雀神得主，赢5000元奖金包！",
	             "url": url,
	             "image": imgUrl,
	             "content": "邀请好友为心目中的雀神投票，共赢5000奖包金！",
	         };
	    }
		
	   	//旧版分享功能参数
		var params = {
	            "text" : "邀请好友为心目中的雀神投票，共赢5000奖包金！",
	            "imageUrl" : imgUrl,
	            "url":url,
	            "title": "竞猜雀神得主，赢5000元奖金包！",
	            "titleUrl" : url,
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : url,
	            "type" : $sharesdk.contentType.WebPage
	        };
		</script>
		
	</body>
</html>
