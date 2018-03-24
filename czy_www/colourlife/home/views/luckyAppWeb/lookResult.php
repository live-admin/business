<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />

<title>查看战绩</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/june/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>

</head>

<body>
<div class="lottery_topic_subweb topic_part3">
   <div class="webtitle">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/webtitle1.jpg'); ?>"/>
   </div>
   <div class="eight_mychoice" style="padding-bottom:50px;">
     
     <div class="eight_top">
       <p>您参与了 <span class="orangefont"><?php echo $customerTotal; ?></span>场竞猜，
           猜对 <span class="orangefont"><?php echo $customerStatistics; ?></span>场！</p>
       <p class="greenfont">根据您的竞猜战绩，距离"<?php echo $redPacket; ?>元"红包还需猜中<?php echo $lack; ?>场比赛，加油哦！猜对越多奖励越高！</p>
     </div>
     <dl class="lookresult">
       <?php foreach($customerTotalRecord as $record){ ?>  
       <dd>
         <p>
        <?php if($record->encounter_id<=48){ ?>
        小组赛<?php echo $record->encounter->team_code?$record->encounter->team_code->group:""; ?>组
        <?php }else if($record->encounter_id>48 && $record->encounter_id<=56){ ?>
        1/8决赛
        <?php }else if($record->encounter_id>56 && $record->encounter_id<=60){ ?>
        1/4决赛
        <?php }else if($record->encounter_id>60 && $record->encounter_id<=62){ ?>
        半决赛
        <?php }else if($record->encounter_id==63){ ?>
        三四名决赛
        <?php }else if($record->encounter_id==64){ ?>
        决赛
        <?php } ?>
        （<?php echo date("m月d日 H:i",strtotime($record->encounter->start_time)); ?>)</p>
         <h3><?php echo $record->encounter->HomeTeam; ?> &nbsp;
       <?php if($record->encounter->outcome == 1){ ?>胜<?php }else if($record->encounter->outcome == 2){ ?>负<?php }else if($record->encounter->outcome == 3){ ?>平<?php }else{ ?>VS<?php } ?>
            &nbsp; <?php echo $record->encounter->GuestTeam; ?></h3>
         <p>您选择了<span> <?php echo $record->encounter->HomeTeam; ?>  
        <?php if($record->myoutcome == 1){ ?>胜<?php }else if($record->myoutcome == 2){ ?>负<?php }else if($record->myoutcome == 3){ ?>平<?php }else{ ?>VS<?php } ?>
               <?php echo $record->encounter->GuestTeam; ?></span>，
             <?php if(!$record->encounter->outcome){ ?>
                <span class="orangefont">请等待比赛结果！</span></p>
             <?php }else if($record->myoutcome == $record->encounter->outcome){ ?>
                <span class="orangefont">恭喜您猜对了！</span></p>
             <?php }else{ ?>
                <span class="orangefont">很遗憾您猜错了！</span></p>
             <?php } ?>   
       </dd>
       <?php } ?>
     </dl>
     <div class="btn_div">
       <a href="/luckyAppWeb">返回</a>
       
     </div>

   </div>
   

 
  
</div>

<script type="text/javascript"> 

</script> 
</body>
</html>
