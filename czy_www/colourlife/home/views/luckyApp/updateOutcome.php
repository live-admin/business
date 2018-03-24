<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
<title>我的竞猜选择</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/june/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
</head>
<body>
<div class="lottery_topic">
   <div class="eight_mychoice">
     <div class="eight_top">
       <h3 class="greenfont">今日竞猜场次</h3>
       <p class="greenfont" style="margin-top:0;">
           您猜对了<span class="orangefont">
           <?php echo $customerStatistics; ?>    
           </span>场比赛，猜对越多奖励越多！
       </p>
       <div class="greenline"></div>
     </div>
     <div class="guess_outcome">
       <?php $i = 1;$z=0; ?>   
       <?php foreach($encounters as $encounter){ ?>       
       <dl>         
         <dt>
           <p>
               <?php if($encounter->id<=48){ ?>
               小组赛<?php echo $encounter->team_code?$encounter->team_code->group:""; ?>组
               <?php }else if($encounter->id>48 && $encounter->id<=56){ ?>
               1/8决赛
               <?php }else if($encounter->id>56 && $encounter->id<=60){ ?>
               1/4决赛
               <?php }else if($encounter->id>60 && $encounter->id<=62){ ?>
               半决赛
               <?php }else if($encounter->id==63){ ?>
               三四名决赛
               <?php }else if($encounter->id==64){ ?>
               决赛
               <?php } ?>
               （竞猜截止时间：<?php echo date("m月d日 H:i",strtotime($encounter->end_quiz)); ?>）</p>
           <p>比赛时间：<?php echo date("m月d日 H:i",strtotime($encounter->start_time)); ?></p>
         </dt>
         <dd>
           <div class="home">
             <img src="<?php echo $encounter->HomeTeamLogo; ?>" />
             <p><?php echo $encounter->HomeTeam; ?></p>
           </div>
           <div class="go_content">
             <span class="check_box check_box1" words="胜" encounter_number="<?php echo $encounter->id; ?>">                
                <?php  if($arr_outcome[$z]['myoutcome'] == 1){ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg" style="display:block;"  />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  style="display:none;"/>
               <?php }else{ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg"  />
               <?php } ?>     
                <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back6.png'); ?>" class="undefine_img lotteryimg" style="display:none;" />
               胜
             </span>
             <span class="check_box check_box1 middle_cb" words="平" encounter_number="<?php echo $encounter->id; ?>">
               <?php  if($arr_outcome[$z]['myoutcome'] == 3){ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg" style="display:block;"  />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  style="display:none;"/>
               <?php }else{ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg"  />
               <?php } ?> 
               <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back6.png'); ?>" class="undefine_img lotteryimg" style="display:none;" />
               平
             </span>
             <span class="check_box check_box1" words="负" encounter_number="<?php echo $encounter->id; ?>">  
               <?php  if($arr_outcome[$z]['myoutcome'] == 2){ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg" style="display:block;"  />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  style="display:none;"/>
               <?php }else{ ?>
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back3.png'); ?>" class="uncheck_img lotteryimg"  />
                    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/selected.png'); ?>" class="checked_img lotteryimg"  />
               <?php } ?> 
               <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/qi_back6.png'); ?>" class="undefine_img lotteryimg" style="display:none;" />
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
                  您选择了<span><?php echo $encounter->HomeTeam; ?></span>
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
           <!--当前世界杯对阵Id-->
             
         </dd>         
       </dl>
       <?php  $i++;$z++;
       ?>  
       <?php } ?>  
     </div>
     <div class="submitchoice">
       <a href="/luckyApp/lookResult" class="rulebtn">查看我的竞猜战绩</a>  
       <a href="javascript:updateMyoutcome();" class="rulebtn">修改我的今日竞猜</a>
       <a href="/luckyApp/worldCupIndex" class="rulebtn">返回</a>
     </div>
    
    <?php $caiPiaoUrl=SmallLoans::model()->getGoucaiUrl(); ?>
     <?php if($caiPiaoUrl){ ?>
    <div class="caipiao_box">
       <p class="greenfont">还不过瘾？马上来买彩票吧，现在彩票充值50送20元哦！</p>
       <a href="<?php echo $caiPiaoUrl; ?>" class="rulebtn">马上购彩</a>
     </div>
     <?php } ?> 
   </div>
   

 
  <!--弹出框 start-->
 <div class="opacity" style="display:none;">
   <div class="alertcontairn" style="margin-top:60%;">
     <div class="alertcontairn_content">
       <div class="textinfo">
         <p> 
          选择失败，请点击确定按钮重试<br />
         </p>
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity"><span>确定</span></a>
       </div>
     </div>
   </div>
 </div>
<!--弹出框 end-->  
</div>

<script type="text/javascript"> 
  $('.check_box').live('click',function(){
	var myguess=$(this).parents('dd').find('.myguess');
	$(this).addClass('checked_box');
	$(this).siblings('.check_box').removeClass('checked_box'); 
        
	myguess.find('.guess_word').text($(this).attr('words'));
	myguess.children('.guess_p').show();
	myguess.children('.guess_p').siblings('p').hide();
        if($(this).attr('words')=="胜"){
            $(this).parent('div').find('.customer_outcome').val(1);            
        }else if($(this).attr('words')=="负"){
            $(this).parent('div').find('.customer_outcome').val(2);
        }else{
            $(this).parent('div').find('.customer_outcome').val(3);
        }
        $(this).siblings('.check_box').find('img').hide();
        $(this).siblings('.check_box').find('.undefine_img').show();
        $(this).siblings('span').removeClass('check_box');
        var encounter_game = $(this).parent('div').find('.word_cup').val();    //世界杯对阵表Id 
        var my_outcome = $("#customer_outcome"+encounter_game).val();   //业主竞猜的结果
        $.post(
            '/luckyApp/myoutcome',
            {'encounter_game':encounter_game,'my_outcome':my_outcome},
            function (data){
                if(data.code === 1){
                    
                }else{
                    $('.opacity').show();
                    var alertheight=$(".alertcontairn").height()/2;
                    alertpos=e.pageY-alertheight;
                    $(".alertcontairn").css('marginTop',alertpos+'px');
                }
            }
        ,'json');        
  })
  
  $('.closeOpacity').click(function(){      
    $('.opacity').hide();
    location.reload();
  }); 
  
  function updateMyoutcome(){
      location.href = "/luckyApp/updateOutcome";
  }
</script> 
</body>
</html>
