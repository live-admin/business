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
   <div class="look_all_result">
     
     <h3 style="font-size:32px;">我的竞猜战绩</h3>
     <div class="greenline" style="margin-bottom:60px;"></div>
     
     <!--竞猜胜负 start-->
     <div class="some_a_result">
       <div class="result_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/result_head1.jpg'); ?>" class="lotteryimg" /> </div>
       <div class="some_a_result_content">
         <p style="font-size:18px;">您参与了<span class="orangefont"><?php echo $customerTotal; ?></span>
             场竞猜，猜对<span class="orangefont"><?php echo $customerStatistics; ?></span>场！</p>
         <p class="greenfont">根据您的竞猜战绩，距离"<?php echo $redPacket; ?>元"红包还差<?php echo $lack; ?>场比赛，加油哦！猜对越多奖励越高！</p>
         <div class="caipiao_box">
           <a href="/luckyAppWeb/lookAllResultWeb?all=all" class="rulebtn">查看详情</a>
         </div>
         <table class="game_info">
           <thead>
             <tr>
               <th style="width:15%">赛制</th>
               <th style="width:34%">比赛时间</th>
               <th style="width:33%">比赛结果</th>
               <th style="width:18%">竞猜情况</th>
             </tr>
           </thead>
           <tbody>
             <?php foreach($customerTotalRecord as $record){ ?>    
             <tr>
               <td>
                <?php if($record->encounter_id<=48){ ?>
                 小组赛
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
               </td>
               <td>
                 <?php echo $record->encounter->HomeTeam; ?><span class="orange">VS</span><?php echo $record->encounter->GuestTeam; ?><br />
                 <?php echo date("m月d日 H:i",strtotime($record->encounter->start_time)); ?>
               </td>
               <td>
                   <?php if($record->encounter->outcome == 1){ ?>
                        <?php echo $record->encounter->HomeTeam; ?><span class="orange">胜</span><?php echo $record->encounter->GuestTeam; ?>
                   <?php }else if($record->encounter->outcome == 2){ ?>
                        <?php echo $record->encounter->GuestTeam; ?><span class="orange">胜</span><?php echo $record->encounter->HomeTeam; ?>
                   <?php }else{ ?>
                        等待比赛结果
                   <?php } ?>     
               </td>
               <td>
                   <?php if($record->encounter->outcome>=1){ ?>
                        <?php if($record->myoutcome == $record->encounter->outcome){ ?>
                             猜对了
                        <?php }else{ ?>
                             猜错了
                        <?php } ?>
                   <?php }else{ ?>
                          未知   
                   <?php } ?>          
               </td>
             </tr>
             <?php } ?>
             
           </tbody>
         
         
         </table>
       </div>
     </div>
     <!--竞猜胜负 end-->
     <!--谁能晋级 start-->
     <div class="some_a_result">
         <div class="result_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/result_head2.jpg'); ?>" class="lotteryimg" /> </div>
         <div class="some_a_result_content">
             <?php

             if($promotionList){

                 foreach($promotionList as $guess){
                     echo '
             <p>'.$guess->teams_promotion->type.'竞猜</p>

         <table class="game_info">
           <thead>
             <tr>
               <th colspan="4">
                 <div style="padding:0 3%; text-align:left">您竞猜的'.$guess->teams_promotion->type.'为：</div>
               </th>
             </tr>
           </thead>
           <tbody>
             <tr>
               <td colspan="4">
                 <div style="padding:0 3%; text-align:left">
             ';

                     $teamsList = explode(",",$guess->my_quiz_teams);
                     $right = $guess->getRightTeams($guess->teams_promotion_id);
                     foreach($teamsList as $code){
                         $team = $guess->getTeamModel($code);
                         echo $team->team.' ';
                     }
                     echo '</div>
               </td>
             </tr>
           </tbody>
         </table>';

                     if($guess->is_right==CustomerPromotion::IS_RIGHT_UNKNOWN){
                         echo '
                <div>
                   <p class="greenfont">正在等待晋级结果：</p>
                   <p>竞猜开始时间：<span style="color:#ff7e00;">'.$guess->teams_promotion->start_time.'</span></p>
                   <p>竞猜结束时间：<span style="color:#ff7e00;">'.$guess->teams_promotion->end_time.'</span></p>
                   <p>竞猜正确奖励：<span style="color:red">'.$guess->teams_promotion->redpacket.'</span>元红包</p>
                 </div>
                ';
                     }else{
                         $result='';
                         foreach($right as $val){
                             $result.=$guess->getCodeName($val).' ';
                         }
                         if($guess->is_right==CustomerPromotion::IS_RIGHT_YES){
                             $award = '恭喜你猜中了<span style="color:#ff7e00;">'.$guess->teams_promotion->type.'</span>结果，获得了<span style="color:red">'.$guess->teams_promotion->redpacket.'</span>红包！';
                             if($guess->is_send==CustomerPromotion::IS_SEND_YES)
                                 $send='(红包已发放)';
                             else
                                 $send='(红包正在审核..)';
                         }elseif($guess->is_right==CustomerPromotion::IS_RIGHT_NO){
                             $award = '很遗憾您没有猜中本轮！';$send='';
                         }
                         echo '
                  <table class="game_info">
           <thead>
             <tr>
               <th colspan="4">
                 <div style="padding:0 3%; text-align:left">'.$guess->teams_promotion->type.'结果为：</div>
               </th>
             </tr>
           </thead>
           <tbody>
             <tr>
               <td colspan="4">
                 <div style="padding:0 3%; text-align:left">'.$result.'</div>
               </td>
             </tr>
           </tbody>
         </table>
         <p class="greenfont">'.$award.'</p>
         <p>'.$send.'</p>
                 ';
                     }
                 }
             }else{
                 echo '<br/><br/><p class="greenfont" style="text-align:center; ">您还没有参与谁能晋级竞猜！</p>';
             }


             ?>

         </div>
     </div>
     <!--谁能晋级 end-->
     <!--谁是王者 start-->
     <div class="some_a_result winner_result">
         <div class="result_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/result_head3.jpg'); ?>" class="lotteryimg" /> </div>

         <?php
         if($winnerList)
         {
             $guess = $winnerList;
             $teamsList = explode(",",$winnerList->my_quiz_teams);
             $code = $teamsList[0];
             $team = $guess->getTeamModel($code);
             echo '<div class="winner_content">
         <div class="qi_imgbox1">
           <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
         </div>
         <p class="greenfont">您心目中的王者之师是：<span>'.$team->team.'</span></p>
         <div class="clr"></div>
       </div>
         ';
             if($guess->is_right==CustomerPromotion::IS_RIGHT_UNKNOWN){
                 echo '
            <div class="some_a_result_content">
            <br/>
               <p class="greenfont">正在等待冠军赛结果：</p>
               <p>竞猜开始时间：<span style="color:#ff7e00;">'.$guess->teams_promotion->start_time.'</span></p>
               <p>竞猜结束时间：<span style="color:#ff7e00;">'.$guess->teams_promotion->end_time.'</span></p>
               <p>竞猜正确奖励：<span style="color:red">'.$guess->teams_promotion->redpacket.'</span>元红包</p>
             </div>
            ';
             }else{
                 $result='';
                 $right = $guess->getRightTeams($guess->teams_promotion_id);
                 foreach($right as $val){
                     $result.=$guess->getCodeName($val).' ';
                 }
                 if($guess->is_right==CustomerPromotion::IS_RIGHT_YES){
                     $award = '恭喜你猜中了<span style="color:#ff7e00;">'.$guess->teams_promotion->type.'</span>结果，获得了<span style="color:red">'.$guess->teams_promotion->redpacket.'</span>红包！';
                     if($guess->is_send==CustomerPromotion::IS_SEND_YES)
                         $send='(红包已发放)';
                     else
                         $send='(红包正在审核..)';
                 }elseif($guess->is_right==CustomerPromotion::IS_RIGHT_NO){
                     $award = '很遗憾您没有猜中本轮！';$send='';
                 }
                 echo '
            <div class="some_a_result_content">
            <br/>
               <p class="greenfont">实际'.$guess->teams_promotion->type.'结果：</p>
               <p>'.$result.'</p>
               <h3 class="greenfont">'.$award.'</h3>
               <p>'.$send.'</p>
             </div>
            ';
             }
         }else{
             echo '<br/><br/><p class="greenfont">您还没有参与谁是王者竞猜！</p>';
         }
         ?>

     </div>
     <!--谁是王者 end-->
     

   </div>
   
</div>

<script type="text/javascript"> 

</script> 
</body>
</html>
