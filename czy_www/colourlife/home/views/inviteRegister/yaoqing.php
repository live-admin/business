<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="MobileOptimized" content="320" />
    <title>年末有礼</title>
    <link href="<?php echo F::getStaticsUrl('/inviteRegister/css/znq.css'); ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/inviteRegister/js/jquery.min.js'); ?>"></script>
    <style>
        .lotteryimg{margin-top:20px}
        .content .no1{ margin-top:50px}
        .content .txt_h3{margin-bottom:40px; margin-top:5px}
        .center_samll{ padding-left:100px}
        .no2{margin-left:20px}
        .no3{margin-left:20px}
        .mag1{margin-left:50px}
    </style>
</head>

<body>
    <div class="znq">
            <div class="head">
                    <img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_01.png'); ?>" class="lotteryimg" />
            </div>
        <div class="content">
            <img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_02.png'); ?>" class="no1">

            <p class="txt_h3">每日成功邀请好友人数最多的前三名邀请人，分别额外获得200元、100元、50元的饭票奖励。</p>

            <img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_07.png'); ?>">

            <p class="txt_h3">邀请一位好友成功注册彩之云，邀请人获得2元饭票，多邀多送。</p>

            <img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_03.png'); ?>" class="center_samll"/>
            <div class="input_row">
                    <input type="tel" placeholder="请输入电话号码" class="moblie_number"/><span class="ljyq send_number">邀请好友</span></div>
            <div class="img_row clearfix">
                    <a href="/inviteRegister/qianSan"><img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_04.png'); ?>" /></a>
                    <a href="/inviteRegister/successList" class="mag1"><img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_05.png'); ?>" /></a>
                    <a href="/inviteRegister/rules"><img src="<?php echo F::getStaticsUrl('/inviteRegister/images/img_06.png'); ?>" /></a>

            </div>
            <h3>温馨提示</h3>
            <div class="rule">
                <p>1、活动时间：2015年11月05日——12月05日</p>
                <p>2、邀请注册有效时间：9 : 00——22：00</p>
                <p>3、饭票发放时效：72小时之内</p>
                <p>4、邀请方式：</p>
                <p class="no2">a.用户通过“邀请送饭票”活动页面，填写好友手机号码邀请，好友成注册，视为有效邀请。</p>
                <p class="no3">b.用户通过"我"  <span style="font-size:1.5em">&gt</span> "邀请好友" <span style="font-size:1.5em">&gt</span>  "分享"至微信、QQ好友等，发送邀请码信息至好友，好友使用邀请码注册彩之云，视为有效邀请。</p>
            </div>
            <table>
                    <tr class="rule">


                    </tr>
            </table>
            <p class="record">★注：彩之云享有本次活动在法律范围内的最终解释权</p>
        </div>
    </div>


    <!--弹出框-->
    <!--已经注册-->
    <div class="pop_up" style="display: none;">
        <!--获得 -->
        <div class="iphone_pop alert1" style="display: none;">
            <div class="close_row clearfix">
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/close.jpg');?>" />
            </div>
            <p class="hint">温馨提示</p>
            <p class="pop_img">恭喜您已成功邀请了您的好友 <br/>复制下载链接发送给你的好友吧！
                <br />
                <a href="http://dwz.cn/8YPIv">http://dwz.cn/8YPIv</a>
            </p>
            <div class="know btn_alert">继续邀请</div>
        </div>

        <div class="iphone_pop alert2" style="display: none;">
            <div class="close_row clearfix">
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/close.jpg');?>" />
            </div>
            <p class="hint">温馨提示</p>
            <p class="pop_img">亲~现在不是活动有效邀请时间，您确定要邀请您的好友么？</p>
            <div class="know btn_send">确定邀请</div>
        </div>

        <div class="iphone_pop alert3" style="display: none;">
          <div class="close_row clearfix">
            <img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/close.jpg');?>" />
          </div>
          <p class="hint">温馨提示</p>
          <p class="textinfo"></p>
          <div class="know btn_alert">关闭</div>
        </div>

	</div>
    <script type="text/javascript">				
		//关闭弹出框
		$('.close_row>img,.btn_alert').click(function(){
			$(this).parents('.iphone_pop').hide();
			$('.pop_up').hide();
		}); 
				
				
		$('.btn_send').click(function(){
			//return false;
			$('.alert2').hide();
			$('.pop_up').show();
		   	if ($('.moblie_number').val().match(/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(19[0-9]{1}))+\d{8})$/)) {
		        $.post(
		          '/InviteRegister/inviteFriend',
		          {'mobile':$('.moblie_number').val()},
		          function (data){
		             if(data.code == "success"){
		                 $('.alert1').show(); 
		                 $('.pop_up').show();     
		                 // $('.ljyq').show(); 
		             }else{
		                 $('.alert3').show();
		                 $('.textinfo').text(data.code);
		                 $('.pop_up').show();
		                 // $('.ljyq').show();
		             } 
		          }
		          ,'json').error(function() {
		              $('.alert3').show(); 
		              $('.textinfo').text("您的网络异常，请检查网络后重试；");
		              $('.pop_up').show();
		              // $('.ljyq').show();
		          });
		   }else{    
		        $('.alert3').show(); 
		        $('.textinfo').text("您输入的手机号码格式不对！");
		        $('.pop_up').show(); 
		        // $('.ljyq').show();
		   }
		 });
	


		$('.ljyq').click(function(){
		   // $('.ljyq').hide();
		   $('.pop_up').show();
		   if ($('.moblie_number').val().match(/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(19[0-9]{1}))+\d{8})$/)) {
		        $.post(
		          '/inviteRegister/inviteFriendWarn',
		          {'mobile':$('.moblie_number').val()},
		          function (data){
		             if(data==1){
						$('.alert2').show(); 
						$('.pop_up').show();     
						// $('.ljyq').show();
						return false;
		             }else if(data.code == "success"){
		             	$('.alert1').show(); 
						$('.pop_up').show();
						// $('.ljyq').show();
		             }else{
						$('.alert3').show(); 
						$('.textinfo').text(data.code);
						$('.pop_up').show();
						// $('.ljyq').show();
		             } 
		          }
		          ,'json').error(function() {
		              $('.alert3').show(); 
		              $('.textinfo').text("您的网络异常，请检查网络后重试；");
		              $('.pop_up').show();
		              // $('.ljyq').show();
		          });
		   }else{    
		        $('.alert3').show(); 
		        $('.textinfo').text("您输入的手机号码格式不对！");
		        $('.pop_up').show(); 
		        // $('.ljyq').show();
		   }
		 });
	</script>
</body>
</html>