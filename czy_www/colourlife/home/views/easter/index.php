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
		<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/lanternFestival/');?>js/ShareSDK.js"></script>
	    <script language="javascript" type="text/javascript">
	    function showShareMenuClickHandler(c_time)
	    {
	        
	        var u = navigator.userAgent, app = navigator.appVersion;
	        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	        
	         if(isAndroid){
	             try{
	                 var version = jsObject.getAppVersion();
	//                 alert(version);
	             }catch(error){
	                  //alert(version);
	                  if(version !="4.3.6.2"){
	                    alert("请到App--我--检查更新,进行更新");
	                    return false;
	                }
	             }finally {}
	            $sharesdk.open("85f512550716", true);
	        }
	        
	        if(isiOS){
	            try{
	                if(getAppVersion && typeof(getAppVersion) == "function"){
	                     getAppVersion();
	                     var vserion = document.getElementById('vserion').value;
	                    }
	                }catch(e){
	                    
	                }
	           
	            if(vserion){
	                //alert(vserion);
	                $sharesdk.open("62a86581b8f3", true); 
	            }else{  
	                alert("请到App--我--检查更新,进行更新");
	                return false;
	             }  
	            }
	        if(c_time!="0.00"){
	        	 var params = {
		     	            "text" : "按出一秒算你赢！",
		     	            "imageUrl" : "<?php echo F::getStaticsUrl('/home/easter/'); ?>images/logo.png",
		     	            "url":"<?php echo F::getHomeUrl('/Easter/Share/?s=1&uid='.$uid); ?>",
		     	            "title" : "我按出了"+c_time+"秒！你也来挑战吧~ ",
		     	            "titleUrl" : "<?php echo F::getHomeUrl('/Easter/Share/?s=1&uid='.$uid); ?>",
		     	            "description" : "描述",
		     	            "site" : "彩之云",
		     	            "siteUrl" : "<?php echo F::getHomeUrl('/Easter/Share/?s=1&uid='.$uid); ?>",
		     	            "type" : $sharesdk.contentType.WebPage
		     	        };
		     }else{
		    	 var params = {
		     	            "text" : "快来挑战吧！",
		     	            "imageUrl" : "<?php echo F::getStaticsUrl('/home/easter/'); ?>images/logo.png",
		     	            "url":"<?php echo F::getHomeUrl('/Easter/Share/?s=1&uid='.$uid); ?>",
		     	            "title" : "这个游戏太难玩了！能按出一秒算你赢！",
		     	            "titleUrl" : "<?php echo F::getHomeUrl('/Easter/Share/?s=1&uid='.$uid); ?>",
		     	            "description" : "描述",
		     	            "site" : "彩之云",
		     	            "siteUrl" : "<?php echo F::getHomeUrl('/Easter/Share/?s=1&uid='.$uid); ?>",
		     	            "type" : $sharesdk.contentType.WebPage
		     	        };
			 }
	       $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {}); 
	         
	    };
	    </script>
	</head>
	<body>
	<div class="contaner">
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
		<div class="easter">
			<div class="easter_top">
				<p>你有<span class="p1"><?php echo $s_chance;?></span>次砸金蛋机会</p>
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
					<p><span id="num"><?php echo $c_chance;?></span>次</p>
				</div>
			</div>
			<div class="easter_banner" id="touch_button">
				<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/egg.png">
			</div>
			<div class="easter_time">
				<p>0.00s</p>
			</div>
			<div class="easter_share">
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/button_03.png">
				</a>
			</div>
			<!-- <div class="egg_button"></div> -->
		</div>
		<!--活动规则-->
		<div class="rule">
			<a href="<?php echo $this->createUrl('rule');?>"><img src="<?php echo F::getStaticsUrl('/home/easter/'); ?>images/rule-btn.png"/></a>
		</div>
		
	</div>
		<div class="mask hide"></div>
		<!--玩法弹窗开始-->
		<div class="play_the_pop-up hide">
			<a href="javascript:void(0)" class="close play_the_pop-up_close"></a>
		</div>
		<!--玩法弹窗结束-->
		<!--邀请注册开始-->
		<div class="Invitation_to_register hide">
			<a href="javascript:void(0)" class="close Invitation_to_register_close"></a>
			<a href="javascript:void(0)"></a>
		</div>
		<!--邀请注册结束-->
		<!--什么都没有抽到弹窗开始-->
		<div class="no_pop_up hide">
			<a href="javascript:void(0)" class="close no_pop_up_close"></a>
		</div>
		<!--什么都没有抽到弹窗结束-->
		<!--获奖礼品开始01-->
		<div class="award_gift hide">
			<a href="javascript:void(0)" class="close award_gift_close"></a>
			<div class="award_gift_p">
				<p>恭喜您！</p>
				<p>获得充电宝一个</p>
			</div>
		</div>
		<!--获奖礼品结束02-->
		
		<div class="award_gift1 hide">
			<a href="javascript:void(0)" class="close award_gift_close1"></a>
			<div class="award_gift_p1">
				<p>获得充电宝一个</p>
			</div>
		</div>
		
		<input type="hidden" value="<?php echo $validate;?>" class="validate"/>
		<input style="display: none" id="vserion" />
		<script>
			$(document).ready(function(){
				//开始加载
				var start_time;//按住的时刻
				var end_time;//松开的时刻

				//ajax request判断是否第一次打开页面、剩余开奖机会、剩余挑战机会

				var isFirst = "<?php echo $is_show;?>";

				if (isFirst != 1) {

					$(".mask").removeClass("hide");
					$(".play_the_pop-up").removeClass("hide");
				};
				//关闭
				$(".close").click(function(){
					$(".mask").addClass("hide");
					$(".play_the_pop-up").addClass("hide");
					$(".down_pop_up").addClass("hide");
					$(".Invitation_to_register").addClass("hide");
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
					$(".Invitation_to_register").addClass("hide");
					$(".no_pop_up").addClass("hide");
					$(".award_gift").addClass("hide");
					$(".award_gift1").addClass("hide");
				    $(".receive_pop_up").addClass("hide");
				});
				
				//按住蛋计时

				$(".easter").delegate("#touch_button","touchstart", function() {
					event.preventDefault();
					$("#touch_button").addClass('active');
					start_time = new Date().getTime();
					event.preventDefault();
				});
				$(".easter").delegate("#touch_button","touchend", function() {
					event.preventDefault();
					var touchTimes = $("#num").text();//剩余挑战机会
					if(touchTimes>0)//可以竖蛋
					{

						var time=0;
						$("#touch_button").removeClass('active');
						end_time = new Date().getTime();
						var just_time = end_time - start_time;
						just_time = parseFloat(just_time / 1000).toFixed(2);
	                	if((just_time>=0.95)&&(just_time<=1.05))//竖蛋成功
	                	{
	                		time=just_time;
	                		$(".easter_time p").css("color","#31c338");
	                		$(".easter_time p").html(just_time+"s");
	                    	//touchTimes--;
	                    	//$("#num").html(touchTimes);
							
	                		$(".award_gift_p1").find("p").html("恭喜你获得1次砸蛋机会");
							$(".mask").removeClass('hide');
							$(".award_gift1").removeClass('hide');
	                	}
	                	else if(just_time < 0.95)
	                	{
	                		$(".easter_time p").css("color","#f5d61c");
	                		$(".easter_time p").html(just_time+"s");
	                    	//touchTimes--;
	                    	//$("#num").html(touchTimes);
	                	}
	                	else if(just_time > 1.05)
	                	{
	                		$(".easter_time p").css("color","#eb1c1c");
	                		$(".easter_time p").html(just_time+"s");
	                    	//touchTimes--;
	                    	//$("#num").html(touchTimes);
	                	}
	                	else
	                	{
	                		return false;
	                	}
	                    $.ajax({
	                        type: 'POST',
	                        url: '/Easter/Challenge',
	                        data: 'time='+time,
	                        dataType: 'json',
	                        success: function (data) {
	                            if(data.status==1){
	                            	$(".p1").text(data.Schance);
	                            	$("#num").text(data.Cchance);
		                        }
	                        }
	                    });
					}
					else//竖蛋机会已经用完
					{
						$(".mask").removeClass('hide');
						$(".Invitation_to_register").removeClass('hide');
					}

				});
				
				//砸蛋领奖
				$(".award a").click(function(){
					var lotteryTimes = $(".p1").text();//剩余开奖机会
					var touchTimes = $("#num").text();//剩余挑战机会
					if(lotteryTimes==0){
						alert("砸蛋机会已经用完！");
						return false;
					}
					//ajax获取获奖信息
					var validate=$(".validate").val();
                    $.ajax({
                        type: 'POST',
                        url: '/Easter/Draw',
                        data: 'param='+validate,
                        dataType: 'json',
                        success: function (data) {
                            if(data.status==1){
                            	$(".p1").text(data.s_chance);
            					switch(data.prizeID)
            					{
            						case 0://什么都没有
            						{
            							$(".mask").removeClass('hide');
            							$(".no_pop_up").removeClass('hide');
            							break;
            						}
            						case 1://游戏增加机会一次
            						{
            							$(".award_gift_p").find("p:eq(1)").html("游戏机会一次");
            							$(".mask").removeClass('hide');
            							$(".award_gift").removeClass('hide');
            							touchTimes++;
            							$("#num").html(touchTimes);
            							break;
            						}
            						case 2://星辰旅游代金券
            						{
            							$(".award_gift_p").find("p:eq(1)").html("获得邮轮代金券");
            							$(".mask").removeClass('hide');
            							$(".award_gift").removeClass('hide');
            							break;
            						}
            						case 3://0.08饭票
            						{
            							$(".award_gift_p").find("p:eq(1)").html("获得0.08饭票");
            							$(".mask").removeClass('hide');
            							$(".award_gift").removeClass('hide');
            							break;
            						}
            						case 4://0.8饭票
            						{
            							$(".award_gift_p").find("p:eq(1)").html("获得0.8饭票");
            							$(".mask").removeClass('hide');
            							$(".award_gift").removeClass('hide');
            							break;
            						}
            						case 5://8饭票
            						{
            							$(".award_gift_p").find("p:eq(1)").html("获得8饭票");
            							$(".mask").removeClass('hide');
            							$(".award_gift").removeClass('hide');
            							break;
            						}
            						case 6://U盘
            						{
            							$(".award_gift_p").find("p:eq(1)").html("获得U盘一个");
            							$(".mask").removeClass('hide');
            							$(".award_gift").removeClass('hide');
            							break;
            						}
            						case 7://充电宝
            						{
            							$(".award_gift_p").find("p:eq(1)").html("获得充电宝一个");
            							$(".mask").removeClass('hide');
            							$(".award_gift").removeClass('hide');
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

				});
				
				//分享
				$(".easter_share a").click(function(){
					 var c_time=$(".easter_time p").text().split("s")[0];
					showShareMenuClickHandler(c_time);
					setTimeout(shareJs,5000);
                    
				});
				function shareJs(){
					$.ajax({
                        type: 'POST',
                        url: '/Easter/Log',
                        data: 'type=2',
                        dataType: 'json',
                        success: function (data) {
                            if(data.status==1){
                            	$("#num").text(data.chance);
                            }
                        }
                    });
				}
				//邀请注册
				$(".Invitation_to_register>a:eq(1)").click(function(){
					$.ajax({
                        type: 'POST',
                        url: '/Easter/Log',
                        data: 'type=3',
                        dataType: 'json',
                        success: function (data) {
                            
                        }
                    });
					mobileJump("Invite");
					});
				function mobileJump(cmd)
			    {
			        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
			            var _cmd = "<?php echo F::getHomeUrl(); ?>*jumpPrototype*colourlife://proto?type=" + cmd;
			            document.location = _cmd;
			        } else if (/(Android)/i.test(navigator.userAgent)) {
			            var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
			            eval(_cmd);
			        }
			    }

			});
		</script>
	</body>
</html>