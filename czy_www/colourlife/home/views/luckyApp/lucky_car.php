<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>抽奖</title>
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">      
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/luckyCar/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jQueryRotate.2.2.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.easing.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/lucky.circle.js'); ?>"></script>

<script type="text/javascript"> 


// $(function(){
// 	$("#startbtn").rotate({
// 		bind:{
// 			move:function(){
// 				var a = Math.floor(Math.random() * 360);
// 				$(this).rotate({
// 					 	duration:3000,
// 					 	angle:0, 
//             			animateTo:3600+a,
// 						easing: $.easing.easeOutSine,
// 						callback: function(){
// 							$('.cover_start').hide();
// 						}
// 				 });
// 			}
// 		}
// 	});
	
// 	var e = jQuery.Event("move");
// 	$("#lottery_confirm").click(function(){
// 	  $('.cover_start').show();
// 	  $('.opacity').hide();
	  
//       $("#startbtn").trigger(e);
//     });
	
// });
</script> 


</head>

<body>
<div class="cover_start" style="display:none;"></div>
<div class="lottery_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/lotteryhead.jpg');?>" class="lotteryimg"/></div>
<div class="lottery_topic">
  <img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/backtop.png');?>" class="lotteryimg" style="position:absolute; left:0; top:0;"/>
   <div class="lottery" id="lottery">
     <span id="luckyTodayCan" style="display: none"><?php echo $balance;?></span>
     <div class="roulette">
       <div id="start">    
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/point.png');?>" id="startbtn" class="cup_start lotteryimg"/>
       </div>
     </div>
     <div class="lottery_details_link">
       <a href="/luckyApp/luckyCarResult" class="acitverule">中奖情况</a> 
     </div>
     <div class="active_details_box">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/backbot.png');?>" class="lotteryimg" style="position:absolute; left:0; bottom:-3%;"/>
       <dl class="active_details">
         <dt>活动时间</dt>
         <dd>5月13日至6月17日</dd>
         <dt>抽奖规则</dt>
         <dd>每抽奖一次需要使用0.1元红包。</dd>
         <dt>奖品设置</dt>
         <dd>1.一万个上海大众2015新朗逸汽车抽奖码</dd>
         <dd>2.一万份彩生活特供黑莓酒礼盒</dd>
         <dd>3.一万份甜蜜红枣</dd>
         <dd>4.百万份精美小礼品</dd>
       </dl>
    </div>   
     <p class="zhu">★注：活动最终解释权归彩生活所有</p>
     <div class="lotterybottom"></div>
   </div>
   
  
   
<!--弹出框 start-->
  <div class="opacity" style="display:none">
   
   <!--谢谢参与 start-->
   <div class="alertcontairn alertthanks" style="display:none">
     <div class="textinfo">
      <div class="alertlottery_img"></div>
       <h3 style="top:50px;">谢谢参与</h3>
       <p class="alert_p">很遗憾，本次抽奖您未抽中。<br/>运气就差一点点，再试试看吧!
       </p>
       <div class="pop_btn">
         <a href="/luckyApp/luckyCarResult">我的抽奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--谢谢参与 end-->

  
  <!--红包余额不足 start-->
   <div class="alertcontairn alertnored" style="display:none">
     <div class="textinfo">
      <div class="alertlottery_img"></div>
       <h3 style="top:50px;">温馨提示</h3>
       <p class="alert_p">
         亲，你的红包余额不足，无法参加活动！
       </p>
       <div class="pop_btn">
         <a href="/luckyApp">活动主页面</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--红包余额不足 end-->



   <!--中抽奖码 start-->
   <div class="alertcontairn alertcar" style="display:none;">
     <div class="textinfo">
       <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/alert_img1.jpg');?>" class="lotteryimg" /></div>
       <h3>生活处处有惊喜</h3>
       <p>恭喜您获得汽车抽奖码一个，<br />
        号码为：<span id="lucky_car_code"></span>，<br />
        请进入"汽车大奖"活动查看。</p>
       <div class="pop_btn">
         <a href="/luckyApp/carTopic">进入活动</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   
   <!--中抽奖码 end-->
   <!--精美礼品 start-->
   <div class="alertcontairn alertgood" style="display:none;">
     <div class="textinfo">
       <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/alert_img2.jpg');?>" class="lotteryimg" /></div>
       <h3>生活处处有惊喜</h3>
       <p>恭喜您获得了精美小礼品1元换购权</p>
       <div class="pop_btn">
         <!-- <a href="<?=$url?>&pid=####">换购</a> -->
         <a href="javascript:void(0);">换购</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--精美礼品 end-->
   <!--甜蜜红枣 start-->
   <div class="alertcontairn alerttonic" style="display:none;">
     <div class="textinfo">
       <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/alert_img3.jpg');?>" class="lotteryimg" /></div>
       <h3>生活处处有惊喜</h3>
       <p>恭喜您获得了若羌红枣1元换购权</p>
       <div class="pop_btn">
         <a href="<?=$url?>&pid=1617">换购</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   
   <!--甜蜜红枣 end-->
   <!--黑莓酒 start-->
   <div class="alertcontairn alertheimeijiu" style="display:none;">
     <div class="textinfo">
       <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/alert_img4.jpg');?>" class="lotteryimg" /></div>
       <h3>生活处处有惊喜</h3>
       <p>恭喜您获得了黑莓酒礼盒1元换购权</p>
       <div class="pop_btn">
         <a href="<?=$url?>&pid=1624">换购</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--黑莓酒 end-->
   <!--确认参加 start-->
   <div class="alertcontairn alertconfirm" style="display:none;">
     <div class="textinfo">
       <div class="alertlottery_img"></div>
       <h3 style="top:50px;">温馨提示</h3>
       <p>参加红包抢惊喜，需扣除0.1元红包，<br/><span style="font-size:16px; font-weight:bold;">确定参加吗？</span></p>
       
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity">取消</a>
         <a href="javascript:void(0);" id="lottery_confirm">确认</a>
       </div>
     </div>
   </div>
   <!--确认参加 end-->
   
  
  
 </div>
<!--弹出框 end-->
  
</div>

<script type="text/javascript"> 


$(function(){
  
//   $('#startbtn').click(function(){
// 	  $('.opacity').show();
//   })
  
  $('.closeOpacity').click(function(){
	  $('.opacity').hide();
    $('.alertcontairn').hide();	  
	})
	
  
})
</script> 
</body>
</html>
