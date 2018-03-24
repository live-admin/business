<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>E停车</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="<?php echo F::getStaticsUrl('/common/advertisement/EComplaints/js/ReFontsize.js'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/common/advertisement/EParking/css/layout.css'); ?>"/>
</head>
<body>
<div class="e_Parking">
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner1.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner2.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner3.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner4.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner5.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner6.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner7.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner8.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner9.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner10.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner11.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner12.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner13.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner14.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner15.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner16.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner17.jpg'); ?>" />
    <img src="<?php echo F::getStaticsUrl('/common/advertisement/EParking/images/banner18.jpg'); ?>" />
</div>
<div class="footer">
    <a href="javascript:mobileJump('PPParkingLicencePlateFee');">去看看</a>
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
