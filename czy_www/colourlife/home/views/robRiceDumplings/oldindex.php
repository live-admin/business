<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>抢粽子</title>
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/zongzi.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.CountDown.js'); ?>"></script>
</head>

<body>
<div class="zongzi">
  <div class="head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/head.jpg');?>" class="lotteryimg"/></div>
  <div class="zongzi_content">
    <div class="timebox">
      <!--本场时间节点 start-->
      <div class="tm_node">
        <input type="hidden" value="20" />
        <p class="current_tm clearfix"><span value=10 >10:00</span><span value=14>14:00</span><span value=16>16:00</span><span value=20>20:00</span></p>
        <p class="mod_count clearfix"><span class="floatleft">本场共有300份粽子</span><span class="nexttime floatright">下一场:14:00准时开抢</span></p>
      </div>
      <!--本场时间节点 end-->
      <!--倒计时容器 start-->
      <div class="count_down_box">
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/time.gif');?>" class="tm_png"/>
        <label>即将开抢倒计时：</label>
        <span class="count_down price">00时03分21秒</span>
      </div>
      <!--倒计时容器 end-->
    </div>
    <!--焦点图 start-->
    <div class="focus_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/focus.jpg');?>" class="lotteryimg"/><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/focus1.jpg');?>" class="go lotteryimg" style="display:none;"/></div>
    <!--焦点图 end-->
    <div class="active_btn clearfix">
      <a href="robRiceDumplings/mylottery" class="floatleft">活动战绩</a>
      <a href="robRiceDumplings/rule" class="floatright">活动规则</a>
    </div> 
    <!--粽子产品 start--> 
    <div class="product_group clearfix">
      <dl class="floatleft">
        <dt><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/img1.jpg');?>" class="lotteryimg"/></dt>
        <dd>
          <h3>合口味广式礼篮粽1160g</h3>
          <p>精选好料，丰富口味</p>
          <p>端午放价：<span class="price">78</span>元</p>
          <p>原价：98元</p>
        </dd>
      </dl>
      <dl class="floatright">
        <dt><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/img2.jpg');?>" class="lotteryimg"/></dt>
        <dd>
          <h3>合口味百福礼盒粽1440g</h3>
          <p>甄选美味，端午佳礼</p>
          <p>端午放价：<span class="price">78</span>元</p>
          <p>原价：98元</p>
        </dd>
      </dl>
    </div>
    <div class="product_group clearfix">
      <dl class="floatleft">
        <dt><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/img3.jpg');?>" class="lotteryimg"/></dt>
        <dd>
          <h3>合口味南粤五福礼篮1320g</h3>
          <p>南粤风味，五福呈祥</p>
          <p>端午放价：<span class="price">118</span>元</p>
          <p>原价：148元</p>
        </dd>
      </dl>
      <dl class="floatright">
        <dt><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/img4.jpg');?>" class="lotteryimg"/></dt>
        <dd>
          <h3>合口味岭南八珍礼盒1280g</h3>
          <p>八珍八味，岭南秘制</p>
          <p>端午放价：<span class="price">148</span>元</p>
          <p>原价：168元</p>
        </dd>
      </dl>
    </div>
    <!--粽子产品 end--> 
    <p class="bot_stamp">★注：彩之云享有本次活动的最终解释权 </p> 
     
  <!--弹出框 start-->
    <div class="opacity">
     
     <!--粽子抢没了 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg1.jpg');?>" class="lotteryimg" /></div>
         <p>小伙伴们已经把粽子都带走了，下次早点来抢吧！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">休息会</a>
         </div>
       </div>
     </div>
     
     <!--粽子抢没了 end-->
     <!--不在时间段内 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg2.jpg');?>" class="lotteryimg" /></div>
         <p>你们城里人真会玩，现在暂停接客，下场早点来吧！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">休息会</a>
         </div>
       </div>
     </div>
     <!--不在时间段内 end-->
     <!--没抢到1 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg2.jpg');?>" class="lotteryimg" /></div>
         <p>粽子过来忽悠了你一下又跑开了，千万别放过它！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">继续抢</a>
         </div>
       </div>
     </div>
     <!--没抢到1 end-->
     <!--没抢到2 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg3.jpg');?>" class="lotteryimg" /></div>
         <p>粽子从你手边溜走了，再来一次，抓住它！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">继续抢</a>
         </div>
       </div>
     </div>
     <!--没抢到2 end-->
     <!--没抢到3 start-->
     <div class="alertcontairn" style="display:none;">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg3.jpg');?>" class="lotteryimg" /></div>
         <p>粽子离你还有一厘米，再接再厉，再来一次！</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">继续抢</a>
         </div>
       </div>
     </div>
     <!--没抢到3 end-->
     <!--抢到 start-->
     <div class="alertcontairn">
       <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/close.jpg');?>" class="close_img">
         <h3 class="price">端午乐悠悠</h3>
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/zongzi/alertimg4.jpg');?>" class="lotteryimg" /></div>
         <p class="price" style="font-size:14px;">恭喜您抢到了一份福气粽子，可凭码1元换购</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity">去换购</a>
         </div>
       </div>
     </div>
     <!--抢到 end-->
     
    
    
   </div>
  <!--弹出框 end-->
    
  </div>
</div>
<script type="text/javascript"> 


$(function(){
  $('.closeOpacity').click(function(){
	  $('.opacity').hide().find('.alertcontairn').hide();
	})
  
  //倒计时
  
 
  $('.count_down').cl_countdown({
	 endTime:[2015,6,4,23],
	 goingTemp:"{hour}时{min}分{sec}秒"
   })
  
  
  
})
</script> 
</body>
</html>
