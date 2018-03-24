     <h3 class="bigword guessinnerbox">
        谁能晋级
        <div class="guesslist clearfix">
          <dl class="guesslist_selected">
            <dt>猜<span>16</span>强</dt>
            <dd>6月12日-18日</dd>
          </dl>
          <dl>
            <dt>猜<span>8</span>强</dt>
            <dd>6月27日-6月28日</dd>
          </dl>
          <dl>
            <dt>猜<span>4</span>强</dt>
            <dd>7月2日-5日</dd>
          </dl>
          <dl>
            <dt style="font-weight:bold; font-size:18px">猜决赛</dt>
            <dd>7月6日-9日</dd>
          </dl>
          <dl>
            <dt style="font-weight:bold; font-size:18px">猜冠军</dt>
            <dd>6月12日-28日</dd>
          </dl>
        </div>
      </h3>
     <!--谁能晋级 start--> 
     <div class="topic_part4" id="go_promotion">
       <div class="webtitle">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/webtitle2.jpg'); ?>"/>
       </div>
       <div class="part4_content">
         <h3>
          <span style="float:right; margin-top:15px;">竞猜时间：6月12日—6月18日</span>
          谁会进入16强？<span>点击国旗，每组4选2，选出您心中的16强</span>
         </h3>
         <dl class="guess_promotion clearfix">
         <?php

         $find = CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>Yii::app()->user->id,':pid'=>1));

         $model = CustomerPromotion::model();
         $groupModel = $model->getGroupTeam();
         $content='';
         if(!$find){

             $updateLabel = '提交我的竞猜';
             $updateId = 'submit_guess';
             foreach($groupModel as $group=>$teamModel){
                 $content .='
            <dd>
             <div class="group_game">
               <ul class="groupleft">
                 <li class="team_left">
                   <div class="check_box">
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                   </div>
                   <div class="check_qi">
                     <img src="'.$teamModel[0]->HomeTeamLogo.'" class="lotteryimg"  />
                   </div>
                   <p>'.$teamModel[0]->team.'</p>
                   <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$teamModel[0]->code.'"/>
                 </li>
                 <li class="team_right">
                   <div class="check_box">
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                   </div>
                   <div class="check_qi">
                     <img src="'.$teamModel[1]->HomeTeamLogo.'" class="lotteryimg"  />
                   </div>
                   <p>'.$teamModel[1]->team.'</p>
                   <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$teamModel[1]->code.'"/>
                 </li>
               </ul>
               <ul class="groupright">
                 <li class="team_left">
                   <div class="check_box">
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                   </div>
                   <div class="check_qi">
                     <img src="'.$teamModel[2]->HomeTeamLogo.'" class="lotteryimg"  />
                   </div>
                   <p>'.$teamModel[2]->team.'</p>
                   <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$teamModel[2]->code.'"/>
                 </li>
                 <li class="team_right">
                   <div class="check_box">
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                   </div>
                   <div class="check_qi">
                     <img src="'.$teamModel[3]->HomeTeamLogo.'" class="lotteryimg"  />
                   </div>
                   <p>'.$teamModel[3]->team.'</p>
                   <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$teamModel[3]->code.'"/>
                 </li>
               </ul>
               <div class="clr"></div>
               <span class="group_class">
                 <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back5.png').'" class="lotteryimg checked_img"  />
                 <span>'.$group.'组</span>
               </span>
             </div>
           </dd>
                 ';

             }
         }else{
             $change = '
        <div class="modifys">您已经选择了<span class="orangefont">16强</span>竞猜结果，您可以更改3次，已经修改'.$find->update_times.'次</div>
     ';
             $updateLabel = '修改我的竞猜';
             $updateId = 'update_guess';

             $myguess=explode(",",$find->my_quiz_teams);

             foreach($groupModel as $group=>$teamModel){
                 $check[0]=in_array($teamModel[0]->code,$myguess);
                 $check[1]=in_array($teamModel[1]->code,$myguess);
                 $check[2]=in_array($teamModel[2]->code,$myguess);
                 $check[3]=in_array($teamModel[3]->code,$myguess);

                 $content .= '
                    <dd>
           <div class="group_game">
             <ul class="groupleft">

               <li class="team_left '.($check[0]?"lichecked":" ").'">
                 <div class="check_box '.($check[0]?"checked_box":" ").'">
                   <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                   <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                 </div>
                 <div class="check_qi '.($check[0]?"checked_qi":" ").'">
                   <img src="'.$teamModel[0]->HomeTeamLogo.'" class="lotteryimg"  />
                 </div>
                 <p>'.$teamModel[0]->team.'</p>
                 <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$teamModel[0]->code.'" '.($check[0]?"checked":" ").'/>
               </li>

               <li class="team_right '.($check[1]?"lichecked":" ").'">
                 <div class="check_box '.($check[1]?"checked_box":" ").'">
                   <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                   <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                 </div>
                 <div class="check_qi '.($check[1]?"checked_qi":" ").'">
                   <img src="'.$teamModel[1]->HomeTeamLogo.'" class="lotteryimg"  />
                 </div>
                 <p>'.$teamModel[1]->team.'</p>
                 <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$teamModel[1]->code.'" '.($check[1]?"checked":" ").'/>
               </li>

             </ul>
             <ul class="groupright">

               <li class="team_left '.($check[2]?"lichecked":" ").'">
                 <div class="check_box '.($check[2]?"checked_box":" ").'">
                   <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                   <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                 </div>
                 <div class="check_qi '.($check[2]?"checked_qi":" ").'">
                   <img src="'.$teamModel[2]->HomeTeamLogo.'" class="lotteryimg"  />
                 </div>
                 <p>'.$teamModel[2]->team.'</p>
                 <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$teamModel[2]->code.'" '.($check[2]?"checked":" ").'/>
               </li>
               <li class="team_right '.($check[3]?"lichecked":" ").'">
                 <div class="check_box '.($check[3]?"checked_box":" ").'">
                   <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back4.png').'" class="lotteryimg uncheck_img"  />
                   <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                 </div>
                 <div class="check_qi '.($check[3]?"checked_qi":" ").'">
                   <img src="'.$teamModel[3]->HomeTeamLogo.'" class="lotteryimg"  />
                 </div>
                 <p>'.$teamModel[3]->team.'</p>
                 <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$teamModel[3]->code.'" '.($check[3]?"checked":" ").'/>
               </li>
             </ul>
             <div class="clr"></div>
             <span class="group_class">
               <img src="'.F::getStaticsUrl('/common/images/lucky/june/qi_back5.png').'" class="lotteryimg checked_img"  />
               <span>'.$group.'组</span>
             </span>
           </div>
         </dd>
                        ';

             }



         }

         $date=date("Y-m-d H:i:s");
         $pid=1;
         $activeTime=SetTeamsPromotion::model()->findByPk($pid);
         if($date<$activeTime->start_time || $date>$activeTime->end_time){
            $change='<div class="modifys">本轮活动已结束，请等待下一轮！</div>>';
         }

         echo $content;

         ?>


         </dl>

       </div>
     <?php if(isset($change))echo $change;?>
       <div class="btn_div">
           <a href="javascript:void(0);" id="<?php echo $updateId;?>" ><?php echo $updateLabel;?></a>
         <a href="/luckyAppWeb/myPromotionGuess">查看晋级竞猜战绩</a>
         <a href="/luckyAppWeb/worldCupRule#two">查看活动规则</a>
       </div>
     </div>
     <!--谁能晋级 end-->

