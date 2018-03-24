<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>630海外直购</title>
		<link href="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>css/gedc.css" rel="stylesheet" type="text/css">
                <script src="<?php echo F::getStaticsUrl('/common/'); ?>js/jquery.min.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="hw_sixmonth">
		  <div><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/head.jpg" class="lotteryimg" /></div>
          <div class="content_grab_box">
            <!--限时抢 start-->
            <div class="content_grab">
              <dl>
                <dt><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/hw1.jpg" class="lotteryimg" /></dt>
                <dd>澳洲直购100g手工羊奶皂1个&nbsp;&nbsp;香味随机&nbsp;限量1000份</dd>
                <dd><a href="javascript:qiang()" class="btn_grab">限量抢</a></dd>
              </dl>
              <div class="cover_grab"  style="<?php if ($displayNone) echo 'display:none;';?>">
                <!--10点开抢 start-->
                <div class="ready_grab" style="<?php if ($time) echo 'display:none;';?>">
                  <h3>限量1000份</h3>
                  <p>10：00开抢</p>
                </div>
                <!--10点开抢 end-->
                <!--抢完了 start-->
                <div class="already_grab" style="<?php if ($oneYuanCode) {echo 'display:none;';}?>">
                  <p>不好意思！</p>
                  <p style="font-size:1.6rem;">海外正品魅力无边，已秒速抢光！</p>
                </div>
                <!--抢完了 end-->
              </div>
            </div>
            <!--限时抢 end-->
            <ul class="grap_word">
              <li>海外直购，全球精品汇！</li>
              <li>彩生活上市周年庆，安适购发来贺电！</li>
              <li>特惠精品，海外直发，100%正品保证！</li>
            </ul>
          </div>
          <!--热卖产品 start-->
          <dl class="content_hot">
            <dt>
              <a href="<?=$anshiHref?>" class="hot_more floatright">更多热卖</a>
              现在加入海购大军
            </dt>
            <dd>
              <ul class="clearfix">
                <li><a href="<?=$anshiHref?>&pid=1679"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/hw2.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$anshiHref?>&pid=1705"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/hw3.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$anshiHref?>&pid=1697"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/hw4.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$anshiHref?>&pid=1733"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/hw5.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$anshiHref?>&pid=1737"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/hw6.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$anshiHref?>&pid=1702"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/hw7.jpg" class="lotteryimg" /></a></li>
              </ul>
            </dd>
          </dl>
          <!--热卖产品 end-->
          <p class="botp" style="background:#fff; padding-top:1em; margin-top:0;">★注：彩之云享有本次活动的最终解释权 </p>  
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
                <p>您已经成功抢购过了，留点机会给其它小伙伴吧^_^。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div>
        </div>
      
        <div class="cd-popup alert3" role="alert">
            <div class="cd-popup-container">
                <p>恭喜你抢到1元换购码。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div> <!-- cd-popup-container -->
        </div> <!-- cd-popup -->
        
        <div class="cd-popup alert4" role="alert">
            <div class="cd-popup-container">
                <p>抢码失败。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div>
        </div>
        <div class="cd-popup alert5" role="alert">
            <div class="cd-popup-container">
                <p>已经抢完啦。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div>
        </div>
        <div class="cd-popup alert6" role="alert">
            <div class="cd-popup-container">
                <p>活动时间还没开始哦。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div>
        </div>
        <!--弹出框 end--> 
  </body>
        <script>
            function qiang(){
               $.ajax({
                 type: 'get',
                 url: '/thanksgivingActivity/anshiCode',
                 data: 'actid=11&flag=colourlife',
                 dataType: 'json',
                 async: false,
                 error: function () {
                    $('.alert4').addClass('is-visible');
                 },
                 success: function (json) {
                    if (json.suc == 2 && json.is_use == '0'){
                        //已经抢过了
                        location.href = "<?=$oneBuyHref?>&pid=9845";
                        return;
                    }else if(json.suc == 3){
                        location.href = "<?=$oneBuyHref?>&pid=9845";
                        return;
                    }
                    $('.alert' + json.suc).addClass('is-visible');
                    if (json.suc == 5) $('.cover_grab,.already_grab').show(); //显示抢完了
                 }
               });          
            }

            $().ready(function(){
                //close popup
                $('.alert1, .alert2, .alert3, .alert4, .alert5, .alert6').click(function(event){
                        if( $(event.target).is('.img-replace') || $(event.target).is('.cd-popup') ) {
                                event.preventDefault();
                                $(this).removeClass('is-visible');
                        }
                });
            });
        </script>

</html>