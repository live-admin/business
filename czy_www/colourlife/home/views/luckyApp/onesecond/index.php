<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>按住一秒</title>
		<link href="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>css/gedc.css" rel="stylesheet" type="text/css">
		<!--<link href="css/common.css" rel="stylesheet" type="text/css" />-->

		<!--<script src="http://www.fz222.com/weixin/share/share.php" type="text/javascript"></script>-->
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/jweixin-1.0.0.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/weixin.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/jquery.lazyload.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/other.js"></script>
		<script src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/gunDong.js" type="text/javascript" charset="utf-8"></script>
	</head>

	<body>
		<div class="gedc">
			<div class="top_bg">
				<img src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>images/top_bg.png" />
				<!--<p class="title">你能精确的按出<span class="red_txt">1秒</span>钟吗？</p>-->
			</div>
			<!--游戏主页-->
			<div class="content" style="display:;">
				<div id="popover_content">
					<p><span class="red_txt" style="position:relative;top: 20px;font-size: 1.2em;font-weight: bold;">您还有<?php echo $luckyCustCan; ?>次游戏挑战机会 </span></p>
				</div>
				<img src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>images/img_01.png" />
				<div class="hlod_img" id="touch_button">
					<!--<img src="images/hlod.png" />-->
				</div>
				<div class="btn_row clearfix">
					<div id="rule_btn" class="activity_btn">活动规则</div>
					<div id="record_btn" class="activity_btn">挑战记录</div>
				</div>
			</div>

			<div class="top_bg">
				<img src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>images/footer_bg.png" />
				<!--<p class="footer_advice">★注：彩之云在法律规定的范围内具有最终解释权</p>-->
			</div>
		</div>

		<div class="pop_up" style="display: block;opacity: .7;">
			<div class="iphone_pop samsung" style="display: none;">
				<div class="pop_chance"> 
					<p class="hint">对不起</p>
					<div class="select_type">您今天的挑战机会用完咯，明天再来吧！</div>
				</div>
				<!--<div class="know">关闭</div>-->
			</div>
	
		<!--提示按住海星开始游戏-->
		<div class="iphone_pop hold" style="display: block;">
			<p class="pop_title">请按住海星进行游戏!</p>
			<div class="hand_pop"> 
				<img src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>images/hand_down.png" />
			</div>
		</div>
			</div>
		<script type="text/javascript">
            var level_name = 2;

			$(function() {
				var start_time;
				var end_time;
				$("#touch_button").live('touchstart', function() {
					event.preventDefault();
					$("#touch_button").addClass('active');
					start_time = new Date().getTime();
					event.preventDefault();
				});
				$("#touch_button").live('touchend', function() {
					event.preventDefault();
					end_time = new Date().getTime();
					var just_time = end_time - start_time;
					just_time = parseFloat(just_time / 1000);

                    doLucky(just_time);

				});
				//活动规则
				$('#rule_btn').click(function() {
                        window.location.href = '/luckyApp/paintPuzzleRule';
				})
					//挑战记录
				$('#record_btn').click(function() {
                        window.location.href = '/luckyApp/paintPuzzleResult';
				})
					//关闭窗口
				$('.know').click(function() {
					$('.pop_up').hide();
				});


				$('.pop_up').click(function(e) {
					var obj = e.srcElement || e.target;
					if ($(obj).is('.pop_up')) {
						$('.pop_up,.pop_ip>div').hide();
					}
				});
			});


            function doLucky(just_time) {
                if (just_time > 0.95 && just_time < 1.05) {
                    if (just_time == 1) {
                        level_name = 1;
                    }

                    getLuckyData(just_time);
                } else {

                    getBadLuckyData(just_time);
                }
            }

            function getLuckyData(just_time) { //得到数据
                var text = '';
                $.ajax({
                    type: 'POST',
                    url: '/luckyApp/oneSecondDo',
                    data: 'actid=13&prize_level='+level_name,
                    dataType: 'json',
                    async: false,
                    error: function () {
                        text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>谢谢参与</p>';
                        $("#popover_content").html(text);
                    },
                    success: function (data) {
                        if (data.success == 0 && data.data.location == 1) {
                            location.href = data.data.href;
                            return;
                        }
                        if (data.success == 0) {
                            text = '<p>谢谢参与</p><p>'+data.data.msg+'</p>';
                            $("#popover_content").html(text);
                        } else {
                            var getPrize = data.data.result;
                            showPackage(just_time, getPrize);
                        }
                    }
                });
            }

            function getBadLuckyData(just_time) { //得到数据
                $.ajax({
                    type: 'POST',
                    url: '/luckyApp/oneSecondDo',
                    data: 'actid=13&flag=colourlife',
                    dataType: 'json',
                    async: false,
                    error: function () {
                        var text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>谢谢参与</p>';
                        $("#popover_content").html(text);
                    },
                    success: function (data) {
                        if (data.success == 0 && data.data.location == 1) {
                            location.href = data.data.href;
                            return;
                        }

                        if (data.success == 0) {
                            var text = '<p>谢谢参与</p><p>'+data.data.msg+'</p>';
                            $("#popover_content").html(text);
                        } else {
                            var text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>啊哦，跑偏了！再来一次吧。</p><div class="select_btn">查看记录</div>';
                            $("#popover_content").html(text);
                        }
                    }
                });
            }

            //根据结果弹出红包
            function showPackage(just_time, prize) {
                var text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>谢谢参与</p>';
                var prizeid=parseInt(prize.id);
                if (prizeid==124) {//谢谢参与
                    text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>谢谢参与</p>';
                } else if (prizeid==161) {//泰康人寿
                    text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>太牛啦！再接再厉，送你泰康人寿免费意外险一份，快快领取吧！</p><div class="select_btn"><a rel="external" data-ajax="false" href="/luckyApp/taikanglingqu">立即领取</a></div>';
                } else if(prizeid>=142&&prizeid<=144){//1秒红包
                    text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>哇，人中极品啊！恭喜获得'+prize.rednum+'饭票，快到<span class="red_txt_none">我的饭票中</span>查看吧!</p><div class="select_btn"><a href="/luckyApp/paintPuzzleResult">立即查看</a></div>';
                } else if(prizeid>=143&&prizeid<=152) {//小额红包
                    text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>太牛啦！再接再厉，送你' + prize.rednum + '饭票，快到<span class="red_txt_none">我的饭票</span>中查看吧!</p><div class="select_btn"><a href="/luckyApp/paintPuzzleResult">立即查看</a></div>';
                } else if(prizeid>=153&&prizeid<=161){//电子优惠吗
                    text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>太牛啦！送你<span class="">'+ prize.prize_name +'</span>优惠码，好好享受！</p><div class="select_btn"><a href="/luckyApp/paintPuzzleResult">查看立即</a></div>';
                }else{//异常
                    text = '<p><span class="red_txt">' + just_time + '</span>秒</p><p>谢谢参与</p>';
                }

                $("#popover_content").html(text);
            }
		</script>
	</body>

</html>