<script type="text/javascript">
//提交竞猜
$('#submit_guess').click(
    function (){
        var check = $("input[type='checkbox']");
        var len=check.length;
        var code=new Array();
        for(i=j=0;i<len;i++){
            if(check.eq(i).attr("checked")){
                code[j++]=check.eq(i).val();
            }
        }
        if(code.length!=16){
            alert('请选择16支队伍！');
        }else{
            if(window.confirm('您已经成功选择了16强队伍，现在就要提交吗？')){
                var pid = 1;
                $.post(
                    '/worldCupPromotionWeb/submitGuess',
                    {'code':code,'pid':pid},
                    function (data){
                        if(data == 1){
                            location.reload();
                            alert('竞猜成功！');
                        }else{
                            alert('不在活动时间！请等待下一轮！');
                        }
                    }
                    ,'json');
            }
        }
    }
);
//修改竞猜
$('#update_guess').click(
    function (){
        var check = $("input[type='checkbox']");
        var len=check.length;
        var code=new Array();
        for(i=j=0;i<len;i++){
            if(check.eq(i).attr("checked")){
                code[j++]=check.eq(i).val();
            }
        }
        if(code.length!=16){
            alert('请选择16支队伍！');
        }else{
            if(window.confirm('您确认要修改您已选择的16强队伍？只能修改3次哦。')){
                var pid = 1;
                $.post(
                    '/worldCupPromotionWeb/updateGuess',
                    {'code':code,'pid':pid},
                    function (data){
                        if(data == 1){
                            location.reload();
                            alert('竞猜修改成功！');
                        }
                        if(data == 2){
                            alert('超过修改次数！');
                        }
                        if(data == 0)
                            alert('不在活动时间！请等待下一轮！');
                    }
                    ,'json');
            }
        }
    }
);
</script>