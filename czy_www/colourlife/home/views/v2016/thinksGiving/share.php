<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>感恩福袋任性送</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>js/share.js"></script> 
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>css/layout.css" />
	</head>
	<body style="background: #f8f4c6;">
		<div class="contaner">
			<div class="contaner_bg">
				<p>好友<span>131xxxx1234</span>邀请您一起领福袋</p>
			</div>
			
			<div class="input_bar_one"><!--该号码已注册彩之云，请直接登录-->
				<p class="sure hide"></p>
				<input type="text" placeholder="请输入您的手机号" class="input_bar_call start" id="phone" maxlength="11"/>
				<div class="input_bar_two">
					<input type="text" placeholder="请输入验证码" class="input_bar_code start" id="re_code" maxlength="4"/>
					<span>获取验证码</span>
				</div>
				<!--确认密码-->
				<div class="input_bar_three">
					<p class="mima hide">密码不符合规则，请重新输入！</p>
					<input type="password" value="" placeholder="请设置您的登录密码" class="input_bar_pw start" id="pass"/>
					<p class="pass_tip">*密码长度必须在8位以上，且同时包含大写小写字母＋数字</p>
				</div>	
				<div class="get_btn_sure">
					<a href="javascript:void(0);">确定</a>
				</div>
				
			</div>
			<!--立即下载-->
			<div class="btm_bg"></div>
	    	<div class="share_footer">
		     	<div class="share_footer_box01">
		     		<img src="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>images/czy_logo.png">
		     	</div>
		     	<div class="share_footer_box02">
		     		<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">
		     			立即下载
		     		</a>
		     	</div>
		     	<div class="share_footer_box03">
		     		<a href="javascript:void(0);">
		     			<img src="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>images/dele.png">
		     		</a>
		     	</div>
   		 	</div>
		</div>
		
		<div class="mask hide"></div>
		
		<div class="Pop_bg hide">
			<div class="Pop_bg_top">
				<p>恭喜您，注册成功！</p>
			</div>
			<div class="Pop_bg_bottom">
				<p>赶紧登录彩之云app领取</p>
				<p>福袋吧！</p>
				<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">去下载</a>
			</div>
			<div class="close">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/thinksGiving/');?>images/close.png"/>
			</div>
		</div>
		
			
		<script type="text/javascript">
			var sd_id = <?php echo json_encode($sd_id);?>;
			var _mobile = <?php echo json_encode($mobile);?>;
		</script>
	</body>
</html>
