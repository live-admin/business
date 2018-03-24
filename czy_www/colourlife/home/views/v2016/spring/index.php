<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>春节活动</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>js/swiper.jquery.min.js"></script>
     	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>js/swiper.min.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>js/index.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>css/draw.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>css/swiper.min.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<input style="display: none" id="vserion" />
		<div class="content">
			<!--横幅-->
			<div class="deng_bg">
				<ul>
					<li></li>
					<li>
						<p></p>
						<p class="animal"></p>
						<!--<img src="images/12.png"/>-->
					</li>
				</ul>
			</div>
			
			
			<div class="nav">
				<!--导航栏-->
				<div class="nav_five">
					<ul>
						<li>
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/icon1.png"/>
							<p>打扫卫生</p>
						</li>
						<li>
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/icon2.png"/>
							<p>放鞭炮</p>
						</li>
						<li>
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/icon3.png"/>
							<p>紫金葫芦</p>
						</li>
						<li>
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/icon4.png"/>
							<p>邻里和睦</p>
						</li>
						<li>
							<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/icon5.png"/>
							<p>聚宝盆</p>
						</li>
					</ul>
				</div>
				
				<!--扫地签到-->
				<div class="qiandao hide">
					<p>剩余<span class="day">0</span>个鞭炮</p>
					<div class="q_today">
						<p><span class="day">0</span><span>天</span></p>
					</div>
					<p class="lianxu">已连续打扫<span class="day">0</span>天</p>
					<div class="data swiper-container">
						<ul class="data_active swiper-wrapper">
							<li class="swiper-slide"><p>1.26</p></li>
							<li class="swiper-slide"><p>1.27</p></li>
							<li class="swiper-slide"><p>1.28</p></li>
							<li class="swiper-slide"><p>1.29</p></li>
							<li class="swiper-slide"><p>1.30</p></li>
							<li class="swiper-slide"><p>1.31</p></li>
							<li class="swiper-slide"><p>2.01</p></li>
							<li class="swiper-slide"><p>2.02</p></li>
							<li class="swiper-slide"><p>2.03</p></li>
							<li class="swiper-slide"><p>2.04</p></li>
							<li class="swiper-slide"><p>2.05</p></li>
							<li class="swiper-slide"><p>2.06</p></li>
							<li class="swiper-slide"><p>2.07</p></li>
							<li class="swiper-slide"><p>2.08</p></li>
							<li class="swiper-slide"><p>2.09</p></li>
							<li class="swiper-slide"><p>2.10</p></li>
						</ul>
					</div>
					<div class="saoba hide"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/saoba.png"/></div>
					<div class="huichen hide"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/huichen.png"/></div>
					<div class="bianpao hide">
						<!--<div class="bianpao_q_today">
							<p><span class="bianpao_day">0</span></p>
						</div>-->
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/test.png" class="hide"/>
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/test.png" class="hide"/>
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/test.png" class="hide"/>
					</div>
				</div>
				<div class="bar_bg"></div>
			</div>
			
			<div class="rank">排行榜</div>
			<div class="rule">活动规则</div>
			<div class="meg">消息</div>
			
			<div class="clour_bg">
			<!--福气值进度条-->
				<div class="num_box">
					<div class="site_bot_box1a">
						<span class="span_left"><span>我的福气值：</span><span>/300</span><span></span></span>
						<div class="clear"></div>
						<p><span class="plan"></span></p>
					</div>
					<div class="qiang_btn">领取</div>
					<p class="prize_p">活动结束后福气值达到300,即可领取“彩之云定制餐具一套”</p>
				</div>
				
				<!--扫地按钮-->
				<div class="bott_btn hide">
					<div class="dasao_btn">打扫</div>
					<div class="return_index">首页</div>
				</div>
				
				<!--鞭炮按钮-->
				<div class="box_pao_btn hide">
					<div class="biaopao_btn">放鞭炮</div>
					<div class="return_index">首页</div>
				</div>
			</div>
		</div>
		
		<div class="mask hide"></div>
		<!--弹窗-选择生肖-->
		<div class="Pop_chose hide">
			<div class="Pop_chose_txt">
				<p>选择您的生肖</p>
				<p>生肖一旦选定不可更改</p>
			</div>
			<div class="Pop_chose_pic">
				<ul>
					<li>
						<p>鼠</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/1.png"/></div>
					</li>
					<li>
						<p>牛</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/2.png"/></div>
					</li>
					<li>
						<p>虎</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/3.png"/></div>
					</li>
					<li>
						<p>兔</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/4.png"/></div>
					</li>
					<li>
						<p>龙</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/5.png"/></div>
					</li>
					<li>
						<p>蛇</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/6.png"/></div>
					</li>
					<li>
						<p>马</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/7.png"/></div>
					</li>
					<li>
						<p>羊</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/8.png"/></div>
					</li>
						<li>
						<p>猴</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/9.png"/></div>
					</li>
					<li>
						<p>鸡</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/10.png"/></div>
					</li>
					<li>
						<p>狗</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/11.png"/></div>
					</li>
					<li>
						<p>猪</p>
						<div class="Pop_chose_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/12.png"/></div>
					</li>
				</ul>
			</div>
		</div>
		
		<!--弹窗生肖确认-->
		<div class="Pop_chose_sure hide">
			<p>您选择的生肖是：</p>
			<div class="Pop_chose_sure_pic_bg"><img src="<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/10.png"/></div>
			<p>一旦确认将不可更改，</p>
			<p>是否确定所选生肖？</p>
			<div class="s_sure_btn">确定</div>
			<div class="chong_btn">重选</div>
		</div>
		
		<!--弹窗-扫地签到/放鞭炮-->	
		<div class="Pop_sao hide">
			<div class="Pop_qiang_txt">
				<p></p>
				<p></p>
				<p></p>
			</div>
			<div class="Pop_qiang_btn_box">
				<div class="Pop_qiang_btn">确定</div>
			</div>
		</div>
			
		<script type="text/javascript">
		var customer_info = <?php echo json_encode($customer_info)?>;
		var img_url = "<?php echo F::getStaticsUrl('/activity/v2016/spring/images');?>";
		var _flag = <?php echo json_encode($flag)?>;
		var hongdian =  <?php echo json_encode($hongdian)?>;
		var _type = <?php echo json_encode($type)?>;
		var lingqu_status = <?php echo json_encode($lingqu_status)?>;
		var endtime_status = <?php echo json_encode($endtime_status)?>;
		var result = <?php echo json_encode($result)?>;
		var time=result.time;
