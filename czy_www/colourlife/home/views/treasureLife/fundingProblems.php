<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>客服帮助</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>js/ReFontsize.js"></script>
    <script src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>js/jquery-1.11.3.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>css/layout.css" />
</head>
<body>
<div class="contains">
    <div class="basic">
        <p>资金问题</p>
    </div>
    <!--第一个-->
    <div class="con-a">
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con01.png" />
            </div>
            <div class="boxa-p">
                <p>冲抵的费用包含哪些部分？</p>
            </div>
        </div>
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con02.png" />
            </div>
            <div class="boxa-p1">
                <p class="content hide_content">如果您参加的是冲抵物业费活动，冲抵的费用只包含应缴的物业管理费。 如果您参加的是冲抵历史欠费，冲抵的费用包括您所有的历史欠费，包括物业费和水电费等。 如果您参加的冲抵停车费，冲抵的费用包含应缴的停车费用。</p>
            </div>
        </div>
    </div>
    <!--第二个-->
    <div class="con-b">
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con01.png" />
            </div>
            <div class="boxa-p">
                <p>我的投资金额是如何计算出来的？</p>
            </div>
        </div>
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con02.png" />
            </div>
            <div class="boxa-p1">
                <p class="content hide_content">您的投资金额是根据您的应缴费信息通过系统后台自动计算出来的。</p>
            </div>
        </div>
    </div>
    <!--第三个-->
    <div class="con-c1">
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con01.png" />
            </div>
            <div class="boxa-p">
                <p>如何保障我的收益？</p>
            </div>
        </div>
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con02.png" />
            </div>
            <div class="boxa-p1">
                <p class="content hide_content">您投资成功之后，小区客户经理将上门为您派送带有物业公司的凭证，凭证上会有您投资的金额明细、可获得预期收益、冲抵费用期限。</p>
            </div>
        </div>
    </div>
    <!--第四个-->
    <div class="con-d">
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con01.png" />
            </div>
            <div class="boxa-p">
                <p>何时可以提现？</p>
            </div>
        </div>
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con02.png" />
            </div>
            <div class="boxa-p1">
                <p class="content hide_content">您的冲抵时长到期后，就可将本金和预期收益一起提现到您绑定的银行卡中，资金将在您发起提现申请后的3个工作日内（T+3)到账。</p>
            </div>
        </div>
    </div>
    <!--第五个-->
    <div class="con-e">
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con01.png" />
            </div>
            <div class="boxa-p">
                <p>冲抵费用的时长有几种？</p>
            </div>
        </div>
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con02.png" />
            </div>
            <div class="boxa-p1">
                <p class="content hide_content">现在我们提供了冲抵半年、一年、两年费用的产品，您可以根据自己的实际情况自由选择。</p>
            </div>
        </div>
    </div>
</div>

<!--资金问题详情-->
<script type="text/javascript">
    $(document).ready(function() {
        $(".con-a").click(function() {
            if($(this).find(".content").hasClass('hide_content'))
            {

                $(this).animate({height:"3.2rem"},500);
                $(this).find(".content").removeClass('hide_content');
                $(this).find(".content").addClass('show_content');
            }
            else if ($(this).find(".content").hasClass('show_content'))
            {
                $(this).animate({height:"1.8rem"},500);
                $(this).find(".content").removeClass('show_content');
                $(this).find(".content").addClass('hide_content');
            };
        });
        $(".con-b").click(function() {
            if($(this).find(".content").hasClass('hide_content'))
            {

                $(this).animate({height:"2.0rem"},500);
                $(this).find(".content").removeClass('hide_content');
                $(this).find(".content").addClass('show_content');
            }
            else if ($(this).find(".content").hasClass('show_content'))
            {
                $(this).animate({height:"1.8rem"},500);
                $(this).find(".content").removeClass('show_content');
                $(this).find(".content").addClass('hide_content');
            };
        });
        $(".con-c1").click(function() {
            if($(this).find(".content").hasClass('hide_content'))
            {

                $(this).animate({height:"2.4rem"},500);
                $(this).find(".content").removeClass('hide_content');
                $(this).find(".content").addClass('show_content');
            }
            else if ($(this).find(".content").hasClass('show_content'))
            {
                $(this).animate({height:"1.8rem"},500);
                $(this).find(".content").removeClass('show_content');
                $(this).find(".content").addClass('hide_content');
            };
        });
        $(".con-d").click(function() {
            if($(this).find(".content").hasClass('hide_content'))
            {

                $(this).animate({height:"2.4rem"},500);
                $(this).find(".content").removeClass('hide_content');
                $(this).find(".content").addClass('show_content');
            }
            else if ($(this).find(".content").hasClass('show_content'))
            {
                $(this).animate({height:"1.8rem"},500);
                $(this).find(".content").removeClass('show_content');
                $(this).find(".content").addClass('hide_content');
            };
        });
        $(".con-e").click(function() {
            if($(this).find(".content").hasClass('hide_content'))
            {

                $(this).animate({height:"2.0rem"},500);
                $(this).find(".content").removeClass('hide_content');
                $(this).find(".content").addClass('show_content');
            }
            else if ($(this).find(".content").hasClass('show_content'))
            {
                $(this).animate({height:"1.8rem"},500);
                $(this).find(".content").removeClass('show_content');
                $(this).find(".content").addClass('hide_content');
            };
        });
    });
</script>
</body>
</html>