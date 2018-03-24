<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />

<title>抽奖</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/june/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>

</head>

<body>
<div class="lottery_topic">
   <div class="worldcup">
     <div class="wc_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/worldcup_head.jpg'); ?>" class="lotteryimg" /></div>
     
     <div class="wc_content">
       <dl class="guessbtn_box">
         <dd>
           <a href="/luckyApp/guessOutcome"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/guessbtn1.png'); ?>" class="lotteryimg"  /></a>
           <span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/word1.png'); ?>" class="lotteryimg" /></span>
         </dd>
         <dd style="margin-bottom:5px;">
           <a href="/worldCupPromotion"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/guessbtn2.png'); ?>" class="lotteryimg"  /></a>
           <span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/word2.png'); ?>" class="lotteryimg"  /></span>
         </dd>
         <dd>
           <a href="/worldCupPromotion/index/winner"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/guessbtn3.png'); ?>" class="lotteryimg"  /></a>
           <span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/word3.png'); ?>" class="lotteryimg"  /></span>
         </dd>
         
         <div class="submitchoice">
       <a href="/luckyApp/lookAllResult" class="rulebtn">查看竞猜战绩</a>        
       <a href="/luckyApp/worldCupRule" class="rulebtn">查看竞猜规则</a>  
       <a href="/luckyApp" class="rulebtn">返回</a>       
     </div>
       </dl>
         
      
     </div>
     

   </div>
   

  
  
</div>


</body>
</html>