//		var time = '1.16';
		var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/spring/');?>images/img_pro.png";
		var url="<?php echo F::getHomeUrl('/spring/Share?reUrl='.$surl.'&share=ShareWeb'); ?>"
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
		             "title": "新年集福气，iPhone 7等你来拿！",
		             "url": url,
		             "image": imgUrl,
		             "content": "隔壁老王又来敲门了，要你.......",
		         };
	    }
	    else if (isiOS) {
	    	var param={
	    		"platform": [
//	                 "ShareTypeWeixiSession",
//	                 "ShareTypeWeixiTimeline",
//	                 "ShareTypeQQ",
//	                 "ShareTypeSinaWeibo",
	            	 "NeighborShare"

	
	             ],
	             "title": "新年集福气，iPhone 7等你来拿！",
	             "url": url,
	             "image": imgUrl,
	             "content": "隔壁老王又来敲门了，要你.......",
	         };
	    }
	    //旧版分享功能参数
		var params = {
	            "text" : "隔壁老王又来敲门了，要你.......",
	            "imageUrl" : imgUrl,
	            "url":url,
	            "title": "新年集福气，iPhone 7等你来拿！",
	            "titleUrl" : url,
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : url,
	            "type" : $sharesdk.contentType.WebPage
	        };
		</script>
	</body>
</html>
