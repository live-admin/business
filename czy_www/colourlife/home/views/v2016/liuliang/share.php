<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>新人有礼！最高可领1G流量！</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	   	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
        <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>js/share.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>css/normalize.css">
    	<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>css/new1.css">
	    
	</head>
	<body style="background-color: #FFF5D7;">
		<div id="top">
			<div class="linqu_tel_wrap">
				<input type="text" placeholder="请输入您的手机号" class="input_bar_call info_start" id="mobile" maxlength="11"/>
				<div class="input_bar_two">
					<input type="text" placeholder="请输入验证码" class="input_bar_code info_start" id="re_code" maxlength="4"/>
					<span>获取验证码</span>
				</div>
				<div type="button" class="linqu_btn disabled">立即领取</div>
			</div>	
		</div>
		<div id="footer">
			<div class="active_rule">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/liuliang/');?>images/active_rule_h1.png"/>				
				<ol>
					<li>注册成为彩之云账户，分享即可领取随机流量包，最高可领1g。</li>
					<li>流量领取成功后，当月生效。</li>
					<li>本次活动适用于全国移动、联通、电信用户（港澳台号码除外）。</li>
				</ol>
			</div>
		</div>
		
		<!--遮罩层-->
		<div class="mask hide"></div>
		<div class="mask1 hide"></div>
		
		<!--领取成功弹窗-->
		<div class="linqu_success_pop pop_bg hide">
			<div class="success_wrap" >
				<p>恭喜你领到</p>
				<p>30M流量包</p>
				<p>(具体到账时间以运营商短信为准)</p>
				<a herf="javascript:void(0);">确&nbsp;定</a>
			</div>
		</div>
		
		<!--还差一步-->
		<div class="pop_next hide">
			<div class="pop_next_txt" >
				<p>还差一步</p>
				<p>使用该手机号码登录彩之云app</p>
				<p>即可领取</p>
				<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">立即下载</a>
				<p>Wi-Fi环境下下载仅需10秒</p>
			</div>
		</div>
		
		<!--领取失败弹窗-->
		<div class="linqu_fail_pop pop_bg hide">
			<div class="fail_wrap">
				<p>每个微信ID限领一次</p>
				<a herf="javascript:;" >确&nbsp;定</a>
			</div>
		</div>
		
		<div class="loaders hide">
	 	 	<div class="loader">
		    	<div class="loader-inner ball-pulse">
			      	<div></div>
			      	<div></div>
			      	<div></div>
			    </div>
		  	</div>
		</div>
		
	<script type="text/javascript">
	$(document).ready(function(){
		document.addEventListener('DOMContentLoaded', function () {
	  	document.querySelector('.main').className += 'loaded';
		});
	});

			var validCode = true;
			var code = /^[0-9]{4}$/;
			var _code=1234;
			var position = null;
			var phone = /^1[3|4|5|7|8]\d{9}$/;
		//手机号码确定
			$(".linqu_btn").click(function(){
				var code01 = $(".input_bar_code").val();
				var mobile = $("#mobile").val();
				if(phone.test(mobile) && code.test(code01)){
					$(".mask").removeClass("hide");
					$(".loaders").removeClass("hide");
					$.ajax({
						async:true,
						type:"POST",
						url:"/Liang/GetChance",
						data:{
							'mobile' : mobile,
							'code' : code01
							},
						dataType:'json',
						success:function(data){
							
							if(data.status == 1){
								$(".loaders").addClass("hide");
								$(".mask").addClass("hide");
//								$(".success_wrap p:eq(1)").text(data.msg);
//								$(".linqu_success_pop").removeClass("hide");
								$(".pop_next").removeClass("hide");
								$(".mask").removeClass("hide");
							}
							else {
								$(".loaders").addClass("hide");
								$(".mask").addClass("hide");
//								$(".fail_wrap p:eq(0)").text(data.msg);
//								$(".linqu_fail_pop").removeClass("hide");
//								$(".pop_next_txt p:eq(0)").text(data.msg);
//								$(".pop_next").addClass("hide");
//								$(".mask").removeClass("hide");
								alert(data.msg);
							}
						} 
					});
				}
				
				else if(phone.test(mobile) && code.test(code01)=="" ) {
					alert("验证码不能为空！");
				}
				
			});
			
			//验证码的短信时间
			$(".input_bar_two span").click(function() {
				if(!validCode){
					return false; 
				}
				clearInterval(position);
				position = self.setInterval(button_class,50);
				if($("#mobile").hasClass("info_astart")||!phone_verificate()){
					return false;
				}
				var isInit = button_class();
				if (isInit) {
					alert("请输入正确的验证码！");
					return false;
				}
					var mobile = $("#mobile").val();
					$.ajax({
					async:true,
					type:"POST",
					url:"/Liang/SendCode",
					data:{'mobile':mobile},
					dataType:'json',
					success:function(data){
						if(data.status == 1){  //根据后台返回的状态
							console.log("短信验证码发送成功123");
							var time = 60;
							if (validCode) {
								validCode = false;
								$(this).addClass("msgs1");
								var t = setInterval(function() {
									time--;
									$(".input_bar_two span").css("color","#9d9b99");
									$(".input_bar_two span").html('' + time + ''+'s后重新获取');
										if (time == 0) {
											clearInterval(t);
											$(".input_bar_two span").html("获取验证码");
											$(".input_bar_two span").css("color","#6a3b96");
											validCode = true;
										    $(this).removeClass("msgs1");
				
										}
								}, 1000)
							}
						}
						else{
							alert(data.msg);
							clearInterval(position);
							return false;
						}
					} 
				});
		});
			
			function button_class () {
					//var _length = ($(".input_bar_code").val()).length;
				if($(".input_bar_code").val().length >= 4 ){
					
					$(".input_bar_code").removeClass("info_start");
				}
				if($(".input_bar_call").hasClass("info_start")){
					return false;
				}
				else{
					if($(".input_bar_code").hasClass("info_start")){
						phone_verificate();
						return false;
					}
					else{
						if (phone_verificate() && psw_txt()) {
						return true;	
					}
					}	
				}
			}
			
			//手机校验
			function phone_verificate(){
				var mobile = $("#mobile").val();
				if (phone.test(mobile)) {
					return true;
				} 
				else{
					alert("请输入正确的手机号码！");
					return false;
				}
			}
			
			//验证码校验
			function  psw_txt(){
				var duanxin = $(".input_bar_code").val();
				if (code.test(duanxin)) {
					return true;
				} 
				else{
					alert("请输入正确的验证码！");
					return false;
				}
			}
			
		$(".fail_wrap>a").click(function(){
			$(".linqu_fail_pop").addClass("hide");
			$(".pop_next").removeClass("hide");
			$(".mask1").removeClass("hide");
		});
		
		//弹框消失
		$(".mask, .fail_wrap a,.pop_next>a").click(function(){
			$(".mask").addClass("hide");
			$(".linqu_success_pop").addClass("hide");
			$(".linqu_fail_pop").addClass("hide");
			$(".pop_next").addClass("hide");
		});
		
//		$(".success_wrap>a").click(function(){
//			$(".linqu_success_pop").addClass("hide");
//			$(".pop_next").removeClass("hide");	
//			$(".mask").removeClass("hide");
//		});
		
	</script>
	</body>
</html>
