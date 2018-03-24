<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>立蛋达人</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta content="telephone=no" name="format-detection">
	    <script src="<?php echo F::getStaticsUrl('/home/easter/'); ?>js/ReFontsize.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/easter/'); ?>js/jquery-1.11.3.js" ></script>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/easter/'); ?>css/layout.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/easter/'); ?>css/normalize.css">
		<style>
			@font-face {
				font-family:fontstyle1;
				src: url('<?php echo F::getStaticsUrl('/home/easter/'); ?>fonts/fontstyle1.ttf');
			}
			@font-face {
				font-family:fontstyle2;
				src: url('<?php echo F::getStaticsUrl('/home/easter/'); ?>fonts/fontstyle2.ttf');
			}
			@font-face {
				font-family:fontstyle3;
				src: url('<?php echo F::getStaticsUrl('/home/easter/'); ?>fonts/fontstyle3.ttf');
			}
		</style>
	</head>
	<body>
		<div class="bg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_01.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_02.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_03.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_04.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_05.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_06.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_07.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_08.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_09.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/bg_10.jpg">
		</div>
		<!--活动规则-->
		<div class="rule">
			<a href="<?php echo $this->createUrl('rule',array('s'=>1));?>"><img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/rule-btn.png"/></a>
		</div>
		<div class="easter">
			<div class="easter_top">
				<p>你有<span class="p1">0</span>次砸金蛋机会</p>
			</div>
			<div class="award">
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/button_01.png">
				</a>
			</div>
			<div class="easter_num">
				<div class="easter_num_box1a">
					<p>剩余挑战机会</p>
				</div>
				<div class="easter_num_box2a">
					<p><span id="num">5</span>次</p>
				</div>
			</div>
			<div class="easter_banner" id="touch_button">
				<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/egg.png">
			</div>
			<div class="easter_time">
				<p>0.00s</p>
			</div>
			<!-- <div class="easter_share">
				<a href="javascript:void(0)">
					<img src="images/button_03.png">
				</a>
			</div> -->
			<div class="egg_button"></div>
		</div>
		<div class="mask hide"></div>
		<!--玩法弹窗开始-->
		<div class="play_the_pop-up hide">
			<a href="javascript:void(0)" class="close"></a>
		</div>
		<!--玩法弹窗结束-->
		<!--下载弹窗开始-->
		<div class="down_pop_up hide">
			<a href="javascript:void(0)" class="close down_pop_up_close"></a>
		    <a href="javascript:void(0)"></a>
		</div>
		<!--下载弹窗结束-->
		<!--邀请注册开始-->
		<div class="Invitation_to_register_czy hide">
			<a href="javascript:void(0)" class="close Invitation_to_register_close"></a>
			<a href="http://dwz.cn/8YPIv"></a>
		</div>
		<!--邀请注册结束-->
		<!--什么都没有抽到弹窗开始-->
		<div class="no_pop_up hide">
			<a href="javascript:void(0)" class="close no_pop_up_close"></a>
		</div>
		<!--什么都没有抽到弹窗结束-->
		
	<!--领取礼品输入手机号开始-->
		
		<div class="award_gift1 hide">
			<a href="javascript:void(0)" class="close award_gift_close1"></a>
			<div class="award_gift_p1">
				<p>获得充电宝一个</p>
			</div>
		</div>

		<!--u盘开始-->
		<div class="receive_pop_up hide">
			<a href="javascript:void(0)" class="close receive_pop_up_close"></a>
			<div class="receive_pop_up_p">
				<p>恭喜您获得U盘一个！</p>
			</div>
			<input type="text" class="input"  placeholder="输入手机号码领取" style="color: #AE0909;letter-spacing: 0px;"/>
			<a href="javascript:void(0)" class="receive_button"></a>
		</div>
		<!--u盘结束-->
	    
	    <!--下载彩之云app开始-->
		<div class="czy_app hide">
			<p>你已经获得8元饭票</p>
			<a href="javascript:void(0)"></a>
		</div>
	    <!--下载彩之云app结束-->
	    <input type="hidden" value="<?php echo $validate;?>" class="validate"/>
	    <input type="hidden" value="<?php echo $uid;?>" class="uid"/>
	    <input  type="hidden" value="" class="rid"/>

	<!--领取礼品输入手机号结束-->
		<script>
			$(document).ready(function(){
				//开始加载
				$(".mask").removeClass("hide");
				$(".play_the_pop-up").removeClass("hide");
				//关闭
				$(".close").click(function(){
					$(".mask").addClass("hide");
					$(".play_the_pop-up").addClass("hide");
					$(".down_pop_up").addClass("hide");
					$(".Invitation_to_register_czy").addClass("hide");
					$(".no_pop_up").addClass("hide");
					$(".award_gift").addClass("hide");
					$(".award_gift1").addClass("hide");
					$(".receive_pop_up").addClass("hide");
				});
				//mask关闭
				$(".mask").click(function(){
					$(".mask").addClass("hide");
					$(".play_the_pop-up").addClass("hide");
					$(".down_pop_up").addClass("hide");
					$(".Invitation_to_register_czy").addClass("hide");
					$(".no_pop_up").addClass("hide");
					$(".award_gift").addClass("hide");
					$(".award_gift1").addClass("hide");
				    $(".receive_pop_up").addClass("hide");
				});
				
				//按住蛋计时

				$(".easter").delegate(".egg_button","touchstart", function() {
					event.preventDefault();
					$("#touch_button").addClass('active');
					start_time = new Date().getTime();
					event.preventDefault();
				});
				$(".easter").delegate(".egg_button","touchend", function() {
					event.preventDefault();
					var touchTimes=$("#num").text();
					var lotteryTimes=$(".p1").text();
					if((touchTimes>0)&&(touchTimes<6))//可以竖蛋
					{

						$("#touch_button").removeClass('active');
						end_time = new Date().getTime();
						var just_time = end_time - start_time;
						just_time = parseFloat(just_time / 1000).toFixed(2);
	                   
	                	if((just_time>=0.95)&&(just_time<=1.05))//竖蛋成功
	                	{
	                		$(".easter_time p").css("color","#31c338");
	                		$(".easter_time p").html(just_time+"s");
	                    	touchTimes--;
	                    	$("#num").html(touchTimes);
	                    	lotteryTimes++;
	                    	$(".p1").html(lotteryTimes);

	                    	$(".award_gift_p1").find("p:eq(0)").html("恭喜你获得1次砸蛋机会");
							$(".mask").removeClass('hide');
							$(".award_gift1").removeClass('hide');
	                    	
	                	}
	                	else if(just_time < 0.95)
	                	{
	                		$(".easter_time p").css("color","#f5d61c");
	                		$(".easter_time p").html(just_time+"s");
	                    	touchTimes--;
	                    	$("#num").html(touchTimes);
	                	}
	                	else if(just_time > 1.05)
	                	{
	                		$(".easter_time p").css("color","#eb1c1c");
	                		$(".easter_time p").html(just_time+"s");
	                    	touchTimes--;
	                    	$("#num").html(touchTimes);
	                	}
	                	else
	                	{
	                		return false;
	                	}
					}
					else//今日竖蛋机会已经用完
					{
						$(".mask").removeClass('hide');
						$(".Invitation_to_register_czy").removeClass('hide');
					}

				});
				
				//砸蛋领奖
				$(".award a").click(function(){
					var lotteryTimes=$(".p1").text();
					var touchTimes=$("#num").text();
					if(lotteryTimes<=0){
						alert("砸蛋机会已用完！");
						return false;
					}
					var validate=$(".validate").val();
                    $.ajax({
                        type: 'POST',
                        url: '/Easter/ShareDraw',
                        data: 's=1&validate='+validate,
                        dataType: 'json',
                        success: function (data) {
                            if(data.status==1){
                                $(".rid").val(data.rid);
            					switch(data.rid)//(ajax向服务器post)
            					{
            						case 1://什么都没有
            						{
            							$(".mask").removeClass('hide');
            							$(".no_pop_up").removeClass('hide');
            							break;
            						}
            						case 2://游戏增加机会一次
            						{
            							$(".award_gift_p1").find("p:eq(0)").html("恭喜您游戏机会一次");
            							$(".mask").removeClass('hide');
            							$(".award_gift1").removeClass('hide');
            							touchTimes++;
            							$("#num").html(touchTimes);
            							break;
            						}
            						case 3://星辰旅游代金券
            						{
            							$(".receive_pop_up_p").find("p").html("恭喜您获得邮轮代金券");
            							$(".mask").removeClass('hide');
            							$(".receive_pop_up>input").val("");
            							$(".receive_pop_up").removeClass('hide');
            							break;
            						}
            						case 4://0.08饭票
            						{
            							$(".receive_pop_up_p").find("p").html("恭喜您获得0.08饭票");
            							$(".mask").removeClass('hide');
            							$(".receive_pop_up>input").val("");
            							$(".receive_pop_up").removeClass('hide');
            							break;
            						}
            						case 5://0.8饭票
            						{
            							$(".receive_pop_up_p").find("p").html("恭喜您获得0.8饭票");
            							$(".mask").removeClass('hide');
            							$(".receive_pop_up>input").val("");
            							$(".receive_pop_up").removeClass('hide');
            							break;
            						}
            						case 6://8饭票
            						{
            							$(".receive_pop_up_p").find("p").html("恭喜您获得8饭票");
            							$(".mask").removeClass('hide');
            							$(".receive_pop_up>input").val("");
            							$(".receive_pop_up").removeClass('hide');
            							break;
            						}
            						case 7://U盘
            						{
            							$(".receive_pop_up_p").find("p").html("恭喜您获得U盘一个");
            							$(".mask").removeClass('hide');
            							$(".receive_pop_up>input").val("");
            							$(".receive_pop_up").removeClass('hide');
            							break;
            						}
            						case 8://充电宝
            						{
            							$(".receive_pop_up_p").find("p").html("恭喜您获得充电宝一个");
            							$(".mask").removeClass('hide');
            							$(".receive_pop_up>input").val("");
            							$(".receive_pop_up").removeClass('hide');
            							break;
            						}
            						default:
            						{
            							return false;
            						}
            					}
	                        }
                        }
                    });
                    lotteryTimes--;
                    $(".p1").text(lotteryTimes);
				});

				//领取
				$(".receive_button").click(function(){
					var telephoneNum = $(this).parents(".receive_pop_up").find("input").val();
					var uid=$(".uid").val();
					var rid=$(".rid").val();

					if(numberCheck(telephoneNum))
					{
	                    $.ajax({
	                        type: 'POST',
	                        url: '/Easter/ShareReceive',
	                        data: 's=1&mobile='+telephoneNum+'&uid='+uid+'&rid='+rid,
	                        dataType: 'json',
	                        success: function (data) {
	                            if(data.status==2){
	                            	alert("您已领取过奖励，想领取更多奖励请下载彩之云参与游戏。");
	                            	$(".receive_pop_up").addClass("hide");
									$(".down_pop_up").removeClass("hide");
		                        }else if(data.status==1){
		                        	alert(data.msg);
		                        	$(".receive_pop_up").addClass("hide");
									$(".down_pop_up").removeClass("hide");
			                    }else{
									alert(data.msg);
			                    }
	                        }
	                    });
					}
				});
				//下载按钮
				$(".down_pop_up").find("a:eq(1)").click(function(){
					window.location.href = "http://dwz.cn/8YPIv";
				});
				function numberCheck(temp){
		        	var a=/^[1]{1}[0-9]{10}$/;

		        	if(!a.test(temp))
		            {
		                alert("手机号输入有误");
		                return false;
		            }

		            return true;
		        }

			});
		</script>
	</body>
</html>