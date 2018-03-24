<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>彩生活住宅品牌发布会</title>

		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>css/conference.css" rel="stylesheet">
		<script src="<?php echo F::getStaticsUrl('/common/'); ?>js/jquery.min.js" type="text/javascript"></script>
	</head>

	<body>
		<div class="conference">
			<div class="head"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/head.jpg" class="lotteryimg" /></div>
            <div class="content_top">
               <p class="the_code"><input type="text" id="the_code" placeholder="请输入领取码" /></p>
               <button class="btn_get_code">点击领取</button>
            </div>
            <div class="spr"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/spr.gif" class="lotteryimg" /></div>
            <dl class="content_bottom">
               <dt>礼包说明</dt>
               <dd>
                 <span>1、</span><span>礼包包括彩之云饭票和黑莓酒1元换购码，输入签到处工作人员发放的领取码即可领取。</span>
               </dd>
               <dd>
                   <span>2、</span><span>彩之云饭票： 彩之云E缴费、京东特供、海外直购、天天团、彩生活特供、手机充值等均可以使用饭票支付。<a href="<?php echo F::getHomeUrl('/redpacketDescription');?>">饭票使用说明 &gt;&gt;</a></span>
               </dd>
               <dd>
                 <span>3、</span><span>1元换购码：仅需支付1元即可换购价值168元的养生黑莓酒一份：500ml*2，换购有效期：6月30日当天。成功换购后，请于发布会现场提货。<a href="<?=$oneBuyHref?>&pid=1619">点击换购&gt;&gt;</a></span>
               </dd>
               <dd>
                 <span>4、</span><span>本礼包仅限6月30日出席彩生活住宅品牌发布会的记者领取，彩生活拥有法律范围内的活动最终解释权。</span>
               </dd>
            </dl>
		</div>	
        <!--弹出框 start-->
        <div class="cd-popup alert1" role="alert">
            <div class="cd-popup-container">
                <p>您还没有登录呢。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div>
        </div>
        
        <div class="cd-popup alert2" role="alert">
            <div class="cd-popup-container">
                <p>您已领取过礼包。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div>
        </div>
        
        <div class="cd-popup alert3" role="alert">
            <div class="cd-popup-container">
                <p>您已获得惊喜礼包一份，包含500<br />元饭票和黑莓酒1元换购码</p>
                <div class="cd-buttons">
                    <a href="#" class="close-pop">礼包说明</a>
                </div>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div> <!-- cd-popup-container -->
        </div> <!-- cd-popup -->
        
        
        
        <div class="cd-popup alert4" role="alert">
            <div class="cd-popup-container">
                <p>领取礼包码失败。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div>
        </div>
        <div class="cd-popup alert5" role="alert">
            <div class="cd-popup-container">
                <p>礼包码不能为空。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div>
        </div>
        <!--弹出框 end-->
        <!--弹出框 end-->
        <script type="text/javascript">
          jQuery(document).ready(function($){
                //open popup
                $('.btn_get_code').click(function(event){
                    if (!$('#the_code').val()){
                        event.preventDefault();
                        $('.alert5').addClass('is-visible');
                        return;
                    }
                    $.ajax({
                        type: 'get',
                        url: '/houseConference/getCode',
                        data: 'type=1&code=' + $('#the_code').val(),
                        dataType: 'json',
                        async: false,
                        error: function () {
                                event.preventDefault();
                                $('.alert4').addClass('is-visible');
                        },
                        success: function (json) {
                            event.preventDefault();
                            $('.alert' + json.suc).addClass('is-visible');
                        }
                    });  
                });

                //close popup
                $('.alert1, .alert2, .alert3, .alert4, .alert5').click(function(event){
                        if( $(event.target).is('.img-replace')||$(event.target).is('.close-pop') || $(event.target).is('.cd-popup') ) {
                                event.preventDefault();
                                $(this).removeClass('is-visible');
                        }
                });

         });
        </script>
	</body>

</html>