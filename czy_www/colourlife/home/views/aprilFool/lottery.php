<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>抽奖</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>js/jquery-1.11.3.js"></script>
	    <script src="<?php echo F::getStaticsUrl('/common/js/invite_reg.js'); ?>" type="text/javascript" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>css/normalize.css">
	    	<style>
	    		body{
	    			background: #B65723;
	    		}
	    	</style>
	</head>
	<body>
		<div class="conter">
			<div class="header">
				<div class="header_center">
					<p class="item01">
						<span id="mask1" class="itemMask">陶瓷杯</span>
					</p>
					<p class="item02">
						<span id="mask2" class="itemMask">零食</span>
					</p>
					<p class="item03">
						<span id="mask3" class="itemMask">U盘</span>
					</p>
					<p class="item06">
						<span id="mask4" class="itemMask" style="line-height:0.4rem; padding-top:0.2rem;">愚人节快乐</span>
					</p>
					<p class="item09">
						<span id="mask5" class="itemMask">陶瓷杯</span>
					</p>
					<p class="item08">
						<span id="mask6" class="itemMask">零食</span>
					</p>
					<p class="item07">
						<span id="mask7" class="itemMask" style="line-height:0.4rem; padding-top:0.2rem;">愚人节快乐</span>
					</p>
					<p class="item04">
						<span id="mask8" class="itemMask">U盘</span>
					</p>
					<p class="item05">
						<span class="item05_p">
						<?php if ($num>0){?>
							<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/winning_bg02.png" />
						<?php }else{?>
							<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/no_prize.png" />
						<?php }?>
						</span>
					</p>
				</div>
			</div>
			<div class="banner">
				<p>你有<span class="banner_p"><?php echo $num;?></span>次抽奖机会，分享留言获得更多次数</p>
			</div>
			<div class="footer">
				<div class="footer_head">
					<marquee direction="up" scrollamount="5" behavior="scroll" id="winner" style="height: 1.9rem">
					
					</marquee>
				</div>
			</div>
			<!--活动规则-->
			<div class="rule">
				<a href="<?php echo $this->createUrl('rule');?>">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/rule_button.png">
				</a>
			</div>
			
			<!--邀请注册-->
			<div class="reg">
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/reg.png">
				</a>
				<input type="hidden" value="<?php echo F::getHomeUrl(); ?>" class="h_url"/>
			</div>
		</div>
		<div class="mask hide"></div>
		<!--分享愚人节开始-->
		<div class="share hide">
			<p>愚人节快乐！</p>
		</div>
		<!--分享愚人节结束-->
		<!--马克杯一元购码开始-->
		<div class="mark_cup hide">
			<p>恭喜您！</p>
			<p>抽中马克杯一元购码</p>
			<a href="javascript:void(0)">立即兑现</a>
		</div>
		<!--马克杯一元购码结束-->
		<!--零食一元购码开始-->
		<div class="snacks hide">
			<p>恭喜您！</p>
			<p>抽中零食一元购码</p>
			<a href="javascript:void(0)">立即兑现</a>
		</div>
		<!--零食一元购码结束-->
		<!--U盘开始-->
		<div class="u_disk hide">
			<p>恭喜您！</p>
			<p>抽中彩生活限量U盘</p>
			<div class="input_txt">
				<input type="text"  placeholder="请输入手机号码"/>
			    <p>活动结束后奖品统一发放</p>
			</div>
			
			<a href="javascript:void(0)">完成</a>
		</div>
		<input type="hidden" value="" class="param"/>
		<!--U盘结束-->
		<script type="text/javascript">
		$(document).ready(function($) {
			//获取中奖者名单(json数据格式)
			var winners = <?php echo $prize;?>;
			var disabledImg = (new Image()).src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/no_prize.png";
			
			var index = 0;//抽奖参数(不要乱改)
			var position;//抽奖参数(不要乱改)
			var maskIds =[];//抽奖参数(不要乱改)
			var result;//中奖结果
			var pnum;//抽奖次数
			var speed = 200;
			var minSpeed = 200;
			var maxSpeed = 20;
			var flag = false;
			var count = 0;

			$(".header_center>p>span").each(function() {
				if($(this).attr("id")){

					maskIds.push($(this).attr("id"));
				}
			}); 

			initPage();
			
			//抽奖按钮
			$(".item05").click(function(event) {

				if(!$(this).hasClass("drawing")){
					var drawTimes = $(".banner_p").text();//可抽奖次数
					if(drawTimes<1){
						alert("没有抽奖机会！");
						return false;
					}
					$.ajax({
	                    type: 'POST',
	                    url: '/AprilFool/Draw',
	                    data: 'mobile=0',
	                    dataType: 'json',
	                    success: function (data) {
	                        if(data.status==1){
	                            var res=data.param;
	                        	result=res.resultID;
	                        	pnum=data.num;
	                        	if(res.rid==3){
									$(".snacks a").attr("href",res.url);
	                            }else if(res.rid==4){
									$(".mark_cup a").attr("href",res.url);
	                            }else if(res.rid==2){
	                                $(".param").val(res.param);
	                            }
	                        }else{
	                        	result=4;
	                        }
	                    }
	                }); 
					index = 0
					count = 0;
					flag = false;
					speed = 200;
					position = self.setInterval(drawAnim,speed);
					$(this).addClass("drawing");
					$(this).find("img").attr("src","<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/no_prize.png");
					
				}
				else{
				//正在抽奖中...点击无反应用户体验最好(不建议弹窗)
					return false;
					
				}
			});
			//抽奖动画
			function drawAnim(){
				
				var len =  maskIds.length;
				var id1;
				var id2;
				if(count == 0){

					if(!flag){
						if(speed > maxSpeed){
							window.clearInterval(position);
							speed-=12;
							position = self.setInterval(drawAnim,speed);
						}
						else{
							flag = true;
						}
					}
					else{
						if(speed < minSpeed+(12*(result-1))){
							window.clearInterval(position);
							speed+=12;
							position = self.setInterval(drawAnim,speed);
												
						}
						else{

							count = 1;
							flag = false;
						}
					}
				}
				else{

					window.clearInterval(position);
					//根据中奖结果弹窗
					setTimeout(showResult,500);
					
				}
				if(index == 0)
					{
						id1 = maskIds[len-1];
						id2 = maskIds[index];
					}
					else
					{
						id1 = maskIds[index-1];
						id2 = maskIds[index];
					}

					if(!$("#"+id1).hasClass("hide")){

						$("#"+id2).addClass("hide");	
					}
					else{
						$("#"+id1).removeClass("hide");
						$("#"+id2).addClass("hide");
					}
					

					if(index<7){
						index++;
					}else{
						index = 0;
					}
			}
			//显示结果
			function showResult(){
				switch(result)
				{
					case 1:
					{
						$(".mark_cup").removeClass("hide");
						$(".mask").removeClass("hide");
						$(".header_center>p>span").removeClass("hide");
						break;
					}
					case 2:
					{
						$(".snacks").removeClass("hide");
						$(".mask").removeClass("hide");
						$(".header_center>p>span").removeClass("hide");
						break;
					}
					case 3:
					{
						$(".u_disk").removeClass("hide");
						$(".mask").removeClass("hide");
						$(".header_center>p>span").removeClass("hide");
						break;
					}
					case 4:
					{
						$(".share").removeClass("hide");
						$(".mask").removeClass("hide");
						$(".header_center>p>span").removeClass("hide");
						break;
					}
					case 5:
					{
						$(".mark_cup").removeClass("hide");
						$(".mask").removeClass("hide");
						$(".header_center>p>span").removeClass("hide");
						break;
					}
					case 6:
					{
						$(".snacks").removeClass("hide");
						$(".mask").removeClass("hide");
						$(".header_center>p>span").removeClass("hide");
						break;
					}
					case 7:
					{
						$(".share").removeClass("hide");
						$(".mask").removeClass("hide");
						$(".header_center>p>span").removeClass("hide");
						break;
					}
					case 8:
					{
						$(".u_disk").removeClass("hide");
						$(".mask").removeClass("hide");
						$(".header_center>p>span").removeClass("hide");
						break;
					}
					default:
					{
						$(".share").removeClass("hide");
						$(".mask").removeClass("hide");
						$(".header_center>p>span").removeClass("hide");
						break;
					}
				}
				if(pnum>0){
					$(".item05").removeClass("drawing");
					$(".item05").find("img").attr("src","<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/winning_bg02.png"); 
				}
				$(".banner_p").text(pnum);
			}
			
			
			//初始化页面
			function initPage(){
				//跑马灯初始化
				$("#winner").children().remove();
				$.each(winners,function(index, el) {
					$("#winner").append('<p>*恭喜'+el.winnerName+'抽中了 <span class="prize">'+el.prize+'</span></p>');
				});
			}
			//关闭弹窗
			$(".mask").click(function(){
				$(".mark_cup").addClass("hide");
				$(".snacks").addClass("hide");
				$(".u_disk").addClass("hide");
				$(".share").addClass("hide");
				$(this).addClass("hide");
				location.reload();
			});
			//U盘完成按钮
			$(".u_disk>a").click(function(){
				var teleNum = $(".u_disk").find("input").val();
				var param=$(".param").val();
				if(!numberCheck(teleNum)){
					return false;
				}
				$(".u_disk").addClass("hide");
				$(".mask").addClass("hide");
				$(".u_disk").find("input").val("");
				$(".u_disk").find(".input_txt>p").removeClass("warning");
				$(".u_disk").find(".input_txt>p").text("活动结束后奖品统一发放");
				$.ajax({
                    type: 'POST',
                    url: '/AprilFool/Complete',
                    data: 'mobile='+teleNum+'&param='+param,
                    dataType: 'json',
                    success: function (data) {
                    	alert(data.msg);
                    	window.location.href = '/AprilFool/lottery';
                    }
                });
			});
			//分享赢取机会
			$(".share a").click(function(){
				showShareMenuClickHandler();
				setTimeout(shareJs,5000);
			});
			function shareJs(){
				$.ajax({
                    type: 'POST',
                    url: '/AprilFool/Log',
                    data: 'type=5',
                    dataType: 'json',
                    success: function (data) {
                        if(data.status==1){
                        	$(".banner_p").text(data.num);
                        }
                    }
                });
			}
			//号码校验
			function numberCheck(temp){
	        	var a=/^[1]{1}[0-9]{10}$/;

	        	if(!a.test(temp))
	            {
	            	$(".u_disk").find(".input_txt>p").addClass("warning");
	                $(".u_disk").find(".input_txt>p").text("请输入正确的手机号码格式");
	                return false;
	            }

	            return true;
	        }
		});
		</script>
	</body>
</html>