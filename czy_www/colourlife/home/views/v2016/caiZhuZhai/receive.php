<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>彩住宅抽奖</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="contaner">
			<div class="info">
				<p>请填写相关信息领取奖品</p>
			</div>
			
			<div class="input_bar_one">
				<input type="text" placeholder="请输入您的手机号" class="input_bar_call info_start" id="phone" maxlength="11"/>
				<div class="input_bar_two">
					<input type="text" placeholder="请输入验证码" class="input_bar_code info_start" id="re_code" maxlength="4"/>
					<span>获取验证码</span>
				</div>
				<!--<p class="add_name"></p>-->
				
				<p class="sure hide sure01"></p>
				
				<div class="get_btn_next">
					<a href="javascript:void(0);">确定</a>
				</div>
			</div>
			<div class="info_bg">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>images/lq_bg.jpg"/>
			</div>
		</div>
		
		<!--下载开始-->
		<div class="btm_bg"></div>
	    <div class="share_footer">
	     	<div class="share_footer_box01">
	     		<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>images/czy_logo.png">
	     	</div>
	     	<div class="share_footer_box02">
	     		<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">
	     			立即下载
	     		</a>
	     	</div>
	     	<div class="share_footer_box03">
	     		<a href="javascript:void(0);">
	     			<img src="<?php echo F::getStaticsUrl('/activity/v2016/caiZhuZhai/');?>images/delet.png">
	     		</a>
	     	</div>
	    </div>
	    
	    <!--遮罩层开始-->
		<div class="mask hide"></div>	
		<!--遮罩层结束-->
	    <div class="pop_over hide">
			<div class="pop_over_txt">
				<p>恭喜您</p>
				<p>领取成功，登入彩之云app</p>
				<p>查看更多活动。</p>
			</div>
			<div class="lq_btn_over">
				<a href="http://a.app.qq.com/o/simple.jsp?pkgname=cn.net.cyberway">去看看</a>
			</div>
		</div>
	
	<script type="text/javascript">
		var activtyId = <?php echo $activity_id; ?>;
	
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
					var _phone = $(".input_bar_call").val();
					var code01 = $(".input_bar_code").val();
					var mobile = $("#phone").val();
					if(phone.test(mobile) && code.test(code01)){
						
						$.ajax({
							async:true,
							type:"POST",
							url:"/CaiZhuZhaiActivity/Receive/"+activtyId,
							data:{
								'mobile' : mobile,
								'code' : code01
							},
							dataType:'json',
							success:function(data){
								if(data.retCode == 1){  //根据后台返回的状态
									rest_suc();
								}
								else{
		//							$(".input_bar_three p").removeClass("hide");
									$(".pop_over_txt p:eq(0)").text("温馨提醒");
									$(".pop_over_txt p:eq(1)").text(data.retMsg);
									$(".pop_over_txt p:eq(2)").text("彩之云APP使用。");
									$(".pop_over").removeClass("hide");
									$(".mask").removeClass("hide");
								}
							} 
				     	});
					}else if(phone.test(mobile) && code.test(code01)=="" ) {
					     $(".sure").removeClass("hide").text("验证码输入不正确！");	
					}
					
					
					
					function rest_suc(){
						var code01 = $(".input_bar_code").val();
						if(code.test(code01)){
							$(".pop_over").removeClass("hide");
							$(".mask").removeClass("hide");
							$(".get_btn_sure a").attr("disablae")
						}
					}
						
						
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
					var isInit = button_class();
						if (isInit) {
//							$(".sure").removeClass("hide").text("验证码已经过时！");
//							return false;
					}
						var mobile = $("#phone").val();
						$.ajax({
						async:true,
						type:"POST",
						url:"/CaiZhuZhaiActivity/SendCode",
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
										$(".input_bar_two span").css("color","#9d9b99");
										$(".input_bar_two span").html('' + time + ''+'s后重新获取');
											if (time == 0) {
												clearInterval(t);
												$(".input_bar_two span").html("获取验证码");
												$(".input_bar_two span").css("color","#ff4040");
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
//					$(".sure").removeClass("hide").text("请输入正确的验证码！");
					return false;
				}
			
			}
			
			//手机校验
			function phone_verificate(){
				if (phone.test($('#phone').val())) {
//						$(".input_bar_one p").addClass("hide");
					return true;
				} 
				else{
					$(".sure").removeClass("hide").text("请输入正确的手机号码！");
					return false;
				}
			}	
			
			
//			
		
				
			$(".mask").click(function(){
				$(".mask").addClass("hide");
				$(".pop_over").addClass("hide");
			});
			
			$(".share_footer_box03").click(function(){
				$(".btm_bg,.share_footer").hide();
			});
			
				if(GetQueryString("parent") !=null){
					$(".add_name").text("万成100家园");
				}else{
					$(".add_name").text("懿合苑");
				}
					
				function GetQueryString(name) {
					var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
					var r = window.location.search.substr(1).match(reg);
					if(r != null) 
						return unescape(r[2]);
					return null;
				}
		
			
		});
	</script>
		
	</body>
</html>
