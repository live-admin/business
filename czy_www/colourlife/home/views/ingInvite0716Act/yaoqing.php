<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>邀请注册送饭票</title>
		<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
		<link href="<?php echo F::getStaticsUrl('/common/css/lucky/ingInvite0716Act/xrl.css'); ?>" rel="stylesheet">
	</head>

	<body>
		<div class="xrl">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/head.jpg');?>" class="lotteryimg" />
			</div>
			<div class="row_first clearfix">
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/img_05.jpg');?>" />
				<!--<span >成功邀请10位好友<br/>注册彩之云APP奖励</span>-->
				<!--华丽的分割线-->
				<!-- <div class="divider"></div> -->
			</div>
			<div class="row_second">
				<input id="phone" type="tel" placeholder="请输入好友手机号码" class="moblie_number"/><span class="ljyq send_number">立即邀请</span>
			</div>

			<div class="jump clearfix">
				<div><a href="/ingInvite0716Act/myInviteRecord"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/img_02.jpg');?>" /></a></div>
		        <div><a href="/ingInvite0716Act/successList"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/img_03.jpg');?>" /></a></div>
		        <div><a href="/ingInvite0716Act/rules"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/img_04.jpg');?>" /></a></div>
		    </div>
			<div class="footer">
				<p class="big_text">温馨提示</p>
				<p>1.邀请有效时间：每天9：00—21:00 。</p>
				<p>2.每邀请10位好友成功注册彩之云，您可以获赠50元饭票，经后台人工审核后赠送到账（好友成功注册后3个工作日内）。 不满10个不予赠送。</p>
				<p>3.若您邀请注册的号码不符合规则，即为邀请无效。</p>
			</div>
			<div class="sm clearfix">
				<div class="sm_left clearfix">
					<p>好友如何注册彩之云：</p>
					<p>1.复制下载链接发送给TA：</p>
					<p><a href="http://dwz.cn/8YPIv">http://dwz.cn/8YPIv</a>
					</p>
					<p>2.扫一扫，没烦恼，让TA扫码下载吧！</p>
				</div>
				<div class="sm_right clearfix">
					<img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/img_07.jpg');?>"/>
				</div>
			</div>
			<p class="botp">★注：彩之云享有本次活动的最终解释权 </p>

			<!--弹出框-->
			<div class="pop_up" style="display: none;">
				<!--获得 -->
				<div class="iphone_pop alert1" style="display: none;">
					<div class="window_close clearfix">
						<img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/close.jpg');?>" />
					</div>
					<p class="tishi">温馨提示</p>
					<p class="pop_img">恭喜您已成功邀请了您的好友 <br/>复制下载链接发送给你的好友吧！
						<br />
						<a href="http://dwz.cn/8YPIv">http://dwz.cn/8YPIv</a>
					</p>
					<div class="btn btn_alert">继续邀请</div>
				</div>

				<div class="iphone_pop alert2" style="display: none;">
					<div class="window_close clearfix">
						<img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/close.jpg');?>" />
					</div>
					<p class="tishi">温馨提示</p>
					<p class="pop_img">亲~现在不是活动有效邀请时间，您确定要邀请您的好友么？</p>
					<div class="btn btn_send">确定邀请</div>
				</div>
        
		        <div class="iphone_pop alert3" style="display: none;">
		          <div class="window_close clearfix">
		            <img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/close.jpg');?>" />
		          </div>
		          <p class="tishi">温馨提示</p>
		          <p class="textinfo"></p>
		          <div class="btn btn_alert">关闭</div>
		        </div>

			</div>
	</div>
	<script type="text/javascript">				
		//关闭弹出框
		$('.window_close>img,.btn_alert').click(function(){
			$(this).parents('.iphone_pop').hide();
			$('.pop_up').hide();
		}); 
				
				
		$('.btn_send').click(function(){
			//return false;
			$('.alert2').hide();
			$('.pop_up').show();
		   	if ($('.moblie_number').val().match(/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(19[0-9]{1}))+\d{8})$/)) {
		        $.post(
		          '/ingInvite0716Act/inviteFriend',
		          {'mobile':$('.moblie_number').val()},
		          function (data){
		             if(data.code == "success"){
		                 $('.alert1').show(); 
		                 $('.pop_up').show();     
		                 // $('.ljyq').show(); 
		             }else{
		                 $('.alert3').show();
		                 $('.textinfo').text(data.code);
		                 $('.pop_up').show();
		                 // $('.ljyq').show();
		             } 
		          }
		          ,'json').error(function() {
		              $('.alert3').show(); 
		              $('.textinfo').text("您的网络异常，请检查网络后重试；");
		              $('.pop_up').show();
		              // $('.ljyq').show();
		          });
		   }else{    
		        $('.alert3').show(); 
		        $('.textinfo').text("您输入的手机号码格式不对！");
		        $('.pop_up').show(); 
		        // $('.ljyq').show();
		   }
		 });
	


		$('.ljyq').click(function(){
		   // $('.ljyq').hide();
		   $('.pop_up').show();
		   if ($('.moblie_number').val().match(/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(19[0-9]{1}))+\d{8})$/)) {
		        $.post(
		          '/ingInvite0716Act/inviteFriendWarn',
		          {'mobile':$('.moblie_number').val()},
		          function (data){
		             if(data==1){
						$('.alert2').show(); 
						$('.pop_up').show();     
						// $('.ljyq').show();
						return false;
		             }else if(data.code == "success"){
		             	$('.alert1').show(); 
						$('.pop_up').show();
						// $('.ljyq').show();
		             }else{
						$('.alert3').show(); 
						$('.textinfo').text(data.code);
						$('.pop_up').show();
						// $('.ljyq').show();
		             } 
		          }
		          ,'json').error(function() {
		              $('.alert3').show(); 
		              $('.textinfo').text("您的网络异常，请检查网络后重试；");
		              $('.pop_up').show();
		              // $('.ljyq').show();
		          });
		   }else{    
		        $('.alert3').show(); 
		        $('.textinfo').text("您输入的手机号码格式不对！");
		        $('.pop_up').show(); 
		        // $('.ljyq').show();
		   }
		 });
			</script>
	</body>

</html>