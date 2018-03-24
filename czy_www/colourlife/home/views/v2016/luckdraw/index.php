<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>630福利</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no"/>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>js/luckyDraw.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>js/jquery.slotmachine.js"></script>
	    <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/ShareSDK.js');?>"></script>
		<script src="<?php echo F::getStaticsUrl('/common/js/share.js'); ?>" type="text/javascript"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>css/normalize.css">
	</head>
	
	<body style="background: #F4F4F4;">
		<input style="display: none" id="vserion" />
		<div class="l_draw">
			<div class="l_draw_b">
				<div class="l_draw_title">
					<p>福利大放送</p>
					<p>你还有 <span><?php echo $allChance;?></span> 次抽奖机会</p>
				</div>
				<div class="conten">
					<div class="content_img" id= "switch">
						<div class="l_draw_con_box01"></div>
						<div  class="l_draw_con_box02 handel"></div>
					</div>
					<div class="content_draw">
						<div id="machine4" class="slotMachine">
							
							<div class="slot slot1"></div>
							<div class="slot slot2"></div>
							<div class="slot slot3"></div>
							<div class="slot slot4"></div>
							<div class="slot slot5"></div>
							<div class="slot slot6"></div>
							<div class="slot slot7"></div>
							<div class="slot slot8"></div>
							<div class="slot slot9"></div>
						</div>
						<div id="machine5" class="slotMachine">
							
							<div class="slot slot1"></div>
							<div class="slot slot2"></div>
							<div class="slot slot3"></div>
							<div class="slot slot4"></div>
							<div class="slot slot5"></div>
							<div class="slot slot6"></div>
							<div class="slot slot7"></div>
							<div class="slot slot8"></div>
							<div class="slot slot9"></div>
						</div>
						<div id="machine6" class="slotMachine">
							
							<div class="slot slot1"></div>
							<div class="slot slot2"></div>
							<div class="slot slot3"></div>
							<div class="slot slot4"></div>
							<div class="slot slot5"></div>
							<div class="slot slot6"></div>
							<div class="slot slot7"></div>
							<div class="slot slot8"></div>
							<div class="slot slot9"></div>
						</div>
					</div>
				</div>			
			</div>	
			<div class="l_draw_footer">
			    <div class="l_draw_p">
			    	<div class="textContent" style="display: none">
			    		<p>恭喜 <span></span> 抽到</p>
			    		<p></p>
			    	</div>
			    </div>
			    <div class="square"></div>
			    <div class="square01"></div>
			    <div class="rule">
			    	<h4>活动规则</h4>
			    	<div class="scr">
				    	<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>images/kuai.png"/></span><span>福利大放送</span></p>
				    	<p>1、每个ID有3次抽奖机会。</p>
				    	<p>2、分享抽奖活动页可增加1次抽奖机会。</p>
				    	<p>3、邀请一名好友成功注册为彩之云用户可增加1次抽奖机会。</p>
				    	<p>4、E系列优惠券将直接存入个人的商户代金券中，实物奖品将在活动结束后统一发放。</p>
				    	<p>5、彩之云定制系列奖品将于活动结束后五个工作日内，由彩之云客服联系发放。</p>
				    	<p>6、用户所抽到的饭票将直接存入个人彩之云账户中。</p>
				    	<p>7、如活动期间发现作弊行为，则取消该ID的中奖资格，并予以封号处理。</p>
				    	<p>8、如因活动期间出现网络异常系统问题，导致无法正常显示中奖结果，一切将以获奖记录为准。</p>
				    	<p>9、本次活动仅限彩之云用户参与。</p>
				    	<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>images/kuai.png"/></span><span>2周年庆典—邻里集赞</span></p>
				    	<p>以＃2周年庆典＃为话题，在邻里发帖集赞，帖子内容需为活动相关照片或者与彩生活有关的图片，并附上对彩生活的祝福。获赞最多的额前十名，每个人将获得1个10000毫安彩之云充电宝。奖品将在结果公布后的5个工作日内由彩之云客服联系发放。</p>
				    	<p>参与集赞的帖子需满足一下三个条件：</p>
				    	<p>1、帖子内有＃2周年庆典＃标签</p>
				    	<p>2、需上传活动照片或者与彩生活有关的图片</p>
				    	<p>3、附上对彩生活的祝福</p>
			    	</div>
			    </div>
			    <div class="rule_red">
				    	<p class="p_red">*彩之云对本次活动保留法律范围内的最终解释权</p>
				    	<p class="p_red">活动由彩之云提供，与设备生产商Apple Inc公司无关</p>
		    	</div>
			</div>
			
		</div>
		<div class="historical"></div>
		<div class="share_btn"></div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<div class="mask1 hide"></div>
		<!--遮罩层结束-->
		<!--奖品弹窗开始-->
		<!--奖品弹窗-->
		<div class="Popup hide">
			<div class="Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>images/gift.png">
			</div>
			<div class="Popup_con"> 
				<div class="Popup_con_b">
					<p>恭喜你抽中奖品</p>
					<p>2饭票</p>
				</div>
				<div class="Popup_con_footer">
					<a href="javascript:void(0)">
						确定
					</a>
				</div>
			</div>
		</div>
		
		<!--流量领取弹窗-->
		<div class="liuliang_Popup hide">
			<div class="liuliang_Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>images/gift.png">
			</div>
			<div class="liuliang_Popup_con">
				<div class="liuliang_Popup_con_b">
					<p>恭喜你抽中奖品</p>
					<p>随机流量</p>
					<input type="text" id="mobile" placeholder="请输入手机号" />
				</div>
				<div class="liuliang_Popup_con_footer">
					<a href="javascript:void(0)">
						领取
					</a>
				</div>
			</div>
		</div>
		
		
		
		<!--流量领取成功、失败弹窗-->
		<div class="flow_pop hide">
			<div class="flow_round">
				<p>你已经领取过了</p>
			</div>
			<div class="flow_footer">
				<a href="javascript:void(0)">
					确定
				</a>
			</div>
		</div>
	
		<!--弹窗结束-->
		<script>
			var allChance = <?php echo $allChance;?>;
			var logoUrl1 = "<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/images/gift.png');?>";
			var logoUrl2 = "<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/images/ticket.png');?>";
			var awarderList = <?php echo empty($awarderList)?'[]':$awarderList;?>;
            var url="<?php echo F::getHomeUrl('/SiQingChou/Share?reUrl='.$url.'&share=ShareWeb&sl_key=siqingchou&isWx=1');?>";
            var imgUrl="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/images/img_pro1.png');?>";
			
		
				
	        //分享
			$(".share_btn").click(function(){
//				alert("123");
				showShareMenuClickHandler();
				setTimeout(shareSuccessPop,2000);
				
			});
			
			
			function shareSuccessPop(){
		
				$.ajax({
		            type:"POST",
		            url:"/SiQingChou/FenXiang",
		            data:"",
		            dataType:'json',
		            success:function(data){
		            	if(data.status == 1){  
							allChance = data.allChance;
		            		$(".l_draw_title p:eq(1) span").text(allChance);
						}
						else {
							console.log("ajax状态失败");
							return false;
						}
		            	
		            } 
		        });
			}
			
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
		                 "ShareTypeSinaWeibo"

		             ],
		             "title": "彩生活上市2周年庆典",
		             "url": url,
		             "image": imgUrl,
		             "content": "百万礼品大放送，最高1G流量免费领！"
		         };
		} 
		else if(isiOS){
			
			var param={
			
		    "platform": [
		                 "ShareTypeWeixiSession",
		                 "ShareTypeWeixiTimeline",
//		                 "ShareTypeQQ",
//		                 "ShareTypeSinaWeibo"
		                 

		             ],
		             "title": "彩生活上市2周年庆典",
		             "url": url,
		             "image": imgUrl,
		             "content": "百万礼品大放送，最高1G流量免费领！"
		         };
		}
		
   	 	//旧版分享功能参数
		var params = {
	            "text" : "百万礼品大放送，最高1G流量免费领！",
	            "imageUrl" : imgUrl,
	            "url":url,
	            "title" : "彩生活上市2周年庆典",
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
					shareSuccessPop();
				} 
				
			}
		function ColourlifeShareHandlerAndroid(response){
			var data=JSON.parse(response);
//				alert(data.shareType);
//				alert(data.state);
				if (response.state==1) {
					shareSuccessPop();
				}
			}
		function shareAfter(){
				shareSuccessPop();
			}
			
			
		</script>
	</body>
</html>
