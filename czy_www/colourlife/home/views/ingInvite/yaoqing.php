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
  <h3 class="indexh3 red">邀请好友送红包</h3>
  <div class="page_content">
    <input type="text" class="telephone moblie_number" value="请输入好友手机号码" onfocus="if(this.value=='请输入好友手机号码'){this.value='';}" onblur="if(this.value.length < 1) this.value='请输入好友手机号码'" style="height:30px; line-height: 30px;"/>
    <a href="javascript:void(0);" class="btnlist btnred send_number">邀  请  好  友</a>
    <dl>
      <dt>温馨提示</dt>
      <dd>1.邀请有效时间：每天9：00—21:00 。</dd>
      <dd>2.在有效时间内，成功发送邀请短信给好友视为有效参与活动。</dd>
      <dd>3.好友成功注册彩之云即可领取百万大礼包。</dd>
      <dd>4.每邀请10位好友成功注册彩之云，邀请人可获得50元红包，工作人员需对所有邀请行为进行审核，审核通过后即可领取红包。不满10个不予发放，每个用户每天最多领取50元红包，超过部分顺延领取。</dd>
      <dd>5.若邀请注册的号码审核不通过，即为邀请无效。同时新人注册红包也将取消。</dd>
      <dd>6.彩生活享有本次活动的最终解释权。</dd>
    </dl>
    <a href="/ingInvite/myInviteRecord" class="btnlist">邀  请  记  录</a>
    <a href="/ingInvite/successList" class="btnlist btnred">我的成功邀请</a>
    <a href="/ingInvite/rules" class="btnlist">活  动  规  则</a>
    <a href="javascript:history.back();" class="btnlist goback">返  回</a>
  </div>
  
  <div class="bot_p">
    <p>★注：活动最终解释权归彩生活所有</p>
    <p></p>
  </div>
  
</div>

<!--弹出框 start-->
 <div class="opacity" style="display: none;">

  <!--未邀请好友 start-->
   <div class="alertcontairn">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <h3 style="margin:50px 0 10px;">温馨提示</h3>
       <p></p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
   </div>
   <!--未邀请好友 end-->


   
 </div>
 <!--弹出框 end-->


 
<script>
 var username = "<?php echo $username;  ?>";   
 $('.send_number').click(function(){
   $('.send_number').hide();  
   if ($('.moblie_number').val().match(/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(19[0-9]{1}))+\d{8})$/)) {
        $.post(
          '/ingInvite/inviteFriend',
          {'mobile':$('.moblie_number').val()},
          function (data){
             if(data.code == "success"){
                 $('.textinfo p').text("您已经成功邀请了好友，短信发送内容为：您的好友"+username+"分享了彩生活百万大礼包，注册领取地址http://dwz.cn/8YPIv 。");
                 $('.opacity').show();     
                 $('.send_number').show(); 
             }else{
                 $('.textinfo p').text(data.code);
                 $('.opacity').show();
                 $('.send_number').show();
             } 
          }
          ,'json').error(function() {
              $('.textinfo p').text("您的网络异常，请检查网络后重试；");
              $('.opacity').show();
              $('.send_number').show();
          });
   }else{
        $('.textinfo p').text("您输入的手机号码格式不对！");
        $('.opacity').show(); 
        $('.send_number').show();
   }
 });
 
$('.closeOpacity').click(function(){
  $('.opacity').hide();   
}); 
</script>
</body>
</html>
