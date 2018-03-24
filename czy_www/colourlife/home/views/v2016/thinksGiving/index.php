<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>感恩节福袋任意送--首页</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>js/index.js"></script> 
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>js/envelopes.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>css/new1.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>css/layout.css" />
</head>
<body style="background-color:#F8E22B">
	<input style="display: none" id="vserion" />
	<header>
		<div class="head_btn">
			<a href="Thanksgiving/Rules">活动规则</a>
			<a href="Thanksgiving/RewardRecord">中奖记录</a>
		</div>
	</header>
	<div class="fudai_time item">
		<div class="top_bg"></div>
		<div class="center">
			<h1></h1>
			<p>每天早上10点开始免费抢福袋，数量有限，先到先得</p>
			<div class="time_icon">
				<i></i>
				<span class="count_down">距下一次抢福袋<em>00</em>时<em>00</em>分<em>00</em>秒</span>
			</div>
			<div class="time_over hide"></div>
		</div>
		<div class="bottom_bg"></div>
	</div>
	<div class="fudai_pay item">
		<h1></h1>
		<p>在平台支付成功将有机会获得福袋</p>
		<ul>
			<li>
				<span>购买彩富人生</span>
				<i></i>
			</li>
			<li>
				<span>缴纳物业费停车费</span>
				<i></i>
			</li>
			<li>
				<span>充值饭票</span>
				<i></i>
			</li>
		</ul>
	</div>
	<div class="invite item">
		<div class="top_bg"></div>
		<div class="center">
			<h1></h1>
			<p>成功邀请5位好友注册，获得福袋机率会更高哦</p>
			<p class="register_no">邀请好友赠福袋 &nbsp;&nbsp;&nbsp;&nbsp;已成功邀请<em>2</em>人注册</p>
			<ul>
				<li>
					<span>短信邀请</span>
				</li>
				<li class="register_no">
					<span>分享邀请</span>
				</li>
				<li>
					<span>邀请记录</span>
				</li>
			</ul>
		</div>
	</div> 
	
	<!--倒计时弹窗-->
	<div class="count hide">
		<span></span>
	</div>
		
	
	<!--奖品弹窗 虚拟奖-->
	<div class="pop sort_pop hide">
		<div class="pop_top">
			<p>恭喜您获得</p>
		</div>
		<div class="pop_middle">
			<i class="fanpiao"><span></span></i><!--彩饭票优惠券-->
			<p></p><!--彩生活特供满100减10元优惠券彩之云定制抱枕-->
			<a href="Thanksgiving/RewardRecord">查看奖品</a>
		</div>
		<div class="pop_bottom">
			<span></span>
		</div>
	</div>
	
	<!--奖品弹窗 实物奖-->
	<div class="pop real_pop hide">
		<div class="pop_top">
			<p>恭喜您获得</p>
		</div>
		<div class="pop_middle">
			<i class="award_pic"></i>
			<p class="award_msg"></p>
			<p>活动结束后将有客服人员与您联系</p>
			<a href="Thanksgiving/RewardRecord">查看奖品</a>
		</div>
		<div class="pop_bottom">
			<span></span>
		</div>
	</div>
	
	<!--获得福袋弹窗-->
	<div class="pop high_pop hide">
		<div class="pop_top">
			<p></p>
		</div>
		<div class="pop_middle">
			<p>恭喜您获得福袋</p>
			<a href="javascript:void(0);">点击查看奖品</a>
		</div>
		<div class="pop_bottom">
			<span></span>
		</div>
	</div>
	
	<!--遮罩层-->
	<div class="mask hide"></div>
	
	<div class="mask2 hide"></div>

	<div class="mask3 hide"></div>
	
	<!--加载-->
	<div class="loaders hide">
	 	 	<div class="loader">
		    	<div class="loader-inner ball-pulse">
			      	<div></div>
			      	<div></div>
			      	<div></div>
			    </div>
		  	</div>
	</div>
	
