<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>630嘿客</title>
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
                <dt><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf1.jpg" class="lotteryimg" /></dt>
                <dd>胡姬花&nbsp;古法小榨花生油&nbsp;158ml&nbsp;&nbsp;限量1000份</dd>
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
                <div class="already_grab" style="<?php if ($over) {echo 'display:none;';}?>">
                  <p>不好意思！</p>
                  <p style="font-size:1.6rem">古法花生油香飘万里，已秒速抢光！</P>
                </div>
                <!--抢完了 end-->
              </div>
            </div>
            <!--限时抢 end-->
            <ul class="grap_word">
              <li>嘿客出没，准备购！</li>
              <li>彩生活上市周年庆，嘿客商城发来贺电！</li>
              <li>现金抵用券+丰富商品，一起买买买，不要停！</li>
            </ul>
          </div>
          <dl class="content_hot">
            <dd>
              <ul class="clearfix">
                <li><a href="<?=$marketurl?>&pid=7608"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf2.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$marketurl?>&pid=7609"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf3.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$marketurl?>&pid=1932"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf4.jpg" class="lotteryimg" /></a></li>
              </ul>
            </dd>
          </dl>
          <!--热卖产品 start-->
          <dl class="content_hot">
            <dt>
              <a href="<?=$sfurl?>" class="hot_more floatright">更多热卖</a>
              现在冲入抢购队伍  
            </dt>
            <dd>
              <ul class="clearfix">
                <li><a href="<?=$sfurl?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf5.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$sfurl?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf6.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$sfurl?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf7.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$sfurl?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf8.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$sfurl?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf9.jpg" class="lotteryimg" /></a></li>
                <li><a href="<?=$sfurl?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/sf10.jpg" class="lotteryimg" /></a></li>
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
                <p>您已领取过换购码了。</p>
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
                <p>不在活动范围内。</p>
                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
            </div>
        </div>
        <!--弹出框 end-->
    </body>
        <script>
            function qiang(){
               location.href = "<?=$sfurl?>";
               return;
               $.ajax({
                 type: 'get',
                 url: '/thanksgivingActivity/sfCode',
                 data: 'actid=11&flag=colourlife',
                 dataType: 'json',
                 async: false,
                 error: function () {
                    $('.alert4').addClass('is-visible');
                 },
                 success: function (json) {
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
