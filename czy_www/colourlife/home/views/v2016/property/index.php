<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>预缴费活动</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/edition.js" ></script>
     	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>js/share.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>js/jquery.flexslider-min.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>js/index.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>css/layout.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>css/flexslider.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<input style="display: none" id="vserion" />
		<div class="conter">
			<header class="header">
				<p>抽奖次数：<span class="num"><?php echo $chance_number ?></span>次</p>
			</header>
			<section class="rotary">
				<div class="start"></div>
				<div class="draw">
					<p>开始<br/>抽奖</p>
				</div>
			</section>
			<section class="button_con">
				<div class="box share">
					<p>分享</p>
				</div>
				<div class="box record">
					<p>中奖记录</p>
				</div>
				<div class="box list">
					<p>奖品列表</p>
				</div>
			</section>
			<section class="management">
				<fieldset>
					<legend>我要预缴费</legend>
					<ul class="management_fee">
						<li>
							<a href="javascript:void(0);">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>images/property.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>images/parking.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>images/parking_treasure.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="<?php echo F::getStaticsUrl('/activity/v2016/property/');?>images/property_treasure.png"> 
							</a>
						</li>
					</ul>
				</fieldset>
			</section>
			<footer class="footer">
				<p>如有疑问请联系客服  联系电话：<a href="tel:0755-33930303">0755-33930303</a></p>
			</footer>
			<article class="rule">
				<p>活动规则</p>
			</article>
			<!--弹窗开始-->
			<div class="flexslider hide">
				<ul class="slides">
					<li>
						<div class="prizePage">
							<p class="title">一等奖</p>
							<p>以下奖品任选其一:</p>
							<p class="prizeItem"><span></span>佳能（Canon）EOS 700D单反双头套机</p>
							<p class="prizeItem"><span></span>Apple iPad Pro平板电脑 9.7 英寸32G 玫瑰金色</p>
							<p class="prizeItem"><span></span>Apple iPhone 7 (A1660) 32G 金色 移动联通电信4G手机</p>
						</div>
					</li>
					<li>
						<div class="prizePage">
							<p class="title">二等奖</p>
							<p>以下奖品任选其一:</p>
							<p class="prizeItem"><span></span>小天鹅（Little Swan）TB65-easy60W 6.5公斤全自动波轮 </p>
							<p class="prizeItem"><span></span>海尔(Haier)60升双热力 可外接太阳能热水器 8年质保 电热水器EC6001-SN2</p>
						</div>
					</li>
					<li><span></span>
						<div class="prizePage">
							<p class="title">三等奖</p>
							<p>以下奖品任选其一:</p>
							<p class="prizeItem"><span></span>苏泊尔（SUPOR）榨汁机 多功能料理机搅拌机JS30-230</p>
							<p class="prizeItem"><span></span>苏泊尔（SUPOR）电磁炉SDHCB8E30-210赠原装汤锅+炒锅</p>
							<p class="prizeItem"><span></span>空调挂机（内机）清洗  120元抵用券（服务范围：深圳）</p>
							<p class="prizeItem"><span></span>空调柜机（内机）清洗 150元抵用券（服务范围：深圳）</p>
							<p class="prizeItem"><span></span>对开门冰箱清洗 170元（服务范围：深圳）</p>
							<p class="prizeItem"><span></span>三开门冰箱清洗 130元抵用券（服务范围：深圳）</p>
							<p class="prizeItem"><span></span>油烟机清洗 180元抵用券（服务范围：深圳）</p>
							<p class="prizeItem"><span></span>全屋消杀蟑螂100㎡ 220元抵用券（服务范围：深圳）</p>
							<p class="prizeItem"><span></span>饭票奖励：150元</p>
						</div>
					</li>
					<li>
						<div class="prizePage">
							<p class="title">四等奖</p>
							<p>以下奖品任选其一:</p>
							<p class="prizeItem"><span></span>福临门 五常长粒香 大米 5kg</p>
							<p class="prizeItem"><span></span>福临门 花生原香食用调和油 5L</p>
							<p class="prizeItem"><span></span>微波炉清洗 58元抵用券（服务范围：深圳）</p>
							<p class="prizeItem"><span></span>热水器清洗  60元抵用券（服务范围：深圳）</p>
							<p class="prizeItem"><span></span>饭票奖励：50元</p>
						</div>
					</li>
					<li>
						<div class="prizePage">
							<p class="title">五等奖</p>
							<p>以下奖品任选其一:</p>
							<p class="prizeItem"><span></span>十月稻田 长粒香大米 东北大米 2.5kg</p>
							<p class="prizeItem"><span></span>金龙鱼 精炼一级 大豆油 1.8L</p>
							<p class="prizeItem"><span></span>饭票奖励：25元</p>
						</div>
					</li>
					<li>
						<div class="prizePage">
							<p class="title">六等奖</p>
							<p>以下奖品任选其一:</p>
							<p class="prizeItem"><span></span>维达 抽纸 超韧3层150抽面巾纸*3包(小规格)</p>
							<p class="prizeItem"><span></span>小白熊 韩国进口婴儿洗衣皂200g 抗菌BB肥皂 香草香</p>
							<p class="prizeItem"><span></span>超能 柠檬草透明皂清新祛味260g*2</p>
							<p class="prizeItem"><span></span>饭票奖励：10元</p>
						</div>
					</li>
				</ul>
				<div class="close"></div>
		    </div>
			<!--弹窗结束-->
			
			
		</div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗01开始-->
		<div  class="pop hide">
			<div class="pop_header">
				<p>恭喜您获得佳能（Canon）EOS 700D单反双头套机</p>
			</div>
			<div class="pop_con">
				<p>奖品已领取成功，快去填写领奖地址吧！</p>
			</div>
			<div class="pop_footer">
				<a href="javascript:void(0);">
					领奖地址
				</a>
			</div>
			<div class="close"></div>
		</div>
	    <!--弹窗01结束-->
	    
	    <!--首次弹窗开始-->
		<div  class="first_Pop hide">
			<div class="pop_header">
				<p>温馨提示</p>
			</div>
			<div class="first_Pop_con">
				<p>本活动已于2016年12月31日结束，</p>
				<p>请还未抽奖的用户尽快抽奖哦。</p>
				<span style="font-size:0.2rem;color:#FE6262;">（抽奖页面将在2017年1月13日关闭）</span>
			</div>
			<div class="first_Pop_footer">
				<a href="javascript:void(0);">
					确定
				</a>
			</div>
			<div class="close"></div>
		</div>
	    <!--首次弹窗结束-->
	    
	    <!--弹窗02开始-->
	    <div class="noChancePop hide">
	    	<p class="title">温馨提示</p>
	    	<p>您目前暂未获得抽奖机会，</p>
	    	<p>可点击抽奖页下方的：</p>
	    	<div></div>
	    	<p>单笔成功预缴6个月即可获得1次抽奖机会</p>
	    	<p>注：<span style="color: #F06669;">缴物业费</span>至少包含2017年期间6个月</p>
	    	<button>去看看</button>
	    	<div class="close"></div>
	    </div>
	    
	    <!--弹窗02结束-->
	    
	    <script type="text/javascript">
    	var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/property/');?>images/img_pro.png";
		var url="<?php echo F::getHomeUrl('/property/Share?reUrl='.$surl.'&share=ShareWeb&sl_key=700'); ?>"
		
		//分享     start  *****************************************************
		
		//新版分享功能参数
		var u = navigator.userAgent, app = navigator.appVersion;
	    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	    
	    if (isAndroid) {
			var param={
		    		"platform": [
//		                 "ShareTypeWeixiSession",
//		                 "ShareTypeWeixiTimeline",
//		                 "ShareTypeQQ",
//		                 "ShareTypeSinaWeibo",
		                 "NeighborShare"
		             ],
		             "title": "预缴物业费、停车费",
		             "url": url,
		             "image": imgUrl,
		             "content": "豪礼抽不停 100%中奖",
		         };
		         
	    	ad();
		  
		         
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
	             "title": "预缴物业费、停车费",
	             "url": url,
	             "image": imgUrl,
	             "content": "豪礼抽不停 100%中奖",
	         };
	         
        	ios();
	    }
		
	   	//旧版分享功能参数
		var params = {
	            "text" : "豪礼抽不停 100%中奖",
	            "imageUrl" : imgUrl,
	            "url":url,
	            "title": "预缴物业费、停车费",
	            "titleUrl" : url,
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : url,
	            "type" : $sharesdk.contentType.WebPage
	        };
	        
	        
	    </script>
	</body>
</html>
