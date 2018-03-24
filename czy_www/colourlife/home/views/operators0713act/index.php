<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>新人注册领饭票</title>
		<link href="<?php echo F::getStaticsUrl('/common/css/lucky/operators0713act/xrl.css'); ?>" rel="stylesheet">
		<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
	</head>

	<body>
		<div class="xrl">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/head.jpg');?>" class="lotteryimg" />
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/XA1_03.jpg');?>" class="img_center" />
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/XA1_04.jpg');?>" class="img_center get" />
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/XA1_05.jpg');?>" class="img_center" />
				<a href="/luckyApp?cust_id=<?php echo $cust_id?>"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/XA1_06.jpg');?>" class="img_center" /></a>
			</div>
			<div class="remark">
				<p>有趣小游戏，丰富饭票奖励，</p>
				<p>神秘小礼品，多重惊喜等着你！</p>
			</div>

			<div class="footer">
				<p>
					<img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/XA1_07.jpg');?>" />
				</p>
				<p>1饭票=1人民币，饭票支持在彩生活消费使用。</p>
				<p>支持在“京东特供、海外直购、生鲜速递、彩生活特供、手机充值”等支付使用。</p>
			</div>
			
			<div class="footer">
				<p>
					<img class="footer_img" src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/XA1_08.jpg');?>" />
				</p>
				<p>1.活动有效期：2015年7月21日至8月21日</p>
				<p>2.本次活动仅限西安彩生活社区的业主参与</p>
				<p>3.注册为“体验区”用户不享受新人礼</p>
				<p>4.彩之云保留对本次活动的最终解释权</p>
			</div>
			 <p class="botp">★注：彩之云享有本次活动的最终解释权 </p>
		</div>
		
		
		<!--弹出框-->
		<div class="pop_up" style="display: none;">
			<!--获得 -->
			<div class="iphone_pop alert1" style="display: none;">
				<div class="window_close clearfix"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/close.jpg');?>"/></div>
					<div class="pop_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/alert_01.jpg');?>"/></div>
					<p class="footer_text">恭喜您领取了5元饭票</p>
			</div>

			<!--不符合 -->
				<div class="iphone_pop alert2" style="display: none;">
				<div class="window_close clearfix"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/close.jpg');?>"/></div>
					<div class="pop_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/alert_02.jpg');?>"/></div>
					<p class="footer_text alert">您不符合饭票领取条件</p>
			</div>

			<!--已经领取过-->
				<div class="iphone_pop alert3" style="display: none;">
				<div class="window_close clearfix"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/close.jpg');?>"/></div>
					<div class="pop_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/alert_02.jpg');?>"/></div>
					<p class="footer_text alert">您已经领取过饭票喽</p>
			</div>

			<!--红包领取失败-->
				<div class="iphone_pop alert4" style="display: none;">
				<div class="window_close clearfix"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/close.jpg');?>"/></div>
					<div class="pop_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/operators0713act/alert_02.jpg');?>"/></div>
					<p class="footer_text alert">饭票领取失败</p>
			</div>
		</div>
		
		<script type="text/javascript">
		//点击立即领取图片触发事件
		$('.get').click(function(){
			$('.pop_up').show();
			getLuckyData();
		});
		
		//关闭弹出框
		$('.window_close>img').click(function(){
			$(this).parents('.iphone_pop').hide();
			$('.pop_up').hide();
		});
		
		
		//触摸弹出框其他区域隐藏当前窗口
		$('.pop_up').click(function(e) { 
				var obj = e.srcElement || e.target;
				if ($(obj).is('.pop_up')) {
					$('.pop_up,.pop_up>div').hide();
				}
			});


		function getLuckyData() { //得到数据
	      $.ajax({
	        type: 'POST',
	        url: '/operators0713act/doSendRedPacket',
	        data: null,
	        dataType: 'json',
	        async: false,
	        error: function () {	 
	          $('.alert4').show();//领取失败
	        },
	        success: function (data) {
	          showPackage(data);
	        }
	      });
    	}


	    //根据结果弹出红包
	    function showPackage(prize) {
	    	// alert(prize);
	      if (prize==0) {//用户不存在或被禁用
	          $('.alert2').show();
	      }else if(prize==1){//活动用户不含体验区
	          $('.alert2').show();
	      }else if(prize==2){//用户不是在活动时间内注册
	          $('.alert2').show();
	      }else if(prize==3){//红包发放失败
	          $('.alert4').show();
	      }else if(prize==4){//成功发放
	          $('.alert1').show();
	      }else if(prize==5){//活动失效
	          $('.alert2').show();
	      }else if(prize==6){//红包已经领取过,不能重复领取
	          $('.alert3').show();
	      }else if(prize==21){//请完善您的个人基本信息
	          $('.alert2').show();
	      }else if(prize==22){//请更改注册名后再领取!
	          $('.alert2').show();
	      }else if(prize==100){//请更改注册名后再领取!
	          $('.alert2').show();
	      }else{//异常
	          $('.alert4').show();
	      }
	    }
		</script>
	</body>

</html>