
<?php

$groupModel=$model->getGroupTeam();
$content='';
if($model->isNewRecord){
    echo '
        <div class="modifys">竞猜正确获<span style="color:red;font-size: 14px;">188.00</span>元红包 !</div>
        ';
    $updateLabel = '提交我的竞猜';
    $updateId = 'submit_guess';
    foreach($groupModel as $group=>$teamModel){

            $content .= '
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
         <p class="spr"></p>
                        ';

    }
}else{
    echo '
        <div class="modifys">您可以更改3次，已经修改'.$model->update_times.'次</div>
     ';
    $updateLabel = '提交我的修改';
    $updateId = 'update_guess';

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
         <p class="spr"></p>
                        ';

    }



}
?>


<p>您已选择了<span class="orangefont teams">0</span>支球队，还有<span class="orangefont unteams">0</span>支球队未选</p>
<p>竞猜时间：<?php echo $model->getGuessTime($promotionid);?></p>
</div>

<div class="guess_outcome">
    <dl style="margin-bottom:20px;">
        <?php echo $content;?>
    </dl>
</div>


<div class="changechoice">
    <a href="javascript:void(0);" id="<?php echo $updateId;?>" class="rulebtn"><?php echo $updateLabel;?></a>
    <a href="/worldCupPromotion/worldRuleTwo" class="rulebtn" style="margin-top: 20px;">查看竞猜规则</a>
    <a href="/luckyApp/worldCupIndex" class="rulebtn" style="margin-top: 20px;">返回</a>
</div>

<?php $caiPiaoUrl=SmallLoans::model()->getGoucaiUrl(); ?>
<?php if($caiPiaoUrl){ ?>
<div class="caipiao_box" style="display: block;">
    <p class="greenfont">还不过瘾？马上来买彩票吧，现在彩票充值50送20元哦！</p>
    <a href="<?php echo $caiPiaoUrl; ?>" class="rulebtn">马上购彩</a>
</div>
<?php } ?>

<script type="text/javascript">
    $('.group_game li').click(function(){
        var parents=$(this).parents('.group_game');
        var lichecked=parents.find('.lichecked');
        if($(this).hasClass('lichecked'))
        {
            $(this).find('.check_box').removeClass('checked_box');
            $(this).find('.check_qi').removeClass('checked_qi');
            $(this).removeClass('lichecked');
            $(this).find('.check_guess').attr("checked",false);
        }
        else{
            if(lichecked.length<2){
                $(this).find('.check_box').addClass('checked_box');
                $(this).find('.check_qi').addClass('checked_qi');
                $(this).addClass('lichecked');
                $(this).find('.check_guess').attr("checked",true);
            }
        }
        var allteam= $('.group_game li').length/2;
        var allchecked=$('.lichecked').length;
        $('.teams').text(allchecked);
        $('.unteams').text(allteam-allchecked);
    });
    //提交竞猜
    $('#submit_guess').click(
        function (e){
            var check = $("input[type='checkbox']");
            var len=check.length;
            var code=new Array();
            for(i=j=0;i<len;i++){
                if(check.eq(i).attr("checked")){
                    code[j++]=check.eq(i).val();
                }
            }
            if(code.length!=16){
                tips('请选择16支队伍！',e);
            }else{
                var pid = 1;
                $.post(
                    '/worldCupPromotion/submitGuess',
                    {'code':code,'pid':pid},
                    function (data){
                        if(data == 1){
                            tips('竞猜成功！',e);
                            window.setTimeout("location.href = '/worldCupPromotion/myGuess'",500);
                        }else{
                            tips('超过活动时间！',e);
                        }
                    }
                    ,'json');
            }
        }
    );
    //修改竞猜
    $('#update_guess').click(
        function (e){
            var check = $("input[type='checkbox']");
            var len=check.length;
            var code=new Array();
            for(i=j=0;i<len;i++){
                if(check.eq(i).attr("checked")){
                    code[j++]=check.eq(i).val();
                }
            }
            if(code.length!=16){
                tips('请选择16支队伍！',e);
            }else{
                var pid = 1;
                $.post(
                    '/worldCupPromotion/updateGuess',
                    {'code':code,'pid':pid},
                    function (data){
                        if(data == 1){
                            tips('竞猜修改成功！',e);
                            window.setTimeout("location.href = '/worldCupPromotion/myGuess'",500);
                        }
                        if(data == 2){
                            tips('超过修改次数！',e);
                        }
                        if(data == 0)
                            tips('超过活动时间！',e);
                    }
                    ,'json');
            }
        }
    );
</script>
