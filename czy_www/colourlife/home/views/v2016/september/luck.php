<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>邀请有奖</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>js/luckyDraw.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>js/jquery.slotmachine.js"></script>
    	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background: #F0E8E4 !important;">
		<div class="l_draw">
			<div class="l_draw_b">
				<div class="l_draw_title">
					<p>新用户抽奖</p>
					<p>你还有 <span><?php echo $chance;?></span> 次抽奖机会</p>
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/jump.png"/>
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
			    <div class="rule">
			    	<h4>活动规则</h4>
			    	<div class="scr">
				    	<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/start.png"/></span><span>新人饭票随机送：</span></p>
				    	<p>1.新用户首次登录app，即可参与随机抽奖送饭票；</p>
				    	<p>2.用户所抽到的饭票将直接存入个人彩之云账户中；</p>
				    	<p>3.每个ID限领取一次。</p>
				    	<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/start.png"/></span><span>首单有礼：</span></p>
				    	<p>新用户在彩生活特供下首单，赠送满100元减10元抵用券。</p>
				    	<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/start.png"/></span><span>邀请送好礼：</span></p>
				    	<p>1.每成功邀请一名好友注册，即可获得1饭票。</p>
				    	<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/start.png"/></span><span>备注：</span></p>
				    	<p>1.如活动期间发现作弊行为，则取消该ID的中奖资格，并予以封号处理;</p>
				    	<p>2.如因活动期间出现网络异常系统问题，导致无法正常显示中奖结果，一切将以获奖记录为准。</p>
			    	</div>
			    </div>
			    
			    <div class="rule_red">
				    	<p class="p_red">活动由彩之云提供，与设备生产商Apple Inc公司无关</p>
		    	</div>
			</div>
			
		</div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<div class="mask1 hide"></div>
		<!--遮罩层结束-->
		<!--奖品弹窗开始-->
		<!--奖品弹窗-->
		<div class="Popup hide">
			<div class="Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/gift.png">
			</div>
			<div class="Popup_con">
				<div class="Popup_con_b">
					<p>恭喜你抽中奖品</p>
				</div>
				<div class="Popup_con_footer">
					<a href="javascript:void(0)">
						确定
					</a>
				</div>
			</div>
		</div>
		
		<!--弹窗结束-->
		<script type="text/javascript">
			var way = <?php echo json_encode($way);?>;
			var chance = <?php echo json_encode($chance);?>;
			var detail = <?php echo json_encode($detail);?>;
//			var cust_id= <?php //echo json_encode($c_id);?>//;
		</script>
	</body>
</html>
