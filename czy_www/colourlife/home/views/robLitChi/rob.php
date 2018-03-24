<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>抢荔枝</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/common/css/lucky/litchi/lizhi.css'); ?>" rel="stylesheet">
		<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
		<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.CountDown.js'); ?>"></script>
		<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
	</head>  

	<body>
		<div class="zongzi">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/QLZ_01.jpg');?>" class="lotteryimg" />
			</div>
			<div class="zongzi_content">
				<div class="lotteryList">
					<dl id="ticker">
				       <?php foreach($newInfo as $_v){ ?>
				          <dt><?php echo $_v; ?></dt>
				       <?php } ?>
				    </dl>
				</div>
				<div class="timebox">
					<!--本场时间节点 start-->
					<div class="tm_node">
						<input type="hidden" value="20" />
						<p class="current_tm clearfix">
							<span class="fristtime"  value=10>10:00</span><!--<span value=14>14:00</span>-->
							<span  class="lasttime" value=16>16:00</span><!--<span value=20>20:00</span>-->
						</p>
						<p class="mod_count clearfix"><span class="floatleft">本场有<i style="font-style:normal;" id="remaining"><?php if($flag ==1){ echo $remaining;}else{ echo "0"; } ?></i>份荔枝</span><span class="nexttime floatright">下一场:<i style="font-style:normal;">14</i>:00准时开抢</span>
						</p>
					</div>
					<!--本场时间节点 end-->
					<!--倒计时容器 start-->
					<div class="count_down_box">
						<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/QLZ_03.jpg');?>" class="tm_png" />
						<label>即将开抢倒计时：</label>
						<span class="count_down price">00时00分00秒</span>
					</div> 
					<!--倒计时容器 end-->
				</div>
				<!--焦点图 start-->
				<div class="focus_img">
					<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/QLZ_02.png');?>" class="lotteryimg" />
					<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/QLZ_02.jpg');?>" class="go lotteryimg" style="display:none;" />
				</div>
				<!--焦点图 end-->
				<p class="hgzj clearfix">
					<a href="/robLitChi/mylottery"><span class="hdzj_text" >活动战绩</span></a>
					<!--<span value=14>14:00</span>-->
				</p>
				<div class="rule">
				<ul>
					<li>活动规则：</li>
					<li>1.活动时间：6月15日- 6月22日</li>
					<li>2.每天10点、16点准时开抢；</li>
					<li>3.每次限量50份开抢，随机抢到。</li>
					<li>4.用户成功抢到后需要支付1元进行换购（中奖页面直接点击“换购”或前往<a href="<?=$url;?>" style="color: red;text-decoration: underline;">1元购专区</a>，换购订单提交后， 则换购码失效， 须于24小时内完成支付，否则订单将自动关闭）。</li>
					<li>5.荔枝统一在活动结束后一个星期内由彩生活客户经理配送到家</li>
				</ul>
			</div>
				<p class="bot_stamp">★注：彩之云享有本次活动的最终解释权 </p>

				<!--弹出框 start-->
				<div class="opacity" style="display:none;">

					<!--荔枝抢没了 start-->
					<div class="alertcontairn grab_over1" style="display:none;">
						<div class="textinfo">
							<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/close.jpg');?>" class="close_img">
							<h3 class="price">免费抢荔枝</h3>
							<div class="alertlottery_img">
								<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/alert_03.png');?>" class="lotteryimg" />
							</div>
							<p>小伙伴们已经把荔枝都带走了，下次早点来抢吧！</p>
							<div class="pop_btn">
								<a href="javascript:void(0);" class="closeOpacity">休息会</a>
							</div>
						</div>
					</div> 
					<!--荔枝抢没了 end-->


					<!--不在时间段内 start-->
					<div class="alertcontairn grab_over2" style="display:none;">
						<div class="textinfo">
							<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/close.jpg');?>" class="close_img">
							<h3 class="price">免费抢荔枝</h3>
							<div class="alertlottery_img">
								<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/alert_02.png');?>" class="lotteryimg" />
							</div>
							<p>你们城里人真会玩，现在暂停接客，下场早点来吧！</p>
							<div class="pop_btn">
								<a href="javascript:void(0);" class="closeOpacity">休息会</a>
							</div>
						</div>
					</div>
					<!--不在时间段内 end-->


					<!--没抢到1 start-->
					<div class="alertcontairn grab_over3" style="display:none;">
						<div class="textinfo">
							<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/close.jpg');?>" class="close_img">
							<h3 class="price">免费抢荔枝</h3>
							<div class="alertlottery_img">
								<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/alert_02.png');?>" class="lotteryimg" />
							</div>
							<p>荔枝过来忽悠了你一下又跑开了，千万别放过它！</p>
							<div class="pop_btn">
								<a href="javascript:void(0);" class="closeOpacity">继续抢</a>
							</div>
						</div>
					</div>
					<!--没抢到1 end-->

					<!--抢到 start-->
					<div class="alertcontairn grab_over6" style="display:none;">
						<div class="textinfo">
							<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/close.jpg');?>" class="close_img">
							<h3 class="price">免费抢荔枝</h3>
							<div class="alertlottery_img">
								<img src="<?php echo F::getStaticsUrl('/common/images/lucky/litchi/alert_01.png');?>" class="lotteryimg" />
							</div>
							<p class="price" style="font-size:14px;">恭喜您抢到了一份荔枝，可凭码1元换购</p>
							<div class="pop_btn">
								<a href="<?=$url?>&pid=6936" class="closeOpacity">去换购</a>
							</div>
						</div>
					</div>
					<!--抢到 end-->

				</div>
				<!--弹出框 end-->

			</div>
		</div>
		
		<!-- <a href="/robLitChi/litChiInvite" class="fxdimg_rob"><img src="<?php //echo F::getStaticsUrl('/common/images/lucky/litchi/QLZ_03.png');?>"/></a> -->
		<script type="text/javascript">
			$(function() {
				$('.go').click(function() {
					$('.opacity').show();
				    $.post(
				        '/robLitChi/rob',
				        function (data){//0抢光了;1抢到了;2今天已抢过不能再抢;4未开始 5/6/7没抢到
				            if(data == 1){//抢到荔枝
				                $('.grab_over6').show();   
				            }else if(data == 4){//活动未开始
				                $(".grab_over2").show();
				            }else if(data == 2 || data == 5 || data == 6 || data == 7){//荔枝未抢到
				                $(".grab_over3").show();
				            }else if(data == 0){//荔枝抢光了
				                $(".grab_over1").show();
				            }else{//荔枝抢光了
				                $('.grab_over1').show();
				            }
				        } 
				    ,'json');
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
//					alert(nodeCurrent.length);//拿到4个span控件，逐一循环
					nodeCurrent.each(function(index) {
						var node_t = parseFloat($(this).attr('value'));
						node_time = node_t;
//						alert("----------q -"+node_time);  
						if (h < 17 && h > 9) {
							if (h > node_t) {
								return true; //往下跳
							}
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
							if (h >= 17 && h < 24) {
								day++;
							}
							node_time = 16;
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
						temp = [2015, 6, opt.tDay, opt.cNodeTime, 59, 59];
						$('.count_down_box label').text('距离本场结束还有:');
						$('.focus_img .go').show().siblings('img').hide();
					} else {
						temp = [2015, 6, opt.tDay, opt.nNodeTime, 0, 0];
						$('.count_down_box label').text('即将开抢倒计时:');
						$('.focus_img .go').hide().siblings('img').show();
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
				countDown();


				function checkTime(){
			      var NowTime = new Date();
			      var colour_h=NowTime.getHours();
			      // var m=NowTime.getMinutes();
			      // var colourlife_sec = NowTime.getSeconds();
			      var num = parseFloat($('#remaining').text());
			      if(colour_h==10 || colour_h==16){//活动结束
			          $.post(
			              '/robLitChi/newFlushByAjax',
			              {'remaining':num},
			              function (data){
			                  if(data.success == "ok"){
			                    $('#remaining').text(data.remaining);
			                  }
			              }
			              ,'json');
			      }
			  	}

			  setInterval(checkTime,3000);



			})
		</script>
	</body>

</html>