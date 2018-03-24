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
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>js/ReFontsize.js"></script>
    <script src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>js/jquery-1.11.3.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>css/layout.css" />
</head>
<body>
<div class="contains">
    <div class="basic">
        <p>基础问题</p>
    </div>
    <!--第一个-->
    <div class="con-a">
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con01.png" />
            </div>
            <div class="boxa-p">
                <p>我应该怎样参加活动？</p>
            </div>
        </div>
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con02.png" />
            </div>
            <div class="boxa-p1">
                <p class="content hide_content">您可以通过彩之云网站www.colourlife.com或者手机客户端参加活动，活动流程如下:（打开彩之云进入首页彩富人生-选择相应产品参加与活动-增加冲抵服务的地址-确认订单并支付-查询订单详情）。</p>
            </div>
        </div>
    </div>
    <!--第二个-->
    <div class="con-b" id="basic-b">
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con01.png" />
            </div>
            <div class="boxa-p">
                <p>我有几套房想参加活动，应该怎么操作？</p>
            </div>
        </div>
        <div class="box-a">
            <div class="boxa-img">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con02.png" />
            </div>
            <div class="boxa-p1">
                <p class="content hide_content">同一个彩之云账号可对应多套房产。进入投资页面，点击为其他房产冲抵，增加冲抵地址，其余流程与第一套房产操作相同。</p>
            </div>
        </div>
    </div>
    <!--资金安全-->
    <div class="con-c">
        <!--资金问题-->
        <a href="<?php echo $this->createUrl("FundingProblems");?>">
            <div class="box-c">
                <div class="box-money">
                    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con03.png" />
                </div>
                <div class="box-money-p">
                    <p>资金问题</p>
                </div>
                <div class="box-next">
                    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/next.png" />
                </div>
            </div>
            <hr style="border:0;background-color:#E6E9EA;height:1px;width: 84.5%; margin-left: 0.9rem;"/>
        </a>
        <!--安全保障-->
        <a href="<?php echo $this->createUrl("SecurityGuarantee");?>">
            <div class="box-c">
                <div class="box-money">
                    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/con04.png" />
                </div>
                <div class="box-money-p">
                    <p>安全保障</p>
                </div>
                <div class="box-next">
                    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/next.png" />
                </div>
            </div>
        </a>
    </div>
    <!--客服热线-->
    <div class="tip">
        <p>客服热线：4008-893-893</p>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".con-a").click(function(){
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

        $(".con-b").click(function(){
            if($(this).find(".content").hasClass('hide_content'))
            {
                $(this).animate({height:"2.6rem"},500);
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