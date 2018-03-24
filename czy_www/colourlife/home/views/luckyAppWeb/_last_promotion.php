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
         <?php

         $date=date("Y-m-d H:i:s");
         //$date='2014-06-28 00:00:00';
         if($date<'2014-07-02 12:00:00'){
             $pid=2;
             $title='<span style="float:right; margin-top:15px;">竞猜时间：6月27日-6月28日</span>
          谁会进入8强？<span>点击国旗，每组2选1，选出您心中的8强</span>';
         }elseif($date<'2014-07-06 12:00:00'){
             $pid=3;
             $title='<span style="float:right; margin-top:15px;">竞猜时间：7月2日-7月5日</span>
          谁会进入4强？<span>点击国旗，每组2选1，选出您心中的4强</span>';
         }else{
             $pid=4;
             $title='<span style="float:right; margin-top:15px;">竞猜时间：7月6日-7月9日</span>
          谁会进入决赛？<span>点击国旗，每组2选1，选出您心中的决赛</span>';
         }

         ?>
         <h3>
          <?php echo $title;?>
         </h3>

         <?php

         $find = CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>Yii::app()->user->id,':pid'=>$pid));

         $model = CustomerPromotion::model();
         $content='';
         if(!$find){
             $updateLabel = '提交我的竞猜';
             $updateId = 'submit_guess';
             foreach($model->getTeamsCode($pid) as $key=>$code){
                 $team = $model->getTeamModel($code);
                 if($key%2==0){
                     $content.='
                <dl class="clearfix">
                 <dd class="elimtn_left">
                   <div class="qi_imgbox">
                     <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
                   </div>
                   <p>'.$team->team.'</p>
                   <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$team->code.'"/>
                   <div class="check_box">
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                   </div>
                 </dd>
            ';
                 }else{
                     $content.='
                <dt><img src="'.F::getStaticsUrl('/common/images/lucky/june/vs.png').'" class="lotteryimg"  /></dt>
                 <dd class="elimtn_right">
                   <div class="qi_imgbox">
                     <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
                   </div>
                   <div class="check_box">
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                   </div>
                   <p>'.$team->team.'</p>
                   <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$team->code.'"/>
                 </dd>
               </dl>
            ';
                 }
             }

         }else{
             $change = '
        <div class="modifys">您已经选择了<span class="orangefont">'.$find->teams_promotion->type.'</span>竞猜结果，您可以更改3次，已经修改'.$find->update_times.'次</div>
     ';
             $updateLabel = '修改我的竞猜';
             $updateId = 'update_guess';

             $myguess=explode(",",$find->my_quiz_teams);

             foreach($model->getTeamsCode($pid) as $key=>$code){
                 $team = $model->getTeamModel($code);
                 $check=in_array($code,$myguess);
                 if($key%2==0){
                     $content.='
                <dl class="clearfix">
                 <dd class="elimtn_left '.($check?"elimchecked":" ").'">
                   <div class="qi_imgbox">
                     <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
                   </div>
                   <p>'.$team->team.'</p>
                   <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$team->code.'" '.($check?"checked":" ").'/>
                   <div class="check_box">
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                   </div>
                 </dd>
            ';
                 }else{
                     $content.='
                <dt><img src="'.F::getStaticsUrl('/common/images/lucky/june/vs.png').'" class="lotteryimg"  /></dt>
                 <dd class="elimtn_right '.($check?"elimchecked":" ").'">
                   <div class="qi_imgbox">
                     <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
                   </div>
                   <div class="check_box">
                     <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg checked_img"  />
                   </div>
                   <p>'.$team->team.'</p>
                   <input style="display:none" class="check_guess" type=checkbox name=checkguess[]  value="'.$team->code.'" '.($check?"checked":" ").'/>
                 </dd>
               </dl>
            ';
                 }
             }



         }

         $activeTime=SetTeamsPromotion::model()->findByPk($pid);
         if($date<$activeTime->start_time || $date>$activeTime->end_time){
            $change='<div class="modifys">本轮活动已结束，请等待下一轮！</div>';
         }


         ?>


       <div class="mychoice elimination">
           <?php echo $content;?>
       </div>


       </div>
     <?php if(isset($change))echo $change;?>
         <div><input type="hidden" id="promotion_id" value="<?php echo $pid;?>" /></div>
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
        var team= $('.elimination dd').length/2;
        var code=new Array();
        for(i=j=0;i<len;i++){
            if(check.eq(i).attr("checked")){
                code[j++]=check.eq(i).val();
            }
        }
        if(code.length!=team){
            alert('请选择'+team+'支队伍！');
        }else{
            if(window.confirm('您已经成功选择了'+team+'支队伍，现在就要提交吗？')){
            var pid = $('#promotion_id').val();
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
        var team= $('.elimination dd').length/2;
        var code=new Array();
        for(i=j=0;i<len;i++){
            if(check.eq(i).attr("checked")){
                code[j++]=check.eq(i).val();
            }
        }
        if(code.length!=team){
            alert('请选择'+team+'支队伍！！');
        }else{
            if(window.confirm('您已经成功选择了'+team+'支队伍，现在就要提交吗？')){
            var pid = $('#promotion_id').val();
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
                        alert('不在活动时间！请等待下一轮！！');
                }
                ,'json');
            }
        }
    }
);
</script>