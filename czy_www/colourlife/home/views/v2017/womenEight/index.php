<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>3.8线上活动</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2017/WomenEight/');?>js/index.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/WomenEight/');?>css/layout.css" /> 
</head>
<body>
	<input style="display: none" id="vserion" />
	<header>
		<div class="banner"></div>
		<div>
			<a href="/WomenEight/Rule">
				<i></i>活动规则
			</a>
		</div>
	</header>
	<nav>
		<ul>
			<li>
				<div>
					<p>女王驾到 美味直降</p>
					<span>吃货狂欢</span>
				</div>
				<i></i>
			</li>
			<li>
				<div>
					<p>一元购</p>
					<span>只需一元便可</span>
				</div>
				<i></i>
			</li>
			<li>
				<div>
					<p>魅力女人芳心挚爱</p>
					<span>女王的美妆</span>
				</div>
				<i></i>
			</li>
			<li>
				<ol class="small three">
					<li>
						<div>
							<p>低至五折</p>
							<span>爆款直降</span>
						</div>
						<i></i>
					</li>
					<li>
						<div>
							<p>巾心挑选 至柔宠爱</p>
							<span>呵护姨妈</span>
						</div>
						<i></i>
					</li>
				</ol>
			</li>
			<li>
				<ol class="small four">
					<li>
						<div>
							<p>大牌惊喜</p>
							<span>必抢尖货</span>
						</div>
						<i></i>
					</li>
					<li>
						<div>
							<p>嗨翻低价</p>
							<span>颜值来了</span>
						</div>
						<i></i>
					</li>
				</ol>
			</li>
			<li>
				<div>
					<p>狂欢购 满99减30</p>
					<span>省钱大动作</span>
				</div>
				<i></i>
			</li>
		</ul>
	</nav>
	<div class="choujiang_btn">
		<a href="/WomenEight/GetPrize">抽奖</a>
		<a href="javascript:void(0);" class="share">分享</a>
	</div>
	<!--弹窗-->
	<div class="pop index_pop hide">
		<div class="pop_wrap">
			<div class="pic_tip">
				<i class="gift"></i>
			</div>
			<div class="font_tip">
				<p>翻牌拿福利</p>
				<p>海量优惠券等你来拿~</p>
			</div>
			<a href="/WomenEight/GetPrize">确定</a>
		</div>
	</div>
	<!--遮罩层-->
	<div class="mask hide"></div>
	
	
	<script>
	var tanCoupons=<?php echo json_encode($tanCoupons);?>;
	var oneUrl = <?php echo json_encode($oneUrl);?>
	//分享
	var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2017/WomenEight/');?>images/img_pro.png";
    var url="<?php echo F::getHomeUrl('/WomenEight/Share?reUrl='.$surl.'&share=ShareWeb&sl_key=814'); ?>"
    var u = navigator.userAgent, app = navigator.appVersion;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    
	 //分享
    $(".share").click(function(){
    	$.ajax({
    		type:"POST",
			url:"/WomenEight/FenXiang",
			data:"",
			dataType:'json',
			cache:false,
			success:function(data){
				if(data.status==1){
					showShareMenuClickHandler();
				}
			}
    	});
    });


    if (isAndroid) {
		var param={
    		"platform": [
			//			 	"ShareTypeWeixiSession",
			//			    "ShareTypeWeixiTimeline",
			//			    "ShareTypeQQ",
			//			    "ShareTypeSinaWeibo",
							"NeighborShare"
             			],
            "title": "送女王大人的礼",
            "url": url,
            "image": imgUrl,
            "content": "女王新宠，满99减30",
   //	    "NeighborShare":1 //0不显示，1显示
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
             "title": "送女王大人的礼 ",
             "url": url,
             "image": imgUrl,
             "content": "女王新宠，满99减30",
             
       };
}
         
 	//旧版分享功能参数
	var params = {
            "text" : "女王新宠，满99减30 ",
            "imageUrl" : imgUrl,
            "url":url,
            "title" : "送女王大人的礼 ",
            "titleUrl" : url,
            "description" : "描述",
            "site" : "彩之云",
            "siteUrl" : url,
            "type" : $sharesdk.contentType.WebPage
        };
	</script>
</body>
</html>
