<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
<title>抽奖</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/web/lotterymobile.css'); ?>" rel="stylesheet">



</head>

<body>
<div class="lotteryWeb">
   <p class="part1">
     您还剩余 <?php echo $luckyCustCan;?> 次抽奖机会
   </p>
   <div class="part2">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/web/content.jpg'); ?>"/>
   </div>
   <div class="part3">
     <a href="/luckyAppWeb/mylottery" class="botbutton"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/web/button.jpg'); ?>" /></a>
   </div>
   
   
</div>

</body>
</html>
