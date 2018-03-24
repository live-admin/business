<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>彩富人生-宣传页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="telephone=no" name="format-detection">
    <script src="<?php echo F::getStaticsUrl('/home/colourRichLife/'); ?>js/flexible.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/colourRichLife/'); ?>css/layout.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/colourRichLife/'); ?>css/normalize.css">

</head>
<body>
<a href="javascript:mobileJump('EReduceList');">
<div class="container">
    <div class="header">
        <img src="<?php echo F::getStaticsUrl('/home/colourRichLife/'); ?>images/logo_01.png"  class="img_lt"/>
        <img src="<?php echo F::getStaticsUrl('/home/colourRichLife/'); ?>images/logo_02.png"  class="img_rg"/>
        <div class="clear"></div>
    </div>
    <div class="banner">
        <img src="<?php echo F::getStaticsUrl('/home/colourRichLife/'); ?>images/banner_01.png" />
    </div>
    <div class="site">
        <img src="<?php echo F::getStaticsUrl('/home/colourRichLife/'); ?>images/banner_02.png"  />
    </div>
    <div class="footer">
        <div class="footer_con">
            <h4>活动内容</h4>
            <p>活动期间购买彩富人生（仅限12期产品）满足一定金额既成为至尊VIP，并赠送相应礼品。</p>
            <h4 style="padding-top: 0.2rem;">活动时间</h4>
            <p>2016年3月3日——2016年4月30日</p>
        </div>
    </div>
</div>
</a>

<script>

    function mobileJump(cmd)
    {
        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
            var _cmd = "http://www.colourlife.com/*jumpPrototype*colourlife://proto?type=" + cmd;
            document.location = _cmd;
        } else if (/(Android)/i.test(navigator.userAgent)) {
            var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
            eval(_cmd);
        } else {

        }
    }
</script>
</body>
</html>

