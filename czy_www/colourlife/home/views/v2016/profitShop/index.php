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
	<body style="background:#F1F2F3;">
		<div class="conter">
			<div class="banner"></div>
			<div class="rule">
				<p>点此查看相关购买规则</p>
				<?php if ($isProfitUser): ?>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/cai.png">
				<?php endif; ?>
			</div>
			<div class="site">

				<?php foreach($goods as $goodsInfo): ?>
				<!--商品01开始-->
				<div class="good">
					<div class="good_l">
						<img src="<?php echo $goodsInfo['img_name']; ?>">
					</div>
					<div class="good_f" data-id="<?php echo $goodsInfo['pid']; ?>">
						<p><?php echo $goodsInfo['name']; ?></p>
						<p>彩富价: ¥<?php echo $goodsInfo['customer_price']; ?></p>
						<p>至尊价: <span style="color:#FF4040">¥</span><span class="price">10.00</span><span class="tips"></span></p>
						<p>立即购买</p>
					</div>
				</div>
				<!--商品01结束-->
				<?php endforeach; ?>


			</div>
		</div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗开始-->
		<div class="Popup hide">
			<div class="Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/smile01.png">
			</div>
			<div class="Popup_con">
				<div class="Popup_con_t"></div>
				<div class="Popup_con_b">
					<p>恭喜您，获得了<?php echo $couponNum; ?>张至尊价优惠券</p>
					<p>可享受至尊价优惠</p>
					<p>有效期: <?php echo $beginDate.'-'. $endDate; ?></p>
				</div>
				<div class="Popup_con_footer">
					<a href="javascript:void(0)">
						领券
					</a>
				</div>
			</div>
		</div>
		<!--弹窗结束-->
		<!--购买须知开始-->
		<div class="Popup01 hide">
			<div class="Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/smile01.png">
			</div>
			<div class="Popup_con">
				<div class="Popup_con_t"></div>
				<div class="Popup_con_zhi">
					<p>至尊价购买须知</p>
					<p>购买时会消耗1张至尊价优惠券，</p>
					<p>您可通过参加彩富人生获得哦！</p>
				</div>
				<div class="Popup_con_footer">
					<a href="javascript:void(0)">
						确定
					</a>
				</div>
			</div>
		</div>
		<!--购买须知结束-->
		<!--非彩富用户开始-->
		<div class="Popup02 hide">
			<div class="Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/smile01.png">
			</div>
			<div class="Popup_con">
				<div class="Popup_con_t"></div>
				<div class="Popup_con_rich">
					<p>彩富用户专享</p>
					<p>投资彩富人生即可尊享该特权</p>
					<p>
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/icon01.png">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/icon02.png">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/icon03.png">
					</p>
				</div>
				<div class="Popup_con_footer">
					<a href="javascript:void(0)">
						我也要参加
					</a>
				</div>
			</div>
		</div>
		<!--非彩富用户结束-->
		
		<!--彩富分享有礼-->
		<div class="Popup03 hide">
			<div class="Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/profitShop/');?>images/smile01.png">
			</div>
			<div class="Popup_con cf_Popup_con">
				<div class="Popup_con_t"></div>
				<div class="cf_Popup_con_zhi">
					<p>彩富分享有礼</p>
					<p>1、已有<span>20</span>人通过您分享的页面领取特权券;</p>
					<p>2、您总共获得<span>20</span>次彩富价购买机会;</p>
					<p>3、您还剩<span>15</span>次彩富价购买机会。</p>
				</div>
				<div class="Popup_con_footer cf_Popup_con_footer">
					<a href="javascript:void(0)">
						确定
					</a>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			
			/*判断用户是否有领卷资格*/
			var couponNum = <?php echo $couponNum; ?>;
			if(couponNum){
				$(".Popup").removeClass("hide");
				$(".mask").removeClass("hide");
			}
			/*领卷*/
			$(".Popup .Popup_con_footer>a").click(function(){
				/*ajax领卷*/
				$.ajax({
					type: 'GET',
					url: '/ProfitShop/Receive',
					dataType: 'json',
					success: function (result) {
						if (1 == result.retCode) {
							$(".Popup").addClass("hide");
							$(".mask").addClass("hide");
							alert('领取成功');
						}
					}
				});
			});
			//非彩富我也要参加
			$(".Popup02 .Popup_con_footer>a").click(function(){
				/*跳转彩富的链接*/
				mobileJump("EReduceList");
			});
			//彩的弹窗
			$(".rule img").click(function(){
				$.ajax({
					type: 'GET',
					url: '/ProfitShop/CountTicket',
					dataType: 'json',
					success: function (result) {
						if (1 == result.retCode) {
							$(".cf_Popup_con_zhi").find("P:eq(1) span").text(result.data.shareNum);
							$(".cf_Popup_con_zhi").find("P:eq(2) span").text(result.data.ownNum);
							$(".cf_Popup_con_zhi").find("P:eq(3) span").text(result.data.overNum);
							$(".Popup03").removeClass("hide");
							$(".mask").removeClass("hide");
						}
					}
				});
			});


			$(".Popup01 .Popup_con_footer a").click(function(){
				$(".Popup01").addClass("hide");
				$(".mask").addClass("hide");
			});
			
				$(".mask,.cf_Popup_con_footer").click(function(){
					$(".Popup").addClass("hide");
					$(".Popup01").addClass("hide");
					$(".Popup02").addClass("hide");
					$(".Popup03").addClass("hide");
					$(".mask").addClass("hide");
				});
			//资格提示
			$(".tips").click(function(){
				$(".Popup_con_zhi p:eq(0)").text("至尊价购买须知");
				$(".Popup_con_zhi p:eq(1)").text("购买时会消耗1张至尊价优惠券，");
				$(".Popup_con_zhi p:eq(2)").text("您可通过参加彩富人生获得哦！");
				$(".Popup01").removeClass("hide");
				$(".mask").removeClass("hide");
			});
			//立即购买
			$(".good").find(".good_l,.good_f p:eq(3),.good_f p:eq(0)").click(function(){
				/*非彩富*/
				var _this = $(this).parents(".good").find(".good_f");
				$.ajax({
					type: 'GET',
					url: '/ProfitShop/CanBuy',
					dataType: 'json',
					success: function (result) {
						if (1 == result.retCode) {
							if (1 == result.data.canBuy) {
								window.location.href = result.data.url + '&pid=' +_this.attr("data-id");
							}
							else if (2 == result.data.canBuy){
								$(".Popup_con_zhi p:eq(0)").text("");
								$(".Popup_con_zhi p:eq(1)").text("您本周的尊享");
								$(".Popup_con_zhi p:eq(2)").text("购买机会已用完！");
								$(".Popup01").removeClass("hide");
								$(".mask").removeClass("hide");
								
							}
							
							else if (3 == result.data.canBuy){
								$(".Popup02").removeClass("hide");
								$(".mask").removeClass("hide");
							}
						}
					}
				});
			});
			//购买规则页面跳转
			$(".rule").find("p").click(function(){
                window.location.href="/ProfitShop/Introduce";
			});
			
			function mobileJump(cmd)
			    {
			        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
			            var _cmd = "http://www.colourlife.com/*jumpPrototype*colourlife://proto?type=" + cmd;
			            document.location = _cmd;
			        } else if (/(Android)/i.test(navigator.userAgent)) {
			            var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
			            eval(_cmd);
			        } else {
			
			        }
			    }
		});
			
		</script>
		
	</body>
</html>
