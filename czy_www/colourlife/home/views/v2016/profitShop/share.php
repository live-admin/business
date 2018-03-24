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
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>css/normalize.css">
	</head>
	<body style="background: #F1F2F3;">
		<div class="conter">
			<div class="conter_top">
				<p>参加彩富人生，还可享受10元一桶油，10元一袋米</p>
			</div>
			<div class="conter_alig">
				<p>用户<span><?php echo $username ?></span></p>
				<p>邀您参加至尊VIP体验</p>
				<p class="hide">请输入正确的手机号码!</p>
				<input id="phoneNum" type="number" placeholder="请输入您的手机号" class="num"/>
				<div class="lingqu_btn">免费领取购买机会</div>
				<p>彩富商城新动态</p>
			</div>
			
			<!--商品-->
			<div class="conter_img share_conter_img">
				<?php foreach($goods as $goodsInfo): ?>
				<div class="conter_img_left">
					<img src="<?php echo $goodsInfo['img_name']; ?>"/>
					<p>原价：<span class="yuanjia">¥ </span><span class="yuanjia_money"><?php echo $goodsInfo['market_price']; ?></span></p>
					<p>彩富价：<span class="cfjia">¥ </span><span class="cfjia_money"><?php echo $goodsInfo['customer_price']; ?></span></p>
				</div>
				<?php endforeach; ?>
			</div>
			
			<div class="sharePage_rule">
				<p>分享规则</p>
				<ul>
					<li>1、每个手机号只限领取一次;</li>
					<li>2、好友领取购买机会越多，分享者获得同等的购买机会次数（分享者每日最多获得5次购买机会）;</li>
					<li>3、获得购买机会的用户请到彩之云中查看并消费。</li>
				</ul>
			</div>
			
			
			<div class="conter_bottom">
				<div class="conter_bottom_p">
					<p>彩之云邀您体验多彩生活，进入“彩富商城”专区购买！</p>
				</div>
				<div class="conter_bottom_buy">我要去抢购</div>
			</div>
		</div>
		
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗-->
		<div class="Popup01 hide">
			<div class="Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/smile01.png">
			</div>
			<div class="Popup_con">
				<div class="Popup_con_t"></div>
				<div class="Popup_con_zhi share_Popup_con_zhi">
					<p>你已经领取过，赶紧去抢购最新的优惠商品吧！！</p>
				</div>
				<div class="Popup_con_footer">
					<a href="javascript:void(0)">
						确定
					</a>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){
				$(".lingqu_btn").click(function(){
					var phone = $(".num").val();
					if(numberCheck(phone)){
						/*ajax*/
						$.ajax({
							type : 'GET',
							url : '/ProfitShop/ReceiveTicket',
							data : {
								'mobile' : phone,
								'user_id' : <?php echo $user_id; ?>
							},
							dataType: 'json',
							success: function (result) {
								if (1 == result.retCode) {
									$(".share_Popup_con_zhi p").text("恭喜您，获得了1次彩富价购买机会！快去彩之云“彩富商城”中使用吧！");
									$(".Popup01").removeClass("hide");
									$(".mask").removeClass("hide");
								}
								else {
									$(".share_Popup_con_zhi p").text(result.retMsg);
									$(".Popup01").removeClass("hide");
									$(".mask").removeClass("hide");
								}
							}
						});

					}
				});
				
				//号码校验
				function numberCheck(temp){
		        	var a=/^[1]{1}[0-9]{10}$/;
					
		        	if(!a.test(temp))
		            {
		            	$(".conter_alig p:eq(2)").removeClass("hide").text("请输入正确的手机号码");
		                return false;
		            }
		            return true;
		        }
				$("#phoneNum").focus(function(event) {
					$(".conter_alig p:eq(2)").addClass("hide")
				});
				$(".mask,.Popup_con_footer").click(function(){
					$(".Popup01").addClass("hide");
					$(".mask").addClass("hide");
				});
			
				/*我要去抢购*/
				$(".conter_bottom_buy").click(function(){
					isInstalled();
				});
				/*立即下载的函调*/
			   function isInstalled() {
					if (navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {
						var userAgent = navigator.userAgent;
						if (userAgent.indexOf("Safari") > -1) {
							window.location.href = "colourlife://"; /***打开app的协议，有安卓同事提供***/
							window.setTimeout(function() {
								window.location.href = "http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway"; /***打开app的协议，有安卓同事提供***/
							}, 1000);
						} else {
							window.location.href = "colourlife://"; /***打开app的协议，有安卓同事提供***/
							window.setTimeout(function() {
								window.location.href = "http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway"; /***打开app的协议，有安卓同事提供***/
							}, 2000);
						}
					} else if (navigator.userAgent.match(/android/i)) {
						try {
							var iframe = document.createElement("iframe");
							iframe.style.cssText = "display:none;width:0px;height:0px;";
							loadIframe.src = "colourlife://splash";
							document.body.appendChild(iframe);
							// window.location.href = "colourlife://splash"; /***打开app的协议，有安卓同事提供***/
							window.setTimeout(function() {
								window.location.href = "http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway"; /***打开app的协议，有安卓同事提供***/
							}, 2000);
						} catch (e) {
							window.location.href = "http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway"; /***打开app的协议，有安卓同事提供***/
						}
					}
				}	
				
			});
		</script>
		
	</body>
</html>
