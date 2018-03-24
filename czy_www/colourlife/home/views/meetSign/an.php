<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>饭票礼包</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	  	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/meetSign/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="content">
			<div class="info">
				<p>欢迎莅临</p>
				<p>智慧社区生态圈的发展路径论坛</p>
				<p>输入手机号领取</p>
			</div>
			
			<div class="input_bar_one">
				<div class="add_div">
					<input type="number" placeholder="请输入你的手机号" class="input_bar_call info_start" id="phone"/>
				</div>
				<div class="input_bar_two">
					<input type="number" placeholder="请输入短信验证码" class="input_bar_code info_start" id="re_code"/>
					<span>获取验证码</span>
				</div>
				<p class="sure hide">手机号输入有误</p>
				<div class="get_btn_next">
					<a href="javascript:void(0);">确定</a>
				</div>
			</div>
		</div>
		
		<div class="mask hide"></div>
		<div class="content_pop hide">
			<div class="content_bg">
				<div class="content_txt">
					<p>恭喜领到10饭票</p>
					<p>已为您注册彩之云</p>
					<p>请留意短信接收密码</p>
					<p>点击 我的→饭票即可查看</p>
				</div>
				<div class="content_btn">
					<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">下载彩之云</a>
				</div>
			</div>
			<div class="dele_btn">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/meetSign/');?>images/dele.png"/>
			</div>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){
			var validCode = true;
			var code = /^[0-9]{4}$/;
			var _code=1234;
			var position = null;
			var phone = /^1[3|4|5|7|8]\d{9}$/;
			
			
			$(".input_bar_call").change(function(){
				$(this).removeClass("info_start");
				clearInterval(position);
				position = self.setInterval(button_class,50);
				$(".sure").addClass("hide");
			});
			
			//点击确定
			$(".get_btn_next").click(function(){
				
				if ($(this).hasClass("activity")) {
					return false;
				}
					var _phone = $(".input_bar_call").val();
					var code01 = $(".input_bar_code").val();
					var mobile = $("#phone").val();
					if(phone.test(mobile) && code.test(code01)){
						
						$.ajax({
							async:true,
							type:"POST",
							url:"/MeetSign/SendMoney",
							data:{
								'mobile' : mobile,
								'code' : code01,
								'type' : '1'
							},
							dataType:'json',
							success:function(data){
								if(data.status == 1){  //老用户
									$(".get_btn_next").removeClass("activity");
									var code01 = $(".input_bar_code").val();
									if(code.test(code01)){
										$(".content_txt p:eq(0)").text(data.param);
										$(".content_txt p:eq(1)").css({"font-size":"0.3rem","color":"#333b46","padding-bottom":"0.6rem"});
										$(".content_txt p:eq(1)").removeClass("hide").text("使用该手机号登陆彩之云");
										$(".content_txt p:eq(2)").addClass("hide");
										$(".content_txt p:eq(3)").removeClass("hide").text("点击 我的→饭票即可查看");
										$(".content_pop").removeClass("hide");
										$(".mask").removeClass("hide");
									}
								}
							 	else if(data.status == 2){//新用户
							 		
							 		$(".get_btn_next").removeClass("activity");
									var code01 = $(".input_bar_code").val();
									if(code.test(code01)){
										$(".content_txt p:eq(2)").removeClass("hide");
										$(".content_txt p:eq(0)").text(data.param);
										$(".content_txt p:eq(1)").css({"font-size":"0.28rem","color":"#7c868d","padding-bottom":"0rem"});
										$(".content_txt p:eq(1)").removeClass("hide").text("已为您注册彩之云，");
										$(".content_txt p:eq(3)").removeClass("hide").text("点击 我的→饭票即可查看");
										$(".content_pop").removeClass("hide");
										$(".mask").removeClass("hide");
									}
								}
								else{
									$(".get_btn_next").removeClass("activity");
									$(".content_txt p:gt(0)").addClass("hide");
									$(".content_txt p:first-child").css("font-size","0.34rem");
									$(".content_txt p:eq(0)").text(data.param);
									$(".content_pop").removeClass("hide");
									$(".mask").removeClass("hide");
								}
							} 
				     	});
				     	
				     	
					}else if(phone.test(mobile) && code.test(code01)=="" ) {
					     $(".sure").removeClass("hide").text("验证码输入不正确！");	
					}
						$(".get_btn_next").addClass("activity");
					});
					//手机校验
				function phone(temp){
		      		var a=/^1[3|4|5|7|8]\d{9}$/;
			        	if(!a.test(temp))
			            {
			            	$(".sure").removeClass("hide").text("手机不符合规则，请重新输入！");
			                return false;
			            }
			            return true;
		        }
				
				function cd(tp){
					var b=/^[0-9]{4}$/;
						if (!b.test(tp))  
						{
			            	$(".sure").removeClass("hide").text("验证码不符合规则，请重新输入！");
			                return false;
			            }
			            $(".sure").addClass("hide");
			            return true;
				}
			
			//验证码的短信时间
			$(".input_bar_two span").click(function() {
				if(!validCode){
						return false; 
					}
					clearInterval(position);
					position = self.setInterval(button_class,50);
					if($("#phone").hasClass("info_start")||!phone_verificate()){
							return false;
					}
//					var isInit = button_class();
//						if (isInit) {
//							$(".sure").removeClass("hide").text("请输入正确的手机号码！");
//							return false;
//					}
						var mobile = $("#phone").val();
						$.ajax({
						async:true,
						type:"POST",
						url:"/MeetSign/SendCode",
						data:{'mobile':mobile,
							  'type':'1'	
						},
						dataType:'json',
						success:function(data){
							if(data.status == 1){  //根据后台返回的状态
								console.log("短信验证码发送成功");
								var time = 100;
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
												$(".input_bar_two span").css("color","#27a2f0");
												validCode = true;
											    $(this).removeClass("msgs1");
					
											}
									}, 1000)
								}
							}
							else if(data.status == 3){
								$(".content_txt p:gt(0)").addClass("hide");
								$(".content_txt p:first-child").css("font-size","0.34rem");
								$(".content_txt p:eq(0)").text(data.param);
								$(".content_pop").removeClass("hide");
								$(".mask").removeClass("hide");
							}
							else if(data.status == 4){
								$(".content_txt p:gt(0)").addClass("hide");
								$(".content_txt p:first-child").css("font-size","0.34rem");
								$(".content_txt p:eq(0)").text(data.param);
								$(".content_pop").removeClass("hide");
								$(".mask").removeClass("hide");
							}
							else{
								$(".sure").removeClass("hide").text(data.param);
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
			
			//验证码
			function  psw_txt(){
				var duanxin = $(".input_bar_code").val();
				if (code.test(duanxin)) {
//						$(".input_bar_one p").addClass("hide");
//						$(".input_bar_code").addClass("start");
					$(".sure").addClass("hide");
					return true;
				} 
				else{
					$(".sure").removeClass("hide").text("请输入正确的验证码！");
					return false;
				}
			
			}
			
			//手机校验
			function phone_verificate(){
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
			
			$(".dele_btn>img").click(function(){
				$(".content_pop").addClass("hide");
				$(".mask").addClass("hide");
			});
			
		});
		</script>
		
		
	</body>
</html>
