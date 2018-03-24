<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>话题页</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>css/layout.css">
	</head>
	<body style="background: url(<?php echo F::getStaticsUrl('/activity/v2017/muQin/');?>images/huati.jpg) no-repeat; background-size: 100% 100%;">
		<input style="display: none" id="vserion" />
		<div class="contaner">
			<div class="topic_tit">#晒出母亲感动时刻的照片#</div>
			<div class="topic_daoyu">
				<ul>
					<li>导语：</li>
					<li>
						<p>天之大，唯有您的爱是完美无瑕；</p>
						<p>天之涯，记得您用心传话。</p>
					</li>
				</ul>
			</div>
			<div class="fatie_btn">发帖</div>
			<div class="contaner_rule">
				<h4>活动规则</h4>
				<div class="contaner_p">
					<p>1、晒出母亲感动时刻的照片，即可参与话题互动；</p>
					<p>2、点赞最多的前10名为获奖者；</p>
					<p>3、每个ID可以发布多张照片，每个ID仅有一次获奖机会，不能重复晒图，不能盗用他人图片。</p>
					<div class="pingjiang_rule">评奖规则：</div>
					<p>1、晒图走心程度；</p>
					<p>2、主题契合度；</p>
					<p>3、点赞数量。</p>
					<div class="zhu">注：获奖名单将于活动结束5个工作日内在彩之云邻里公布，现金券自动发自获奖用户卡包，适用于京东特供商品，使用优惠券下单成功后，若用户申请退款，只返还减去奖励以后的金额，有效时间截止2017年5月30日。</div>
				</div>
				<div class="line"></div>
			</div>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){
				var userId;
				var u = navigator.userAgent, app = navigator.appVersion;
				var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
    			var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    			$(".fatie_btn").click(function(){
    				if(isAndroid){
						try{
								userId = $.parseJSON(jsObject.getUserInfoHandler()).uid;
								var vserionStr = jsObject.Dynamic();
							}
						catch(error){
								console.log(error.message);
								alert("请升级到最新版本");
							}
											
					}
    			});
				
				$(".fatie_btn").click(function(){
					$.ajax({
					async:true,
					type:"POST",
					url:"/MuQin/Dian",
					data:"tid=8",
					dataType:'json',
					success:function(data){
						if(data.status == 1){  //根据后台返回的状态
							mobileJump("Dynamic");
						}
					} 
				});
				});
				
				function mobileJump(cmd){
			        if(/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
			            var _cmd = "http://www.colourlife.com/*jumpPrototype*colourlife://proto?type=" + cmd;
			            document.location = _cmd;
			        }else if (/(Android)/i.test(navigator.userAgent)) {
		            	var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
			            eval(_cmd);
			        } else {
			        }
			    }
			});
		</script>
		
		
	</body>
</html>