<script>
	var number = <?php echo $number;?>;
	var hasBag = <?php echo $hasBag;?>;
	var url = "<?php echo F::getHomeUrl('/thanksgiving/Share?reUrl='.$surl.'&share=ShareWeb&sl_key=712'); ?>"
	var imgUrl= "<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>images/share_icon.png";
	var timeNow ='<?php echo $timeNow;?>';
	var timeEnd = '<?php echo $timeEnd;?>';
	
	var timeNowSecond = new Date(timeNow).getTime();
	var leftTime = null;
	var isFall = <?php echo $hasRain;?>;
	leftTime = getLeftSeconds(timeNow,timeEnd);
	function getLeftSeconds(timeNow,timeEnd){
		var timeNowS = new Date(timeNow).getTime();
		var day = timeNow.split(" ")[0];
		day += " 10:00:00";
		var timeEndS =  new Date(day).getTime();
		
		return parseInt((timeEndS-timeNowS)/1000);   
	}
	
	//红包雨
	var container = document.body;
	var img = new Image();
	img.src = "<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>images/envelopes.png";
	container.style.overflow = "hidden";
	var objCount = 0;
	var rainStartPostion = null;
	

	window.onload = function(){
		var count = setInterval(countDown,1000);
		function countDown(){
			console.log(leftTime);
			if(leftTime > 0){
				leftTime--;
			}
			else if( leftTime <= 0 && isFall )
			{
				clearInterval(count);
				$(".mask3").removeClass("hide");
				$(".mask2").removeClass("hide");
				rainStartPostion = setInterval(startRain,400);
			}
			
		}
		
		function startRain(){
			$(".high_pop").addClass("hide");
			$(".sort_pop").addClass("hide");
			objCount = new Envelopes(img,container);
			objCount.fall();			

			objCount++;
		}

		$(".mask3").click(function(){
			var _this = $(this);
			if($(this).hasClass("disabled")){
				return false; 
			}
			$(this).addClass("disabled");
			
			$.ajax({
				async:true,
				type:"POST",
				dataType: 'json',
				url:'Thanksgiving/Open',
				data:'type=3',
				success:function(result){
					console.log(result);
					endRain();
					if(result.retCode == 1 && result.data.state == 1){
					switch(result.data.prize_id)
						{
							case 1:
								console.log("1");
								$(".mask").addClass("hide");
								$(".mask2").removeClass("hide");
								$(".sort_pop").removeClass("hide");
								$(".sort_pop .fanpiao span").text("优惠券");
								//$(".sort_pop .pop_middle p").text(result.data.msg);
								$(".sort_pop .pop_middle p").text(result.data.msg).css('margin','0.2rem auto 0.3rem');
							break;
							case 2:
								console.log("2");
								$(".mask").addClass("hide");
								$(".mask2").removeClass("hide");
								$(".sort_pop").removeClass("hide");
								$(".sort_pop .fanpiao span").text("彩饭票");
								//$(".sort_pop .pop_middle p").text(result.data.msg);
								$(".sort_pop .pop_middle p").text(result.data.msg).css('margin','0.2rem auto 0.3rem');
							break;
							case 3:
								console.log("3");
								$(".mask").addClass("hide");
								$(".mask2").removeClass("hide");
								$(".sort_pop").removeClass("hide");
								$(".sort_pop .fanpiao span").text("彩饭票");
								$(".sort_pop .pop_middle p").text(result.data.msg)
							break;
							case 4:
								console.log("4");
								$(".mask").addClass("hide");
								$(".mask2").removeClass("hide");
								$(".real_pop").removeClass("hide");
								$(".real_pop i").addClass("baozhen");
								$(".real_pop .award_msg").text(result.data.msg);
							break;
							case 5:
								console.log("5");
								$(".mask").addClass("hide");
								$(".mask2").removeClass("hide");
								$(".real_pop").removeClass("hide");
								$(".real_pop i").addClass("shuibei");
								$(".real_pop .award_msg").text(result.data.msg);
							break;
							case 6:
								console.log("6");
								$(".mask").addClass("hide");
								$(".mask2").removeClass("hide");
								$(".real_pop").removeClass("hide");
								$(".real_pop i").addClass("shouhuan");
								$(".real_pop .award_msg").text(result.data.msg);
							break;
							case 7://apple watch//手环//水杯//抱枕//2饭票//1饭票
								console.log("7");
								$(".mask").addClass("hide");
								$(".mask2").removeClass("hide");
								$(".real_pop").removeClass("hide");
								$(".real_pop i").addClass("watch");
								$(".real_pop .award_msg").text(result.data.msg);
							break;	
						}
						_this.removeClass("disabled");
						
						isFall = false;
					}else if(result.retCode == 0 && result.data == "" ){
						endRain();
						$(".mask").addClass("hide");
						$(".mask2").removeClass("hide");
						$(".high_pop").removeClass("hide");
						$(".high_pop .pop_middle").empty();
						$(".high_pop .pop_middle").html('<p>'+result.data.msg+'</p>').css('padding-top','0.8rem');
					}
					else if(result.retCode == 0){
						$(".mask").addClass("hide");
						$(".mask2").removeClass("hide");
						$(".high_pop").removeClass("hide");
						$(".high_pop .pop_middle").empty();
						$(".high_pop .pop_middle").html('<p>'+result.data.msg+'</p>').css('padding-top','0.8rem');
					} else if(result.data.state == 2 || result.data.state == 0){
						endRain();
						isFall = false;
						$(".mask").addClass("hide");
						$(".mask2").removeClass("hide");
						$(".high_pop").removeClass("hide");
						$(".high_pop .pop_middle").empty();
						$(".high_pop .pop_middle").html('<p>'+result.data.msg+'</p>').css('padding-top','0.8rem');
					}
				}			
			})
		});
		
		function endRain(){
			clearInterval(rainStartPostion);
			$(".mask2").addClass("hide");
			$(".mask3").addClass("hide");
			$("canvas").hide();
		}

	};
	
	
	//分享     start  *****************************************************
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
	             "title": "感恩福袋任性送",
	             "url": url,
	             "image": imgUrl,
	             "content": "一言不合送福袋，丰厚大礼等着你，赶紧登录彩之云领取吧",
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
             "title": "感恩福袋任性送",
             "url": url,
             "image": imgUrl,
             "content": "一言不合送福袋，丰厚大礼等着你，赶紧登录彩之云领取吧",
         };
    }
	
   	//旧版分享功能参数
	var params = {
            "text" : "一言不合送福袋，丰厚大礼等着你，赶紧登录彩之云领取吧",
            "imageUrl" : imgUrl,
            "url":url,
            "title": "感恩福袋任性送",
            "titleUrl" : url,
            "description" : "描述",
            "site" : "彩之云",
            "siteUrl" : url,
            "type" : $sharesdk.contentType.WebPage
        };
</script>
</body>
</html>
