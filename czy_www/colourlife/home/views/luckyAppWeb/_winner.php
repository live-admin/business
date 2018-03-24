     <h3 class="bigword">
        谁是王者
     </h3>
     <!--谁是王者 start--> 
     <div class="topic_part5"  id="go_winner">
       <div class="webtitle">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/webtitle3.jpg'); ?>"/>
       </div>

      <?php

          $find = CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>Yii::app()->user->id,':pid'=>5));

          $model = CustomerPromotion::model();
          $promotionid=5;
          $content='';
          if(!$find){

                 $updateLabel = '提交我的竞猜';
                 $updateId = 'submit_winner';

                 $content.='
                    <div class="part5_content the_winner">
                 <dl class="clearfix">
                ';
                 foreach($model->getTeamsCode($promotionid) as $key=>$code){
                     $team = $model->getTeamModel($code);
                     $content .= '
             <dd>
               <div class="check_box right_wrong">
                 <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                 <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
               </div>
               <div class="check_qi qi_imgbox">
                 <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
               </div>
               <p class="choicename">'.$team->team.'</p>
               <p class="choicecode" style="display:none;">'.$team->code.'</p>
             </dd>
                    ';
                  if(in_array($key,array(7,15,23)))
                      $content .='
                      </dl>
                        <p class="spr"></p>
                     <dl class="clearfix">
                      ';
                 }
              $content.='
                </dl>
            </div>
           ';

             }else{
                $myguess=explode(",",$find->my_quiz_teams);

                 $change = '
        <div class="modifys" style="margin-bottom:20px">您心目中的王者之师是<span class="orangefont">'.$find->getCodeName($myguess[0]).'队</span>，您可以更改3次，已经修改'.$find->update_times.'次</div>
    ';
                 $updateLabel = '修改我的竞猜';
                 $updateId = 'update_winner';

                 $content.='
                    <div class="part5_content the_winner">
                 <dl class="clearfix">
                ';
                 foreach($find->getTeamsCode($promotionid) as $key=>$code){
                     $team = $find->getTeamModel($code);
                     $content .= '
             <dd>
               <div class="check_box right_wrong '.(in_array($code,$myguess)?"checked_box":" ").'">
                 <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                 <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
               </div>
               <div class="check_qi qi_imgbox '.(in_array($code,$myguess)?"checked_qi":" ").'">
                 <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
               </div>
               <p class="choicename">'.$team->team.'</p>
               <p class="choicecode" style="display:none;">'.$team->code.'</p>
             </dd>
            ';
                 if(in_array($key,array(7,15,23)))
                     $content .='
                  </dl>
                    <p class="spr"></p>
                 <dl class="clearfix">
                  ';
                 }
              $content.='
                </dl>
            </div>
           ';

             }

            echo $content;

            if(isset($change))echo $change;

             ?>

     <div class="btn_div">
         <div><input type="hidden" id="winner_val" value="<?php echo (!$find)?"":$myguess[0];?>" /></div>
         <a href="javascript:void(0);" id="<?php echo $updateId;?>" ><?php echo $updateLabel;?></a>
         <a href="/luckyAppWeb/myWinnerGuess">查看王者竞猜战绩</a>
         <a href="/luckyAppWeb/worldCupRule#three">查看活动规则</a>
     </div>


     </div>
     <!--谁是王者 end-->


<script type="text/javascript">
//提交王者竞猜
$('#submit_winner').click(
 function (){
     var code = $('#winner_val').val();
     if(code.length>0){
         if(window.confirm('您已经成功选择了冠军队伍，现在就要提交吗？')){
         $.post(
             '/worldCupPromotionWeb/submitWinner',
             {'code':code},
             function (data){
                 if(data == 1){
                     location.reload();
                     alert('竞猜成功！');
                 }else{
                     alert('超过活动时间！');
                 }
             }
             ,'json');
         }
     }else{
         alert('请选择队伍！');
     }
 }
);
//修改王者竞猜
$('#update_winner').click(
 function (){
     var code = $('#winner_val').val();
     if(code.length>0){
         if(window.confirm('您确认要修改您已选择的冠军队伍？只能修改3次哦。')){
         $.post(
             '/worldCupPromotionWeb/updateWinner',
             {'code':code},
             function (data){
                 if(data == 1){
                     location.reload();
                     alert('竞猜修改成功！');
                 }
                 if(data == 2){
                     alert('超过修改次数！');
                 }
                 if(data == 0)
                     alert('超过活动时间！');
             }
             ,'json');
         }
     }else{
         alert('请选择队伍！');
     }
 }
);
 </script>