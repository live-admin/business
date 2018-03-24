<?php 
    $customerStatistics = CustomerOutcome::model()->getCustomerStatistics($custId);   //业主猜中的次数 

?>
<div class="topic_part3">
   <div class="webtitle">
     <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/webtitle1.jpg'); ?>"/>
   </div>
   <!--小组赛 start-->       
   <div class="guess_outcome" style="display:block;">
     <div class="eight_top">
       <p class="greenfont" style="margin-top:0;">您猜对了<span class="orangefont"><?php echo $customerStatistics; ?></span>场比赛，猜对越多奖励越多！</p>
       <div class="greenline"></div>
     </div>
     <?php $z = 0; ?>  
     <?php $i = 1; ?>     
     <?php foreach($encounters as $encounter){ ?>  
     <dl>
       <dt>
         <span>比赛时间：<?php echo date("m月d日 H:i",strtotime($encounter->start_time)); ?></span>
         <p><?php if($encounter->game_number<=48){ ?>
               小组赛<?php echo $encounter->team_code?$encounter->team_code->group:""; ?>组
               <?php }else if($encounter->game_number>48 && $encounter->game_number<=56){ ?>
               1/8决赛
               <?php }else if($encounter->game_number>56 && $encounter->game_number<=60){ ?>
               1/4决赛
               <?php }else if($encounter->game_number>61 && $encounter->game_number<=62){ ?>
               半决赛
               <?php }else if($encounter->game_number==63){ ?>
               三四名决赛
               <?php }else if($encounter->game_number==64){ ?>
               决赛
               <?php } ?>
             （竞猜截止时间：<?php echo date("m月d日 H:i",strtotime($encounter->end_quiz)); ?>）</p>
         <p style="color:red; font-size: 12px;">（温馨提示：开奖结果不包含加时赛及点球大战）</p>
       </dt>
       <dd>
         <div class="home">
           <img src="<?php echo $encounter->HomeTeamLogo; ?>" />
           <p><?php echo $encounter->HomeTeam; ?></p>
         </div>
           <div class="go_content">
           <span class="check_box" words="胜" encounter_number="<?php echo $encounter->id; ?>">
               <?php if($arr_outcome[$z]['myoutcome'] == ""){ ?>  
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg"  />
               <?php }else if($arr_outcome[$z]['myoutcome'] == 1){ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  style="display:none;" />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg" style="display:block;"  />
               <?php }else{ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg" />
               <?php } ?>     
               胜
             </span>
             <span class="check_box middle_cb" words="平" encounter_number="<?php echo $encounter->id; ?>">
               <?php if($arr_outcome[$z]['myoutcome'] == ""){ ?>    
               <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />
               <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg"  />
               <?php }else if($arr_outcome[$z]['myoutcome'] == 3){ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  style="display:none;" />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg" style="display:block;"  />
               <?php }else{ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg"  />
               <?php } ?>     
               平
             </span>
             <span class="check_box" words="负" encounter_number="<?php echo $encounter->id; ?>">
               <?php if($arr_outcome[$z]['myoutcome'] == ""){ ?>  
               <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />
               <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg"  />
               <?php }else if($arr_outcome[$z]['myoutcome'] == 2){ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  style="display:none;" />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg" style="display:block;"  />
               <?php }else{ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg"  />
               <?php } ?>
               胜
             </span>   
           <!--业主竞猜的结果-->
           <input type="hidden" class="customer_outcome" name="my_outcome<?php echo $i; ?>" id="customer_outcome<?php echo $encounter->id ?>" value="">
           <input type="hidden" class="word_cup" name="word_cup<?php echo $i; ?>" id="the_world_cup<?php echo $encounter->id; ?>" value="<?php echo $encounter->id; ?>">
           </div>
         <div class="guest">
           <img src="<?php echo $encounter->GuestTeamLogo; ?>"  />
           <p><?php echo $encounter->GuestTeam; ?></p>
         </div>
         <div class="myguess greenfont">
           <p style="<?php  if($arr_outcome[$z]['myoutcome'] == ""){ ?>display:block<?php }else{ ?>display:none<?php } ?>">您还未参与本场次竞猜</p>
            <p class="guess_p" style="<?php  if($arr_outcome[$z]['myoutcome'] == ""){ ?>display:none<?php }else{ ?>display:block<?php } ?>">
              您成功选择了<span><?php echo $encounter->HomeTeam; ?></span>
                    <?php if($arr_outcome[$z]['myoutcome'] == 1){ ?>
                     <span class="guess_word">胜</span>
                    <?php }else if($arr_outcome[$z]['myoutcome'] == 2){ ?>
                     <span class="guess_word">负</span>
                    <?php }else{ ?>
                     <span class="guess_word">平</span>
                    <?php } ?>
                     <span><?php echo $encounter->GuestTeam; ?></span>
            </p>
         </div>
       </dd>
     </dl>
     <?php  $i++;
               $z++;
       ?>  
     <?php } ?>  
       
   </div>
   <!--小组赛 end-->  
   <div class="btn_div">
       
     <!--<a href="javascript:void();" id="editMyOutcome" target="_blank">修改我的今日竞猜</a>-->
     <a href="/luckyAppWeb/lookResult" target="_blank">查看我的竞猜战绩</a>
     <a href="/luckyAppWeb/worldCupRule#one" target="_blank">查看活动规则</a>
   </div>

 </div>