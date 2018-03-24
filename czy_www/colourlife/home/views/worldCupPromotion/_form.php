
<?php
$content='';
if($model->isNewRecord){
    $redpacket=SetTeamsPromotion::model()->findByPk($promotionid)->redpacket;
    echo '
        <div class="modifys">竞猜正确获<span style="color:red;font-size: 14px;">'.$redpacket.'</span>元红包 !</div>
    ';
    $updateLabel = '提交我的竞猜';
    $updateId = 'submit_guess';

    foreach($model->getTeamsCode($promotionid) as $key=>$code){
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
    echo '
        <div class="modifys">您可以更改3次，已经修改'.$model->update_times.'次</div>
    </div>
    ';
    $updateLabel = '提交我的修改';
    $updateId = 'update_guess';

    foreach($model->getTeamsCode($promotionid) as $key=>$code){
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
?>

<p>您已选择了<span class="orangefont teams">0</span>支球队，还有<span class="orangefont unteams">0</span>支球队未选</p>
<p>竞猜时间：<?php echo $model->getGuessTime($promotionid);?></p>
</div>

<div class="mychoice elimination">
        <?php echo $content;?>
</div>

<div><input type="hidden" id="promotion_id" value="<?php echo $promotionid;?>" /></div>
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
    $('.elimination dd').click(function(){
        $(this).addClass('elimchecked');
        $(this).find('.check_guess').attr("checked",true);
        $(this).siblings('dd').removeClass('elimchecked');
        $(this).siblings('dd').find('.check_guess').attr("checked",false);
        var allteam= $('.elimination dd').length/2;
        var allchecked=$('.elimchecked').length;
        $('.teams').text(allchecked);
        $('.unteams').text(allteam-allchecked);
    });
    //提交竞猜
    $('#submit_guess').click(
        function (e){
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
                tips('请选择'+team+'支队伍！',e);
            }else{
                var pid = $('#promotion_id').val();
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
            var team= $('.elimination dd').length/2;
            var code=new Array();
            for(i=j=0;i<len;i++){
                if(check.eq(i).attr("checked")){
                    code[j++]=check.eq(i).val();
                }
            }
            if(code.length!=team){
                tips('请选择'+team+'支队伍！',e);
            }else{
                var pid = $('#promotion_id').val();
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
