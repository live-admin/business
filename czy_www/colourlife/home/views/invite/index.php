<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=yes;" />
<title>抽奖</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/lottery.css?time=123456'); ?>" rel="stylesheet" />
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>


</head>

<body style="background:#fff;">
<div class="lottery_topic" style="background:#fff;">

     <div class="lottery_content">
      <div class="invite_friends">
        <h3>邀请好友送红包</h3>
        <h4 style="color:#000;">邀请好友注册</h4>
        <input type="text" class="moblie_number" style="height:30px; line-height: 30px;" value="请输入好友手机号码" onfocus="if(this.value=='请输入好友手机号码'){this.value='';}" onblur="if(this.value.length < 1) this.value='请输入好友手机号码'" />
        
        
        <div class="lottery_bottom">
          <a href="javascript:void(0);" class="send_number">发送</a>
        </div>
        <div class="send_tips" style="display:none;">
          <h4 style="color:#000;">您即将发送的短信内容：</h4>
          <p>您的好友邀请您注册彩之云,彩之云App下载地址：http://dwz.cn/8YPIv。</p>
        </div>
        
        <dl>
          <dt>温馨提示</dt>
          <dd>
            1、活动时间：2015年2月8日至2015年3月8日。
          </dd>
          <dd>
            2、系统将自动发送彩之云APP下载地址到对方手机上。
          </dd>
          <dd>
            3、邀请好友成功注册彩之云，您将获得5次抽奖机会，您的好友成功注册将获得10次抽奖机会。
          </dd>
          <dd>
            4、每成功邀请一位好友注册成功彩之云可获赠５元现金红包，不限领取次数。　
          </dd>
          <dd>
            5、您的好友如果没有居住在彩生活管辖小区，请注册至“1778体验区”。
          </dd>
          <dd>
            6、系统每日定时统计用户的邀请成功人数，人工审核后将在次日发放现金红包至用户的账号中。
          </dd>
        </dl>
        <div class="lottery_bottom lb_btn">
          <a href="/invite/myInviteRecord">我的邀请</a>
          <a href="/invite/successList">我的成功邀请</a>
          <a href="/invite/rules">活动规则</a>
          <a href="/luckyApp" style="background:#dcdcdc; color:#505050">返回</a>
        </div>
        
      </div>
     </div>
</div>
    
<!--弹出框 start-->
 <div class="opacity" style="display: none;">
   <div class="tips_contairn">

       <h3>温馨提示</h3>
       <div class="textinfo">
         <p style="word-spacing: -4px;"> 
          您已经成功邀请了好友，短信发送内容为：您的好友詹秋凤邀请您注册彩之云，下载地址http://dwz.cn/8YPIv。
         </p>
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity">确定</a>
       </div>

   </div>
 </div>
 <!--弹出框 end-->    
<script>
 var username = "<?php echo $username;  ?>";   
 $('.send_number').click(function(){
   $('.send_number').hide();  
   if ($('.moblie_number').val().match(/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(19[0-9]{1}))+\d{8})$/)) {
        $.post(
          '/invite/inviteFriend',
          {'mobile':$('.moblie_number').val()},
          function (data){
             if(data.code == "success"){
                 $('.textinfo p').text("您已经成功邀请了好友，短信发送内容为：您的好友"+username+"邀请您注册彩之云,下载地址http://dwz.cn/8YPIv。");
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
