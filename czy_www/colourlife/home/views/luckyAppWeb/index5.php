<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>感恩大抽奖</title>
    <link href="<?php echo F::getStaticsUrl('/common/css/lucky/july/lotteryweb.css?time=76543210'); ?>" rel="stylesheet">
    <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo F::getStaticsUrl('/common/js/lucky/jQueryRotate.2.2.js'); ?>"></script>
    <script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.easing.min.js'); ?>"></script>
    <script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js'); ?>"></script>
    <script src="<?php echo F::getStaticsUrl('/common/js/lucky/lucky.circle.web.js?datetime=123456'); ?>"></script>
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
    <script type="text/javascript">
        $(function(){
            var e = jQuery.Event("click");
            $(".cup_start").click(function(){
                lottery();
            });
        });
    </script>

</head>

<body style="background:#7c2f35;">
<div class="lottery_topic_web">
    <div class="topic">
        <div class="lotteryindex">
            <div class="lotteryitem">
          <span class="indextt">
            <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/indextt1.png'); ?>" class="lotteryimg" />
          </span>
          <span class="indexword">
            <i>感恩回馈一</i>
            邀请好友送红包, 红包无上限
          </span>
                <a href="/luckyAppWeb/invite" class="">&gt;&gt;&nbsp;立即邀请</a>
            </div>
            <div class="lotteryitem">
          <span class="indextt">
            <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/indextt2.png'); ?>" class="lotteryimg"  />
          </span>
          <span class="indexword">
            <i>感恩回馈二</i>
            投资E理财，坐享10%收益
          </span>
                <a href="/smallLoans" class="">&gt;&gt;&nbsp;立即理财</a>
            </div>
            <div class="lotteryitem" style="border:none;">
          <span class="indextt">
            <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/indextt3.png'); ?>" class="lotteryimg"  />
          </span>
          <span class="indexword">
            <i>感恩回馈三</i>
            天天抽大奖，红包抢不停
          </span>
                <a href="#startbtn" class="">&gt;&gt;&nbsp;立即抽奖</a>
            </div>
        </div>
        <h3 class="lt_title">
            <a href="/luckyAppWeb/lotteryrule" class="lookforrule">&gt;&gt;&nbsp; 查看活动规则</a>
            <p class="changes">
                <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
                您有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span> 次机会，已有<span id=><?php echo $allJoin; ?></span>人次参加
            </p>
        </h3>
        <div class="topic_part1">
            <div class="lottery_web">
                <div class="roulette">
                    <div id="startbtn">
                        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/lotterypoint.png'); ?>" class="cup_start lotteryimg"/>
                    </div>

                </div>
            </div>
            <div class="new_lottery">
                <h3>
                    <a href="/luckyAppWeb/mylottery" class="lookfor_lottery">&gt;&gt;查看中奖情况</a>
                    最新中奖
                </h3>
                <div class="lotteryList" style="position:relative;">

                    <dl id="ticker">
                        <?php foreach($listResult as $result){ ?>
                            <dt><?php echo $result['msg']; ?></dt>
                        <?php } ?>
                    </dl>
                </div>
            </div>
            <div class="clr"></div>
        </div>

    </div>
    <div class="lottery_bottom_web"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/bot.jpg'); ?>" class="lotteryimg"/></div>

    <!--弹出框 start-->
    <div class="opacity" style="display:none;">
        <!--手气用完 start-->
        <div class="alertcontairn" style="display:none;">
            <div class="alertcontairn_content">
                <div class="textinfo">
                    <p class="redfont">亲，你的抽奖次数用光了。</p>
                    <p>
                        邀请邻居注册成功可获得5次抽奖机会哦。<br />
                        满10位好友得50元红包，<a href="/luckyAppWeb/invite" class="redfont">&gt;&gt;立即邀请</a>
                    </p>
                </div>
                <div class="pop_btn">
                    <a href="/luckyAppWeb/lotteryrule"><span>查看活动规则</span></a>
                    <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
                </div>
            </div>
        </div>
        <!--手气用完 end-->
        <!--每天5次 start-->
        <div class="alertcontairn0" style="display:none;">
            <div class="alertcontairn_content">
                <div class="textinfo">
                    <p class="redfont">亲，每天只能抽奖5次哦，</p>
                    <p class="redfont">明天再来抽大奖吧！</p>
                    <p>
                        邀请邻居注册成功可获得5次抽奖机会哦。<br />
                        满10位好友得50元红包，<a href="/luckyAppWeb/invite" class="redfont">&gt;&gt;立即邀请</a>
                    </p>
                </div>
                <div class="pop_btn">
                    <a href="/luckyAppWeb/lotteryrule"><span>查看活动规则</span></a>
                    <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
                </div>
            </div>
        </div>
        <!--每天5次 end-->
        <!--谢谢参与 start-->
        <div class="alertcontairn1" style="display:none;">
            <div class="alertcontairn_content">

                <div class="textinfo">
                    <p class="redfont">谢谢您的参与，彩生活有您更加精彩！</p>
                    <p>
                        邀请邻居注册成功可获得5次抽奖机会哦。<br />
                        满10位好友得50元红包，<a href="/luckyAppWeb/invite" class="redfont">&gt;&gt;立即邀请</a>
                    </p>
                </div>
                <div class="pop_btn">
                    <a href="/luckyAppWeb/lotteryrule"><span>查看活动规则</span></a>
                    <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
                </div>
            </div>
        </div>
        <!--谢谢参与 end-->
        <!--中红包 start-->
        <div class="alertcontairn6" style="display:none;">
            <div class="alertcontairn_content">
                <div class="textinfo">
                    <div class="redpack_box" style="width:147px;">
                        <img src="<?php echo F::getStaticsUrl('/common/images/lucky/july/redpack.jpg'); ?>"/>
                        <span id="bonus_amount_small">0.18</span>
                    </div>
                    <p class="redfont" style="margin-bottom:0;">恭喜您获得了<span id="bonus_amount">0.18</span>元红包！</p>
                    <p>
                        邀请邻居注册成功可获得5次抽奖机会哦。<br />
                        满10位好友得50元红包，<a href="/luckyAppWeb/invite" class="redfont">&gt;&gt;立即邀请</a>
                    </p>
                </div>
                <div class="pop_btn">
                    <a href="/luckyAppWeb/mylottery"><span>查看中奖情况</span></a>
                    <a href="javascript:void(0);" class="closeOpacity"><span>关闭</span></a>
                </div>
            </div>
        </div>
        <!--中红包 end-->


    </div>
    <!--弹出框 end-->




</div>
<script type="text/javascript">

    $(function(){
        $('.closeOpacity').click(function(){
            $('.opacity').hide();
            $('.alertcontairn,.alertcontairn1,.alertcontairn2,.alertcontairn3,.alertcontairn4,.alertcontairn5,.alertcontairn6,.alertcontairn7').hide();



        })



    })


</script>
</body>
</html>
