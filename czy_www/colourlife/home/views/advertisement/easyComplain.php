<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>E投诉</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/js/ReFontsize.js'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/css/draw.css'); ?>"/>

</head>
<body>
<div class="container">
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm01.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm02.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm03.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm04.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm05.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm06.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm07.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm08.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm09.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm10.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm11.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm12.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm13.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm14.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm15.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm16.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm17.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm18.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm19.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/img/pm20.jpg'); ?>" />
</div>
<div class="footer">
    <a href="<?php echo $url;?>">去看看</a>
</div>
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
