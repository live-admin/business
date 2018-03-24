<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>双旦寻宝</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no, email=no"/>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/edition.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>js/index.js"></script>
     	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<section class="conter">
			<input style="display: none" id="vserion" />
			<header class="header">
				<!--<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/banner01.png">
					<span>0</span>
				</a>-->
			</header>
			<section class="bao">
				<h4>集齐不同的拼图即可开启相应宝箱</h4>
				<div class="bao_banner">
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/bao01.png">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/bao01_kai.png" class="hide">
					    <p>集齐2张不同的拼图</p>
					</a>
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/bao02.png">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/bao02_kai.png" class="hide">
					    <p>集齐4张不同的拼图</p>
					</a>
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/bao03.png">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/bao03_kai.png" class="hide">
					    <p>集齐所有的拼图</p>
					</a>
				</div>
			</section>
			<section class="guide">
				<h5>成功开启3个宝箱，即可瓜分10万彩饭票</h5>
				<div class="time">
					<p>距离开奖还有<span>00</span>天<span>00</span>时<span>00</span>分<span>00</span>秒</p>
					<p>已有 <span class="popele">23</span>人集齐</p>
				</div>
				<a href="/SdActivity/Guide">寻宝指引</a>
			</section>
			<section class="rule">
				<h4>活动规则</h4>
				<p>1、活动期间用户在彩之云内任意模块点击，将随机出现拼图或奖品，集齐6张不同的拼图，成功开启三个礼盒后，即可共同瓜分大奖；</p>
				<p>2、用户可通过“求赠”的方式，向好友索取拼图；多余的拼图可通过“转赠”的方式赠送给好友；</p>
				<p>3、同种类宝箱每个ID仅可开启一次；</p>
				<p>4、活动奖品将在活动结束后10个工作日内陆续发放；</p>
				<p>5、活动期间若出现作弊行为，则取消该帐号活动资格，情节严重者将给予封号处理。</p>
				<strong>＊活动由彩之云提供，与设备生产商Apple Inc.公司无关</strong>
			</section>
		</section>
		<!--遮罩层开始-->
		<div class="mask1a hide"></div>
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--首次弹窗开始-->
		<div class="pop firstTime  hide">
			<div class="pop_c">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/pop_bao.png">
				<p>在彩之云内寻找遗失的拼图，集齐6张不同的拼图即可瓜分10万彩饭票！</p>
				<a href="javascript:void(0);">去看看</a>
			</div>
			<div class="pop_close"></div>
		</div>
		<!--首次弹窗结束-->
		<!--卡片赠送弹窗开始-->
		<div class="pop_cark zeng_pop_cark hide">
			<div class="pop_cark_header">
				<div class="pop_c_img">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/pop_title01.png">
				</div>
				<p>多余的拼图可以转赠给需要的朋友</p>
				<div class="pop_cark_b">
						<span id="reduc">-</span>
						<input type="text" id="num" value="1"  readonly="readonly" >
						<span id="add">+</span>
				</div>
				<p class="tishi hide">数量</p>
				<a href="javascript:void(0);">转赠</a>
			</div>
			<div class="pop_close"></div>
		</div>
		<!--卡片赠送弹窗结束-->
		<!--卡片求赠灰色弹窗开始-->
		<div class="hui_pop_cark hide">
			<div class="pop_cark_header">
				<div class="pop_c_img ">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/pop_title01.png" class="grayscale">
				</div>
				<div class="hui_pop_cark_p">
					<p>缺少的拼图可以向朋友求赠</p>
				</div>
				<div class="hui_pop_cark_input">
					<input type="text" placeholder="请输入手机号">
				</div>
				<div class="hui_pop_cark_phone">
					<p class="hide">手机号码输入错误</p>
				</div>
				<a href="javascript:void(0);" class="btn">确认</a>
			</div>
			<div class="pop_close"></div>
		</div>
		<!--卡片求赠灰色弹窗结束-->
		<!--版本号弹窗开始-->
		<div class="version hide">
			<div class="version_pop">
				<p>赶紧升级彩之云，参与双旦夺宝大狂欢，齐来瓜分10万大奖</p>
				<a href="javascript:void(0);">确认</a>
			</div>
			<div class="pop_close"></div>
		</div>
		<!--版本号弹窗结束-->
	    <script>
			//后台传来的数据
	    	var data= <?php echo $outData;?>;
//	    	console.log(data);
	    	//banner数据
	    	var cards=data.cards;
//	    	console.log(cards);
	    	//宝箱点击数据
	    	var chest=data.chest;
//	    	console.log(chest.length);
	    	//首次弹窗数据
	    	var tips=data.tips;
	    	//console.log(tips);
	  		if(tips){
	    		$(".firstTime .pop_c").find("img").attr("src",tips.image);
		    	$(".firstTime .pop_c").find("p").text(tips.text);
		    	$(".firstTime .pop_c").find("a").text(tips.button_text);
		    	$(".firstTime").removeClass("hide");
		    	$(".mask1a").removeClass("hide");
		    	if(tips.url != ''){
		    		$(".firstTime .pop_c").find("img").attr("src",tips.image);
			    	$(".firstTime .pop_c").find("p").text(tips.text);
			    	$(".firstTime .pop_c").find("a").text(tips.button_text);
			    	$(".firstTime .pop_c").find("a").attr("href",tips.url);
			    	$(".firstTime").removeClass("hide");
			    	$(".mask1a").removeClass("hide");
		    	}
	    	}else{
	    		console.log("false");
	    	}
	    	//时间的数据
	    	var daojitime=data.countdown_time;
//			var daojitime=0;
//	    	console.log(daojitime);
			//分享
	        var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/sd/');?>images/img_pro.png";
		    var url = "<?php echo F::getHomeUrl('/SdActivity/Share?reUrl='); ?>";
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
			             "title": "您的好友赠送了拼图给您，快快来收取哟~！",
			             "url": url,
			             "image": imgUrl,
			             "content": "好友给您赠送了拼图",
	       //		     "NeighborShare":1 //0不显示，1显示
			        };
			   	ad();    
		    }
		    else if (isiOS) {
		    	var param={
		    		"platform": [
		                 "ShareTypeWeixiSession",
		                 "ShareTypeWeixiTimeline",
		                 "NeighborShare",
	//	                 "ShareTypeQQ",
	//	                 "ShareTypeSinaWeibo"
		
		             ],
		             "title": "您的好友赠送了拼图给您，快快来收取哟~！",
		             "url": url,
		             "image": imgUrl,
		             "content": "好友给您赠送了拼图",
		             
		        };
		        ios();
		}
		         
		    
			
	   	 	//旧版分享功能参数
			var params = {
		            "text" : "好友给您赠送了拼图",
		            "imageUrl" : imgUrl,
		            "url":url,
		            "title" : "您的好友赠送了拼图给您，快快来收取哟~！",
		            "titleUrl" : url,
		            "description" : "描述",
		            "site" : "彩之云",
		            "siteUrl" : url,
		            "type" : $sharesdk.contentType.WebPage
		        };
			
	    </script> 
	    
	</body>
</html>
