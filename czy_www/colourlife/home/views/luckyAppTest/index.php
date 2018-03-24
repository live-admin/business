<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=yes;" />
<title>感恩大抽奖</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/july/lottery.css'); ?>" rel="stylesheet">
</head>

<body style=" background:url(<?php echo F::getStaticsUrl('/common/images/lucky/july/indexback.jpg'); ?>) center 0 repeat;">
<div class="lottery_topic">
  <div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/indextop.jpg'); ?>"  class="lotteryimg"/></div>
  <div class="lotteryindex">
    <div class="lotteryitem">
      <span class="indextt">
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/indextt1.png'); ?>" class="lotteryimg" />
      </span>
      <span class="indexword">
        <i>感恩回馈一</i>
        邀请好友送红包, 红包无上限
      </span>
      <a href="/invite">&gt;&gt;&nbsp;立即邀请</a>
    </div>
    <div class="lotteryitem">
      <span class="indextt">
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/indextt2.png'); ?>" class="lotteryimg"  />
      </span>
      <span class="indexword">
        <i>感恩回馈二</i>
        投资E理财，坐享10%收益
      </span>
      <a href="<?php echo $licaiyiUrl; ?>" class="">&gt;&gt;&nbsp;立即理财</a>
    </div>
    <div class="lotteryitem" style="border:none;">
      <span class="indextt">
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/indextt3.png'); ?>" class="lotteryimg"  />
      </span>
      <span class="indexword">
        <i>感恩回馈三</i>
        天天抽大奖，红包抢不停
      </span>
      <a href="/luckyApp/luckyApp" class="">&gt;&gt;&nbsp;立即抽奖</a>
    </div>
  </div>
    
</div>
<script type="text/javascript">
  var h=$(document).height();
  $('body').css('min-height',h);
</script>
</body>
</html>
