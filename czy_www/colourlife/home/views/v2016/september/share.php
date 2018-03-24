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
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>js/share.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body style="background: #f0e8e4;">
		<input type="hidden" name="" id="toke" value="" />
		<div class="contanis">
			<div class="bg_img ">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/bg1.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/bg2.jpg"/>
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/bg3.jpg"/>
					<div class="send_txt">
						<p><span>您的好友</span><span>131xxxx1234</span></p>
						<p>送您10元购物券！</p>
						<p></p>
					</div>
			</div>
			
			<div class="get_btn">
				<a href="javascript:void(0);">立即领取</a>
			</div>
			
			<div class="input_bar_one hide"><!--该号码已注册彩之云，请直接登录-->
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
					<div class="get_btn_sure">
						<a href="javascript:void(0);">确定</a>
					</div>
				</div>
				<!--<div class="get_btn_next active_a">
					<a href="javascript:void(0);">下一步</a>
				</div>-->
			</div>
			<!--立即下载-->
			<div class="btm_bg"></div>
	    	<div class="share_footer">
		     	<div class="share_footer_box01">
		     		<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/czy_logo.png">
		     	</div>
		     	<div class="share_footer_box02">
		     		<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">
		     			立即下载
		     		</a>
		     	</div>
		     	<div class="share_footer_box03">
		     		<a href="javascript:void(0)">
		     			<img src="<?php echo F::getStaticsUrl('/activity/v2016/september/');?>images/delet.png">
		     		</a>
		     	</div>
   		 	</div>
		</div>
		
		<!--遮罩层-->
		<div class="mask hide"></div>
		<div class="res_pop hide">
			<div class="res_pop_bg">
				<div class="res_pop_txt">
					<p>恭喜您，注册成功！</p>
					<p>登入彩之云app，即可参与抽奖，瓜分好礼。</p>
				</div>
				<div class="res_pop_btn">
					<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">去下载</a>
				</div>
			</div>
		</div>
		
		
		<script type="text/javascript">
			var sd_id = <?php echo json_encode($sd_id);?>;
			var _mobile = <?php echo json_encode($mobile);?>;
			
		
			$(document).ready(function(){
				//手机格式正则
     			var phone = /^1[3|4|5|7|8]\d{9}$/;
				
     			var validCode = true;
     			//密码格式正则
				var code = /^[0-9]{4}$/;
				//确认秘密 
				var a=/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,16}/;
				
				var phoneFlag = false;
				var codeFlag = false;
				var position = null;
				//立即领取切换
				$(".get_btn").click(function(){
					$(".send_txt p:first-child").text("好友"+_mobile+"邀请您注");
					$(".send_txt p:nth-child(2)").text("册彩之云账号，登入app");
					$(".send_txt p:nth-child(3)").text("即可参与抽奖哦！");
					$(".input_bar_one").removeClass("hide");
					$(".get_btn").addClass("hide");
					
				});
				
				$(".send_txt>p:eq(0)>span:eq(1)").text(_mobile);
				
				//手机号码
				$(".input_bar_call").change(function(){
					$(this).removeClass("start");
					clearInterval(position);
					position = self.setInterval(button_class,50);
					$(".sure").addClass("hide");
				});
				//确认密码
				$(".input_bar_pw").change(function(){
					if(!a.test($("#pass").val()))
		            {
		            	$(".mima").removeClass("hide").text("密码不符合规则，请重新输入！");
		                return false;
		            }else{
		            	$(".sure").removeClass("hide").text("密码不符合规则，请重新输入！");
		            }
				});
				//点击下一步
//				$(".get_btn_next").click(function(){
//					var mobile = $("#phone").val();
//					var code = $("#re_code").val();
//				 	$.ajax({
//					async:true,
//					type:"POST",
//					url:"/september/InviteRegister",
//					data:{
//						'mobile' : mobile,
//						'code' : code
//					},
//					dataType:'json',
//					success:function(data){
//						if(data.retCode == 1){  //根据后台返回的状态
//							if(!$(this).hasClass("active_a"))
//							{
//								$(".input_bar_one").addClass("hide");
//								$(".input_bar_three").removeClass("hide");
//							 	$("#toke").val(data.data._token);
//							}
//						}
//						else{
//							$(".input_bar_one p").removeClass("hide").text(data.retMsg);
//							clearInterval(position);
//						}
//					} 
//				});
//				});
				
				//点击确定
				$(".get_btn_sure").click(function(){
					var pass = $(".input_bar_pw").val();
					var mobile = $("#phone").val();
					var code01 = $("#re_code").val();
					var password = $("#pass").val();
					var toke = $("#toke").val();
					console.log(password);
					if(phone.test(mobile) && a.test(pass) && code.test(code01)){
						$.ajax({
							async:true,
							type:"POST",
							url:"/september/Register",
							data:{
								'mobile' : mobile,
								'code' : code01,
								'password' : password,
//								'_token' : toke,
								'sd_id' : sd_id
							},
							dataType:'json',
							success:function(data){
								if(data.retCode == 1){  //根据后台返回的状态
									rest_suc();
								}
								else{
		//							$(".input_bar_three p").removeClass("hide");
									$(".sure").removeClass("hide").text(data.retMsg);
								}
							} 
				     	});
					}else if(phone.test(mobile) &&　a.test(pass)=="") {
					     $(".sure").removeClass("hide").text("密码不符合规则，请重新输入！");	
					}

					
					
					function rest_suc(){
						if(pw(pass)){
							$(".res_pop").removeClass("hide");
							$(".mask").removeClass("hide");
							$(".get_btn_sure a").attr("disablae")
						}
					}
					
				 	
				});	
					
				//密码校验
				function pw(temp){
		        	if(!a.test(temp))
		            {
		            	$(".input_bar_three p:eq(0)").removeClass("hide").text("密码不符合规则，请重新输入！");
		                return false;
		            }
		            $(".input_bar_three p:eq(0)").removeClass("hide").text("请输入密码");	
		            return true;
		        }
				
				function button_class () {
					//var _length = ($(".input_bar_code").val()).length;
					if($(".input_bar_code").val().length >= 4 ){
						
						$(".input_bar_code").removeClass("start");
					}
					if($(".input_bar_call").hasClass("start")){
						return false;
					}
					else{
						if($(".input_bar_code").hasClass("start")){
							phone_verificate();
							return false;
						}
						else{
							if (phone_verificate() && psw_txt()) {
							$(".get_btn_next").removeClass("active_a");
						}
						}	
					}
				}
				//手机校验
				function phone_verificate (){
					if (phone.test($('#phone').val())) {
//						if ($('#phone').val() == num) {
//							$(".input_bar_one p").removeClass("hide").text("手机号已被使用！");
//							return false;
//						}else{
//							$(".input_bar_one p").addClass("hide");
////						    $(".input_bar_call").addClass("start");
//							return true;
//						}
//						$(".input_bar_one p").addClass("hide");
						return true;
					} 
					else{
						$(".sure").removeClass("hide").text("请输入正确的手机号码！");
						return false;
					}
				}	
				
				//按钮高亮
				function hightLight(){
					var tel=$(".input_bar_call");
					var info=$(".input_bar_code");
					
						if(tel.hasClass("start") && tel.hasClass("start")){
						$(".get_btn_next").removeClass("active_a");
					}else{
						$(".get_btn_next").addClass("active_a");
						return false;
				
					}
					
				}
				//验证码
				function  psw_txt(){
					var duanxin = $(".input_bar_code").val();
					if (code.test(duanxin)) {
//						$(".input_bar_one p").addClass("hide");
//						$(".input_bar_code").addClass("start");
						$(".mima").addClass("hide");
						return true;
					} 
					else{
						$(".sure").removeClass("hide").text("请输入正确的验证码！");
						return false;
					}
				
				}
				
				//验证码的短信时间
				$(".input_bar_two span").click(function() {
					if(!validCode){
						return false; 
					}
					clearInterval(position);
					position = self.setInterval(button_class,50);
					if($("#phone").hasClass("start")||!phone_verificate ()){
							return false;
					}
					var isInit = button_class();
						if (isInit) {
							$(".sure").removeClass("hide").text("请输入正确的手机号码！");
							return false;
					}
					
						
						var mobile = $("#phone").val();
						$.ajax({
						async:true,
						type:"POST",
						url:"/September/SendCode",
						data:{'mobile':mobile},
						dataType:'json',
						success:function(data){
							if(data.retCode == 1){  //根据后台返回的状态
								console.log("短信验证码发送成功");
								var time = 60;
								if (validCode) {
									validCode = false;
									$(this).addClass("msgs1");
									var t = setInterval(function() {
										time--;
										$(".input_bar_two span").html('' + time + ''+'s后重新获取');
											if (time == 0) {
												clearInterval(t);
												$(".input_bar_two span").html("获取验证码");
												$(".input_bar_two span").css("color","red");
												validCode = true;
											    $(this).removeClass("msgs1");
					
											}
									}, 1000)
								}
							}
							else{
								$(".sure").removeClass("hide").text(data.retMsg);
								clearInterval(position);
								return false;
							}
						} 
					});
				});
				
				/*遮罩层消失*/
				$(".mask").click(function(){
					$(".res_pop").addClass("hide");
					$(".mask").addClass("hide");
					location.reload();
				});
					
				
			});
		</script>
	</body>
</html>
