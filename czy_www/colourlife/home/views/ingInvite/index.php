<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>上市感恩季</title>
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">      
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/invite/hongbao.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
</head>

<body style="background:#fcf0d1;">
<div class="hongbao">
  <div class="like_h3"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/h3.gif');?>" class="lotteryimg" /></div>
  <div class="like_h4"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/hx1.gif');?>" class="lotteryimg" /></div>
  <h3 class="indexh3">新注册用户快快领取百万大礼包！</h3>
  <p>（记得填写小区信息，注册在体验区可没有礼包领哦）</p>
  <a href="/ingInvite/links" class="hongbaolink hglink_two" style="margin:15px auto 30px;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/hongbao1.gif');?>" class="lotteryimg" /></a>
  <div class="like_h4"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/hx2.gif');?>" class="lotteryimg" /></div>
  <p>每邀请10位好友成功注册彩之云，即可领取50元红包。</p>
  <a href="javascript:void(0);" class="hongbaolink hglink_one" style="margin:10px auto;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/hongbao2.gif');?>" class="lotteryimg" /></a>
  <p>您已邀请<?php echo $mycount; ?>位好友成功注册彩之云；</p>
  <p>已领取<?php echo $mysum?$mysum:0; ?>红包，待领取<?php echo $diff; ?>元红包。</p>
  <p>再邀请<?php echo $lack; ?>位好友可以再领取50元红包，加油哦！</p>
  <div class="sublink">
    <a href="/ingInvite/invite">邀请好友</a>
    <a href="/ingInvite/myInviteRecord">邀请记录</a>
  </div>
  <div class="bot_p">
    <p>★注：活动最终解释权归彩生活所有</p>
  </div>





<!--弹出框 start-->
  <div class="opacity" style="display:none;">
   
   <!--已邀请好友 start-->
   <div class="alertcontairn sendSuccess" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/alertimg1.gif');?>" class="lotteryimg" /></div>
       <p class="red">恭喜您领取了50元红包</p>
       <div class="pop_btn">
         <a href="/ingInvite/invite">继续邀请</a>
       </div>
     </div>
   </div>   
   <!--已邀请好友 end-->

   <!--未邀请好友 start-->
   <div class="alertcontairn noSend" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/alertimg2.gif');?>" class="lotteryimg" /></div>
       <p>您没有待领取的红包<br />赶紧去邀请好友注册吧！</p>
       <div class="pop_btn">
         <a href="/ingInvite/invite">邀请好友</a>
       </div>
     </div>
   </div>
   <!--未邀请好友 end-->

    <div class="alertcontairn noSend100" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"></div>
       <p class="alert_p">亲~您的邀请战绩太出色，<br/>小二努力数红包中，请稍等。</p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
   </div>
   

    <!-- 未审核 -->
    <div class="alertcontairn noSend102" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"></div>
       <p class="alert_p">亲，小二正在努力数红包，我们将在24小时内进行审核发放！</p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
   </div>




   <!--网络异常 start-->
   <div class="alertcontairn noNetWork" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"></div>
       <p>您的网络异常，请检查网络后重试。</p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
   </div>
   <!--网络异常 end-->


  
 </div>
<!--弹出框 end-->
  
</div>

<script type="text/javascript"> 


$(function(){
  
  $('.hglink_one').click(function(){
	  $('.alertcontairn').hide();
    $('.opacity').show();
    $.post(
      '/ingInvite/sendRedPacket',
      function (data){
         if(data.state == "ok"){
             $('.sendSuccess').show();
         }else if (data.state == "100"){
             $('.noSend100').show();
         }else if(data.state == "102"){
             $('.noSend102').show();
         }else{
             $('.noSend').show();
         } 
      }
      ,'json').error(function() {
          $('.noNetWork').show();
      });
  })


  
  $('.closeOpacity').click(function(){
	  $('.opacity').hide();	  
	})
	
  
})
</script> 
</body>
</html>
