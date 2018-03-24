<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <title>水果嘉年华主页面</title>
    <link href="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>css/style.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>js/jquery-1.11.3.js"></script>
</head>

<body>
<div class="container">
    <div class="background">
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg01.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg02.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg03.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg04.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg05.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg06.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg07.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg08.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg09.jpg"/>
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/main_bg/main_bg10.jpg"/>
    </div>

    <div class="buttons">
        <div class="button1">
            <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/btn_1.png">
            <a href="<?php echo $this->createUrl("Show",array('type'=>'tgg')); ?>"></a>
        </div>
        <div class="button2">
            <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/btn_2.png">
            <a href="<?php echo $this->createUrl("Show",array('type'=>'qparty')); ?>"></a>
        </div>
        <div class="button3">
            <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/btn_3.png">
            <a href="<?php echo $this->createUrl("Show",array('type'=>'cs')); ?>"></a>
        </div>
        <div class="button4">
            <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/btn_4.png">
            <a href="<?php echo $this->createUrl("Show",array('type'=>'xyrg')); ?>"></a>
        </div>
        <div class="button5">
            <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/btn_5.png">
            <a href="<?php echo $this->createUrl("invite"); ?>"></a>
        </div>
        <div id="rule_btn">
            <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/rule.png">
            <a href="javascript:void(0);"></a>
        </div>
    </div>

    <div id="rules" class="rules hidden">
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/rules_content.png">
        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/close_btn.png">
        <a href="javascript:void(0);"></a>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#rule_btn").find("a").click(function(){
            $(".buttons").addClass("hidden");
            $("#rules").removeClass("hidden");
        });

        $("#rules").find("a").click(function(){
            $("#rules").addClass("hidden");
            $(".buttons").removeClass("hidden");
        });

    });
</script>
</body>
</html>