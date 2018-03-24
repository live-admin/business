<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />

    <title>我的竞猜选择</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/june/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>

<?php if($isGuest){?>
<script type="text/javascript">
	var isGuest=1;
	var loginHref="<?php echo $href;?>";
</script>
<?php }else{?>
<script type="text/javascript">
	var isGuest=0;
</script>
<?php }?>


</head>

<body>


<div class="lottery_topic_subweb topic_part4 webmychoice">
    <div class="webtitle">
        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/june/webtitle2.jpg');?>"/>
    </div>
    <div class="eight_mychoice">
        <div class="greenline" style="margin:30px auto 20px"></div>

        <?php

        $pid=array();
        foreach($guessList as $guess){
            echo '<div class="eight_top">
            <p>我的<span class="orangefont">'.$guess->teams_promotion->type.'</span>竞猜：</p>
        </div>
        <div class="mychoice" style="padding:0;">
          <dl>
           ';
            $pid[]=$guess->teams_promotion_id;
            $teamsList = explode(",",$guess->my_quiz_teams);
            $right = $guess->getRightTeams($guess->teams_promotion_id);
            foreach($teamsList as $code){
                $team = $guess->getTeamModel($code);
                if($guess->is_right==CustomerPromotion::IS_RIGHT_UNKNOWN){

                    echo '
                    <dd>
                       <div class="qi_imgbox">
                         <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
                       </div>
                       <p>'.$team->team.'</p>
                     </dd>
                    ';
                }else{
                    if(in_array($team->code,$right)){
                        echo '
                    <dd>
                        <div class="right_wrong">
                         <img src="'.F::getStaticsUrl('/common/images/lucky/june/selected2.png').'" class="lotteryimg"  />
                       </div>
                       <div class="qi_imgbox">
                         <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
                       </div>
                       <p>'.$team->team.'</p>
                     </dd>
                    ';
                    }else{
                        echo '
                    <dd>
                        <div class="right_wrong">
                         <img src="'.F::getStaticsUrl('/common/images/lucky/june/wrong.png').'" class="lotteryimg"  />
                       </div>
                       <div class="qi_imgbox">
                         <img src="'.$team->HomeTeamLogo.'" class="lotteryimg"  />
                       </div>
                       <p>'.$team->team.'</p>
                     </dd>
                    ';
                    }
                }
            }
            echo '</dl></div>';

            if($guess->is_right==CustomerPromotion::IS_RIGHT_UNKNOWN){
                echo '
                <div class="eight_result">
                   <h3 class="greenfont">正在等待晋级结果：</h3>
                   <p>竞猜开始时间：<span style="color:#ff7e00;">'.$guess->teams_promotion->start_time.'</span></p>
                   <p>竞猜结束时间：<span style="color:#ff7e00;">'.$guess->teams_promotion->end_time.'</span></p>
                   <p>竞猜正确奖励：<span style="color:red">'.$guess->teams_promotion->redpacket.'</span>元红包</p>
                   <div class="greenline" style="margin:30px auto 20px"></div>
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
                <div class="eight_result">
                   <h3 class="greenfont">实际'.$guess->teams_promotion->type.'结果：</h3>
                   <p>'.$result.'</p>
                   <div class="greenline"></div>
                   <h3 class="greenfont">'.$award.'</h3>
                   <p>'.$send.'</p>
                   <div class="greenline" style="margin:30px auto 20px"></div>
                 </div>
                ';
            }
        }



        ?>


        <div class="btn_div">
            <a href="/luckyAppWeb#go_promotion">修改我的竞猜</a>
            <a href="/luckyAppWeb">返回</a>
        </div>

        <?php $caiPiaoUrl=SmallLoans::model()->getGoucaiUrl(); ?>
        <?php if($caiPiaoUrl){ ?>
        <div class="btn_div" style="display: block;">
            <p class="greenfont">还不过瘾？马上来买彩票吧，现在彩票充值50送20元哦！</p>
            <a href="<?php echo $caiPiaoUrl; ?>">马上购彩</a>
        </div>
        <?php } ?>

     </div>

</div>







</div>
<script type="text/javascript">


 </script>
</body>
</html>
