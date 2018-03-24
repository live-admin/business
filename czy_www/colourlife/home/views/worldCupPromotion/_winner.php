
<?php
    $content='';
    if($model->isNewRecord){
        echo '
        <div class="modifys">竞猜正确获<span style="color:red;font-size: 14px;">58.00</span>元红包 !</div>
    </div>
    ';
        $updateLabel = '提交我的竞猜';
        $updateId = 'submit_winner';

        $content.='
            <div class="mychoice the_winner">
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
        }
        $content.='
            </dl>
        </div>
       ';
    }else{
        echo '
        <div class="modifys">您可以更改3次，已经修改'.$model->update_times.'次</div>
    </div>
    ';
        $updateLabel = '提交我的修改';
        $updateId = 'update_winner';

        $content.='
            <div class="mychoice the_winner">
         <dl class="clearfix">
        ';
       foreach($model->getTeamsCode($promotionid) as $code){
           $team = $model->getTeamModel($code);
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
       }
        $content.='
            </dl>
        </div>
       ';



    }
?>

<div class="winner_box">
    <div>
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/king.png');?>" class="lotteryimg" />
        <p class="king_name"><?php echo $model->isNewRecord?"":$model->getCodeName($myguess[0]);?></p>
    </div>
</div>
<?php echo $content;?>
<div><input type="hidden" id="winner_val" value="<?php echo $model->isNewRecord?"":$myguess[0];?>" /></div>
<div class="changechoice">
    <a href="javascript:void(0);" id="<?php echo $updateId;?>" class="rulebtn"><?php echo $updateLabel;?></a>
    <a href="/worldCupPromotion/worldRuleThree" class="rulebtn" style="margin-top: 20px;">查看竞猜规则</a>
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
$('.mychoice dd').click(function(){
    $('.check_box').removeClass('checked_box');
    $('.check_qi').removeClass('checked_qi');
    $(this).find('.check_box').addClass('checked_box');
    $(this).find('.check_qi').addClass('checked_qi');
    var winner=$(this).find('.choicename').text();
    var choice=$(this).find('.choicecode').text();
    $('.king_name').text(winner);
    $('#winner_val').val(choice);

});
//提交竞猜
$('#submit_winner').click(
    function (e){
        var code = $('#winner_val').val();
        if(code.length>0){
        $.post(
            '/worldCupPromotion/submitWinner',
            {'code':code},
            function (data){
                if(data == 1){
                    tips('竞猜成功！',e);
                    window.setTimeout("location.href = '/worldCupPromotion/myWinnerGuess'",500);
                }else{
                    tips('超过活动时间！',e);
                }
            }
            ,'json');
        }else{
            tips('请选择队伍！',e);
        }
    }
);
//修改竞猜
$('#update_winner').click(
    function (e){
        var code = $('#winner_val').val();
        $.post(
            '/worldCupPromotion/updateWinner',
            {'code':code},
            function (data){
                if(data == 1){
                    tips('竞猜修改成功！',e);
                    window.setTimeout("location.href = '/worldCupPromotion/myWinnerGuess'",500);
                }
                if(data == 2){
                    tips('超过修改次数！',e);
                }
                if(data == 0)
                    tips('超过活动时间！',e);
            }
            ,'json');
    }
);
</script>
