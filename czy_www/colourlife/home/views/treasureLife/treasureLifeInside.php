<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>彩富人生内页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bpp-style" content="black">
    <meta name="format-detection" content="telephone=no"/>
    <script src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>js/ReFontsize.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>css/layout.css" />
</head>

<body>
<div class="contans">
    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bg01.jpg" />
    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bg02.jpg" />
    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bg03.jpg" />
    <div class="top_a">
        <p>彩富人生</p>
    </div>
    <div class="top_b">
        <p>告别</p>
    </div>
    <div class="top_c">
        <p>月月缴费</p>
        <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/nx.png" />
    </div>
    <div class="cont_a_box">
        <div class="cont_a_box_img">
            <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/pig_03.png" />
        </div>
        <div class="cont_a_box_p">
            <p><span>定投</span><span>一次</span><span>省心</span><span>一年</span></p>
        </div>
    </div>
    <div class="cont_b">
<!--        <div class="cont_b_a">-->
<!--            <p><span>至尊</span><span>VIP业主</span></p>-->
<!--        </div>-->
        <div class="cont_b_vip">
            <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/vip_bg.png" alt="">
        </div>
        <div class="cont_b_b">
            <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/nx.png" />
            <p>保证物业费、停车费不上涨</p>
        </div>
    </div>
    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bg04.jpg" />
    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bg05.jpg" />
    <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bg06.jpg" />
    <div class="cont_c">
        <div class="cont_c_a">
            <p>任性</p>
        </div>
        <div class="cont_c_b">
            <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/nx.png" />
            <p>1整年</p>
            <p>（海量服务券免费送：保洁、装修、维修等）</p>
            <p>还您一个温暖的家</p>
        </div>
    </div>
    <div class="cont_d">
        <div class="cont_d_box_a">
            <div class="cont_d_box_a_p1">
                <p>未参加前</p>
            </div>
            <div class="cont_d_box_a_p2">
                <p>参加彩富人生后</p>
            </div>
        </div>
        <div class="cont_d_box_b">
            <div class="cont_d_box_b_img1">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bn01.png" />
            </div>
            <div class="cont_d_box_b_img2">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/fw.png" />
            </div>
            <div class="cont_d_box_b_img3">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bn02.png" />
            </div>
        </div>
        <div class="cont_d_box_b">
            <div class="cont_d_box_b_img1">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bn03.png" />
            </div>
            <div class="cont_d_box_b_img2">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/fw.png" />
            </div>
            <div class="cont_d_box_b_img3">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bn04.png" />
            </div>
        </div>
        <div class="cont_d_box_b">
            <div class="cont_d_box_b_img1">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bn05.png" />
            </div>
            <div class="cont_d_box_b_img2">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/fw.png" />
            </div>
            <div class="cont_d_box_b_img3">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/bn06.png" />
            </div>
        </div>
    </div>
    <div class="cont_b">
        <div class="cont_b_a" style="border: 2px solid #B8A492;">
            <p class="cont_d_d">更多</p>
        </div>
        <div class="cont_b_b">
            <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/nx.png" />
            <p>请咨询您的专属管家</p>
        </div>
    </div>

    <div class="cont_f_box_a">


        <?php if(false == $result['is_bind']): ?>
            <div class="cont_f_box_a_p1">
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/head.png" />
            </div>
            <!--还未绑定专属管家-->
        <div class="cont_f_box_a_p2 ">
            <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/talk.png" />
            <p>您还未绑定您的专属管家</p>
            <p>请前往绑定</p>
            <a href="<?php echo $result['url'] ;?>">
                <p>点击进入</p>
                <img src="<?php echo F::getStaticsUrl('/home/treasureLife/'); ?>img/a_next.png" />
            </a>
        </div>
        <?php else: ?>
            <div class="cont_f_box_a_p1">
                <img src="<?php echo $result['portrait']; ?>" />
            </div>

            <!--已绑定专属管家信息-->
            <div class="info_show">
                <p>客户经理：<?php echo $result['name']; ?></p>
                <p>联系电话：<a href="tel:<?php echo $result['mobile'] ;  ?>" style="color:#0085f5;text-decoration:underline"><?php echo $result['mobile'] ;  ?></a></p>
                <p>所在职位：彩生活专属客户经理</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="cont_g">
        <p>4008-893-893</p>
        <p>www.colourlife.com</p>
    </div>

</div>
</body>

</html>