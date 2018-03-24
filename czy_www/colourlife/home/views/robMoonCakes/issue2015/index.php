<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>抽奖首页</title>

		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>css/zongzi.css" rel="stylesheet">
		<script src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>js/jquery.min.js"></script>
		<script src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>js/jquery.CountDown.js"></script>
		<script src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>js/gunDong.js"></script>
		<script src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>js/gunDong1.js"></script>
	</head>

	<body>
		<div class="zongzi">
			<div class="head"><img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/head.png" class="lotteryimg" /></div>
			<div class="zongzi_content">
				<div class="timebox">
					<!--本场时间节点 start-->
					<div class="tm_node">
						<input type="hidden" value="20" />
						<p class="current_tm clearfix"><span class="ten time_after" value=10>10:00</span><span class="ten time_after time_before_left" value=16>16:00</span><span class="ten time_before_left " value=20>20:00</span></p>
						<p class="mod_count clearfix"><span class="floatleft">本场共有<?php if($flag ==1){ echo $remaining;}else{ echo "0"; } ?>份月饼</span><span class="nexttime floatright">下一场:<i style="font-style:normal;"><?php echo (date('H') < 10 || date('H') > 20) ? '10:00' : ((date('H') < 14) ? '14:00' : '20:00');  ?></i>:00准时开抢</span></p>
					</div>
					<!--本场时间节点 end-->
					<!--倒计时容器 start-->
					<div class="count_down_box">
						<img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/time.png" class="tm_png" />
						<label>即将开抢倒计时：</label>
						<!--span class="count_down price">00时00分00秒</span-->
						<span class="count_down price">活动已结束</span>
					</div>
					<!--倒计时容器 end-->
				</div>
				<!--焦点图 start-->
				<div class="focus_img"><img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/focus.png" class="lotteryimg" style="display: block;width: 75%;" />
					<a class="go"></a>
				</div>
				<!--焦点图 end-->
				<div class="active_btn clearfix">
					<a href="/robMoonCakes/myLottery" class="floatleft">活动战绩</a>
					<a href="/robMoonCakes/rule" class="floatright">活动规则</a>
				</div>

				<div class="lotteryList" style="position:relative; display:block;">
					<dl id="ticker">
                        <?php foreach($newInfo as $_v){ ?>
                            <dt><?php echo $_v; ?></dt>
                        <?php } ?>
					</dl>
				</div>

				<div class="tow_row clearfix">
					<div class="tow_row_left">
						<img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/img_01.png" />
					</div>
					<div class="tow_row_right">
						<p>1.活动时间：8月20号—8月31号</p>
						<p>2.每天新注册的前1000名用户，按注册时间排序，个位数逢8的用户获赠彩云追月的1元换购码</p>
						<p>3.符合要求的用户注册后次日内赠送到位</p>
						<p>4.换购有效期：8月20号—9月2号</p>
					</div>

				</div>

				<div class="lotteryList" style="position:relative; display:block;">
					<dl id="ticker1">
                        <dt>恭喜金陵天成小区业主陈**注册获得了一份彩生活月饼！</dt>
                        <dt>恭喜汇港名苑小区业主赖**注册获得了一份彩生活月饼！</dt>
                        <dt>恭喜高行绿洲四期业主沈**注册获得了一份彩生活月饼！</dt>
                        <dt>恭喜东洲花园业主李**注册获得了一份彩生活月饼！</dt>
                        <dt>恭喜东洲花园业主成**注册获得了一份彩生活月饼！</dt>
                        <dt>恭喜135****5783注册获得了一份彩生活月饼！</dt>
					</dl>
				</div>

				<div class="head"><img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/ZQ_15.png" class="lotteryimg" /></div>

				<div class="preferential_prompt">
					<p>单笔订单每<span class="red_txt">买满100返5元饭票&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
					<p style="text-indent: -2em;"><span class="red_txt">满200返10元饭票，</span>依此类推</p>
				</div>
				<!--粽子产品 start-->
				<div class="product_group clearfix">
					<div class="floatleft">
							<img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/ZQ_03.png" />
							<p>（图片仅供参考，请以实物为准）</p>
					</div>
					<div class="floatright pad_updown">
							<p style="font-size: 1.3em;font-weight: bold;color: #ff001a;">花好月圆礼盒</p>
							<p>(8枚)</p>
							<p>中秋佳礼&nbsp;浓情月圆</p>
							<p style="font-size: 1.3em;font-weight: bold;color: #ff001a;">¥188</p>
							<!--div class="nqg"><a href="<?php echo $url; ?>&pid=19679"><span>浓情购</span></a></div-->
							<div class="nqg"><a href="<?php echo $url; ?>&pid=19679"><span>浓情购</span></a></div>
					</div>
				</div>
				
				<div class="product_group clearfix">
					<div class="floatleft pad_updown">
						<p style="font-size: 1.3em;font-weight: bold;color: #ff001a;">彩云追月</p>
							<p>(2枚)</p>
							<p>经典珍味&nbsp;相约中秋</p>
							<p style="font-size: 1.3em;font-weight: bold;color: #ff001a;">¥18</p>
							<div class="nqg"><a href="<?php echo $url; ?>&pid=19568"><span>浓情购</span></a></div>
					</div>
					<div class="floatright">
							<img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/right_img.png" />
							<p>（图片仅供参考，请以实物为准）</p>
					</div>
				</div>
				<!--粽子产品 end-->
			</div>
			    <div class=""><img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/ZQ_25.png" class="lotteryimg" /></div>
			    
		</div>
		
		
		
		
			<!--弹出框 start-->
				<div class="opacity" style="display:none;">
					<!--没抢到 start-->
					<div class="alertcontairn" style="display:none;">
						<div class="textinfo">
							<img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/close.jpg" class="close_img">
							<h3 class="price">花好月圆人团圆</h3>
							<div class="alert_img"><img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/alert_01.png" class="lotteryimg" /></div>
							<p>月饼离你还有一厘米，再接再厉，再来一次！</p>
							<div class="pop_btn">
								<a href="javascript:void(0);" class="closeOpacity">继续抢</a>
							</div>
						</div>
					</div>

					<!--没抢到 end-->
					<!--抢到了start-->
					<div class="alertcontairn" style="display:none;">
						<div class="textinfo">
							<img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/close.jpg" class="close_img">
							<h3 class="price">花好月圆人团圆</h3>
							<div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/alert_03.png" class="lotteryimg" /></div>
							<p style="margin-top: 5%;color: #ff001a;">恭喜您抢到了一份彩云追月，可凭码1元换购</p>
							<div class="pop_btn">
								<a href="<?php echo $url2; ?>&pid=20755" class="closeOpacity" style="color: #fefb00;">去换购</a>
							</div>
						</div>
					</div>
					<!--抢到了 end-->
					<!--没抢到 start-->
                    <div class="alertcontairn" style="display:none;">
                        <div class="textinfo">
                            <img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/close.jpg" class="close_img">
                            <h3 class="price">花好月圆人团圆</h3>
                            <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/alert_02.png" class="lotteryimg" /></div>
                            <p style="margin-top: 5%;">小伙伴们已经把月饼都带走了，下次早点来抢吧！</p>
                            <div class="pop_btn">
                                <a href="javascript:void(0);" class="closeOpacity">休息会</a>
                            </div>
                        </div>
                    </div>
                    <!--没抢到 end-->
                    <!--没抢到 start-->
                    <div class="alertcontairn" style="display:none;">
                        <div class="textinfo">
                            <img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/close.jpg" class="close_img">
                            <h3 class="price">花好月圆人团圆</h3>
                            <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/home/moonCakes/issue2015/'); ?>images/alert_02.png" class="lotteryimg" /></div>
                            <p style="margin-top: 5%;">活动已结束</p>
                            <div class="pop_btn">
                                <a href="javascript:void(0);" class="closeOpacity">关闭</a>
                            </div>
                        </div>
                    </div>
                    <!--没抢到 end-->
				</div>
				<!--弹出框 end-->
		<script type="text/javascript">
			$(function() {
				//抢到了
				$('.go').click(function() {
                    $('.opacity').show().find('.alertcontairn').eq(3).show();
//                    $.post(
//                        '/robMoonCakes/rob',
//                        function (data){
//                            if(data == 1){//抢到粽子
//                                $('.opacity').show().find('.alertcontairn').eq(1).show();
//                            }else if(data == 2){//今天已抢到粽子,不能再抢
//                                var modal = $('.opacity').show().find('.alertcontairn').eq(0);
//                                modal.find('p').html('今天已抢到月饼,把机会留给别人吧!');
//                                modal.show();
//                            }else if(data == 4){//活动未开始
//                                var modal = $('.opacity').show().find('.alertcontairn').eq(0);
//                                modal.find('p').html('活动未开始，敬请期待！');
//                                modal.show();
//                            }else if(data == 5){//粽子未抢到
//                                var modal = $('.opacity').show().find('.alertcontairn').eq(0);
//                                modal.find('p').html('月饼离你还有一厘米，再接再厉，再来一次！');
//                                modal.show();
//                            }else if(data == 6){//粽子未抢到
//                                var modal = $('.opacity').show().find('.alertcontairn').eq(0);
//                                modal.find('p').html('月饼离你还有一厘米，再接再厉，再来一次！');
//                                modal.show();
//                            }else if(data == 7){//粽子未抢到
//                                var modal = $('.opacity').show().find('.alertcontairn').eq(0);
//                                modal.find('p').html('月饼离你还有一厘米，再接再厉，再来一次！');
//                                modal.show();
//                            }else if(data == 8){//粽子未抢到
//                                var modal = $('.opacity').show().find('.alertcontairn').eq(0);
//                                modal.find('p').html('月饼离你还有一厘米，再接再厉，再来一次！');
//                                modal.show();
//                            }else if(data == 0){//粽子抢光了
//                                $('.opacity').show().find('.alertcontairn').eq(2).show();
//                            }else{//粽子抢光了
//                                $('.opacity').show().find('.alertcontairn').eq(2).show();
//                            }
//                        },
//                        'json'
//                    );
				})
				$('.closeOpacity').click(function() {
					$('.opacity').hide().find('.alertcontairn').hide();
				})
				$('.close_img').click(function() {
						$('.opacity').hide().find('.alertcontairn').hide();
					})
					//倒计时
				var nodeCurrent = $('.current_tm span'),
					day, hour, minute, second, id2;
				var is_inner = false,
					node_time, next_time, optn;

				function currentTimeNode(day, h, m, s) {
					is_inner = false;
					nodeCurrent.each(function(index) {
						var node_t = parseFloat($(this).attr('value'));
						node_time = node_t;
						if (h < 21 && h > 9) {
//							if (h > node_t) {
//								return true; //往下跳
//							}
							if (h < node_t) {
								node_time = parseFloat($(this).prev('span').attr('value')); //获取当前时间
								next_time = node_t; //获取下一场时间
								return false; //跳出循环
							}
							if (h == node_t) {
								(m < 60) ? is_inner = true: is_inner = false;
								node_time = node_t; //获取当前时间
								next_time = parseFloat($(this).next('span').attr('value')) || 10; //获取下一场时间
								return false; //跳出循环	 
							}
						} else { //不在时间段内
							if (h >= 21 && h < 24) {
								day++;
							}
							node_time = 20;
							next_time = 10;
							return false;
						}
					})
					return optn = {
						tDay: day,
						cNodeTime: node_time,
						nNodeTime: next_time,
						isInner: is_inner
					}
				}

				function countDown() { //执行时的回调函数
					var nowTime = new Date();
					day = nowTime.getDate();
					hour = nowTime.getHours();
					minute = parseFloat(nowTime.getMinutes());
					second = nowTime.getSeconds();
					var temp;
					var opt = currentTimeNode(day, hour, minute, second); //返回对象，对象包含：当前时间，下一场时间，是否是开抢时间
					$('.current_tm span:contains(' + opt.cNodeTime + ')').addClass('cur_time').siblings('span').removeClass('cur_time');
					$('.nexttime i').text(opt.nNodeTime);
					if (opt.isInner) {
						$('.go').show();//点击可以抢
						temp = [2015, 8, opt.tDay, opt.cNodeTime, 59, 59];
						$('.count_down_box label').text('距离本场结束还有:');
						//		  $('.focus_img .go').show().siblings('img').hide();
					} else {
						$('.go').hide();
                        //$('.go').show();//点击可以抢
						temp = [2015, 8, opt.tDay, opt.nNodeTime, 0, 0];
						$('.count_down_box label').text('开抢倒计时:');
						//		  $('.focus_img .go').hide().siblings('img').show();
					}
					$('.count_down').cl_countdown({
							endTime: temp,
							goingTemp: "{hour}时{min}分{sec}秒",
							endCallback: function() {
								nowTime = null;
								countDown();
							}
						})
						//if(hour-nodeh)
						//获取当前的系统后台时间小时
						//与时间节点比较，有一个开抢的时间差是有效的。
						//在开抢时间差内，1点绿当前节点字体，2改变下一场时间，
						//3改变倒计时结束时间（开抢时间差倒计）
						//在开抢时间差外，改变倒计时结束时间（下一场时间差倒计）
						//id2=setTimeout(countDown, 1000);		 
				}
				//countDown();
			})
		</script>
	</body>

</html>