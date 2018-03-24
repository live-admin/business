<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>新人有礼</title>
		<meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>js/libs/jquery/jquery.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>js/src/jquery.slotmachine.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>js/luckyDraw.js"></script>
  	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>css/normalize.css">
	</head>
	<body style="overflow:hidden;">
		<div class="conter conter_hieght">
			<div class="conter_hieght_ban">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>images/bg02.jpg">
			</div>
			<div class="conter_bg">
				<div class="conter_bg_top">
					<p>你还有 <span><?php echo $chance;?></span> 次抽奖机会</p>
				</div>
				<div class="conten">
					<div class="content_img" id="switch">
						<div class="l_draw_con_box01"></div>
						<div class="l_draw_con_box02  handel"></div>
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
					<div class="l_draw_p">
						<div class="textContent" style="display: none">
							<p></p>
							<p></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="on_time">
			<p>6月4日-6月13日 每天前200名注册者可参与抽奖</p>
		</div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<!--遮罩层结束-->
		<!--弹窗开始-->
		<div class="Popup hide">
			<div class="Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>images/litchi01.png">
			</div>
			<div class="Popup_con">
				<div class="Popup_con_b">
					<p>恭喜你抽中奖品</p>
					<p>荔枝一元换购码</p>
				</div>
				<div class="Popup_con_s">
					<p>已发到您的帐户中，请直接换购下单</p>
				</div>
				<div class="Popup_con_footer">
					<a href="javascript:void(0)">
						去换购
					</a>
				</div>
			</div>
		</div>
		
		<!--弹窗结束-->
		<!--谢谢开始-->
		<div class="Popup01 hide">
			<div class="Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/liZhiCulture/');?>images/smile.png">
			</div>
			<div class="Popup_con">
				<div class="Popup_con_b Popup_con_p">
					<p>谢谢参与</p>
				</div>
				<div class="Popup_con_footer">
					<a href="javascript:void(0)">
						确定
					</a>
				</div>
			</div>
		</div>
		<!--谢谢结束-->
		<script type="text/javascript">
		 	var awarderList = <?php echo $prizeList;?>;
		</script>
	</body>
</html>