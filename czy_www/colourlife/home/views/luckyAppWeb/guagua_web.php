<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<title>刮刮卡</title>
		<link href="<?php echo F::getStaticsUrl('/common/css/lucky/november/scrape.css');?>" rel="stylesheet">
		<script src="<?php echo F::getStaticsUrl('/common/js/lucky/november/jquery.js');?>"></script>
		<script src="<?php echo F::getStaticsUrl('/common/js/lucky/november/wScratchPad.js');?>"></script>
	</head>
	
	<body class="scrape_web">
		<div class="main">
            <p class="chance"><span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
            您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span>刮奖次机会，已有 <span id=""><?php echo $allJoin; ?></span> 人次参加</p>
            <div class="scr_contairn">
              <div class="cover">
                <p class="rule_cord">
                  <a href="/luckyAppWeb/guaguaResultWeb">刮奖记录</a>
                  <span>|</span>
                  <a href="/luckyAppWeb/guaguaRuleWeb">活动规则</a>
                </p>
                <div class="cover_content">
                  <div id="prize">
                  </div>
                  <div id="scratchpad">
                  </div>
                </div>
                <div class="wordlist">
                  <p class="normal_p">鼠标移动到刮奖区，即开即兑</p>
                  <div class="lottery_words">
                    <!-- <p class="nothing" style="display:none;">很遗憾没能中奖，别灰心继续努力！</p>
                    <p class="taikang" style="display:none;">恭喜您，刮到泰康人寿一年"免费意外保险"，最高保10万。</p>
                    <p class="lottery" style="display:none;">恭喜您，500大奖和您只有一步之遥。只要您是彩生活业主或租户，待
  客户经理上门核实身份后，彩生活将发放5000元到您的红包账号。</p>
                    <p class="littlemoney" style="display:none;">幸运刮刮乐，好运刮出来，恭喜您，刮中0.18元红包！</p> -->

                    <p class="nothing" style="display:none;">很遗憾没能中奖，别灰心继续努力！</p>

                  <p class="taikang" style="display:none;">恭喜您，刮到泰康人寿一年"免费意外保险"，最高保100万。</p>

                  <p class="lottery" style="display:none;">恭喜您，<span class="red_packet5000">5000元</span>大奖和您只有一步之遥。只要您是彩生活业主或租户，待客户经理上门核实身份后，彩生活将发放<span class="red_packet5000">5000元</span>到您的红包帐号。</p>

                  <p class="littlemoney" style="display:none;">幸运刮刮乐，好运刮出来，恭喜您，刮中<span id="prize_nums"></span>！</p>

                  <p class="mingtzlai" style="display:none;">亲，每天只能刮奖5次哦，明天再来刮奖吧！<br/>邀请好友注册成功可获得5次刮奖机会哦。<br/><!-- <a href="/invite">邀请路径：APP首页——>邀请注册。</a> --></p>

                  <p class="yonggle" style="display:none;">亲，您的刮奖次数用光了。<br/>邀请好友注册成功可获得5次刮奖机会哦。<br/><!-- <a href="/invite">邀请路径：APP首页——>邀请注册。</a> --></p>

                  <p class="heimeijiu" style="display:none;">恭喜您喜中黑莓酒一瓶</p>



                  </div>
                </div>
              </div>
              <div class="sublink clearfix">
                <!-- <a href="" class="continue" style="display:none">继续刮奖</a>
                <a href="" class="continue" style="display:none">去领取</a> -->
                <a href="javascript:void(0);" class="continue" style="display:none">&gt;&gt;继续刮奖</a>
              <a href="/luckyAppWeb/lingqutaikang" class="lingqu lingqu_taikang" style="display:none">&gt;&gt;去领取</a>
              <a href="/luckyAppWeb/guaguas_5000" class="lingqu lingqu_5000" style="display:none">&gt;&gt;去领取</a>
              <a href="/luckyAppWeb/guagua_heimei" class="lingqu lingqu_heimei" style="display:none">&gt;&gt;去领取</a>
              </div>
            
            </div>
			
			<div class="web_rule">
              <dl>
                <dt>刮奖规则：</dt>
                <dd>1、老用户每天登陆彩之云App或网站获赠1次刮奖机会 </dd>
                <dd>2、新注册用户登录App或网站成功后马上获赠10次刮奖机会</dd>
                <dd>3、用户使用投诉报修功能每天获赠1次刮奖机会</dd>
                <dd>4、推荐好友注册成功获赠5次刮奖机会</dd>
                <dd>5、任意交易成功（充值、商品交易、缴物业费、缴停车费）获赠5次刮奖机会</dd>
                <dd>6、每天每位用户最多可刮奖5次</dd>
              </dl>
            </div>
            
           <!--  <div class="easy">
              <div class="easy_content">
              	<a href="/activity/createProperty" class="atonce_join">
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/november/active.jpg');?>" />
         		</a>
              </div> -->
              <!--广告 start-->
            <div>
            	<a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/activity/createProperty');?>">
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/november/active+.jpg');?>" class="lotteryimg"/></a>

               <!-- <a href=""><img src="<?php //echo F::getStaticsUrl('/common/images/lucky/november/advertisement1.jpg');?>" class="lotteryimg"/></a> -->
      
      <!--  <a href="http://www.oznerwater.com/Api/saleredirect.aspx?<?php echo $completeURL;?>" style="display:block; margin-bottom:10px;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/666-03.jpg');?>" class="lotteryimg" /></a>
       <a href='http://www.e-zhongjie.com:81/Sceret/zi/Yad.aspx'><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/advertisement.jpg');?>" class="lotteryimg" /></a> -->
            </div>
            <!--广告 end-->
            <!--页脚 start-->
            <div>
               <img src="<?php echo F::getStaticsUrl('/common/images/lucky/november/bot_logo.jpg');?>" class="lotteryimg"/>
            </div>
            <!--页脚 end-->
            </div>
            
		</div>
        
		<script type="text/javascript">
           //test for HTML5 canvas
			
             
			  var num = 0;
			  var htmlword;

			  $(function() {
				    var test = document.createElement('canvas');
					if(!test.getContext)
					{//如果不支持canvas
					  $('#prize').addClass('scratchpad');
					  $('.scratchpad').mousedown(function(){
						var isMove = true;
					
					if(lottery()){
					  $('.scratchpad').mousemove(function(){
						  if(isMove){
						  	if(num<1) htmlword=getLuckyData(); 
							  num++;
							  if(num>30){
							  $(this).css('background-image','url(images/scrapeWeb1.jpg)');
							  // $(this).text("靠，中奖了！鞋底臭臭的");
							  document.getElementById('prize').innerHTML =htmlword.message;
							  // $('.normal_p').hide();//隐藏底下默认提示文字
							  // $('.lottery_words p').hide();
							  // $('.lottery_words p').eq(1).show();//显示相关中奖提示文字
							  // $('.continue').show();//显示"继续刮奖"字样

							  $('.normal_p').hide();//隐藏底下默认提示文字
								$('.lottery_words p').hide();
								if(htmlword.nums==3){
									$("#prize_nums").text(htmlword.message);
								}
								$('.lottery_words p').eq(htmlword.nums).show();//显示相关中奖提示文字
								if(htmlword.is_show==1&&htmlword.nums==1){
									$('.lingqu_taikang').show();
								}
								if(htmlword.is_show==1&&htmlword.nums==2){
									$('.lingqu_5000').show();
								}

								if(htmlword.is_show==1&&htmlword.nums==6){
									$('.lingqu_heimei').show();
								}
								// $('.normal_p').hide();//隐藏底下默认提示文字
								// $('.lottery_words p').hide();
								// $('.lottery_words p').eq(1).show();//显示相关中奖提示文字
								$('.continue').show();//显示"继续刮奖"字样
							
							   }
							}
						  }).mouseup(function(){
							  isMove = false;
						  });
					  }
					  })
						
					}
					else{
					if(lottery()){
					$("#scratchpad").wScratchPad({
						width: 640,
						height: 225,
						color:'#a9a9a9',
						size:20,
						scratchMove: function() {
							if(num<1) htmlword=getLuckyData(); 
							num++;
							if (num == 40) {//当刮奖刮开一定程度之后
								document.getElementById('prize').innerHTML =htmlword.message;
								// document.getElementById('prize').innerHTML = "泰康人寿一年免费意外保"; //刮开后显示字样

								$('.normal_p').hide();//隐藏底下默认提示文字
								$('.lottery_words p').hide();
								if(htmlword.nums==3){
									$("#prize_nums").text(htmlword.message);
								}
								$('.lottery_words p').eq(htmlword.nums).show();//显示相关中奖提示文字
								if(htmlword.is_show==1&&htmlword.nums==1){
									$('.lingqu_taikang').show();
								}
								if(htmlword.is_show==1&&htmlword.nums==2){
									$('.lingqu_5000').show();
								}

								if(htmlword.is_show==1&&htmlword.nums==6){
									$('.lingqu_heimei').show();
								}
								// $('.normal_p').hide();//隐藏底下默认提示文字
								// $('.lottery_words p').hide();
								// $('.lottery_words p').eq(1).show();//显示相关中奖提示文字
								$('.continue').show();//显示"继续刮奖"字样
							}
	
							
						}
					 });
					}
				   }	 
			  });

			function lottery(){
				var postCan=parseInt($("#luckyTodayCan").text());
				var custCan=parseInt($("#luckyCustCan").text());
				
			    if(custCan<1){
			       	$('.normal_p').hide();//隐藏底下默认提示文字
					$('.lottery_words p').hide();
					$('.lottery_words p').eq(5).show();//显示相关中奖提示文字
			        return false;
			    }
			    else if(postCan<1){
		          	$('.normal_p').hide();//隐藏底下默认提示文字
					$('.lottery_words p').hide();
					$('.lottery_words p').eq(4).show();//显示相关中奖提示文字
			        return false;
			    }else{
			    	 return true;
			    }
			   
			}
			

			var	message;
			var	nums;
			var	is_show;
			var	gua_result;


			function getLuckyData() { //得到数据
	          $.ajax({
	            type: 'POST',
	            url: '/luckyAppWeb/doShakeLucky',
	            data: 'actid=8',
	            dataType: 'json',
	            async: false,
	            error: function () {
	              nums = 0;
	              message = "谢谢惠顾";
	              is_show = 0;
	              gua_result = {"nums":nums,"message":message,"is_show":is_show};
	            },
	            success: function (data) {
	              var postCan = parseInt($('#luckyTodayCan').text());
	              var custCan = parseInt($('#luckyCustCan').text());
	              $('#luckyTodayCan').text(postCan-1);
	              $('#luckyCustCan').text(custCan-1);
	              if (data.success == 0 && data.data.location == 1) {
	                location.href = data.data.href;
	                return;
	              }
	              if (data.success == 0) {
	                nums = 0;
	                message = "谢谢惠顾";
	                is_show = 0;
	                gua_result = {"nums":nums,"message":message,"is_show":is_show};
	              } else {
	                getPrize = data.data.result;
	                gua_result = showPackag(getPrize);
	              }
	            }
	          });				
				return gua_result;
	    	}

	    	//根据结果弹出红包
		    function showPackag(prize) {        
		        var prizeid=parseInt(prize.id);
		        if (prizeid==79) {
		          nums = 0;
		          message = "谢谢惠顾";
		          is_show = 0;
		        } else if (prizeid==77) {
		          nums = 1;
		          message = "泰康人寿";
		          is_show = 1;
		        } else if(prizeid==76){
		          nums = 2;  
		          message = "5000元大奖";
		          is_show = 1;
		        } else if(prizeid>=71&&prizeid<=75){
		          nums = 3;
		          message = prize.rednum+"元红包";
		          is_show = 0;
		        } else if(prizeid==78){
		          nums = 6;
		          message = "黑莓酒一瓶";
		          is_show = 1;
		        }else{
		          nums = 0;
		          message = "谢谢惠顾"; 
		          is_show = 0;
		        }
		        gua_result = {"nums":nums,"message":message,"is_show":is_show};
		        return gua_result;
		    }

			$('.continue').click(function(){
			  window.location.href = location.href;	
		    })
		</script>
		
	</body>

</html>